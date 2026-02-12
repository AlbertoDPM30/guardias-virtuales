console.log("Login.js cargado correctamente");

// Manejar el envío del formulario de login
$("#loginForm").on("submit", function (e) {
  e.preventDefault();

  let datos = {
    cedula: $("#cedula").val(),
    password: $("#password").val(),
  };

  $.ajax({
    url: "endpoints/auth.endpoint.php",
    method: "POST",
    dataType: "json",
    data: JSON.stringify(datos),
    cache: false,
    contentType: "application/json",
    processData: false,
    success: function (response) {
      console.log(response);
      if (response.success) {
        window.location.href = "hall";
      } else {
        alert("Credenciales incorrectas");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Error al iniciar sesión. Por favor, inténtalo de nuevo.");
    },
  });
});
