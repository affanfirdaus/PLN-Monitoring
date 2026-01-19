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

    // Profile Dropdown Toggle Logic
    // Profile Dropdown Toggle Logic (Auth)
    const dropdownToggle = document.getElementById('userDropdownToggle');
    const dropdownMenu = document.getElementById('userDropdownMenu');

    // Guest Dropdown Logic
    const guestDropdownToggle = document.getElementById('guestDropdownToggle');
    const guestDropdownMenu = document.getElementById('guestDropdownMenu');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            if(guestDropdownMenu) guestDropdownMenu.classList.add('hidden');
        });
    }

    if (guestDropdownToggle && guestDropdownMenu) {
        guestDropdownToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            guestDropdownMenu.classList.toggle('hidden');
            if(dropdownMenu) dropdownMenu.classList.add('hidden');
        });
    }

    // Global Outside Click
    document.addEventListener('click', (e) => {
        if (dropdownToggle && dropdownMenu && !dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
        if (guestDropdownToggle && guestDropdownMenu && !guestDropdownToggle.contains(e.target) && !guestDropdownMenu.contains(e.target)) {
            guestDropdownMenu.classList.add('hidden');
        }
    });
});