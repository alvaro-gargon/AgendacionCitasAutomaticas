<form action="index.php?action=guardar" method="POST">
    <p>
        <input class="obligatorio" type="date" name="fecha" value="<?php echo $aRespuestas['fecha'] ?>" required>
        <p class="error"><?php echo($aErrores['fecha'])?></p>
    </p>
    <p>
        <input class="obligatorio" type="time" name="hora" value="<?php echo $aRespuestas['hora'] ?>" required>
        <p class="error"><?php echo($aErrores['hora'])?></p>
    </p>
    <p>
        <input class="obligatorio" type="text" name="asunto" value="<?php echo $aRespuestas['asunto'] ?>" required>
        <p class="error"><?php echo($aErrores['asunto'])?></p>
    </p>
    <p>
        <textarea name="observaciones" value="<?php echo $aRespuestas['observaciones'] ?>"></textarea>
        <p class="error"><?php echo($aErrores['observaciones'])?></p>
    </p>
    <p>
        <button name="CANCELAR">Cancelar</button>
        <button type="submit" name="GUARDAR">Guardar</button>
    </p>
</form>