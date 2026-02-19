/* LOGICA PARA PEERJS */
const customId = Math.floor(100000 + Math.random() * 900000).toString();
const peer = new Peer(customId);

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

// 1. Iniciar cámara de inmediato
navigator.mediaDevices
  .getUserMedia({ video: true, audio: true })
  .then((stream) => {
    localStream = stream;
    // En jQuery, para acceder a la propiedad srcObject, usamos el elemento nativo [0]
    $localVideo[0].srcObject = stream;
  })
  .catch((err) => console.error("Error cámara:", err));

peer.on("open", (id) => {
  $peerIdDisplay.text(id);
});

peer.on("call", (call) => {
  currentCall = call;
  $callBox.removeClass("d-none");

  // Manejo de botones con jQuery (.off para evitar duplicar eventos si hay re-llamadas)
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
          const hasVideo = remoteStream.getVideoTracks().some((t) => t.enabled);
          if (!hasVideo) {
            $remoteVideo.addClass("d-none");
            $micIcon.removeClass("d-none");
          } else {
            $remoteVideo.removeClass("d-none");
            $micIcon.addClass("d-none");
          }
        }, 1000);
      });

      // 2. Si el invitado se desconecta, refrescar host
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
