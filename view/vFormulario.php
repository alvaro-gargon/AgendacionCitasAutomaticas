<button name="CANCELAR">Volver</button>
<form action="index.php?action=guardar" method="POST">
    <p>
        <label for="fechaInicio">Fecha inicio:</label>
        <input class="obligatorio" type="date" name="fechaInicio" value="<?php echo $aRespuestas['fechaInicio'] ?>">
        <p class="error"><?php echo($aErrores['fechaInicio'])?></p>
    </p>
    <p>
        <label for="fechaFin">Hora Inicio:</label>
        <input class="obligatorio" type="time" name="horaInicio" value="<?php echo $aRespuestas['horaInicio'] ?>">
        <p class="error"><?php echo($aErrores['horaInicio'])?></p>
    </p>
    <p>
        <label for="fechaInicio">Fecha fin:</label>
        <input class="obligatorio" type="date" name="fechaFin" value="<?php echo $aRespuestas['fechaFin'] ?>">
        <p class="error"><?php echo($aErrores['fechaFin'])?></p>
    </p>
    <p>
        <label for="fechaInicio">Hora fin:</label>
        <input class="obligatorio" type="time" name="horaFin" value="<?php echo $aRespuestas['horaFin'] ?>">
        <p class="error"><?php echo($aErrores['horaFin'])?></p>
    </p>
    <p>
        <label for="fechaInicio">Asunto:</label>
        <input class="obligatorio" type="text" name="asunto" value="<?php echo $aRespuestas['asunto'] ?>">
        <p class="error"><?php echo($aErrores['asunto'])?></p>
    </p>
    <p>
        <label for="fechaInicio">Observaciones:</label>
        <textarea name="observaciones" value="<?php echo $aRespuestas['observaciones'] ?>"></textarea>
        <p class="error"><?php echo($aErrores['observaciones'])?></p>
    </p>
    <p>
        <label for="Usuarios">Usuarios: (Indica a que personas quieres agendarle la cita)</label></br>

        <label for="alejandrohuerga">alejandrohuerga</label>
        <input type="checkbox" id="alejandrohuerga" name="correos[]" value="alejandrodelahuerga@gmail.com">
        <label for="webqinamical">webqinamical</label>
        <input type="checkbox" id="webqinamical" name="correos[]" value="webqinamical@gmail.com">
        
    </p>
    <p>
        <button type="submit" name="GUARDAR">Guardar</button>
    </p>
</form>