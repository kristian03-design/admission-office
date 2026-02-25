<?php
// ============================================================
// app/Controllers/DocumentController.php
// Secure file upload and download for application documents
// ============================================================

namespace App\Controllers;

use App\Config\Database;
use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use PDO;

class DocumentController
{
    private PDO   $db;
    private array $config;
    private array $auth;

    private const ALLOWED_MIME = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];

    private const ALLOWED_EXT = ['pdf', 'jpg', 'jpeg', 'png'];

    public function __construct()
    {
        $appConfig       = require __DIR__ . '/../../config/app.php';
        $this->db        = Database::pdo();
        $this->config    = $appConfig['upload'];
        $this->auth      = AuthMiddleware::authUser() ?? [];
    }

    // ── POST /api/applications/:appId/documents ───────────────
    public function upload(int $applicationId): void
    {
        (new AuthMiddleware())->authenticated();
        $this->authoriseApplicationAccess($applicationId);

        // Validate document_type field
        $documentType = $_POST['document_type'] ?? '';
        $allowedTypes = [
            'tor','birth_certificate','good_moral','form137',
            'honorable_dismissal','medical_certificate','id_photo','other',
        ];

        if (!in_array($documentType, $allowedTypes, true)) {
            Response::error('Invalid document_type', 422);
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $error = $this->fileErrorMessage($_FILES['file']['error'] ?? -1);
            Response::error($error, 400);
        }

        $file = $_FILES['file'];

        // ── Security checks ───────────────────────────────────

        // 1. Size limit
        if ($file['size'] > $this->config['max_size']) {
            $maxMb = round($this->config['max_size'] / 1048576, 1);
            Response::error("File too large. Maximum size is {$maxMb} MB", 413);
        }

        // 2. Extension check (client-provided name)
        $originalName = basename($file['name']);
        $ext          = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, self::ALLOWED_EXT, true)) {
            Response::error('File type not allowed. Accepted: PDF, JPG, PNG', 415);
        }

        // 3. MIME type via finfo (server-side, not trusting client)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);

        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            Response::error('File content does not match allowed types', 415);
        }

        // 4. PDF magic bytes check (extra safety)
        if ($ext === 'pdf' && $mime === 'application/pdf') {
            $handle  = fopen($file['tmp_name'], 'rb');
            $magic   = fread($handle, 4);
            fclose($handle);
            if ($magic !== '%PDF') {
                Response::error('Invalid PDF file', 415);
            }
        }

        // ── Storage ───────────────────────────────────────────

        $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
        $subDir     = 'documents/' . date('Y/m');
        $fullDir    = $this->config['path'] . '/' . $subDir;
        $fullPath   = $fullDir . '/' . $storedName;
        $diskPath   = $subDir . '/' . $storedName;

        if (!is_dir($fullDir) && !mkdir($fullDir, 0755, true)) {
            Response::serverError('Could not create upload directory');
        }

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            Response::serverError('Failed to save file');
        }

        // ── Database record ───────────────────────────────────

        $userId = (int) $this->auth['sub'];

        $stmt = $this->db->prepare(
            'INSERT INTO documents
             (application_id, document_type, original_name, stored_name,
              mime_type, file_size, disk_path, uploaded_by)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $applicationId,
            $documentType,
            $originalName,
            $storedName,
            $mime,
            $file['size'],
            $diskPath,
            $userId,
        ]);
        $docId = (int) $this->db->lastInsertId();

        Response::success([
            'id'            => $docId,
            'document_type' => $documentType,
            'original_name' => $originalName,
            'file_size'     => $file['size'],
            'mime_type'     => $mime,
        ], 'Document uploaded successfully', 201);
    }

    // ── GET /api/applications/:appId/documents ────────────────
    public function index(int $applicationId): void
    {
        (new AuthMiddleware())->authenticated();
        $this->authoriseApplicationAccess($applicationId);

        $stmt = $this->db->prepare(
            'SELECT id, document_type, original_name, file_size, mime_type,
                    verified, verified_at, created_at
             FROM documents
             WHERE application_id = ?
             ORDER BY created_at DESC'
        );
        $stmt->execute([$applicationId]);
        Response::success($stmt->fetchAll());
    }

    // ── GET /api/documents/:id/download ───────────────────────
    public function download(int $docId): void
    {
        (new AuthMiddleware())->authenticated();

        $stmt = $this->db->prepare(
            'SELECT d.*, a.applicant_id
             FROM documents d JOIN applications a ON a.id = d.application_id
             WHERE d.id = ?'
        );
        $stmt->execute([$docId]);
        $doc = $stmt->fetch();

        if (!$doc) {
            Response::notFound('Document not found');
        }

        $this->authoriseApplicationAccess((int) $doc['application_id']);

        $fullPath = $this->config['path'] . '/' . $doc['disk_path'];
        if (!file_exists($fullPath)) {
            Response::notFound('File not found on disk');
        }

        // Serve file
        header('Content-Type: ' . $doc['mime_type']);
        header('Content-Disposition: attachment; filename="' . rawurlencode($doc['original_name']) . '"');
        header('Content-Length: ' . filesize($fullPath));
        header('X-Content-Type-Options: nosniff');
        readfile($fullPath);
        exit;
    }

    // ── PATCH /api/documents/:id/verify (staff/admin) ─────────
    public function verify(int $docId): void
    {
        (new AuthMiddleware())->staffOrAdmin();

        $stmt = $this->db->prepare('SELECT id FROM documents WHERE id = ?');
        $stmt->execute([$docId]);
        if (!$stmt->fetch()) {
            Response::notFound('Document not found');
        }

        $userId = (int) $this->auth['sub'];

        $stmt = $this->db->prepare(
            'UPDATE documents SET verified = 1, verified_by = ?, verified_at = NOW() WHERE id = ?'
        );
        $stmt->execute([$userId, $docId]);

        Response::success(null, 'Document verified');
    }

    // ── DELETE /api/documents/:id (uploader or admin) ─────────
    public function destroy(int $docId): void
    {
        (new AuthMiddleware())->authenticated();

        $stmt = $this->db->prepare('SELECT * FROM documents WHERE id = ?');
        $stmt->execute([$docId]);
        $doc = $stmt->fetch();

        if (!$doc) {
            Response::notFound('Document not found');
        }

        $userId = (int) $this->auth['sub'];
        $role   = $this->auth['role'] ?? '';

        if ($role !== 'admin' && (int)$doc['uploaded_by'] !== $userId) {
            Response::forbidden('You cannot delete this document');
        }

        // Delete physical file
        $fullPath = $this->config['path'] . '/' . $doc['disk_path'];
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $stmt = $this->db->prepare('DELETE FROM documents WHERE id = ?');
        $stmt->execute([$docId]);

        Response::success(null, 'Document deleted');
    }

    // ── Helpers ───────────────────────────────────────────────

    private function authoriseApplicationAccess(int $applicationId): void
    {
        $role   = $this->auth['role'] ?? '';
        $userId = (int) $this->auth['sub'];

        if (in_array($role, ['admin', 'staff'], true)) return;

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

    private function fileErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File exceeds maximum allowed size',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            default => 'Unknown upload error',
        };
    }
}
