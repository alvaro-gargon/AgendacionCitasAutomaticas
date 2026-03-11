<form method="post">
    <p>
        CITA
    </p>
    <p>
        <label>Introduce fecha y hora</label><br>
        <input class="obligatorio" type="datetime" name="fechayhora">
        <p class="error"><?php echo($aErrores['fechayhora'])?></p>
    </p>
    <p>
        <label>Asunto</label><br>
        <input class="obligatorio" type="text" name="asunto">
        <p class="error"><?php echo($aErrores['asunto'])?></p>
    </p>
    <p>
        <label>Observaciones</label><br>
        <input type="text" name="observaciones">
    </p>
    <button class="botonGenericoFormulario" type="submit" name="ACEPTAR">ACEPTAR</button>
    <button class="botonGenericoFormulario" name="CANCELAR">CANCELAR</button>
</form>