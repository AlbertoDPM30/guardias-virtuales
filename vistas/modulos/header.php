<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><?php echo ($_GET["ruta"] == "hall") ? "Guardia" : "Asesoría"; ?></a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link <?php echo ($_GET["ruta"] == "hall") ? "active" : ""; ?>" href="hall">Host</a>
                <a class="nav-link <?php echo ($_GET["ruta"] == "inicio") ? "active" : ""; ?>" href="inicio">Client</a>

                <?php if((isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] === "ok")): ?>
                    <button class="btn btn-outline-danger btn-sm" id="btnLogout">Salir</button>
                <?php endif; ?>
            </div>
        </div>
    </nav>