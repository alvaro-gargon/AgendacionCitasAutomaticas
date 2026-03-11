<?php
/**
 * Controlador que manejara las dos posibilidades de login (Google o IOS)
 */
// si el usuario se conecta con google
 if(isset($_REQUEST['loginGoogle'])){
        $_SESSION['paginaEnCurso']='vistaPrincipal';
        header('Location: index.php');
        exit;
    }
    require_once $view['layout'];
?>