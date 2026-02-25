<?php
// ============================================================
// app/Controllers/ApplicantController.php
// CRUD for applicant profiles
// ============================================================

namespace App\Controllers;

use App\Config\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Middleware\AuthMiddleware;
use PDO;

class ApplicantController
{
    private PDO   $db;
    private array $auth;

    public function __construct()
    {
        $this->db   = Database::pdo();
        $this->auth = AuthMiddleware::authUser() ?? [];
    }

    // ── GET /api/applicant/profile ────────────────────────────
    public function profile(): void
    {
        (new AuthMiddleware())->authenticated();
        $userId = (int) $this->auth['sub'];

        $stmt = $this->db->prepare(
            'SELECT ap.*, u.email, u.name AS account_name
             FROM applicants ap
             JOIN users u ON u.id = ap.user_id
             WHERE ap.user_id = ?'
        );
        $stmt->execute([$userId]);
        $profile = $stmt->fetch();

        if (!$profile) {
            Response::notFound('Applicant profile not found');
        }

        Response::success($profile);
    }

    // ── PUT /api/applicant/profile ────────────────────────────
    public function updateProfile(): void
    {
        (new AuthMiddleware())->authenticated();
        $userId = (int) $this->auth['sub'];

        $body = $this->jsonBody();

        $v = (new Validator($body))->validate([
            'first_name'    => 'required|string|max:80',
            'last_name'     => 'required|string|max:80',
            'middle_name'   => 'nullable|string|max:80',
            'gender'        => 'required|in:male,female,other,prefer_not_to_say',
            'birthdate'     => 'required|date',
            'phone'         => 'nullable|string|max:20',
            'address_line1' => 'required|string|max:200',
            'address_line2' => 'nullable|string|max:200',
            'city'          => 'required|string|max:100',
            'province'      => 'nullable|string|max:100',
            'postal_code'   => 'nullable|string|max:10',
            'country'       => 'nullable|string|max:80',
            'last_school'   => 'nullable|string|max:200',
            'school_address'=> 'nullable|string|max:200',
            'year_graduated'=> 'nullable|integer',
            'honors'        => 'nullable|string|max:150',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        $clean = $v->sanitised();

        $stmt = $this->db->prepare('SELECT id FROM applicants WHERE user_id = ?');
        $stmt->execute([$userId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update
            $stmt = $this->db->prepare(
                'UPDATE applicants SET
                    first_name = ?, middle_name = ?, last_name = ?,
                    gender = ?, birthdate = ?,
                    phone = ?, address_line1 = ?, address_line2 = ?,
                    city = ?, province = ?, postal_code = ?, country = ?,
                    last_school = ?, school_address = ?, year_graduated = ?, honors = ?
                 WHERE user_id = ?'
            );
            $stmt->execute([
                $clean['first_name'], $clean['middle_name'] ?? null, $clean['last_name'],
                $clean['gender'], $clean['birthdate'],
                $clean['phone'] ?? null, $clean['address_line1'], $clean['address_line2'] ?? null,
                $clean['city'], $clean['province'] ?? null, $clean['postal_code'] ?? null,
                $clean['country'] ?? 'Philippines',
                $clean['last_school'] ?? null, $clean['school_address'] ?? null,
                $clean['year_graduated'] ?? null, $clean['honors'] ?? null,
                $userId,
            ]);
        } else {
            // Prevent profile creation if user is not applicant role
            if ($this->auth['role'] !== 'applicant') {
                Response::error('Only applicant accounts have profiles', 400);
            }
            $appNo = 'APP-' . date('Y') . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT);
            $stmt  = $this->db->prepare(
                'INSERT INTO applicants
                 (user_id, applicant_no, first_name, middle_name, last_name, gender, birthdate,
                  phone, address_line1, address_line2, city, province, postal_code, country,
                  last_school, school_address, year_graduated, honors)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $userId, $appNo,
                $clean['first_name'], $clean['middle_name'] ?? null, $clean['last_name'],
                $clean['gender'], $clean['birthdate'],
                $clean['phone'] ?? null, $clean['address_line1'], $clean['address_line2'] ?? null,
                $clean['city'], $clean['province'] ?? null, $clean['postal_code'] ?? null,
                $clean['country'] ?? 'Philippines',
                $clean['last_school'] ?? null, $clean['school_address'] ?? null,
                $clean['year_graduated'] ?? null, $clean['honors'] ?? null,
            ]);
        }

        // Re-fetch and return
        $stmt = $this->db->prepare(
            'SELECT ap.*, u.email FROM applicants ap JOIN users u ON u.id = ap.user_id WHERE ap.user_id = ?'
        );
        $stmt->execute([$userId]);

        Response::success($stmt->fetch(), 'Profile updated successfully');
    }

    // ── GET /api/programs (public endpoint) ───────────────────
    public function programs(): void
    {
        $stmt = $this->db->query(
            'SELECT id, code, name, department, description, duration_years, slots
             FROM programs WHERE is_active = 1 ORDER BY name ASC'
        );
        Response::success($stmt->fetchAll());
    }

    private function jsonBody(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? $_POST ?? [];
    }
}
