<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Correo;
require 'vendor/autoload.php';

class Correo extends Model
{
    public function checkEmail($correo, $user){
        $tabla=DB::select('SELECT users.id_rol AS rol, users.nombre, users.email FROM users WHERE users.email = ? AND users.id_rol = ?', [$correo, $user]);
        return $tabla;
    }
 
    public function comprobarEstadoEmailUsuario($identificador, $pass){
        $salida = '';
        $tabla = DB::select('SELECT users.id, users.password FROM users WHERE users.email = ' ."'$identificador'" .' AND users.estado=1');
        for ($i = 0; $i < count($tabla); $i++) {
            if(Hash::check($pass, $tabla[$i]->password)){
                $salida = $tabla[$i]->id;
            }
        }
        return $salida;
	}

	function decifrarClave($datos){
		$clave_cifrada = $datos[0]->clave_random;
		return $clave_normal;
	}

	public function getTokenToChangePassword($email, $user){
		$token = hash("sha512",$email .Time());
		$existe=DB::select('SELECT * FROM token_password WHERE token_password.correo = ? AND token_password.user = ?', [$email, $user]);
        if(!$existe){
                DB::insert('INSERT INTO token_password (user, correo, token) VALUES (?, ?, ?)', [$user, $email, $token]);
            }else{
                DB::update('UPDATE token_password SET token_password.token = ? WHERE token_password.correo = ? AND token_password.user = ?', [$token, $email, $user]);
            }
        
            $tabla=DB::select('SELECT token_password.token FROM token_password WHERE token_password.correo = ? AND token_password.user = ?', [$email, $user]);
        return $tabla;
	}

	public function comprobarTokenPassword($token){
		$tabla=DB::select('SELECT * FROM token_password WHERE token_password.token = ' ."'$token'");
		return $tabla;
	}
 
	public function changePassword($user, $correo, $newpassword){
    		$nom_tabla;
        $esRepetida = 0;
        switch ($user) {
            case 1:
                $nom_tabla = 'auxiliar';
                break;
            case 2:
                $nom_tabla = 'docente';
                $existe = DB::select('SELECT docente.password FROM docente WHERE docente.correo = ? AND  docente.puesto = "DOCENTE"', [$correo]);
                for ($i = 0; $i < count($existe); $i++) {
                  if(Hash::check($newpassword, $existe[$i]->password)){
                      $esRepetida = 1;
                  }
                }
                break;
            case 3:
                $nom_tabla = 'docente';
                $existe = DB::select('SELECT docente.password FROM docente WHERE docente.correo = ? AND docente.puesto = "RESPONSABLE"', [$correo]);
                for ($i = 0; $i < count($existe); $i++) {
                  //var_dump(Hash::check($newpassword, $existe[$i]->password));
                  if(Hash::check($newpassword, $existe[$i]->password)){
                      $esRepetida = 1;
                  }
                }
                break;
            case 4:
                $nom_tabla = 'alumno';
                break;
        }
        /*if(isset($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }*/
        if($esRepetida == 0){
          $newpassword = Hash::make($newpassword);
          DB::update('UPDATE ' .$nom_tabla .' SET password = ' ."'$newpassword'" .' WHERE correo = ? AND id_rol = ?', [$correo, $user]);

          DB::update('UPDATE users SET password = ' ."'$newpassword'" .' WHERE email = ? AND id_rol = ?', [$correo, $user]);

          $salida = array((object) array('nombre' => $nom_tabla, 'correo'=> $correo));
        }else{
          $salida = 'contraseña repetida';
        }
        return $salida;
	} 

