<?php
    /*  Nombre: Alvaro Garcia Gonzalez
    *   Fecha: 11/03/2026
    *   Uso:  controlador del formulario*/

use Google\Service\Calendar;

    require_once __DIR__ . '/../model/Calendar.php';
    /**
     * Boton cancelar que te devuelve al login si el usuario decide no registrarse
     */
    if(isset($_REQUEST['CANCELAR'])){
        $_SESSION['paginaEnCurso']='vistaPrincipal';
        header('Location: index.php');
        exit;
    }

    $entradaOK=true; //boolean para comprobar si el formulario esta correcto o no
    //array donde recojo los errores si los hubiera
    $aErrores=[
        'fecha'=>null,
        'hora'=>null,
        'asunto'=>null,
        'observaciones'=>null
    ];
    //array donde recojo las respuestas
    $aRespuestas=[
        'fecha'=>null,
        'hora'=>null,
        'asunto'=>null,
        'observaciones'=>null
    ];
    /**
     * acciones que pasaran si el usuario intenta registrarse
     */
    if(isset($_REQUEST['GUARDAR'])){
        //validamos los diferentes campos del formulario
        $aErrores['fecha']= validacionFormularios::validarFecha($_REQUEST['fecha'],obligatorio:1);//validacion sintactica del campo fechayhora
        $aErrores['hora']=validacionFormularios::comprobarAlfaNumerico($_REQUEST['hora'],obligatorio:1);//validación de la hora
        $aErrores['asunto']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['asunto'],32,4,obligatorio:1);//validacion alfabtica del campo asunto
        foreach ($aErrores as $clave => $valor){
            if($valor!=null){
                $entradaOK=false;
            }
        }
    }else{
        $entradaOK=false;
    }
    
    
    if($entradaOK){
        CalendarModel::procesarCita();
        //$_SESSION['paginaEnCurso']='vistaPrincipal';
        //header('Location: index.php');
        //exit;
    }
    require_once $view['layout'];
?>