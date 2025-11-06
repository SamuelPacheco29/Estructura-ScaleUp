<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Clase EmailService
class EmailService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->configurar();
    }

    // Definimos la configuración del email
    private function configurar() {
        try {
            // Configuración del servidor SMTP
            $this->mail->SMTPDebug = 0; // 0 = sin debug, 2 = con debug
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'scaleup.development@gmail.com'; // Correo de la cuenta de Gmail
            $this->mail->Password = 'fkbb vnca ztya tmrp'; // Contraseña de aplicacion de Gmail
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
            $this->mail->CharSet = 'UTF-8';
            
            // Configuración del remitente
            $this->mail->setFrom('scaleup.development@gmail.com', 'Gestion de Inventario ScaleUp'); // Correo de la cuenta de Gmail

        } catch (Exception $e) {
            error_log("Error configurando el EmailService: " . $e->getMessage());
        }
    }

    public function enviarCodigoVerificacion($email, $nombre, $codigo) {
        try {
            // Destinatario
            $this->mail->addAddress($email, $nombre);

            // Contenido
            $this->mail->isHTML(true); // Permite personalizar el mensaje
            $this->mail->Subject = 'Codigo de Verificacion - Gestion de Inventario ScaleUp';

            $this->mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2 style='color: #4a90e2;'>Verificación de Email</h2>
                    <p>Hola <strong>$nombre</strong>,</p>
                    <p>Gracias por registrarte en nuestro sistema de inventario.</p>
                    <p>Tu código de verificación es:</p>
                    <div style='background: #f5f5f5; padding: 20px; text-align: center; margin: 20px 0;'>
                        <h1 style='color: #4a90e2; font-size: 48px; margin: 0; letter-spacing: 10px;'>$codigo</h1>
                    </div>
                    <p><strong>Este código expira en 15 minutos.</strong></p>
                    <p>Si no solicitaste este código, ignora este email.</p>
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                    <p style='color: #888; font-size: 12px;'>Sistema de Inventario ScaleUp</p>
                </div>
            ";

            // Enviar el email
            $resultado = $this->mail->send();

            // Limpiar destinatario para proximos envios
            $this->mail->clearAddresses();

            return $resultado;

        } catch (Exception $e) {
            error_log("Error enviando el email de verificacion: " . $this->mail->ErrorInfo);
            return false;
        }
    } 
}
?>