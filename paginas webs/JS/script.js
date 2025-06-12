// script.js
document.addEventListener("DOMContentLoaded", () => {
    console.log("Página de inicio cargada");

    // Scroll to top
    const btnTop = document.getElementById("btn-top");
    if (btnTop) {
        btnTop.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // Animación sobre las cards
    const cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.classList.add("shadow-lg");
            card.style.transform = "scale(1.03)";
            card.style.transition = "transform 0.3s ease";
        });
        card.addEventListener("mouseleave", () => {
            card.classList.remove("shadow-lg");
            card.style.transform = "scale(1)";
        });
    });

    // Alerta de bienvenida
    // alert("Bienvenido a Masterchess 🏁");

    // Click en testimonio para resaltarlo
    const testimonio = document.querySelector(".testimonial-card");
    if (testimonio) {
        testimonio.addEventListener("click", () => {
            testimonio.classList.toggle("border");
            testimonio.classList.toggle("border-warning");
        });
    }
});
