<?php

/*  Nombre: Nombre: 
    Alvaro Garcia Gonzalez
    Alejandro de la Huerga 
*   Fecha: 11/03/2026
*   Uso:  requires de todos los archivos del modelo necesitado*/ 
//incluyo la libreria de validacion
require_once 'core/231018libreriaValidacion.php';

//aqui se incluyen todos los archivos del modelo
require_once 'model/DBPDO.php';

//array para cargar los archivos del controlador
$controller=[
    'inicioPublico'=>'controller/cInicioPublico.php',
    'vistaPrincipal' => 'controller/cVistaPrincipal.php'
];

//array para cargar los archivos de la vista
$view=[
    'layout' => 'view/vLayout.php',
    'inicioPublico' => 'view/vInicioPublico.php',
    'vistaPrincipal' => 'view/vVistaPrincipal.php'
];
?>