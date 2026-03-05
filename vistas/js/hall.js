/* OBTENER PARAMETROS DESDE LA URL */
const urlParams = new URLSearchParams(window.location.search);
const idSala = urlParams.get("id");
const idGuardia = urlParams.get("guardia");

/* FUNCION PARA OBTENER DATOS DE SALA */
function obtenerDatosSala() {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "GET",
      url: `endpoints/hall.endpoint.php?id=${idSala}`,
      dataType: "json",
      success: function (response) {
        resolve(response.data);
      },
      error: function (error) {
        console.error("Error al obtener datos de sala:", error);
        reject(error);
      },
    });
  });
}

/* OBTENER DATOS DE GUARDIA */
function obtenerDatosGuardia() {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "GET",
      url: `endpoints/guardias.endpoint.php?id=${idGuardia}`,
      dataType: "json",
      success: function (response) {
        resolve(response.data);
      },
      error: function (error) {
        console.error("Error al obtener datos de guardia:", error);
        reject(error);
      },
    });
  });
}

/* FINALIZAR GUARDIA */
function finalizarGuardia(id, status) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: "endpoints/guardias.endpoint.php",
      method: "PUT",
      cache: false,
      contentType: "application/json",
      processData: false,
      data: JSON.stringify({
        id: id,
        status: status,
        final_guardia: new Date().toLocaleString("sv-SE", {
          timeZone: "America/Caracas",
        }),
      }),
      success: function (response) {
        if (response.success) {
          resolve(true);
        } else {
          reject(false);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al finalizar guardia:", error);
        reject(error);
      },
    });
  });
}

/* FUNCION PARA CERRAR LA SALA */
function cerrarSala(status) {
  return new Promise((resolve, reject) => {
    const datos = {
      id: idSala,
      status: status,
    };

    $.ajax({
      type: "PUT",
      url: `endpoints/hall.endpoint.php`,
      dataType: "json",
      data: JSON.stringify(datos),
      cache: false,
      contentType: "application/json",
      processData: false,
      success: function (response) {
        finalizarGuardia(idGuardia, 0).then((dataGuardia) => {
          if (dataGuardia) {
            window.location.href = "dashboard";
          } else {
            alert("Error al finalizar la guardia. Permanecerás en la sala.");
          }
        });
        resolve(response);
      },
      error: function (error) {
        console.error("Error al cerrar sala:", error);
        alert("Error al cerrar la sala.");
        reject(error);
      },
    });
  });
}

$(document).ready(function () {
  obtenerDatosGuardia();
  /* VALIDACION DE PARAMETROS */
  if (!idSala || !idGuardia || isNaN(idSala) || isNaN(idGuardia)) {
    alert("Ha ocurrido un error. Por favor, inténtalo de nuevo.");
    cerrarSala(0);
    window.location.href = "dashboard";
    return;
  }

  /* INICIAR INTERFAZ DE SALA CON DATOS */
  obtenerDatosSala().then((datosSala) => {
    if (!datosSala) {
      alert("No se pudieron obtener los datos de la sala.");
      return;
    }
  });

  /* INICIAR PEER JS */
  const codigoSala = async () => {
    return new Promise((resolve, reject) => {
      const urlParams = new URLSearchParams(window.location.search);
      const idSala = urlParams.get("id");

      $.ajax({
        type: "GET",
        url: `endpoints/hall.endpoint.php?id=${idSala}`,
        dataType: "json",
        success: function (response) {
          resolve(response.data.numero);
        },
        error: function (error) {
          console.error("Error al obtener código de sala:", error);
          reject(error);
        },
      });
    });
  };

  codigoSala().then((codigo) => {
    if (!codigo) {
      alert(
        "No se pudo obtener el código de sala. Por favor, inténtalo de nuevo.",
      );
      return;
    }
    iniciarPeerJS(codigo);
  });
});

function iniciarPeerJS(codigoSala) {
  if (!codigoSala) {
    alert("Código de sala inválido.");
    return;
  }

  /* LOGICA PARA PEERJS */
  const peer = new Peer(codigoSala);

  let localStream;
  let currentCall;

  // Referencias a elementos usando jQuery
  const $localVideo = $("#local-video");
  const $remoteVideo = $("#remote-video");
  const $micIcon = $("#mic-icon-guest");
  const $peerIdDisplay = $("#peer-id");
  const $statusBadge = $("#status-badge");
  const $callBox = $("#incoming-call-box");
  const $closeBtn = $("#close-room-btn");

  // Iniciar cámara de inmediato
  navigator.mediaDevices
    .getUserMedia({ video: true, audio: true })
    .then((stream) => {
      localStream = stream;

      $localVideo[0].srcObject = stream;
    })
    .catch((err) => console.error("Error cámara:", err));

  peer.on("open", (id) => {
    $peerIdDisplay.text(id);
  });

  peer.on("call", (call) => {
    currentCall = call;
    $callBox.removeClass("d-none");

    // Manejo de botones (.off para evitar duplicar eventos si hay re-llamadas)
    $("#accept-btn")
      .off("click")
      .on("click", () => {
        call.answer(localStream);
        $callBox.addClass("d-none");
        $closeBtn.removeClass("d-none");

        call.on("stream", (remoteStream) => {
          // Actualizar indicador a Online
          $statusBadge
            .text("ONLINE")
            .removeClass("bg-secondary")
            .addClass("bg-success");

          $remoteVideo[0].srcObject = remoteStream;

          // Forzar play y detección de tracks
          $remoteVideo.on("loadedmetadata", function () {
            this.play();
          });

          setTimeout(() => {
            const hasVideo = remoteStream
              .getVideoTracks()
              .some((t) => t.enabled);
            if (!hasVideo) {
              $remoteVideo.addClass("d-none");
              $micIcon.removeClass("d-none");
            } else {
              $remoteVideo.removeClass("d-none");
              $micIcon.addClass("d-none");
            }
          }, 1000);
        });

        // Si el invitado se desconecta, refrescar host
        call.on("close", () => {
          location.reload();
        });
      });

    $("#reject-btn")
      .off("click")
      .on("click", () => {
        call.close();
        $callBox.addClass("d-none");
      });
  });

  // Botón cerrar sala
  $closeBtn.on("click", () => {
    if (currentCall) currentCall.close();
    location.reload();
  });
}

/* BOTON PARA CERRAR SALA */
$("#btnSalirSala").on("click", function () {
  if (confirm("¿Estás seguro de que deseas cerrar la sala?")) {
    cerrarSala(0);
  } else {
    alert("La sala permanecerá abierta.");
  }
});
