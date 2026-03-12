<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="webroot/styles.css">
    <title>Agendacion</title>
</head>
<body>
    <header>
        <h2>Agendación automático de citas</h2>
    </header>
    <main>
        <?php require_once $view[$_SESSION['paginaEnCurso']];?>
    </main>
    <footer>
        <p>Qinamical y Qinetica</p>
    </footer>
</body>
</html>