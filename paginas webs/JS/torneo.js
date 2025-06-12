// torneo.js

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-torneo");
  const jugador1 = document.getElementById("jugador1");
  const jugador2 = document.getElementById("jugador2");
  const resultado = document.getElementById("resultado");

  const output = document.createElement("div");
  output.id = "torneo-result";
  output.classList.add("mt-3");
  form.parentNode.insertBefore(output, form.nextSibling);

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    output.innerHTML = "";
    output.className = "";

    const errores = [];

    if (!esNoVacio(jugador1.value)) {
      errores.push("El nombre del Jugador 1 es obligatorio.");
    }

    if (!esNoVacio(jugador2.value)) {
      errores.push("El nombre del Jugador 2 es obligatorio.");
    }

    if (jugador1.value === jugador2.value) {
      errores.push("Los nombres deben ser distintos.");
    }

    if (errores.length > 0) {
      mostrarMensaje(output, "error", errores);
    } else {
      const ganador = Math.random() < 0.5 ? jugador1.value : jugador2.value;
      mostrarMensaje(output, "exito", `🏆 El ganador es: <strong>${ganador}</strong>`);
      animarElemento(output, "animate__tada");
      lanzarConfeti();
      animarElemento(output, "animate__tada");
    }
  });
});


// 🎊 Animación de confeti
function lanzarConfeti() {
  for (let i = 0; i < 30; i++) {
    const confeti = document.createElement("div");
    confeti.className = "confeti";
    confeti.style.left = Math.random() * 100 + "vw";
    confeti.style.background = `hsl(${Math.random() * 360}, 100%, 50%)`;
    document.body.appendChild(confeti);
    setTimeout(() => confeti.remove(), 2000);
  }
}
