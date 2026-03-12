<?php
require_once __DIR__ . '/../models/Calendar.php';

class CalendarController {
    public function procesarCita() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modelo = new CalendarModel();
            try {
                $modelo->crearEvento($_POST);
                echo "Cita creada con éxito.";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}