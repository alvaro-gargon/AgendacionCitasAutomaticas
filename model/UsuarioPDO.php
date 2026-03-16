<?php

/**
 * Clase Usuario que usaremos para la creacion de usuarios y gestionar estos objetos
 * Uso: clase Usuario con su constructor y sus atributos
 * @author Alvaro Garcia Gonzalez
 * @author Alejandro de la Huerga
 * @since 13/03/2026
 * @package model
 */
class UsuarioPDO {
    /**
     * Funcion que usara un correo para busacar un usuario unico
     * @param  string $correo , codigo que usaremos para buscar el departamento
     * @return array $oUsuarios, devuelve un array con los objetos usuarios correspondientes
     */
    public static function buscaUsuarioPorCorreo($correo) {
        //consulta sql para seleccionar todos los datos del departamento
        $consultaCorreo = <<<CONSULTA
                select * from usuarios
                where Correo='{$correo}'
                
                CONSULTA;
        $resultado = DBPDO::ejecutaConsulta($consultaCorreo);

        $aUsuarios = [];
        //si hay registro, crea el objeto departamento
        while ($registro = $resultado->fetchObject()) {
            $aUsuarios[] = new Usuario(
                    $registro->Correo,
                    $registro->Nombre,
                    $registro->Sistema,
            );
        }
        return $aUsuarios;
    }
}

?>