<?php
/*  Nombre: Alvaro Garcia Gonzalez
    *   Fecha: 11/03/2026
    *   Uso:  controlador del formulario*/

use Google\Service\Calendar;

require_once __DIR__ . '/../model/Calendar.php';
/**
 * Boton cancelar que te devuelve al login si el usuario decide no registrarse
 */
if (isset($_REQUEST['CANCELAR'])) {
    $_SESSION['paginaEnCurso'] = 'vistaPrincipal';
    header('Location: index.php');
    exit;
}

$entradaOK = true; //boolean para comprobar si el formulario esta correcto o no

//array donde recojo los errores si los hubiera
$aErrores = [
    'fechaInicio' => null,
    'horaInicio' => null,
    'fechaFin' => null,
    'horaFin' => null,
    'asunto' => null,
    'observaciones' => null
];

//array donde recojo las respuestas
$aRespuestas = [
    'fechaInicio' => null,
    'horaInicio' => null,
    'fechaFin' => null,
    'horaFin' => null,
    'asunto' => null,
    'observaciones' => null
];

/**
 * acciones que pasaran si el usuario intenta registrarse
 */
if (isset($_REQUEST['GUARDAR'])) {
    //comprobamos que la lista de correos no esta vacia
    if (empty($_REQUEST['correos'])) {
        $entradaOK=false;
        $aErrores['observaciones']="La lista de correos no puede estar vacia";
    }

//validamos los diferentes campos del formulario
    $aErrores['fechaInicio'] = validacionFormularios::validarFecha($_REQUEST['fechaInicio'], obligatorio: 1); //validacion sintactica del campo fechayhora
    $aErrores['horaInicio'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['horaInicio'], obligatorio: 1); //validación de la hora
    $aErrores['fechaFin'] = validacionFormularios::validarFecha($_REQUEST['fechaFin'], obligatorio: 1); //validacion sintactica del campo fechayhora
    $aErrores['horaFin'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['horaFin'], obligatorio: 1); //validación de la hora
    $aErrores['asunto'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['asunto'], 32, 4, obligatorio: 1); //validacion alfabtica del campo asunto
    foreach ($aErrores as $clave => $valor) {
        if ($valor != null) {
            $entradaOK = false;
        }
    }
} else {
    $entradaOK = false;
}


if ($entradaOK) {

    CalendarModel::procesarCita();
    //$_SESSION['paginaEnCurso']='vistaPrincipal';
    //header('Location: index.php');
    //exit;
}
require_once $view['layout'];
