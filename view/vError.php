<form method="post">
    <button class="volver" name="VOLVER">VOLVER</button>
</form>

<h3>¡Vaya! Ha ocurrido un error... </h3>
<div class="mensaje">
    <p>Codigo de error: <?php echo $avError['codError']; ?></p>
    <p>Descripción del error: <?php echo $avError['descError']; ?></p>
    <p>Archivo del error: <?php echo $avError['archivoError']; ?></p>
    <p>Línea del error: <?php echo $avError['lineaError']; ?></p>
</div>