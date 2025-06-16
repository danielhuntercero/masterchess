
// 🧑‍🤝‍🧑 Interacciones específicas para sobre-nosotros.html

document.addEventListener('DOMContentLoaded', () => {
  const equipo = document.querySelectorAll('.card');

  equipo.forEach(card => {
    card.addEventListener('click', () => {
      card.classList.toggle('expandida');
      const extra = card.querySelector('p.extra');
      if (extra) {
        extra.style.display = extra.style.display === 'block' ? 'none' : 'block';
      }
    });
  });

  const mensaje = document.createElement('div');
  mensaje.className = 'alert alert-info text-center';
  mensaje.innerHTML = '🎯 ¡Conoce a quienes hacen Masterchess posible!';
  document.querySelector('main')?.prepend(mensaje);

  setTimeout(() => mensaje.remove(), 4000);
});
