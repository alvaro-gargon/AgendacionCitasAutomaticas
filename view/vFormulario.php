<form action="index.php?action=guardar" method="POST">
    <p>
        <input type="datetime-local" name="fechayhora" required>
    </p>
    <p>
        <input type="text" name="asunto" required>
    </p>
    <p>
        <textarea name="observaciones"></textarea>
    </p>
    <p>
        <button type="submit">Guardar</button>
    </p>
</form>