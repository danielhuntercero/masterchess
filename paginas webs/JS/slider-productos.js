
// 🚀 Auto-scroll del slider de productos
const slider = document.querySelector('.slider-productos');
let autoScrollInterval;

function autoScrollSlider() {
  autoScrollInterval = setInterval(() => {
    if (slider) {
      slider.scrollBy({ left: 200, behavior: 'smooth' });
      // Si llegó al final, vuelve al inicio
      if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
        slider.scrollTo({ left: 0, behavior: 'smooth' });
      }
    }
  }, 3000); // cada 3 segundos
}

// Llamamos al autoscroll si está presente
if (slider) autoScrollSlider();

// 🔁 Botones para scroll manual
document.getElementById('btn-prev')?.addEventListener('click', () => {
  slider.scrollBy({ left: -200, behavior: 'smooth' });
});

document.getElementById('btn-next')?.addEventListener('click', () => {
  slider.scrollBy({ left: 200, behavior: 'smooth' });
});
