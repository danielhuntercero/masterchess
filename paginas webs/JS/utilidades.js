// utilidades.js

// Validación: email simple
function esEmailValido(correo) {
  return correo.includes("@") && correo.includes(".");
}

// Validación: campo no vacío
function esNoVacio(valor) {
  return valor.trim() !== "";
}

// Validación: número positivo
function esNumeroPositivo(valor) {
  return !isNaN(valor) && Number(valor) >= 0;
}

// Mostrar mensaje DOM
function mostrarMensaje(container, tipo, mensajes) {
  container.className = tipo === "error" ? "alert alert-danger mt-3" : "alert alert-success mt-3";
  container.innerHTML = Array.isArray(mensajes)
    ? mensajes.map(e => `<p class='mb-1'>• ${e}</p>`).join("")
    : `<p>${mensajes}</p>`;
}

// Animación simple para destacar un elemento
function animarElemento(elemento, clase = "animate__pulse") {
  elemento.classList.add("animate__animated", clase);
  elemento.addEventListener("animationend", () => {
    elemento.classList.remove("animate__animated", clase);
  }, { once: true });
}
