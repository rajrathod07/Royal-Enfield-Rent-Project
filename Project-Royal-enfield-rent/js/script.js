
AOS.init();
let slides = document.querySelectorAll('.hero-slide');
let current = 0;
function rotateSlide() {
  slides.forEach((s, i) => {
    s.classList.remove('active');
    if (i === current) s.classList.add('active');
  });
  current = (current + 1) % slides.length;
}
setInterval(rotateSlide, 4000);




