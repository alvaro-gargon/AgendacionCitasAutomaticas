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
 * Documentación de Composer: https://getcomposer.org/doc/
 * 
 * @author Alejandro De la Huerga
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
            if ($usuario->getSistema() === 'GOOGLE') {
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
            }
        }


        // Send updates manda un aviso a todos los invitados.
        return $idsCreados;
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
