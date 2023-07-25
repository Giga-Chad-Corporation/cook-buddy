import './bootstrap.js';
import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();


// Import the required Bootstrap JavaScript components
import { Carousel } from './bootstrap.js';


// Initialize the carousel
document.addEventListener('DOMContentLoaded', function () {
    const carouselElement = document.getElementById('carouselExampleIndicators');
    const carousel = new Carousel(carouselElement);
});

var swiper = new Swiper('.swiper-container', {
    slidesPerView: 1.5, // adjust this to how much of the next slide you want to be visible
    spaceBetween: 10, // adjust this to the space you want between slides
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});



