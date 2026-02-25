<?php
// ============================================================
// app/Controllers/AdminController.php
// Dashboard metrics, user management, program management
// ============================================================

namespace App\Controllers;

use App\Config\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Middleware\AuthMiddleware;
use PDO;

class AdminController
{
    private PDO   $db;
    private array $auth;

    public function __construct()
    {
        $this->db   = Database::pdo();
        $this->auth = AuthMiddleware::authUser() ?? [];
    }

    // ── GET /api/admin/dashboard ──────────────────────────────
    public function dashboard(): void
    {
        (new AuthMiddleware())->staffOrAdmin();

        // Total applications per status
        $stmt = $this->db->query(
            "SELECT cs.status, COUNT(*) AS count
             FROM applications a
             LEFT JOIN v_application_current_status cs ON cs.application_id = a.id
             GROUP BY cs.status"
        );
        $statusCounts = [];
        foreach ($stmt->fetchAll() as $row) {
            $statusCounts[$row['status'] ?? 'unknown'] = (int) $row['count'];
        }

        // Applications this month
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM applications
             WHERE submitted_at >= DATE_FORMAT(NOW(), '%Y-%m-01')"
        );
        $thisMonth = (int) $stmt->fetchColumn();

        // Total applicants
        $stmt = $this->db->query("SELECT COUNT(*) FROM applicants");
        $totalApplicants = (int) $stmt->fetchColumn();

        // Applications per program
        $stmt = $this->db->query(
            "SELECT p.name, p.code, COUNT(a.id) AS applications
             FROM programs p
             LEFT JOIN applications a ON a.program_id = p.id
             GROUP BY p.id
             ORDER BY applications DESC"
        );
        $byProgram = $stmt->fetchAll();

        // Recent applications (last 10)
        $stmt = $this->db->query(
            "SELECT a.application_no, a.submitted_at,
                    CONCAT(ap.first_name,' ',ap.last_name) AS applicant_name,
                    p.name AS program_name, cs.status
             FROM applications a
             JOIN applicants ap ON ap.id = a.applicant_id
             JOIN programs p ON p.id = a.program_id
             LEFT JOIN v_application_current_status cs ON cs.application_id = a.id
             ORDER BY a.submitted_at DESC LIMIT 10"
        );
        $recentApplications = $stmt->fetchAll();

        // Total active users
        $stmt = $this->db->query(
            "SELECT role, COUNT(*) AS count FROM users WHERE is_active = 1 GROUP BY role"
        );
        $usersByRole = [];
        foreach ($stmt->fetchAll() as $row) {
            $usersByRole[$row['role']] = (int) $row['count'];
        }

        // Monthly trend (last 6 months)
        $stmt = $this->db->query(
            "SELECT DATE_FORMAT(submitted_at, '%Y-%m') AS month, COUNT(*) AS count
             FROM applications
             WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY month ORDER BY month ASC"
        );
        $monthlyTrend = $stmt->fetchAll();

