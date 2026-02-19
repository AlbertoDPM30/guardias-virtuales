$(document).ready(function () {
  function obtenerSalas() {
    $.ajax({
      url: "ajax/hall.ajax.php",
      method: "GET",
      dataType: "json",
      success: function (response) {
        const $salasContainer = $(".salas-container");
        $salasContainer.empty(); // Limpiar contenido previo

        response.forEach((sala) => {
          const $salaCard = $(`
                        <a href="hall?id=${sala.id}" class="col-12 col-md-6 text-decoration-none bg-text-primary">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h3 class="fw-bold mb-3">${sala.numero}</h3>
                                    <p class="mb-0">ID: ${sala.id}</p>
                                </div>
                            </div>
                        </a>
                    `);
          $salasContainer.append($salaCard);
        });
      },
    });
  }

  obtenerSalas();
});
