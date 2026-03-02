let seconds = 0,
  minutes = 0,
  hours = 0;
let timerInterval = null;
let savedTimes = []; // Variable para guardar los registros

function updateDisplay() {
  let h = hours < 10 ? "0" + hours : hours;
  let m = minutes < 10 ? "0" + minutes : minutes;
  let s = seconds < 10 ? "0" + seconds : seconds;
  $("#display").text(h + ":" + m + ":" + s);
}

$("#start").click(function () {
  $(this).prop("disabled", true);
  $("#stop").prop("disabled", false);

  timerInterval = setInterval(function () {
    seconds++;
    if (seconds == 60) {
      seconds = 0;
      minutes++;
    }
    if (minutes == 60) {
      minutes = 0;
      hours++;
    }
    updateDisplay();
  }, 1000);
});

$("#stop").click(function () {
  clearInterval(timerInterval);

  // Capturamos el tiempo actual
  let currentTime = $("#display").text();
  savedTimes.push(currentTime);

  // Lo añadimos a la interfaz
  $("#saved-times").append("<li>" + currentTime + "</li>");

  $("#start").prop("disabled", false);
  $(this).prop("disabled", true);
});

$("#reset").click(function () {
  clearInterval(timerInterval);
  seconds = 0;
  minutes = 0;
  hours = 0;
  updateDisplay();

  // Limpiamos también el registro visual
  $("#saved-times").empty();
  savedTimes = [];

  $("#start").prop("disabled", false);
  $("#stop").prop("disabled", true);
});
