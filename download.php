<?php
// === AGGRESSIVE DEBUGGING START ===
ini_set('display_errors', 1);
error_reporting(E_ALL);
// This will start an output buffer to catch any stray output.
ob_start();
// === AGGRESSIVE DEBUGGING END ===

include('includes/db.php');
session_start();

if (!isset($_GET['token']) || !isset($_GET['product_id'])) {
    die("Error 1: Invalid download request (missing token or product_id).");
}

$token = $_GET['token'];
$product_id = intval($_GET['product_id']);

$stmt = $conn->prepare("SELECT cart_data, user_id FROM pending_orders WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error 2: Download authorization failed or order expired.");
}

$order_data = $result->fetch_assoc();
$items = json_decode($order_data['cart_data'], true);

$product_found_in_order = false;
$download_file_name = '';
foreach ($items as $item) {
    if ($item['id'] === $product_id && ($item['product_type'] ?? 'physical') === 'digital' && !empty($item['download_file'])) {
        $product_found_in_order = true;
        $download_file_name = $item['download_file'];
        break;
    }
}

if (!$product_found_in_order) {
    die("Error 3: Product not found in this order or it's not a digital download.");
}

$download_folder = __DIR__ . '/assets/files/downloads/';
$file_path = $download_folder . basename($download_file_name);

if (!file_exists($file_path)) {
    die("Error 4: File not found on server at: " . htmlspecialchars($file_path));
}

if (!is_readable($file_path)) {
    die("Error 5: File exists but is not readable. Check permissions for: " . htmlspecialchars($file_path));
}

// === AGGRESSIVE DEBUGGING - Check for any accidental output before headers ===
$buffer_length = ob_get_length();
if ($buffer_length > 0) {
    // There is unwanted output. This is what is corrupting your file.
    // We will output a message and the unwanted content itself to help diagnose.
    $unwanted_output = ob_get_contents();
    ob_end_clean(); // Discard the corrupted output
    die("Error 6: Output started before headers could be sent! Unwanted output: " . htmlspecialchars($unwanted_output));
}

// Ensure the output buffer is empty before we send the file headers.
ob_end_clean();
// === AGGRESSIVE DEBUGGING END ===

// Headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));

readfile($file_path);
exit;

?>