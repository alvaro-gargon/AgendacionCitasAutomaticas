<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Gris muy claro profesional */
        }
        header {
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        main {
            min-height: calc(100vh - 160px); /* Ajuste para que el footer baje */
        }
        footer {
            background-color: #ffffff;
            border-top: 1px solid #dee2e6;
        }
    </style>
    <title>Agendación | Qinamical</title>
</head>
<body class="d-flex flex-column h-100">
    <header class="bg-white py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-bold text-primary">
                <span class="text-dark">Agendador</span> Automático
            </h2>
            <span class="badge bg-light text-secondary border">v1.0</span>
        </div>
    </header>
    <main class="flex-shrink-0 container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <?php require_once $view[$_SESSION['paginaEnCurso']]; ?>
            </div>
        </div>
    </main>
    <footer class="footer mt-auto py-4">
        <div class="container text-center">
            <p class="mb-0 text-muted small fw-semibold text-uppercase tracking-wider">
                &copy; <?php echo date("Y"); ?> &middot; Qinamical y Qinetica
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>