        Response::success([
            'status_counts'       => $statusCounts,
            'this_month'          => $thisMonth,
            'total_applicants'    => $totalApplicants,
            'by_program'          => $byProgram,
            'recent_applications' => $recentApplications,
            'users_by_role'       => $usersByRole,
            'monthly_trend'       => $monthlyTrend,
        ]);
    }

    // ── GET /api/admin/users ──────────────────────────────────
    public function users(): void
    {
        (new AuthMiddleware())->adminOnly();

        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = min(100, max(10, (int) ($_GET['per_page'] ?? 20)));
        $offset  = ($page - 1) * $perPage;

        $where  = [];
        $params = [];

        if (!empty($_GET['role'])) {
            $where[]  = 'role = ?';
            $params[] = $_GET['role'];
        }
        if (!empty($_GET['search'])) {
            $where[]  = '(name LIKE ? OR email LIKE ?)';
            $s = '%' . $_GET['search'] . '%';
            $params[] = $s; $params[] = $s;
        }
        if (isset($_GET['is_active'])) {
            $where[]  = 'is_active = ?';
            $params[] = (int) $_GET['is_active'];
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare(
            "SELECT id, name, email, role, is_active, email_verified_at, created_at
             FROM users $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset"
        );
        $stmt->execute($params);
        $users = $stmt->fetchAll();

        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM users $whereClause");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        Response::success([
            'data'       => $users,
            'pagination' => compact('total', 'page', 'perPage'),
        ]);
    }

    // ── PUT /api/admin/users/:id ──────────────────────────────
    public function updateUser(int $userId): void
    {
        (new AuthMiddleware())->adminOnly();

        $body = $this->jsonBody();
        $v = (new Validator($body))->validate([
            'name'      => 'nullable|string|max:150',
            'role'      => 'nullable|in:admin,staff,applicant',
            'is_active' => 'nullable',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        $stmt = $this->db->prepare('SELECT id FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            Response::notFound('User not found');
        }

        $fields = [];
        $params = [];

        if (isset($body['name'])) {
            $fields[] = 'name = ?';
            $params[] = trim(strip_tags($body['name']));
        }
        if (isset($body['role'])) {
            $fields[] = 'role = ?';
            $params[] = $body['role'];
        }
        if (isset($body['is_active'])) {
            $fields[] = 'is_active = ?';
            $params[] = (int)(bool) $body['is_active'];
        }

        if (!$fields) {
            Response::error('No fields to update', 400);
        }

        $params[] = $userId;
        $stmt = $this->db->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?');
        $stmt->execute($params);

        Response::success(null, 'User updated');
    }

    // ── DELETE /api/admin/users/:id ───────────────────────────
    public function deleteUser(int $userId): void
    {
        (new AuthMiddleware())->adminOnly();

        // Prevent self-deletion
        if ((int) $this->auth['sub'] === $userId) {
            Response::error('Cannot delete your own account', 400);
        }

        $stmt = $this->db->prepare('SELECT id FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            Response::notFound('User not found');
        }

        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$userId]);

        Response::success(null, 'User deleted');
    }

    // ── GET /api/admin/programs ───────────────────────────────
    public function programs(): void
    {
        (new AuthMiddleware())->staffOrAdmin();

        $stmt = $this->db->query(
            'SELECT p.*, COUNT(a.id) AS total_applications
             FROM programs p
             LEFT JOIN applications a ON a.program_id = p.id
             GROUP BY p.id
             ORDER BY p.name ASC'
        );
        Response::success($stmt->fetchAll());
    }

    // ── POST /api/admin/programs ──────────────────────────────
    public function createProgram(): void
    {
        (new AuthMiddleware())->adminOnly();

        $body = $this->jsonBody();
        $v = (new Validator($body))->validate([
            'code'           => 'required|string|max:20',
            'name'           => 'required|string|max:200',
            'department'     => 'required|string|max:150',
            'description'    => 'nullable|string',
            'duration_years' => 'nullable|integer',
            'slots'          => 'nullable|integer',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        $clean = $v->sanitised();

        // Check code uniqueness
        $stmt = $this->db->prepare('SELECT id FROM programs WHERE code = ?');
        $stmt->execute([$clean['code']]);
        if ($stmt->fetch()) {
            Response::error('Program code already exists', 409);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO programs (code, name, department, description, duration_years, slots)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            strtoupper($clean['code']),
            $clean['name'],
            $clean['department'],
            $clean['description'] ?? null,
            $clean['duration_years'] ?? 4,
            $clean['slots'] ?? 0,
        ]);

        $id = (int) $this->db->lastInsertId();
        $stmt = $this->db->prepare('SELECT * FROM programs WHERE id = ?');
        $stmt->execute([$id]);

        Response::success($stmt->fetch(), 'Program created', 201);
    }

    // ── PUT /api/admin/programs/:id ───────────────────────────
    public function updateProgram(int $id): void
    {
        (new AuthMiddleware())->adminOnly();

        $body = $this->jsonBody();

        $stmt = $this->db->prepare('SELECT id FROM programs WHERE id = ?');
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            Response::notFound('Program not found');
        }

        $fields = [];
        $params = [];
        $allowed = ['name', 'department', 'description', 'duration_years', 'slots', 'is_active'];

        foreach ($allowed as $field) {
            if (array_key_exists($field, $body)) {
                $fields[] = "$field = ?";
                $params[] = is_string($body[$field]) ? trim(strip_tags($body[$field])) : $body[$field];
            }
        }

        if (!$fields) {
            Response::error('No valid fields to update', 400);
        }

        $params[] = $id;
        $this->db->prepare('UPDATE programs SET ' . implode(', ', $fields) . ' WHERE id = ?')
                 ->execute($params);

        Response::success(null, 'Program updated');
    }

    // ── GET /api/admin/reports/summary ───────────────────────
    public function reportsSummary(): void
    {
        (new AuthMiddleware())->staffOrAdmin();

        $academicYear = $_GET['academic_year'] ?? date('Y') . '-' . (date('Y') + 1);

        $stmt = $this->db->prepare(
            "SELECT p.code, p.name, p.slots,
                    SUM(CASE WHEN cs.status = 'submitted' THEN 1 ELSE 0 END) AS submitted,
                    SUM(CASE WHEN cs.status = 'under_review' THEN 1 ELSE 0 END) AS under_review,
                    SUM(CASE WHEN cs.status = 'accepted' THEN 1 ELSE 0 END) AS accepted,
                    SUM(CASE WHEN cs.status = 'rejected' THEN 1 ELSE 0 END) AS rejected,
                    SUM(CASE WHEN cs.status = 'enrolled' THEN 1 ELSE 0 END) AS enrolled,
                    COUNT(a.id) AS total
             FROM programs p
             LEFT JOIN applications a ON a.program_id = p.id AND a.academic_year = ?
             LEFT JOIN v_application_current_status cs ON cs.application_id = a.id
             WHERE p.is_active = 1
             GROUP BY p.id
             ORDER BY p.name"
        );
        $stmt->execute([$academicYear]);

        Response::success([
            'academic_year' => $academicYear,
            'programs'      => $stmt->fetchAll(),
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────

    private function jsonBody(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? $_POST ?? [];
    }
}
