// comentarios.js

document.addEventListener("DOMContentLoaded", () => {
  // Botones Me gusta (por cada entrada con clase .like-btn)
  document.querySelectorAll(".like-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const contador = btn.querySelector(".like-count");
      let count = parseInt(contador.textContent);
      contador.textContent = count + 1;
    });
  });

  // Mostrar/Ocultar comentarios (por cada .toggle-comments-btn)
  document.querySelectorAll(".toggle-comments-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const contenedor = btn.closest(".entrada")?.querySelector(".comentarios");
      if (contenedor) {
        contenedor.classList.toggle("d-none");
        btn.textContent = contenedor.classList.contains("d-none") ? "Mostrar comentarios" : "Ocultar comentarios";
      }
    });
  });

  // Añadir comentario general (formulario con id #form-comentario)
  const formComentario = document.getElementById("form-comentario");
  const lista = document.getElementById("lista-comentarios");

  if (formComentario && lista) {
    formComentario.addEventListener("submit", (e) => {
      e.preventDefault();
      const input = document.getElementById("nuevo-comentario");
      const texto = input.value.trim();

      if (texto !== "") {
        const nuevo = document.createElement("li");
        nuevo.textContent = texto;
        nuevo.className = "list-group-item";
        lista.appendChild(nuevo);
        input.value = "";
      }
    });
  }
});
