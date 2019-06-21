<?php 
print_r($_POST);
$nombre=$_POST['nombre'];
$email=$_POST['email'];
$mensaje=$_POST['mensaje'];

$g=$_POST['g-recaptcha-response'];
$sectret= $config['SECRET_API_KEY_ReCaptchGoogle'];
$ip=$_SERVER['REMOTE_ADDR'];
$result= file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$sectret&response=$g&remoteip=$ip");

$array = json_decode($result, TRUE);



if ($nombre =='' or $consulta =='' or $email ==''){
            echo   " Por favor complete todos los datos del Formulario. ";
          } else if($g =='') {
            echo   " Error con el Captcha. ";
}
else if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
            echo   "El formato de email no es válido. ";
          }
else{ require("phpmailer/class.phpmailer.php");
                // php mailer code starts
                $mail = new PHPMailer(true);
                
                $mail->IsSMTP(); // telling the class to use SMTP
                $mail->CharSet = 'UTF-8';
                $mail->SMTPDebug = 0;    
                $mail->Host = "correo.politicassociales.gob.ar";      // sets GMAIL as the SMTP server
                $mail->Port = 25;                   // set the SMTP port for the GMAIL server
                
                
                $mail->SetFrom('no-responder@odsargentina.gob.ar', 'Concurso ODS');
                $mail->AddAddress('consejoddhh@defensoria.org.ar');
                //$mail->AddAddress('marianabelgrano@gmail.com');
                
                $mail->Subject = trim("Consulta - Concurso Fotográfico (ODS)");
                
                
                $body = file_get_contents('mail-consulta.php');
                $body = eregi_replace("[\]",'',$body);
                
                //setup vars to replace
                $vars = array('{nombre}','{consulta}','{email}');
                $values = array($nombre,$consulta, $email);
                
                //replace vars
                $body = str_replace($vars,$values,$body);
                
                //add the html tot the body
                $mail->MsgHTML($body);
                //$body =file_get_contents('html_mail.php');
                $mail->Body = $body; // Mensaje a enviar
                $exito = $mail->Send(); // Envía el correo.
                
                    //También podríamos agregar simples verificaciones para saber si se envió:
                    if($exito){
                    echo "Tu consulta se efectuó con éxito. <br>";
                    }else{
                   // echo 'Mailer Error: ' . $mail->ErrorInfo;
                    echo "Ocurrió un error al momento del envio de la consulta, por favor intentar más tarde. <br> Gracias.";
                    }
                }
?>