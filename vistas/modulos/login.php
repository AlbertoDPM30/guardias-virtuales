<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 fw-bold text-primary">Guardia virtual</h2>
                    
                    <form method="POST" id="loginForm" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="cedula" class="form-label text-secondary">Cédula</label>
                            <input type="text" class="form-control form-control-lg" id="cedula" placeholder="Ingrese su identificación" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label text-secondary">Contraseña</label>
                            <input type="password" class="form-control form-control-lg" id="password" placeholder="********" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">Ingresar</button>
                        </div>
                    </form>

                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">&copy; <a href="https://consolitex.org">Consolitex Bienes Raíces</a> - Todos los derechos reservados</p>
        </div>
    </div>
</div>

<script src="vistas/js/login.js"></script>