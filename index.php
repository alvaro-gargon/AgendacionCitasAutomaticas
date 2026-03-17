<?php
    require_once 'vendor/autoload.php';
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
        /* Nombre: 
        Alvaro Garcia Gonzalez
        Alejandro de la Huerga 
        * Fecha: 11/03/2026
        * Uso:  index de la aplicacion*/
        
        //incluyo la configuracion 
        require_once 'config/confAPP.php';
        require_once 'config/confDBPDO.php';
        //inicio la sesion
        session_start();
        
        if(!isset($_SESSION['paginaEnCurso'])){
            $_SESSION['paginaEnCurso']='inicioPublico';
        }
        require_once $controller[$_SESSION['paginaEnCurso']];
    ?>