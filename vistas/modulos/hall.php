
<script src="libraries/peerjs/peerjs.min.js"></script>
<div class="bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-3">
                <div class="card shadow-sm border-0 mb-3 text-center">
                    <div class="card-body">
                        <small class="text-muted fw-bold text-uppercase">ID de Sala</small>
                        <h2 class="fw-bold text-primary mb-1" id="peer-id">...</h2>
                        <div id="status-badge" class="badge bg-secondary mb-3">OFFLINE</div>
                        <button id="close-room-btn" class="btn btn-danger btn-sm w-100 d-none">Cerrar Sala (Finalizar)</button>
                    </div>
                </div>

                <div id="incoming-call-box" class="card border-primary d-none shadow-sm animate__animated animate__bounceIn">
                    <div class="card-body text-center">
                        <p class="mb-2 fw-bold text-primary">¡Invitado llamando!</p>
                        <div class="d-grid gap-2">
                            <button id="accept-btn" class="btn btn-success">Aceptar</button>
                            <button id="reject-btn" class="btn btn-outline-secondary btn-sm">Rechazar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-9">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="bg-dark rounded shadow position-relative" style="aspect-ratio: 16/9;">
                            <video id="local-video" autoplay muted playsinline class="w-100 h-100 rounded" style="object-fit: cover;"></video>
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark opacity-75">Tú (Host)</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="bg-dark rounded shadow position-relative d-flex align-items-center justify-content-center" style="aspect-ratio: 16/9;">
                            <div id="mic-icon-guest" class="text-white text-center d-none">
                                <i class="bi bi-mic-fill" style="font-size: 3rem;"></i>
                                <p class="small m-0">Invitado sin video</p>
                            </div>
                            <video id="remote-video" autoplay playsinline class="w-100 h-100 rounded" style="object-fit: cover;"></video>
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-primary opacity-75">Invitado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vistas/js/hall.js"></script>
</div>