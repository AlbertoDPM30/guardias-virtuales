/* ACTUALIZAR STATUS DE LA SALA */
function actualizarStatusSala(id, status) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "endpoints/hall.endpoint.php",
      method: "PUT",
      contentType: "application/json",
      data: JSON.stringify({ id: id, status: status }),
      success: function (response) {
        resolve(response);
      },
      error: function (xhr, status, error) {
        console.error("Error al actualizar status:", error);
        reject(error);
      },
    });
  });
}

/* OBTENER GUARDIA */
function obtenerGuardia(filtro, id_sala) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: `endpoints/guardias.endpoint.php?${filtro}=${id_sala}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        resolve(response.data);
      },
      error: function (xhr, status, error) {
        console.error("Error al obtener guardia:", error);
        reject(error);
      },
    });
  });
}

/* INICIAR GUARDIA */
function iniciarGuardia(id_usuario, id_sala) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "endpoints/guardias.endpoint.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ id_usuario: id_usuario, id_sala: id_sala }),
      success: function (response) {
        if (response.success) {
          console.log("Guardia creada con éxito:", response.data);
          resolve(response.data); // Retornamos los datos de la guardia creada
        } else {
          resolve(response);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al iniciar guardia:", error);
        reject(error);
      },
    });
  });
}

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
          /* Si la sala está disponible */
          actualizarStatusSala($(this).attr("data-id"), 1).then(
            (statusSala) => {
              if (statusSala) {
                console.log(
                  "Status de sala actualizado a ocupado. Iniciando guardia...",
                );
                /* Se crea una nueva guardia */
                iniciarGuardia(
                  sessionStorage.getItem("id_usuario"),
                  $(this).attr("data-id"),
                ).then(
                  (idGuardia) => {
                    if (idGuardia) {
                      console.log("Guardia iniciada con ID:", idGuardia);
                      window.location.href = `hall?id=${$(this).attr("data-id")}&guardia=${idGuardia}`; // Redirigir a la sala con el ID de la guardia
                    } else {
                      actualizarStatusSala($(this).attr("data-id"), 0); // Revertir status a disponible
                      alert("Error al iniciar la guardia. Inténtalo de nuevo.");
                    }
                  },
                  (error) => {
                    actualizarStatusSala($(this).attr("data-id"), 0); // Revertir status a disponible
                    alert(
                      "Error al iniciar la guardia. Comunicate con un administrador. " +
                        error,
                    );
                    console.error("Error al iniciar guardia:", error);
                  },
                );
              } else {
                actualizarStatusSala($(this).attr("data-id"), 0); // Revertir status a disponible
                alert(
                  "Error al actualizar el estado de la sala. Inténtalo de nuevo.",
                );
              }
            },
            (error) => {
              alert(
                "Error al actualizar el estado de la sala. Inténtalo de nuevo.",
              );
              console.error("Error al actualizar status de sala:", error);
            },
          );
        } else {
          /* Si la sala está ocupada */
          obtenerGuardia("id_sala", $(this).attr("data-id"))
            .then((dataGuardia) => {
              if (dataGuardia) {
                dataGuardia.forEach((guardia) => {
                  if (
                    guardia.id_usuario ==
                      sessionStorage.getItem("id_usuario") &&
                    guardia.id_sala == $(this).attr("data-id") &&
                    guardia.status == "1"
                  ) {
                    window.location.href = `hall?id=${$(this).attr("data-id")}&guardia=${guardia.id}`;
                  }
                });
              }
            })
            .catch((error) => {
              console.error("Error al obtener guardia:", error);
              alert("Error al obtener información del guardia.");
            });
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
