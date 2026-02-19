<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard">Guardia Virtual</a>
            <div class="navbar-nav ms-auto d-flex align-items-center flex-row gap-3">
                <b class="nav-link"><?php echo $_SESSION["nombres"] . " " . $_SESSION["apellidos"]; ?></b>

                <?php if((isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] === "ok")): ?>
                    <button class="btn btn-outline-danger btn-sm" id="btnLogout">Salir</button>
                <?php endif; ?>
            </div>
        </div>
    </nav>