<?php
    use Sabre\VObject; // Para manipular el archivo .ics fácilmente
    use Sabre\DAV\Client; // Para la conexión con SOGo
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
            
            if (!$aUsuarios) {
                echo "⚠️ No se encontró el usuario en la BD: $emailId <br>";
                continue;
            }

            foreach ($aUsuarios as $usuario) {
                $sistema = strtoupper($usuario->getSistema());

                switch ($sistema) {
                    case 'GOOGLE':
                        try {
                            $eventData = [
                                'summary' => $datos['asunto'],
                                'description' => $datos['observaciones'] ?? '',
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
                            // Insertamos usando el email del usuario actual
                            $resultado = $this->service->events->insert($usuario->getCorreo(), $event);
                            $idsCreadosGoogle[] = $resultado->getId();
                            echo "✅ Google: Evento creado para " . $usuario->getCorreo() . "<br>";
                        } catch (Exception $e) {
                            echo "❌ Google Error ($emailId): " . $e->getMessage() . "<br>";
                        }
                        break;

                    case 'SOGO':
                        $this->procesarSogo($usuario, $datos);
                        break;

                    case 'OUTLOOK':
                    case 'APPLE':
                        $this->procesarEnvioEmail($usuario, $datos);
                        break;

                    default:
                        echo "❓ Sistema desconocido para " . $usuario->getCorreo() . "<br>";
                        break;
                }
            }
        }
        return $idsCreadosGoogle;
    }

    /**
     * Lógica específica para insertar en SOGo vía CalDAV
     */
    private function procesarSogo($usuario, $datos) {
        $settings = [
            'baseUri'  => 'https://webmail.qinamical.com/SOGo/dav/',
            'userName' => $usuario->getCorreo(),
            'password' => '9B9HkeLyd4X3&Dh%', // Nota: Idealmente esto debería venir de la BD
        ];
        $client = new \Sabre\DAV\Client($settings);
        try {
            $uid = uniqid() . '-' . bin2hex(random_bytes(8));
            $vcalendar = new \Sabre\VObject\Component\VCalendar([
                'VEVENT' => [
                    'SUMMARY'     => $datos['asunto'],
                    'DTSTART'     => new DateTime($datos['fechaInicio'] . ' ' . $datos['horaInicio']),
                    'DTEND'       => new DateTime($datos['fechaFin'] . ' ' . $datos['horaFin']),
                    'DESCRIPTION' => $datos['observaciones'],
                    'UID'         => $uid,
                    'DTSTAMP'     => new DateTime('now', new DateTimeZone('UTC')),
                    'ORGANIZER'   => 'mailto:' . $usuario->getCorreo(),
                    'STATUS'      => 'CONFIRMED',
                ]
            ]);

            $url = $usuario->getCorreo() . '/Calendar/personal/' . $uid . ".ics";
            $response = $client->request('PUT', $url, $vcalendar->serialize(), ['Content-Type' => 'text/calendar']);

            if ($response['statusCode'] == 201 || $response['statusCode'] == 204) {
                echo "✅ SOGo: Cita insertada para " . $usuario->getCorreo() . "<br>";
            } else {
                echo "❌ SOGo Error (" . $response['statusCode'] . ") para " . $usuario->getCorreo() . "<br>";
            }
        } catch (Exception $e) {
            echo "❌ SOGo Excepción (" . $usuario->getCorreo() . "): " . $e->getMessage() . "<br>";
        }
    }

    /**
     * Lógica para enviar invitación .ics vía PHPMailer
     */
    private function procesarEnvioEmail($usuario, $datos) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.qinamical.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'practicasweb@qinamical.com';
            $mail->Password   = '9B9HkeLyd4X3&Dh%';
            $mail->Port       = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom('practicasweb@qinamical.com', 'Sistema de Citas');
            $mail->addAddress($usuario->getCorreo());

            $uid    = uniqid() . '@qinamical.com';
            $ahora  = gmdate('Ymd\THis\Z');
            $inicio = date('Ymd\THis', strtotime($datos['fechaInicio'] . ' ' . $datos['horaInicio']));
            $fin    = date('Ymd\THis', strtotime($datos['fechaFin']   . ' ' . $datos['horaFin']));

            $icsContent = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nMETHOD:REQUEST\r\nBEGIN:VEVENT\r\n"
                        . "UID:{$uid}\r\nDTSTAMP:{$ahora}\r\nDTSTART:{$inicio}\r\nDTEND:{$fin}\r\n"
                        . "SUMMARY:{$datos['asunto']}\r\nDESCRIPTION:{$datos['observaciones']}\r\n"
                        . "END:VEVENT\r\nEND:VCALENDAR\r\n";

            $mail->addStringAttachment($icsContent, 'invite.ics', 'base64', 'text/calendar; charset=utf-8; method=REQUEST');
            $mail->addCustomHeader('Content-Class', 'urn:content-classes:calendarmessage');
            $mail->Subject = 'Nueva cita: ' . $datos['asunto'];
            $mail->Body    = "Se ha programado una nueva cita para el " . $datos['fechaInicio'];

            $mail->send();
            echo "✅ Email: Invitación enviada a " . $usuario->getCorreo() . "<br>";
        } catch (Exception $e) {
            echo "❌ Email Error (" . $usuario->getCorreo() . "): " . $mail->ErrorInfo . "<br>";
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