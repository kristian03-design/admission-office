<?php
// ============================================================
// app/Controllers/ApplicationController.php
// Handles applicant and staff/admin application operations
// ============================================================

namespace App\Controllers;

use App\Config\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Middleware\AuthMiddleware;
use App\Services\EmailService;
use PDO;

class ApplicationController
{
    private PDO   $db;
    private array $auth;

    public function __construct()
    {
        $this->db   = Database::pdo();
        $this->auth = AuthMiddleware::authUser() ?? [];
    }

    // ── POST /api/applications  (applicant submits) ───────────
    public function store(): void
    {
        (new AuthMiddleware())->authenticated();

        $body = $this->jsonBody();

        $v = (new Validator($body))->validate([
            'program_id'       => 'required|integer',
            'academic_year'    => 'required|string|max:10',
            'semester'         => 'required|in:1st,2nd,summer',
            'application_type' => 'required|in:new,transferee,returnee,cross_enrollee',
            'remarks'          => 'nullable|string|max:1000',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        $userId = (int) $this->auth['sub'];

        // Get applicant id from user
        $stmt = $this->db->prepare('SELECT id FROM applicants WHERE user_id = ?');
        $stmt->execute([$userId]);
        $applicant = $stmt->fetch();

        if (!$applicant) {
            Response::error('Applicant profile not found. Please complete your profile first.', 404);
        }

        $applicantId = $applicant['id'];

        // Check program exists and is active
        $stmt = $this->db->prepare('SELECT id, name FROM programs WHERE id = ? AND is_active = 1');
        $stmt->execute([$body['program_id']]);
        $program = $stmt->fetch();

        if (!$program) {
            Response::error('Program not found or inactive', 404);
        }

        // Prevent duplicate applications for same program/year/semester
        $stmt = $this->db->prepare(
            'SELECT id FROM applications
             WHERE applicant_id = ? AND program_id = ? AND academic_year = ? AND semester = ?'
        );
        $stmt->execute([$applicantId, $body['program_id'], $body['academic_year'], $body['semester']]);
        if ($stmt->fetch()) {
            Response::error('You have already applied to this program for this period', 409);
        }

        // Generate application number: AY2024-000001
        $yearShort = substr($body['academic_year'], 0, 4);
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM applications WHERE academic_year = ?');
        $stmt->execute([$body['academic_year']]);
        $count = (int) $stmt->fetchColumn() + 1;
        $appNo = "AY{$yearShort}-" . str_pad($count, 6, '0', STR_PAD_LEFT);

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO applications
                 (application_no, applicant_id, program_id, academic_year, semester,
                  application_type, remarks, submitted_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())'
            );
            $stmt->execute([
                $appNo,
                $applicantId,
                $body['program_id'],
                $body['academic_year'],
                $body['semester'],
                $body['application_type'],
                $body['remarks'] ?? null,
            ]);
            $applicationId = (int) $this->db->lastInsertId();

            // Initial status: submitted
            $this->insertStatus($applicationId, 'submitted', 'Application submitted', $userId);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log('Application store error: ' . $e->getMessage());
            Response::serverError('Failed to submit application');
        }

        // Email notification
        try {
            $user = $this->getUserById($userId);
            (new EmailService())->sendApplicationSubmitted(
                $user['email'],
                $user['name'],
                $appNo,
                $program['name']
            );
        } catch (\Throwable) {}

        $application = $this->getApplicationById($applicationId);
        Response::success($application, 'Application submitted successfully', 201);
    }

