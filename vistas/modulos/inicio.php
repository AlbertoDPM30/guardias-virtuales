<script src="libraries/peerjs/peerjs.min.js"></script>
<script src="vistas/js/app.js"></script>

<div class="bg-light vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center g-4">
            <div class="col-12 col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Entrar a Sala</h5>
                        <input type="number" id="remote-id" class="form-control mb-3" placeholder="ID numérico">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="video-toggle">
                            <label class="form-check-label" for="video-toggle">Enviar mi video</label>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="join-btn" class="btn btn-primary">Llamar</button>
                            <button id="hangup-btn" class="btn btn-outline-danger d-none">Desconectarse</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-7">
                <div class="bg-dark rounded shadow position-relative" style="aspect-ratio: 16/9;">
                    <video id="remote-video" autoplay playsinline class="w-100 h-100 rounded" style="object-fit: cover;"></video>
                    <span class="position-absolute bottom-0 start-0 m-2 badge bg-primary">Ejecutivo</span>
                    
                    <div id="local-preview" class="position-absolute top-0 end-0 m-2 d-none" style="width: 140px; aspect-ratio: 16/9;">
                        <video id="local-video" autoplay muted playsinline class="w-100 h-100 rounded border border-white" style="object-fit: cover;"></video>
                    </div>
                </div>
                <div id="status" class="text-center mt-2 small text-muted">Listo</div>
            </div>
        </div>
    </div>
</div>