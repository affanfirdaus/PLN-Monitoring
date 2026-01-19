import "./bootstrap";
import "../css/app.css";

// Hero Slider Implementation
document.addEventListener("DOMContentLoaded", () => {
    const track = document.getElementById("heroSlideTrack");
    if (!track) return;

    let index = 0;
    const slides = track.children;
    const totalSlides = slides.length;

    // Auto Slide function
    function nextSlide() {
        index = (index + 1) % totalSlides;
        updateSlide();
    }

    function updateSlide() {
        track.style.transform = `translateX(-${index * 100}%)`;
        // Optional: Update dots if we add them later
        const dots = document.querySelectorAll(".slide-dot");
        dots.forEach((dot, i) => {
            if(i === index) dot.classList.add("bg-blue-600", "w-6");
            else dot.classList.remove("bg-blue-600", "w-6");
            if(i !== index) dot.classList.add("bg-slate-300");
        });
    }

    // Start Interval (3500ms)
    setInterval(nextSlide, 3500);
});