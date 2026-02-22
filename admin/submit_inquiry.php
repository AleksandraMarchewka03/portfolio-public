<?php
// ──────────────────────────────────────────────
// submit_inquiry.php
// Handles contact form submissions via fetch() POST
// Returns JSON
// ──────────────────────────────────────────────
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// Read + sanitize POST data
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
$errors = [];
if ($name === '')                          $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if ($message === '')                       $errors[] = 'Message is required.';

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'errors' => $errors]);
    exit;
}

// Get client IP
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
$ip = trim(explode(',', $ip)[0]);

try {
    $db = get_db();

    // Rate-limit: max 5 submissions per IP per hour
    $stmt = $db->prepare(
        'SELECT COUNT(*) FROM inquiries WHERE ip_address = :ip AND created_at > NOW() - INTERVAL 1 HOUR'
    );
    $stmt->execute([':ip' => $ip]);
    $count = (int) $stmt->fetchColumn();

    if ($count >= 5) {
        http_response_code(429);
        echo json_encode(['ok' => false, 'error' => 'Too many submissions. Please try again later.']);
        exit;
    }

    // Insert inquiry
    $insert = $db->prepare(
        'INSERT INTO inquiries 
            (sender_name, sender_email, best_contact, message, ip_address, status)
         VALUES 
            (:name, :email, :contact, :message, :ip, :status)'
    );

    $insert->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':contact' => $contact,
        ':message' => $message,
        ':ip'      => $ip,
        ':status'  => 'new', // default status
    ]);

    echo json_encode(['ok' => true, 'message' => 'Inquiry saved. Thank you!']);

} catch (PDOException $e) {
    http_response_code(500);
    error_log('submit_inquiry PDO error: ' . $e->getMessage());
    echo json_encode(['ok' => false, 'error' => 'Server error. Please try again.']);
}