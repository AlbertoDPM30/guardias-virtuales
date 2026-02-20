<div class="container">
    <div class="d-flex flex-column align-items-center justify-content-between mb-4">
        <h3>Bienvenido a la Guardia Virtual</h3>
        <h2 class="fw-bold"> <?php echo $_SESSION["nombres"]. " ".$_SESSION["apellidos"]; ?></h2>
    </div>
    <div class="row g-4 salas-container">
        <div class="col-12 col-md-6 text-decoration-none">
            <h3 class="fw-bold mb-3">Error</h3>
            <p class="mb-0">No se pudieron cargar las salas</p>
        </div>
    </div>
</div>

<script src="vistas/js/dashboard.js"></script>