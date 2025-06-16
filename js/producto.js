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
    const filtroForm = document.querySelector("form");
    
    if (filtroForm) {
        filtroForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const precioMax = parseFloat(document.getElementById("precio").value);
            const categoriaSeleccionada = document.getElementById("categoria").value;

            document.querySelectorAll(".col").forEach(producto => {
                const precioProducto = parseFloat(
                    producto.querySelector(".precio").textContent.replace(' €', '').replace(',', '.')
                );
                const categoriaProducto = producto.closest(".row").previousElementSibling.textContent.trim();

                const coincidePrecio = isNaN(precioMax) || precioProducto <= precioMax;
                const coincideCategoria = categoriaSeleccionada === "" || 
                    categoriaProducto.toLowerCase().includes(categoriaSeleccionada.toLowerCase());

                producto.style.display = (coincidePrecio && coincideCategoria) ? "block" : "none";
            });
        });
    }
});