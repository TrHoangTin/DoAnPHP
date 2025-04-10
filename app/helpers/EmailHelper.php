<?php
// File: helpers/EmailHelper.php
class EmailHelper {
    private $mailer;

    public function __construct() {
        // Cấu hình PHPMailer
        $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Cấu hình SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'trantin064@gmail.com'; 
        $this->mailer->Password = 'bqve zueu dkyp bkbf'; 
        $this->mailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom('trantin064@gmail.com', 'TechShop');
        
    }

    public function sendOTP($toEmail, $otp) {
        try {
            $this->mailer->setFrom('your_email@gmail.com', 'WebBanHang');
            $this->mailer->addAddress($toEmail);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Mã OTP đặt lại mật khẩu';
            
            $body = "
                <h2>Đặt lại mật khẩu</h2>
                <p>Bạn đã yêu cầu đặt lại mật khẩu. Mã OTP của bạn là:</p>
                <h3 style='color: #4e54c8; font-size: 24px;'>{$otp}</h3>
                <p>Mã OTP có hiệu lực trong 10 phút.</p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
            ";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Mã OTP đặt lại mật khẩu của bạn là: {$otp}";
            
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}