<?php
// getUID.php - APPEND MODE (fast, no DB)
header('Content-Type: text/plain; charset=utf-8');

// Shared secret (set via Render env var SECRET_KEY)
$SECRET_KEY = getenv('SECRET_KEY') ?: 'PLEASE-SET-A-SECRET';

// Read POST
$uid = isset($_POST['UIDresult']) ? trim($_POST['UIDresult']) : null;
$key = isset($_POST['key']) ? trim($_POST['key']) : null;

if (empty($uid) || empty($key)) {
    http_response_code(400);
    echo "ERROR: Missing UID or key";
    exit;
}

if (!hash_equals($SECRET_KEY, $key)) {
    http_response_code(403);
    echo "ERROR: Invalid key";
    exit;
}

if (!preg_match('/^[0-9A-Fa-f]{2,64}$/', $uid)) {
    http_response_code(400);
    echo "ERROR: UID format invalid";
    exit;
}

// Append UID with timestamp
$logLine = date('Y-m-d H:i:s') . " " . $uid . PHP_EOL;
$logFile = __DIR__ . '/rfid_log.txt';

if (file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    echo "ERROR: Failed to write log";
    exit;
}

echo "OK";
