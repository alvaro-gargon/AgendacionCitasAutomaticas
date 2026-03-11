<?php
 if(isset($_REQUEST['loginGOOGLE'])){
        $_SESSION['paginaEnCurso']='vistaPrincipal';
        header('Location: index.php');
        exit;
    }
    require_once $view['layout'];
?>