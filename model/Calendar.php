<?php
require_once __DIR__ . '/../vendor/autoload.php';


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
                case 'OUTLOOK': {
                        $tenantId = "400bc7a3-ff8b-4be9-9791-b00b7afff0b5";
                        $clientId = "3dcac8b3-d98d-4619-a691-c05da5f1e0db";
                        $clientSecret = "ef78bbee-742c-4b72-841c-f899cdb56b47";

                        $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

                        $data = [
                            "client_id" => $clientId,
                            "client_secret" => $clientSecret,
                            "scope" => "https://graph.microsoft.com/.default",
                            "grant_type" => "client_credentials"
                        ];

                        $ch = curl_init();

                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,  // La URL de token
                            CURLOPT_RETURNTRANSFER => true,  // Queremos recibir la respuesta
                            CURLOPT_POST => true,  // Usamos el método POST
                            CURLOPT_POSTFIELDS => http_build_query($data),  // Los datos que se enviarán en la solicitud
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: application/x-www-form-urlencoded"  // Formato correcto de los datos
                            ]
                        ]);

                        $response = curl_exec($ch);
                        curl_close($ch);
                        // -------------------------------------------------------------------------------------------
                        // Mostrar la respuesta completa para depuración
                        if (curl_errno($ch)) {
                            echo 'Error cURL: ' . curl_error($ch);
                        }

                        // Mostrar la respuesta JSON para ver qué contiene
                        echo "<pre>";
                        print_r($response);
                        echo "</pre>";
                        // -------------------------------------------------------------------------------------------
                        $result = json_decode($response, true);

                        // -------------------------------------------------------------------------------------------
                        // Verificar si se devolvió el token
                        if (isset($result['access_token'])) {
                            return $result['access_token'];
                        } else {
                            echo "Error: No se pudo obtener el access_token";
                            echo "<pre>";
                            print_r($result);  // Muestra el error completo de la respuesta
                            echo "</pre>";
                            return null;
                        }
                        // -------------------------------------------------------------------------------------------
                        // Obtener el access token
                        $token = $result['access_token'];
                        // Email del usuario
                        $emailUsuario = $usuario->getCorreo();
                        // Crear el evento (con sus detalles)
                        $evento = [
                            "subject" => $datos['asunto'],
                            "body" => [
                                "contentType" => "HTML",
                                "content" => $datos['observaciones']
                            ],
                            "start" => [
                                "dateTime" => $datos['fechaInicio'] . 'T' . $datos['horaInicio'] . ':00',
                                "timeZone" => "Europe/Madrid"
                            ],
                            "end" => [
                                "dateTime" => $datos['fechaFin'] . 'T' . $datos['horaFin'] . ':00',
                                "timeZone" => "Europe/Madrid"
                            ],
                            "attendees" => [
                                [
                                    "emailAddress" => [
                                        "address" => $emailUsuario, // Email del invitado
                                        "name" => "Invitado Outlook"
                                    ],
                                    "type" => "required" // Tipo de asistencia (obligatorio)
                                ]
                            ]
                        ];

                        // URL para crear el evento en el calendario de Outlook
                        $url = "https://graph.microsoft.com/v1.0/me/events"; // "me" se refiere a tu cuenta de Outlook

                        // Inicializar cURL
                        $ch = curl_init();

                        // Configuración de la petición
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_HTTPHEADER => [
                                "Authorization: Bearer $token",
                                "Content-Type: application/json"
                            ],
                            CURLOPT_POSTFIELDS => json_encode($evento)
                        ]);

                        // Ejecutar la petición
                        $response = curl_exec($ch);
                        curl_close($ch);

                        // Procesar la respuesta
                        $resultado = json_decode($response, true);

                        if (isset($resultado['id'])) {
                            return $resultado['id']; // Retorna el ID del evento creado
                        } else {
                            return null; // Si ocurre un error
                        }
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
