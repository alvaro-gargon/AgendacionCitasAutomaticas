<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Mailtrap\MailtrapClient;
use Mailtrap\Entity\Email;
use Mailtrap\Entity\Address;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * CalendarModel | Clase Calendario de Google
 * 
 * Para la realización de la siguiente clase se han consultado diferentes fuentes oficiales:
 * 
 * Google Calendar API: https://developers.google.com/workspace/calendar/api/guides/overview?hl=es-419
 * Google APIs Client Library for PHP: https://github.com/googleapis/google-api-php-client
 * Google API PHP Client Docs: https://googleapis.github.io/google-api-php-client/main/
 * Microsoft API PHP: https://learn.microsoft.com/es-es/graph/outlook-calendar-online-meetings?tabs=php
 * Documentación de Composer: https://getcomposer.org/doc/
 * 
 * @author Alejandro De la Huerga
 * @author Alvaro Garcia Gonzalez
 * @since 12/03/2026
 */

class CalendarModel
{

    // Variable la cual contiene todos los métodos de la API de Google Calendar (Crear,Editar,Borrar ...)
    private $service;
    private $calendarId = 'alejandrodelahuerga@gmail.com';


    /**
     * __construct 
     * 
     * En el constructor utilizamos el archivo credentials.json el cual le indica
     * a Google que estamos autorizados y es una aplicación autorizada.
     *
     * @return void
     */

    public function __construct()
    {
        // Objeto encargado de la autenticación.
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../config/credentials.json');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->service = new Google_Service_Calendar($client);
    }

    /**
     * procesarCita Función static que procesa los datos del formulario.
     *
     * @return void 
     * 
     * @author Alejandro De la Huerga
     * @since 12/03/2026
     */

    public static function procesarCita()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Creamos un nuevo objeto de la clase CalendarModel.
            $modelo = new CalendarModel();
            try {
                //Utilizamos el método crearEvento del modelo.
                $modelo->crearEvento($_POST);
                // Si todo ha salido bien imprimimos por pantalla.
                echo "Cita creada con éxito.";
            } catch (Exception $e) {
                // Si ha ocurrido algún error.
                echo "Error: " . $e->getMessage();
            }
        }
    }

    /**
     * crearEvento
     * Recibe un array con los datos intriducidos en el formulario y los inserta en el calendario.
     *
     * @param  Array $datos | Array con los datos introducidos en el formulario.
     * @return Object del evento que acabamos de crear.
     */

    public function crearEvento($datos)
    {
        

        $calendarioDestino = $datos['correos'];
        foreach ($calendarioDestino as $emailId) {
            $aUsuarios = UsuarioPDO::buscaUsuarioPorCorreo($emailId);
        }
        foreach ($aUsuarios as $usuario) {
            switch ($usuario->getSistema()) {

                case 'APPLE': {
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->SMTPDebug  = 0; // Ponlo en 0 cuando funcione, 2 para depurar
                        $mail->Host       = 'smtp.qinamical.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'practicasweb@qinamical.com';
                        $mail->Password   = '9B9HkeLyd4X3&Dh%';
                        $mail->Port       = 587;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                        $mail->setFrom('practicasweb@qinamical.com', 'Sistema de Citas');
                        $mail->addAddress($usuario->getCorreo());

                        // ── Generar contenido del .ics ──────────────────────────────
                        $uid       = uniqid() . '@qinamical.com';
                        $ahora     = gmdate('Ymd\THis\Z');
                        $inicio    = date('Ymd\THis', strtotime($datos['fechaInicio'] . ' ' . $datos['horaInicio']));
                        $fin       = date('Ymd\THis', strtotime($datos['fechaFin']   . ' ' . $datos['horaFin']));
                        $asunto    = $datos['asunto'];
                        $notas     = $datos['observaciones'] ?? '';

                        $icsContent = "BEGIN:VCALENDAR\r\n"
                            . "VERSION:2.0\r\n"
                            . "PRODID:-//QinamicalCitas//ES\r\n"
                            . "CALSCALE:GREGORIAN\r\n"
                            . "METHOD:REQUEST\r\n"
                            . "BEGIN:VEVENT\r\n"
                            . "UID:{$uid}\r\n"
                            . "DTSTAMP:{$ahora}\r\n"
                            . "DTSTART:{$inicio}\r\n"
                            . "DTEND:{$fin}\r\n"
                            . "SUMMARY:{$asunto}\r\n"
                            . "DESCRIPTION:{$notas}\r\n"
                            . "END:VEVENT\r\n"
                            . "END:VCALENDAR\r\n";
                        // ───────────────────────────────────────────────────────────

                        $mail->addStringAttachment(
                            $icsContent,
                            'invite.ics',
                            'base64',
                            'text/calendar; charset=utf-8; method=REQUEST; name=invite.ics'
                        );

                        // Añade también esta cabecera extra
                        $mail->addCustomHeader('Content-Class', 'urn:content-classes:calendarmessage');

                        $mail->isHTML(false);
                        $mail->Subject = 'Nueva cita: ' . $asunto;
                        $mail->Body    = "Se ha programado una nueva cita.\n\nAsunto: {$asunto}\nInicio: {$datos['fechaInicio']} {$datos['horaInicio']}\nFin: {$datos['fechaFin']} {$datos['horaFin']}\nObservaciones: {$notas}";

                        $mail->send();
                        echo "Correo enviado con éxito a " . $usuario->getCorreo() . "<br>";

                    } catch (Exception $e) {
                        echo "<h1>Error Crítico de Envío</h1>";
                        echo "Mensaje de PHPMailer: " . $mail->ErrorInfo . "<br>";
                        echo "Excepción de PHP: " . $e->getMessage() . "<br>";
                        exit();
                    }
                }
                
                case 'GOOGLE': {
                        $eventData = [
                            'summary' => $datos['asunto'],
                            'description' => $datos['observaciones'],
                            // Formateamos las fechas al formato de Google Calendar (RFC3339) Ex: 2025-10-25T15:30:00.
                            'start' => [
                                'dateTime' => $datos['fechaInicio'] . 'T' . $datos['horaInicio'] . ':00',
                                'timeZone' => 'Europe/Madrid'
                            ],
                            'end' => [
                                'dateTime' => $datos['fechaFin'] . 'T' . $datos['horaFin'] . ':00',
                                'timeZone' => 'Europe/Madrid'
                            ],
                        ];

                        $event = new Google_Service_Calendar_Event($eventData);
                        $idsCreados = [];
                        foreach ($calendarioDestino as $emailId) {
                            try {
                                $resultado = $this->service->events->insert($emailId, $event);
                                $idsCreados[] = $resultado->getId();
                            } catch (Exception $e) {
                                throw new Exception("Error en el calendario $emailId: " . $e->getMessage());
                            }
                        }
                        // Send updates manda un aviso a todos los invitados.
                        return $idsCreados;
                }

            }

        }
            
    }
        

    /**
     * getCalendarId
     *
     * @return  String $calendarId
     */

    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * setCalendarId
     *
     * @param String $value
     * @return void
     */

    public function setCalendarId($value)
    {
        $this->calendarId = $value;
    }


}