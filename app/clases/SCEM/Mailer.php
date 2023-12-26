<?php
namespace App\clases\SCEM;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

use Exception;

class Mailer
{
    public $username_email;
    public $password_email;
    public $to_email;
    public $to_name;
    public $cc_email;
    public $subject;
    public $body;
    public $mail;

    /**
    * @param array
    */
    public function __construct(array $info = []) {
        $this->username_email = $info['username_email'];
        $this->password_email = $info['password_email'];
        $this->to_email = $info['to_email'];
        $this->to_name = $info['to_name'];
        $this->cc_email = $info['cc_email'] ?: '';
        $this->subject = $info['subject'];
        $this->body = $info['body'] ?: '';
        $this->mail = new PHPMailer(true);
    }

    public function armar_correo() {
        
        // Server settings
        $this->mail->CharSet = "UTF-8";
        $this->mail->Encoding = 'base64';
        $this->mail->SMTPDebug = 0;                           // Enable verbose debug output
        $this->mail->isSMTP();                                // Set mailer to use SMTP
        $this->mail->Host = 'smtp.office365.com';             // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true;                         // Enable SMTP authentication
        $this->mail->Username = $this->username_email;        // SMTP username
        $this->mail->Password = $this->password_email;        // SMTP password
        $this->mail->SMTPSecure = 'tls';                      // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port = 587;                              // TCP port to connect to

        // $this->mail->CharSet = "UTF-8";
        // $this->mail->Encoding = 'base64';
    
        // $this->mail->SMTPDebug = 0;                           // Enable verbose debug output
        // $this->mail->isSMTP();                                // Set mailer to use SMTP
        // $this->mail->Host = 'mail.unimodelo.com';             // Specify main and backup SMTP servers
        // $this->mail->SMTPAuth = true;                         // Enable SMTP authentication
        // $this->mail->Username = $this->username_email;   // SMTP username
        // $this->mail->Password = $this->password_email;                   // SMTP password
        // $this->mail->SMTPSecure = 'ssl';                      // Enable TLS encryption, `ssl` also accepted
        // $this->mail->Port = 465;                              // TCP port to connect to
    

        #Mail settings
        $this->mail->setFrom($this->username_email, 'Universidad Modelo');
        $this->mail->addAddress($this->to_email, $this->to_name);
        if($this->cc_email) $this->mail->addCC($this->cc_email);
        $this->mail->isHTML(true);
        $this->mail->Subject = $this->subject;
        $this->mail->Body = $this->body;

        return $this->mail;
    }

    public function enviar() {
        try {
            $this->mail = $this->armar_correo();
            $this->mail->send();
        } catch (MailerException $e) {
            throw $e;
        }
    }

    /**
     * Recibe una instancia de DOMPDF
     */
    public function adjuntar_pdf($archivo, $nombre = 'document.pdf')
    {   
        try {
            $string = $archivo->output();
            $this->mail->addStringAttachment($string, $nombre);
        } catch (Exception $e) {
            throw $e;    
        }
    }

    /**
     * Se puede adjuntar cualquier tipo de archivo, proporcionando su url
     * El nombre es opcional, debe contener extensión del archivo.
     * 
     * @param string $url
     * @param string $nombre
     */
    public function adjuntar_archivo(string $url, string $nombre = null)
    {
        $this->mail->addAttachment($url, $nombre);
    }

    public function agregar_destinatario($email, $nombre = '')
    {
        $this->mail->addAddress($email, $nombre);
    }

}