    // ── GET /api/applications  (list – role-aware) ────────────
    public function index(): void
    {
        (new AuthMiddleware())->authenticated();

        $role   = $this->auth['role'] ?? '';
        $userId = (int) $this->auth['sub'];

        $params = [];
        $where  = [];

        if ($role === 'applicant') {
            // Applicants see only their own applications
            $stmt = $this->db->prepare('SELECT id FROM applicants WHERE user_id = ?');
            $stmt->execute([$userId]);
            $applicant = $stmt->fetch();
            if (!$applicant) {
                Response::success([]);
            }
            $where[] = 'a.applicant_id = ?';
            $params[] = $applicant['id'];
        }

        // Filters (staff/admin)
        if ($role !== 'applicant') {
            if (!empty($_GET['status'])) {
                $where[] = 'cs.status = ?';
                $params[] = $_GET['status'];
            }
            if (!empty($_GET['program_id'])) {
                $where[] = 'a.program_id = ?';
                $params[] = (int) $_GET['program_id'];
            }
            if (!empty($_GET['academic_year'])) {
                $where[] = 'a.academic_year = ?';
                $params[] = $_GET['academic_year'];
            }
            if (!empty($_GET['search'])) {
                $where[] = "(ap.first_name LIKE ? OR ap.last_name LIKE ? OR a.application_no LIKE ?)";
                $s = '%' . $_GET['search'] . '%';
                $params[] = $s; $params[] = $s; $params[] = $s;
            }
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        // Pagination
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = min(100, max(5, (int) ($_GET['per_page'] ?? 20)));
        $offset  = ($page - 1) * $perPage;

        $sql = "
            SELECT
                a.id, a.application_no, a.academic_year, a.semester,
                a.application_type, a.submitted_at, a.created_at,
                cs.status AS current_status,
                p.code AS program_code, p.name AS program_name,
                CONCAT(ap.first_name, ' ', ap.last_name) AS applicant_name,
                ap.applicant_no
            FROM applications a
            JOIN programs      p  ON p.id = a.program_id
            JOIN applicants    ap ON ap.id = a.applicant_id
            LEFT JOIN v_application_current_status cs ON cs.application_id = a.id
            $whereClause
            ORDER BY a.created_at DESC
            LIMIT $perPage OFFSET $offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $applications = $stmt->fetchAll();

        // Total count
        $countSql = "
            SELECT COUNT(*) FROM applications a
            JOIN programs   p  ON p.id = a.program_id
            JOIN applicants ap ON ap.id = a.applicant_id
            LEFT JOIN v_application_current_status cs ON cs.application_id = a.id
            $whereClause
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        Response::success([
            'data'       => $applications,
            'pagination' => [
                'total'    => $total,
                'page'     => $page,
                'per_page' => $perPage,
                'pages'    => (int) ceil($total / $perPage),
            ],
        ]);
    }

    // ── GET /api/applications/:id ─────────────────────────────
    public function show(int $id): void
    {
        (new AuthMiddleware())->authenticated();
        $this->authoriseApplicationAccess($id);

        $application = $this->getApplicationById($id);
        if (!$application) {
            Response::notFound('Application not found');
        }

        // Status history
        $stmt = $this->db->prepare(
            'SELECT s.status, s.notes, s.created_at, u.name AS changed_by_name
             FROM application_statuses s
             LEFT JOIN users u ON u.id = s.changed_by
             WHERE s.application_id = ?
             ORDER BY s.created_at ASC'
        );
        $stmt->execute([$id]);
        $application['status_history'] = $stmt->fetchAll();

        // Documents
        $stmt = $this->db->prepare(
            'SELECT id, document_type, original_name, file_size, mime_type, verified, created_at
             FROM documents WHERE application_id = ?'
        );
        $stmt->execute([$id]);
        $application['documents'] = $stmt->fetchAll();

        Response::success($application);
    }

    // ── PATCH /api/applications/:id/status (staff/admin) ──────
    public function updateStatus(int $id): void
    {
        (new AuthMiddleware())->staffOrAdmin();

        $body = $this->jsonBody();
        $v = (new Validator($body))->validate([
            'status' => 'required|in:draft,submitted,under_review,pending_docs,for_interview,accepted,rejected,waitlisted,enrolled,cancelled',
            'notes'  => 'nullable|string|max:1000',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        // Verify application exists
        $stmt = $this->db->prepare('SELECT id FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            Response::notFound('Application not found');
        }

        $userId = (int) $this->auth['sub'];

        $this->db->beginTransaction();
        try {
            // Update reviewed_by and reviewed_at on the application
            $stmt = $this->db->prepare(
                'UPDATE applications SET reviewed_by = ?, reviewed_at = NOW() WHERE id = ?'
            );
            $stmt->execute([$userId, $id]);

            $this->insertStatus($id, $body['status'], $body['notes'] ?? null, $userId);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log('Status update error: ' . $e->getMessage());
            Response::serverError('Failed to update status');
        }

        // Email the applicant about status change
        try {
            $this->sendStatusNotification($id, $body['status']);
        } catch (\Throwable) {}

        Response::success([
            'application_id' => $id,
            'new_status'     => $body['status'],
        ], 'Status updated successfully');
    }

    // ── DELETE /api/applications/:id (admin only) ─────────────
    public function destroy(int $id): void
    {
        (new AuthMiddleware())->adminOnly();

        $stmt = $this->db->prepare('SELECT id FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            Response::notFound('Application not found');
        }

        $stmt = $this->db->prepare('DELETE FROM applications WHERE id = ?');
        $stmt->execute([$id]);

        Response::success(null, 'Application deleted');
    }

    // ── Helpers ───────────────────────────────────────────────

    private function insertStatus(int $appId, string $status, ?string $notes, int $changedBy): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO application_statuses (application_id, status, notes, changed_by)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$appId, $status, $notes, $changedBy]);
    }

    private function getApplicationById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, p.name AS program_name, p.code AS program_code,
                    p.department, p.duration_years,
                    CONCAT(ap.first_name,' ',ap.last_name) AS applicant_name,
                    ap.applicant_no, ap.phone,
                    u.email AS applicant_email,
                    cs.status AS current_status, cs.notes AS status_notes,
                    cs.created_at AS status_updated_at
             FROM applications a
             JOIN programs   p  ON p.id = a.program_id
             JOIN applicants ap ON ap.id = a.applicant_id
             JOIN users      u  ON u.id = ap.user_id
             LEFT JOIN v_application_current_status cs ON cs.application_id = a.id
             WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function authoriseApplicationAccess(int $applicationId): void
    {
        $role   = $this->auth['role'] ?? '';
        $userId = (int) $this->auth['sub'];

        if (in_array($role, ['admin', 'staff'], true)) return;

        // Applicant – verify ownership
        $stmt = $this->db->prepare(
            'SELECT a.id FROM applications a
             JOIN applicants ap ON ap.id = a.applicant_id
             WHERE a.id = ? AND ap.user_id = ?'
        );
        $stmt->execute([$applicationId, $userId]);
        if (!$stmt->fetch()) {
            Response::forbidden('You do not have access to this application');
        }
    }

    private function sendStatusNotification(int $applicationId, string $status): void
    {
        $stmt = $this->db->prepare(
            'SELECT u.email, u.name, a.application_no, p.name AS program_name
             FROM applications a
             JOIN applicants ap ON ap.id = a.applicant_id
             JOIN users u ON u.id = ap.user_id
             JOIN programs p ON p.id = a.program_id
             WHERE a.id = ?'
        );
        $stmt->execute([$applicationId]);
        $row = $stmt->fetch();
        if ($row) {
            (new EmailService())->sendStatusUpdated(
                $row['email'],
                $row['name'],
                $row['application_no'],
                $row['program_name'],
                $status
            );
        }
    }

    private function getUserById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT id, name, email FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function jsonBody(): array
    {
        $raw  = file_get_contents('php://input');
        $body = json_decode($raw, true) ?? [];
        return $body ?: $_POST ?: [];
    }
}
