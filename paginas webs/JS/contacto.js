// contacto.js

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const nombre = document.getElementById("nombre");
  const email = document.getElementById("email");
  const mensaje = document.getElementById("mensaje");

  // Crear contenedor para los resultados o errores
  let result = document.createElement("div");
  result.id = "form-result";
  form.parentNode.insertBefore(result, form.nextSibling);

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    result.innerHTML = "";
    result.className = "";

    const errores = [];

    if (nombre.value.trim() === "") {
      errores.push("El nombre es obligatorio.");
    }

    if (email.value.trim() === "" || !email.value.includes("@")) {
      errores.push("El correo electrónico no es válido.");
    }

    if (mensaje.value.trim().length < 10) {
      errores.push("El mensaje debe tener al menos 10 caracteres.");
    }

    if (errores.length > 0) {
      result.className = "alert alert-danger mt-3";
      result.innerHTML = errores.map(e => `<p class='mb-1'>• ${e}</p>`).join("");
    } else {
      result.className = "alert alert-success mt-3";
      result.innerHTML = `<strong>✅ Mensaje enviado correctamente</strong><br>Gracias, <b>${nombre.value}</b>. Te responderemos a <i>${email.value}</i>.`;

      // form.reset(); // Descomenta si deseas limpiar los campos después
    }
  });
});


// Funciones de validación
if (!esNoVacio(username.value)) {
  errores.push("El nombre de usuario es obligatorio.");
}

if (!esEmailValido(email.value)) {
  errores.push("Introduce un correo electrónico válido.");
}

mostrarMensaje(result, "error", errores);

