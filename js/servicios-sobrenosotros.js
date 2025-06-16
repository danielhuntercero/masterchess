
// 🎯 Interacción en cards de servicios y sobre nosotros

document.querySelectorAll('.card').forEach(card => {
  card.addEventListener('click', () => {
    card.classList.toggle('expandida');

    const extra = card.querySelector('p.extra');
    if (extra) {
      extra.style.display = extra.style.display === 'block' ? 'none' : 'block';
    }
  });
});

// 🎉 Mensaje dinámico al cargar la página
window.addEventListener('DOMContentLoaded', () => {
  if (document.title.includes('Sobre Nosotros')) {
    const mensaje = document.createElement('div');
    mensaje.className = 'alert alert-info text-center';
    mensaje.innerHTML = '💡 ¡Gracias por interesarte en nuestro equipo!';
    document.querySelector('main')?.prepend(mensaje);

    setTimeout(() => mensaje.remove(), 4000);
  }
});
