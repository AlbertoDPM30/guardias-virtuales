const peer = new Peer();
let localStream = null;
let currentCall = null;

const joinBtn = document.getElementById('join-btn');
const hangupBtn = document.getElementById('hangup-btn');
const remoteVideo = document.getElementById('remote-video');
const localVideo = document.getElementById('local-video');
const videoToggle = document.getElementById('video-toggle');

videoToggle.onchange = async () => {
    if (videoToggle.checked) {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            localVideo.srcObject = localStream;
            document.getElementById('local-preview').classList.remove('d-none');
        } catch (e) {
            videoToggle.checked = false;
            alert("Error cámara");
        }
    } else {
        document.getElementById('local-preview').classList.add('d-none');
    }
};

joinBtn.onclick = async () => {
    const id = document.getElementById('remote-id').value;
    if (!id) return;

    // Si no se capturó stream previo, capturar al menos audio
    if (!localStream) {
        localStream = await navigator.mediaDevices.getUserMedia({ video: false, audio: true });
    }

    const call = peer.call(id, localStream);
    currentCall = call;

    joinBtn.classList.add('d-none');
    hangupBtn.classList.remove('d-none');

    call.on('stream', hostStream => {
        document.getElementById('status').innerText = "Conectado";
        remoteVideo.srcObject = hostStream;
        // Garantizar reproducción
        remoteVideo.onloadedmetadata = () => remoteVideo.play();
    });

    // 2. Si el anfitrión cierra la sala, el cliente refresca
    call.on('close', () => {
        location.reload();
    });
};

hangupBtn.onclick = () => {
    if (currentCall) currentCall.close();
    location.reload();
};