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
     * Funcion que usara un codigo de departamento dado para busacar un departamento unico
     * @param  string $codDepartamento , codigo que usaremos para buscar el departamento
     * @return Departamento $oDepartamento, devuelve un objeto departamento, ya sea con informacion o con valor null si ha habido algun error
     */
    public static function buscaUsuarioPorCorreo($correo) {
        //consulta sql para seleccionar todos los datos del departamento
        $consultaCorreo = <<<CONSULTA
                select * from usuarios
                where Correo='{$correo}'
                
                CONSULTA;
        $resultado = DBPDO::ejecutaConsulta($consultaCorreo);

        $aUsuario = [];
        //si hay registro, crea el objeto departamento
        while ($registro = $resultado->fetchObject()) {
            $aUsuario = new Usuario(
                    $registro->Correo,
                    $registro->Nombre,
                    $registro->Sistema,
            );
        }
        return $aUsuario;
    }
}

?>