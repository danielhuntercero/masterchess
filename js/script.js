// js/script.js
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script personalizado cargado.');

    // Ejemplo de validación básica con JS (puedes expandirlo) 
    const loginForm = document.querySelector('form'); // Asume que solo hay un formulario en login.php
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (!emailInput.value || !passwordInput.value) {
                alert('Por favor, completa todos los campos.'); // Mensaje dinámico 
                event.preventDefault(); // Evita el envío del formulario
            }
            // Puedes añadir más validaciones aquí
        });
    }

    // Ejemplo de animación simple (requiere un elemento HTML con la clase 'animated-element') 
    const animatedElement = document.querySelector('.animated-element');
    if (animatedElement) {
        animatedElement.addEventListener('mouseover', function() {
            animatedElement.style.transform = 'scale(1.05)';
            animatedElement.style.transition = 'transform 0.3s ease-in-out';
        });
        animatedElement.addEventListener('mouseout', function() {
            animatedElement.style.transform = 'scale(1)';
        });
    }

    // Puedes añadir aquí al menos 5 animaciones y más validaciones 
    // Por ejemplo:
    // - Un slider de imágenes en la página principal.
    // - Efectos de hover en productos.
    // - Mensajes de confirmación con desvanecimiento.
    // - Validación de formularios más compleja (ej. formato de email, longitud de contraseña).
});