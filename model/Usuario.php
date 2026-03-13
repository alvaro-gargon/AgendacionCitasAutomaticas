<?php

/**
 * Clase Usuario que usaremos para la creacion de usuarios y gestionar estos objetos
 * Uso: clase Usuario con su constructor y sus atributos
 * @author Alvaro Garcia Gonzalez
 * @author Alejandro de la Huerga
 * @since 13/03/2026
 * @package model
 */
class Usuario {
    private $correo;
    private $nombre;
    private $sistema;

    public function __construct($correo,$nombre,$sistema) {
        $this->correo =$correo;
        $this->nombre =$nombre;
        $this->sistema =$sistema;
    }
    
    public function getCorreo() {
        return $this->correo;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getSistema() {
        return $this->sistema;
    }
    
    public function setCorreo($correo){
        $this->correo=$correo;
    }
    
    public function setNombre($nombre){
        $this->nombre=$nombre;
    }
    
    public function setSistema($sistema){
        $this->sistema=$sistema;
    }
}

?>