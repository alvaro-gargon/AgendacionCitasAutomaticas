<?php
/**
 * Este archivo maneja los distintos casos en los que el usuario intenta crear una cita
 * o evento
 */

/**
 * Si el usuario le da al boton de hacer una cita con google
 */
 if(isset($_REQUEST['google'])){
        $_SESSION['paginaEnCurso']='formulario';
        header('Location: index.php');
        exit;
    }
    require_once $view['layout'];
?>