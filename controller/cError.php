<?php
/*  Nombre: Alvaro Garcia Gonzalez
*   Fecha: 13/03/2026
*   Uso:  controlador de la página de error*/ 
    $avError = [
    'codError' => '',
    'descError' => '',
    'archivoError' => '',
    'lineaError' => ''
    ];
    //SE recoge los datos del error guardados en la sesión
    if(isset($_SESSION['error'])){
        $oError=$_SESSION['error'];
        $avError=[
            'codError'=>$oError->getCodError(),
            'descError'=>$oError->getDescError(),
            'archivoError'=>$oError->getArchivoError(),
            'lineaError'=>$oError->getLineaError()
        ];
        unset($_SESSION['error']);
    }
    
    if(isset($_REQUEST['VOLVER'])){
        $_SESSION['paginaEnCurso']=$_SESSION['paginaAnterior'];
        header('Location: index.php');
        exit;
    }

    require_once $view['layout'];

?>