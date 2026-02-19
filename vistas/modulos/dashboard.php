<div class="container">
    <div class="d-flex flex-column align-items-center justify-content-between mb-4">
        <h3>Bienvenido a la Guardia Virtual</h3>
        <h2 class="fw-bold"> <?php echo $_SESSION["nombres"]. " ".$_SESSION["apellidos"]; ?></h2>
    </div>
    <div class="row g-4">

        <a href="hall" class="col-12 col-md-6 text-decoration-none bg-text-primary">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">Sala de Videoconferencia</h3>
                    <p class="mb-0">Contenido del dashboard aquí...</p>
                </div>
            </div>
        </a>
    </div>
</div>