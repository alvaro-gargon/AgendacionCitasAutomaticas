<?php
// Instanciamos el modelo de Google Calendar.
require_once __DIR__ . '/../models/Calendar.php';

/**
 * CalendarController Controlador de Google Calendar.
 * 
 * @author Alejandro De la Huerga
 * @since 12/03/2026
 */

class CalendarController {
        
    /**
     * procesarCita Función static que procesa los datos del formulario.
     *
     * @return void 
     * 
     * @author Alejandro De la Huerga
     * @since 12/03/2026
     */
    public static function procesarCita() {
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
}