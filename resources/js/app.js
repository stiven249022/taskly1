import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Typewriter effect
const phrases = [
    "Organiza tus tareas",
    "Coordina trabajos grupales",
    "Mejora tu productividad",
    "Alcanza tus metas académicas"
];

let currentPhrase = 0;
let currentChar = 0;
let isDeleting = false;
let typewriterElement = document.getElementById('typewriter');

function typeWriter() {
    const currentText = phrases[currentPhrase];
    
    if (isDeleting) {
        typewriterElement.textContent = currentText.substring(0, currentChar - 1);
        currentChar--;
    } else {
        typewriterElement.textContent = currentText.substring(0, currentChar + 1);
        currentChar++;
    }
    
    if (!isDeleting && currentChar === currentText.length) {
        isDeleting = true;
        setTimeout(typeWriter, 2000);
    } else if (isDeleting && currentChar === 0) {
        isDeleting = false;
        currentPhrase = (currentPhrase + 1) % phrases.length;
        setTimeout(typeWriter, 500);
    } else {
        setTimeout(typeWriter, isDeleting ? 50 : 100);
    }
}

// Start typewriter effect
typeWriter();

// Counter animation
const counters = document.querySelectorAll('.counter');
const speed = 200;

counters.forEach(counter => {
    const target = +counter.innerText;
    const increment = target / speed;
    
    let count = 0;
    const updateCount = () => {
        if (count < target) {
            count += increment;
            counter.innerText = Math.ceil(count);
            setTimeout(updateCount, 1);
        } else {
            counter.innerText = target;
        }
    };
    
    updateCount();
});
