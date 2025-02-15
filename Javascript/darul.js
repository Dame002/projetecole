const wrapper = document.querySelector(".carousel-wrapper");
const nextBtn = document.querySelector(".btn-next");
const prevBtn = document.querySelector(".btn-prev");

let scrollAmount = 0;
const cardWidth = document.querySelector(".course-card").offsetWidth + 15; // Largeur de la carte + marge

nextBtn.addEventListener("click", () => {
  if (scrollAmount < wrapper.scrollWidth - cardWidth * 4) {
    scrollAmount += cardWidth;
    wrapper.style.transform = `translateX(-${scrollAmount}px)`;
  }
});

prevBtn.addEventListener("click", () => {
  if (scrollAmount > 0) {
    scrollAmount -= cardWidth;
    wrapper.style.transform = `translateX(-${scrollAmount}px)`;
  }
});
