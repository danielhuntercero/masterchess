// registro.js

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const username = document.getElementById("username");
  const email = document.getElementById("email");
  const password = document.getElementById("password");

  // Crear contenedor de resultados o errores si no existe
  let result = document.createElement("div");
  result.id = "form-result";
  form.parentNode.insertBefore(result, form.nextSibling);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    result.innerHTML = ""; // Limpiar mensajes anteriores
    result.className = "";

    const errors = [];

    // Validaciones básicas
    if (username.value.trim() === "") {
      errors.push("El nombre de usuario es obligatorio.");
    }

    if (email.value.trim() === "" || !email.value.includes("@")) {
      errors.push("Introduce un correo electrónico válido.");
    }

    if (password.value.length < 6) {
      errors.push("La contraseña debe tener al menos 6 caracteres.");
    }

    if (errors.length > 0) {
      result.className = "alert alert-danger mt-3";
      result.innerHTML = errors.map(e => `<p class='mb-1'>• ${e}</p>`).join("");
    } else {
      result.className = "alert alert-success mt-3";
      result.innerHTML = `<strong>✅ Registro exitoso</strong><br>Bienvenido/a, <b>${username.value}</b>. Te enviaremos un correo a <i>${email.value}</i>.`;

      // Limpiar campos si deseas
      // form.reset();
    }
  });
});

// Validaciones
if (!esNoVacio(username.value)) {
  errores.push("El nombre de usuario es obligatorio.");
}

if (!esEmailValido(email.value)) {
  errores.push("Introduce un correo electrónico válido.");
}

mostrarMensaje(result, "error", errores);

