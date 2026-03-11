<?php
/**  
*   
*   Clase DBPDO
*   Uso:  clase DBPDO con su metodos que usaremos para manejar la base de datos
*   @author Alvaro Garcia Gonzalez 
*   @author Alejandro de la HUerga
*   @since: 18/12/2025
*   @package model
*/

class DBPDO {

    /**
     * Esta funcion sirve para ejecutar una consulta dada.
     * @param string $sentenciaSQL , parametro que sera la sentencia sql a ejecutar
     * @param  $parametros , es un parametro opcional para si la sentencia los necesita
     * @return ? Devuelve una consulta preparada si todo ha ido bien (tipo variable), o te envía a una página de error si hay algun fallo 
     */
    public static function ejecutaConsulta($sentenciaSQL,$parametros=null) {
    try{
        $miDB=new PDO(DNS,USERNAME,PASSWORD);
        $consultaPreparada=$miDB->prepare($sentenciaSQL);
        $consultaPreparada->execute($parametros);
        return $consultaPreparada;
    }catch(PDOException $exception){
        $_SESSION['paginaAnterior'] = $_SESSION['paginaEnCurso'];
        $_SESSION['paginaEnCurso'] = 'error';
        $_SESSION['error'] = new AppError($exception->getCode(),$exception->getMessage(),$exception->getFile(),$exception->getLine());
        header('Location: index.php');
        exit;
    }finally{
        unset($miDB);
    }
  }
}
?>