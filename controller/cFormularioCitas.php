<?php
    /*  Nombre: Alvaro Garcia Gonzalez
    *   Fecha: 11/03/2026
    *   Uso:  controlador del formulario*/

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
        'fechayhora'=>null,
        'asunto'=>null,
        'observaciones'=>null
    ];
    //array donde recojo las respuestas
    $aErrores=[
        'fechayhora'=>null,
        'asunto'=>null,
        'observaciones'=>null
    ];
    /**
     * acciones que pasaran si el usuario intenta registrarse
     */
    if(isset($_REQUEST['ACEPTAR'])){
        $aErrores['fechayhora']= validacionFormularios::validarFecha($_REQUEST['fechayhora'],obligatorio:1);//validacion sintactica del campo fechayhora
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
        $_SESSION['paginaEnCurso']='vistaPrincipal';
        header('Location: index.php');
        exit;
    }
    require_once $view['layout'];
?>