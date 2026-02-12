$("#btnLogout").on("click", function () {
  $.ajax({
    url: "endpoints/auth.endpoint.php",
    method: "POST",
    dataType: "json",
    data: JSON.stringify({ logout: true }),
    cache: false,
    contentType: "application/json",
    processData: false,
    success: function (response) {
      if (response.success) {
        window.location.href = "login";
      } else {
        alert("Error al cerrar sesión.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la solicitud AJAX:", error);
      alert("Error al cerrar sesión. Por favor, inténtalo de nuevo.");
    },
  });
});