    function sendEmailwithBody($name, $correo, $tipo, $mensaje){
        $url = 'http://localhost/proyecto/frontend/';
        $cuerpo_inicio = 
              '<!DOCTYPE html>
              <html>
                 <head>
                    <meta charset="utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                 </head>
                 <body>
                    <div style="background-color: white">
                       <table>
                          <tr>
                             <td style="background-color: white; padding: 0">
                                <div class="row" style="margin: 10px 80px 10px 15px">
                                   <img align="left" width="285" height="auto" style="display:block; margin: 2%" src="https://image.ibb.co/hbFKob/logocgesysc_37523795310_o.jpg">
                                   <img align="right" width="285" height="auto" style="display:block; margin: 1.5% 3%" src="https://image.ibb.co/hP6g1w/logotbc_37733343536_o.png">
                                </div>
                             </td>
                          </tr>
                          <tr>
                             <td>
                                <p style="height: 10px; background-color: #D60071; margin: 10px 80px 10px 15px"></p>
                             </td>
                          </tr>
                          <tr>
                             <td style="background-color: white">
                                <div style="color: #34495e; margin: 4% 10% 2%; text-align: justify;font-family: sans-serif">
                                  <h2 align="center">SISTEMA DE REGISTRO Y CONTROL DE TELEBACHILLERATOS COMUNITARIOS DEL ESTADO DE OAXACA</h2>
                                   <h3 style="color: #e67e22; margin: 0px 10px 15px -30px"> Hola estimado(a) ' .$name .'</h3>';

            $cuerpo_fin = 
                '<p style="color: #b3b3b3; font-size: 12px; text-align: center;margin: 30px 0 0">TELEBACHILLERATOS COMUNITARIOS DE OAXACA</p>
                                 </div>
                              </div>
                           </td>
                        </tr>
                     </table>
                  </div>
               </body>
            </html>';

        switch($tipo){ 
            case 'sendTokentoEmail': 
                $cuerpo_mensaje_token = 
                    '<p style="margin: 10px 100px 10px 10px; font-size: 15px justify">
                        <strong>si estás viendo esto, significa que solicitaste un cambio de contraseña para:</strong>
                     </p>
                     <ul style="margin: 10px 100px 10px 10px; font-size: 20px justify">
                        <ol>Usuario: ' .$correo .'</ol>
                     </ul>
                     <p style="margin: 10px 100px 10px 10px; font-size: 15px justify"><strong>
                        de no ser este el caso, por favor ignora este correo, de lo contrario copia el token de abajo y regresa a la página para comprobar el token.
                        </strong>
                     </p>
                     <br>
                     <div style="width: 100%; text-align: center">
                        <label>TOKEN:</label>
                        <br>
                        <br>
                        <div style="width: 100%; text-align: center">
                          <textarea rows="4" cols="50">' .$mensaje .'</textarea>  
                        </div>';
                $cuerpo = $cuerpo_inicio .$cuerpo_mensaje_token .$cuerpo_fin;
                return Correo::sendEmail($name, $correo, $cuerpo, 'solicitud de password');
            break;
            case 'sendNewPasswordtoEmail':
                $cuerpo_mensaje_newPassword = 
                    '<p style="margin: 10px 100px 10px 10px; font-size: 15px justify">
                        <strong>Mediante el presente correo electrónico le notificamos que el </strong>
                     </p>
                     <ul style="margin: 10px 100px 10px 10px; font-size: 20px justify">
                        <ol>Usuario: ' .$correo .'</ol>
                     </ul>
                     <p style="margin: 10px 100px 10px 10px; font-size: 15px justify"><strong>
                        ha cambiado la contraseña por una nueva que es </strong></p>
                     <br>
                     <div style="width: 100%; text-align: center">
                        <label>Password:</label>
                        <br>
                        <br>
                        <h3><strong>' .$mensaje .'</strong></h3>
                        <br>'; 
                $cuerpo = $cuerpo_inicio .$cuerpo_mensaje_newPassword .$cuerpo_fin;
                return Correo::sendEmail($name, $correo, $cuerpo, 'nueva password');
            break;
            case 'sendNotifyNewUsertoEmail':
                $cuerpo_mensaje_new_ususario = 
                    '<p style="margin: 10px 100px 10px 10px; font-size: 15px justify">
                        <strong>Mediante el presente correo electronico le notificamos que ha sido agregado exitosamente con los siguientes datos</strong>
                     </p>
                     <ul style="margin: 10px 100px 10px 10px; font-size: 20px justify">
                        <ol>Usuario: ' .$correo .'</ol>
                     </ul>
                     <br>
                     <div style="width: 100%; text-align: center">
                        <label>Password:</label>
                        <br>
                        <br>
                        <h3><strong>' .$mensaje .'</strong></h3>
                        <br>';
                $cuerpo = $cuerpo_inicio .$cuerpo_mensaje_new_ususario .$cuerpo_fin;
                return Correo::sendEmail($name, $correo, $cuerpo, 'registro exitoso');
            break;
        }   
    }
    function sendEmail($name, $correo, $cuerpo, $asunto){
      $mail = new PHPMailer(true);
      try { 
            $cuenta = 'villegas09sanz09@gmail.com';
            $contra = '1q2w3e4rtbc';
            //Server settings
            $mail->isSMTP();//Habilitar SMTP
            //$mail->SMTPDebug = 4;
            $mail->Host = 'smtp.gmail.com';//Definir el host
            $mail->SMTPAuth = true;//Habilitar Autenticacion
            $mail->Username = $cuenta;//Correo/Username del que envia
            $mail->Password = $contra;//Password del correo del que envia
            $mail->SMTPSecure = 'ssl';//Definir el tipo de seguridad, para gmail es ssl
            $mail->Port = 465;//Definir el puerto, para gmail es el 465
            
            //$mail->Port = 587;

            //Configuracion del Destinatario
            $mail->setFrom($cuenta, 'TBCOaxaca');
            $mail->addAddress($correo, $name);          // El segundo parametro es Opcionall
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Contenido del cuerpo del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            
            //$mail->Body = file_get_contents('paginas/correo/cuerpoRestorePassword.html');
            $mail->Body = $cuerpo;
            //$mail->AltBody = file_get_contents('paginas/correo/
            //cuerpoRestorePassword.html');
            $mail->AltBody = $cuerpo;
            $mail->smtpConnect(
                array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                )
            );
            $mail->send();
            $salida = array((object) array('mensaje' => 'success|El Email ha sido enviado'));
        } catch (Exception $e) {
            $salida = array((object) array('mensaje' => "warning|El Email no se pudo enviar -> " .$mail->ErrorInfo));
        }
        return $salida;
    }

}
