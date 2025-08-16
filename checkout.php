<?php
include('includes/db.php');
require 'vendor/autoload.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$user_id = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['customer_name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    // MODIFIED QUERY: Added product_type and download_file
    $cartQuery = $conn->prepare("SELECT p.id, p.name, p.price, p.product_type, p.download_file, c.quantity
                                 FROM cart c
                                 JOIN products p ON c.product_id = p.id
                                 WHERE c.user_id = ?");
    $cartQuery->bind_param("i", $user_id);
    $cartQuery->execute();
    $cartResult = $cartQuery->get_result();

    if ($cartResult->num_rows === 0) {
        die("Your cart is empty.");
    }

    $items = [];
    $total = 0;

    while ($item = $cartResult->fetch_assoc()) {
        $items[] = $item; // Now $item will include 'product_type' and 'download_file'
        $total += $item['price'] * $item['quantity'];
    }

    // Store pending order details in session for confirm_order.php
    $_SESSION['pending_order'] = [
        'token' => bin2hex(random_bytes(16)),
        'user_id' => $user_id,
        'customer_name' => $name,
        'total_amount' => $total,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'items' => $items // This now includes type and download_file for each product
    ];
    $token = $_SESSION['pending_order']['token'];

    // Insert into pending_orders table
    $cartData = json_encode($items);
    $insert = $conn->prepare("INSERT INTO pending_orders (token, user_id, customer_name, total_amount, email, phone, address, cart_data)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sisdssss", $token, $user_id, $name, $total, $email, $phone, $address, $cartData);
    $insert->execute();

    // Send confirmation email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hadiyahsaeed2012@gmail.com';
        $mail->Password = 'spck cczq qnah xgbb';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('hadiyahsaeed2012@gmail.com', 'Nexora');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Confirm Your Nexora Order';
        $productList = '';
        foreach ($items as $item) {
            $productList .= "{$item['name']} (Qty: {$item['quantity']})<br>";
        }

        $confirmLink = "http://192.168.1.250:8081/nexora/confirm_order.php?token=$token";
        $mail->Body = "
            Dear $name,<br><br>
            Thank you for placing this order with Nexora!<br><br>
            <strong>Order Details:</strong><br>$productList<br>
            <strong>Total:</strong> Rs. " . number_format($total, 2) . "<br><br>
            <a href='$confirmLink'>Click here to confirm your order</a><br><br>
            If you did not place this order, you can safely ignore this email.
        ";

        $mail->send();
        echo "Please check your email to confirm your order.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>