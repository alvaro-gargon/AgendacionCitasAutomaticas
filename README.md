# AGENDADOR DE CITAS MULTI PLATAFORMA

Este proyecto consta de un gestor de citas el cual su principal función es centralizar la tarea de las gestiones y avisos de citas en equipos de trabajo de dos o mas personas, permitiendo asi que la persona administradora sea capaz mediante un solo formulario agendar dicha cita a todas las personas de manera automatica sin necesidad de que el usuario tenga que hacer nada.

Este proyecto ha supuesto para nosotros un gran reto debido a las tan diferentes tecnologias y formas de comunicación que utilizan las diferentes empresas en cuanto a los web services (APIS) o al sistema de calendario que tiene dicha aplicación.

**ÍNDICE**
 1. [Guía de instalación](#guía-de-instalación)
 2. [Requisitos](#requisitos)
 3. [Configuración del proyecto](#configuración-del-proyecto)
 4. [Modo de uso de la aplicación](#modo-de-uso-de-la-aplicacion)
 5. [Estructura de directorios](#estructura-de-directorios)
 6. [Autores](#autores)

## GUÍA DE INSTALACIÓN

En cuanto a la guía de instalación no es nada complicado debido a que estamos hablando de una aplicación web la cual nos permitira acceder a ella a traves de cualquier navegador.

De todos modos si vas a optar por la visualización de la aplicación de manera local, te recomiendo el uso de Laragon ya que nos permite desde un mismo programa la ejecución de la base de datos , su administración y creación de live server de manera local.

### REQUISITOS

- Sistema Operativo Servidor Web (Ubuntu Server).
- Servidor Web (Apache Server).
- Laragon (Local , con esta opción podemos eliminar las dos anteriores). https://laragon.org/
- MySQL (Para poder gestionar la Base de Datos, incluido en Laragon).  https://www.mysql.com/
- PHP (Laragon nos permitira optar por cualquier versión). https://www.php.net/
- Navegador Web (Mozilla Firefox ,Chrome, Edge ...).

### CONFIGURACIÓN DEL PROYECTO

En cuanto a la configuración del proyecto y de nuestro Calendario para sacarle el máximo partido a la aplicación debemos de realizar una serie de pasos los cuales vamos a explicar detalladamente: 

- CONFIGURAR CORREO ELECTRÓNICO (Debemos darle permisos al usuario para poder escribir en nuestro correo).

- ACCEDER MEDIANTE UN USUARIO ADMINISTRADOR (Solamente una cuenta administrador sera capaz de agendar citas a los demás personas).

- CLONAR EL REPOSITORIO (Realizar la descarga o clonamiento del repositorio).

- CAMBIAR LOS ARCHIVOS DE CONFIGURACIÓN (Es donde va la conexión con la base de datos y todo lo necesario).

### MODO DE USO DE LA APLICACION

La aplicación tiene un uso muy simple e intuitivo para cualquier persona que entre por primera vez en ella.

Una vez iniciada sesión la aplicación nos mostrara un formulario con los diferentes campos que debemos rellenar con la información de la cita que queremos agendar.

Una vez se muestra el mensaje de "Cita agendada con exito" nuestra cita estara disponible y marcada en los correos de aquellas cuentas que hayamos marcado en dicho calendario.

### ESTRUCTURA DE DIRECTORIOS

La siguiente infromación tendra que ver con la estructura de directorios de la aplicación web: 

- **/config** : Es donde se encuentran los diferentes archivos de configuración de la aplicación.

- **/controller** : Directorio con todos los controladores de la aplicación.

- **/core**: Se encuentra la libreria de validación del formulario.

- **/model**: Todos los modelos de la aplicación.

- **/scriptsDB**: Los scripts de creación de la base de datos.

- **/vendor**: Es donde se encuentran todas las dependencias instaladas mediante Composer.

- **/view**: Todas las vistas de nuestra aplicación.

- **/webroot**: Contiene el archivo de estilos css y el directorio doc.

- **/doc**: Contiene el directorio images (Todas las imagenes que requiere nuestra aplicación) y el directorio Documentos.

- **/Documentos**: Contiene la documentación de la aplicación y las documentaciones de las diferentes APIS de correos que se utilizan.

- **index.php**: Es el index principal de la aplicación.

- **LICENSE**: Archivo con la licencia de la aplicación y toda la información necesaria.

### AUTORES

Esta aplicación ha sido realizada en igual procentaje de colaboración por [Alejandro De la Huerga Fernández](https://github.com/alejandrohuerga) y [Álvaro García González](https://github.com/alvaro-gargon) durante su periodo en las prácticas del ciclo Superior de Desarrollo de Aplicaciones Web en la empresa Qinamical y Qinetical (La Bañeza).




