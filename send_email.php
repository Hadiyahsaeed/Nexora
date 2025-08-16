<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOrderEmail($to, $name, $orderDetails, $total, $confirmationLink) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hadiyahsaeed2012@gmail.com'; 
        $mail->Password   = 'gxwq cvou eprm ngzx'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('hadiyahsaeed2012@gmail.com', 'Nexora Orders');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Nexora Order Confirmation';
        $mail->Body    = "
            <h3>Dear $name,</h3>
            <p>Thank you for placing this order! Here are your order details:</p>
            <pre>$orderDetails</pre>
            <p><strong>Total: </strong>$total Rs</p>
            <p><a href='$confirmationLink'>Click here to confirm your order</a></p>
            <p>Team Nexora</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
