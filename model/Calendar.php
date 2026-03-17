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
        }
        foreach ($aUsuarios as $usuario) {
            switch ($usuario->getSistema()) {
                case 'OUTLOOK':
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
                
                /**
                 * Caso SOGO : Mediante este caso la empresa podrá agendar citas en el calendario
                 * del proveedor de correo electrónico SoGo que tiene Calendario implementado.
                 * 
                 * Se usara el protocolo CalDav el cual permitira agendar citas sin necesidad de invitación.
                 * Líbreria en uso de PHP: sabre/dav.
                 * Se enviara el archivo .ics directamente al servidor para no tener que aceptar la invitación
                 * mediante un comando PUT.
                 * 
                 * La implementación se puede realizar de dos maneras diferentes:
                 * 1. Mediante la obtención de los credenciales de los usuarios.
                 * 2. La utilización de una cuenta administrador con permisos.
                 * 
                 * @author Alejandro De la Huerga | Álvaro García
                 * @since 17/03/2026
                 * @version 1.0.0
                 */

                case "SOGO" : {
                    // 1. Datos de acceso
                    $settings = [
                        'baseUri'  => 'https://webmail.qinamical.com/SOGo/dav/', // Añadido /SOGo/dav/ que es el estándar
                        'userName' => $usuario->getCorreo(),
                        'password' => '9B9HkeLyd4X3&Dh%',
                    ];

                    $client = new \Sabre\DAV\Client($settings);

                    try {
                        // 2. Generar el identificador único
                        $uid = uniqid() . '-' . bin2hex(random_bytes(8));
                        $filename = $uid . ".ics";

                        // 3. Crear el formato ICS
                        // Eliminamos el ATTENDEE para que no genere una invitación pendiente.
                        // Al ser solo ORGANIZER, entra como cita directa.
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
                                'SEQUENCE'    => 0,
                                'TRANSP'      => 'OPAQUE', 
                                'CLASS'       => 'PUBLIC',
                            ]
                        ]);

                        // SOGo espera: /SOGo/dav/usuario@dominio.com/Calendar/personal/archivo.ics
                        // Como el baseUri ya tiene el prefijo, aquí usamos la ruta relativa
                        $urlCalendario = $usuario->getCorreo() . '/Calendar/personal/' . $filename;

                        // 5. Ejecutar la subida con Header de contenido específico
                        $headers = [
                            'Content-Type' => 'text/calendar; charset=utf-8'
                        ];

                        $response = $client->request('PUT', $urlCalendario, $vcalendar->serialize(), $headers);

                        if ($response['statusCode'] == 201 || $response['statusCode'] == 204) {
                            echo "Éxito: Cita insertada directamente en el calendario de " . $usuario->getCorreo();
                        } else {
                            echo "Error: SOGo devolvió código " . $response['statusCode'];
                        }

                    } catch (\Exception $e) {
                        echo "Error de conexión SOGo: " . $e->getMessage();
                    }
                    break;
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