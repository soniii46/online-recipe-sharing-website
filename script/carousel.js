let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-item');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = slides.length;
let autoPlayInterval = null;
const autoPlaySpeed = 3000; // Speed in milliseconds

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) {
            slide.classList.add('active');
        }
    });
    indicators.forEach((indicator, i) => {
        indicator.classList.remove('active');
        if (i === index) {
            indicator.classList.add('active');
        }
    });
    const offset = -index * 100;
    document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);
}

function startAutoPlay() {
    autoPlayInterval = setInterval(nextSlide, autoPlaySpeed);
}

function stopAutoPlay() {
    clearInterval(autoPlayInterval);
}

// Event listeners to start and stop auto-play on hover
document.querySelector('.carousel').addEventListener('mouseover', stopAutoPlay);
document.querySelector('.carousel').addEventListener('mouseout', startAutoPlay);

// Initialize the carousel
showSlide(currentSlide);
startAutoPlay();
