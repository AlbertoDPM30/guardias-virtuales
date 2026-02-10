const customId = Math.floor(100000 + Math.random() * 900000).toString();
const peer = new Peer(customId);

let localStream;
let currentCall;

const localVideo = document.getElementById('local-video');
const remoteVideo = document.getElementById('remote-video');
const micIcon = document.getElementById('mic-icon-guest');
const peerIdDisplay = document.getElementById('peer-id');
const statusBadge = document.getElementById('status-badge');
const callBox = document.getElementById('incoming-call-box');
const closeBtn = document.getElementById('close-room-btn');

// 1. Iniciar cámara de inmediato
navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    .then(stream => {
        localStream = stream;
        localVideo.srcObject = stream;
    })
    .catch(err => console.error("Error cámara:", err));

peer.on('open', id => {
    peerIdDisplay.innerText = id;
});

peer.on('call', call => {
    currentCall = call;
    callBox.classList.remove('d-none');

    document.getElementById('accept-btn').onclick = () => {
        call.answer(localStream);
        callBox.classList.add('d-none');
        closeBtn.classList.remove('d-none');

        call.on('stream', remoteStream => {
            // Actualizar indicador a Online
            statusBadge.innerText = "ONLINE";
            statusBadge.classList.replace('bg-secondary', 'bg-success');
            
            remoteVideo.srcObject = remoteStream;
            
            // Forzar play y detección de tracks
            remoteVideo.onloadedmetadata = () => remoteVideo.play();

            setTimeout(() => {
                const hasVideo = remoteStream.getVideoTracks().some(t => t.enabled);
                if (!hasVideo) {
                    remoteVideo.classList.add('d-none');
                    micIcon.classList.remove('d-none');
                } else {
                    remoteVideo.classList.remove('d-none');
                    micIcon.classList.add('d-none');
                }
            }, 1000);
        });

        // 2. Si el invitado se desconecta, refrescar host
        call.on('close', () => {
            location.reload();
        });
    };

    document.getElementById('reject-btn').onclick = () => {
        call.close();
        callBox.classList.add('d-none');
    };
});

// Botón cerrar sala: Al cerrar el host, el cliente detectará el 'close'
closeBtn.onclick = () => {
    if (currentCall) currentCall.close();
    location.reload();
};