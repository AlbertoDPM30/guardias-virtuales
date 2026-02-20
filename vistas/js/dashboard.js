/* ACTUALIZAR STATUS DE LA SALA */
function actualizarStatusSala(id, status) {
  $.ajax({
    url: "endpoints/hall.endpoint.php",
    method: "PUT",
    contentType: "application/json",
    data: JSON.stringify({ id: id, status: status }),
    success: function (response) {
      console.log("Status actualizado:", response);
    },
    error: function (xhr, status, error) {
      console.error("Error al actualizar status:", error);
    },
  });
}

/* OBTENER GUARDIA */
const dataGuardia = null;
function obtenerGuardia(filtro, id_sala) {
  $.ajax({
    url: `endpoints/guardias.endpoint.php?${filtro}=${id_sala}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Guardia obtenida:", response);
      dataGuardia = response.data;
    },
    error: function (xhr, status, error) {
      console.error("Error al obtener guardia:", error);
    },
  });
}

$("#btn-obtener-guardia").on("click", function () {
  obtenerGuardia("id_sala", 1); // Ejemplo: obtener guardia por id_sala = 1
});

/* OBTENER TODAS LAS SALAS */
function obtenerSalas() {
  $.ajax({
    url: "endpoints/hall.endpoint.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
      const $salasContainer = $(".salas-container");
      $salasContainer.empty(); // Limpiar contenido previo

      response.data.forEach((sala) => {
        let statusClass;

        if (sala.status === 0) {
          statusClass = "bg-success";
        } else {
          statusClass = "bg-danger";
        }

        const $salaCard = $(`
          <div class="col-12 col-md-6 text-decoration-none btn-entrar-sala" data-status="${sala.status}" data-id="${sala.id}">
            <div class="card shadow-sm border-0 btn btn-primary text-bg-primary bg-gradient h-100">
              <div class="card-body">
              <h3 class="fw-bold mb-3">SALA: ${sala.id}</h3>
                <h5 class="badge ${statusClass}">${sala.status === 0 ? "Disponible" : "Ocupada"}</h5>
              </div>
            </div>
          </div>
        `);
        $salasContainer.append($salaCard);
      });

      $(".btn-entrar-sala").on("click", function (e) {
        e.preventDefault();
        if ($(this).attr("data-status") === "0") {
          actualizarStatusSala($(this).attr("data-id"), 1); // Marcar como ocupada
          window.location.href = `hall?id=${$(this).attr("data-id")}`;
        } else {
          alert("La sala está ocupada.");
        }
      });
    },
    error: function () {
      $(".salas-container").html(
        "<p class='text-danger'>Error al cargar las salas.</p>",
      );
    },
  });
}

$(document).ready(function () {
  /* CARGAR SALAS */
  obtenerSalas();
});
