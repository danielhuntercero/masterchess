
// 🛠️ Interacciones específicas para servicios.html

document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.card');

  cards.forEach(card => {
    card.addEventListener('mouseover', () => {
      card.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
    });
    card.addEventListener('mouseout', () => {
      card.style.boxShadow = '0 6px 15px rgba(0,0,0,0.06)';
    });

    card.addEventListener('click', () => {
      card.classList.toggle('expandida');
      const extra = card.querySelector('p.extra');
      if (extra) {
        extra.style.display = extra.style.display === 'block' ? 'none' : 'block';
      }
    });
  });
});
