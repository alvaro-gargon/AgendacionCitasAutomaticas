<?php
require_once __DIR__ . '/../vendor/autoload.php';

class CalendarModel {
    private $service;
    private $calendarId = 'tu_correo@gmail.com';

    public function __construct() {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../config/credentials.json');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $this->service = new Google_Service_Calendar($client);
    }

    public function crearEvento($datos) {
        $event = new Google_Service_Calendar_Event([
            'summary' => $datos['asunto'],
            'description' => $datos['observaciones'],
            'start' => ['dateTime' => $datos['fecha'] . 'T' . $datos['hora'] . ':00'],
            'end' => ['dateTime' => $datos['fecha'] . 'T' . date('H:i', strtotime($datos['hora'] . ' +1 hour')) . ':00'],
        ]);
        return $this->service->events->insert($this->calendarId, $event);
    }
}