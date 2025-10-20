<?php
// getUID.php - APPEND MODE
header('Content-Type: text/plain; charset=utf-8');

// Option A: hard-coded secret (simple) - replace with env var on Render if you prefer
$SECRET_KEY = "mysecret123";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uid = $_POST["UIDresult"] ?? "";
    $key = $_POST["key"] ?? "";

    if ($key !== $SECRET_KEY) {
        http_response_code(403);
        echo "Invalid key";
        exit;
    }

    if (!empty($uid)) {
        $logEntry = date("Y-m-d H:i:s") . " - UID: " . $uid . PHP_EOL;
        file_put_contents("UID_log.txt", $logEntry, FILE_APPEND);
        $write = "<?php \$UIDresult='" . $uid . "'; echo \$UIDresult; ?>";
        file_put_contents("UIDContainer.php", $write);
        echo "UID Saved: " . $uid;
    } else {
        http_response_code(400);
        echo "No UID provided";
    }
} else {
    echo "RFID API running";
}
?>
