// producto.js

class Producto {
  constructor(id, nombre, precio, categoria, elemento) {
    this.id = id;
    this.nombre = nombre;
    this.precio = precio;
    this.categoria = categoria;
    this.elemento = elemento; // nodo del DOM asociado al producto
  }

  mostrar() {
    this.elemento.style.display = "";
  }

  ocultar() {
    this.elemento.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", () => {
  console.log("Filtrado de productos activo");

  const cards = document.querySelectorAll(".card.h-100");
  const productos = [];

  // Asignamos cada card a un producto
  cards.forEach((card, index) => {
    const nombre = card.querySelector(".card-title")?.textContent.trim() || "Producto";
    const precioTexto = card.querySelector(".card-text")?.textContent.match(/\d+[.,]?\d*/);
    const precio = precioTexto ? parseFloat(precioTexto[0].replace(',', '.')) : 0;
    const categoria = "piezas"; // Asignación provisional o con data-* según prefieras
    productos.push(new Producto(index, nombre, precio, categoria, card));
  });

  const filtroForm = document.querySelector("form");
  if (filtroForm) {
    filtroForm.addEventListener("submit", (e) => {
      e.preventDefault(); // Evita recargar la página

      const precioMax = parseFloat(document.getElementById("precio").value);
      const categoria = document.getElementById("categoria").value;

      productos.forEach(prod => {
        const coincidePrecio = isNaN(precioMax) || prod.precio <= precioMax;
        const coincideCategoria = categoria === "" || prod.categoria === categoria;

        if (coincidePrecio && coincideCategoria) {
          prod.mostrar();
        } else {
          prod.ocultar();
        }
      });
    });
  }
});
