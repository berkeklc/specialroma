import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import focus from '@alpinejs/focus';
import { initSpecialRoma } from './special-roma';

Alpine.plugin(intersect);
Alpine.plugin(focus);

Alpine.data('siteHeader', () => ({
    scrolled: false,
    mobileOpen: false,
    init() {
        this.$watch('mobileOpen', (v) => {
            document.body.style.overflow = v ? 'hidden' : '';
        });
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 32;
        }, { passive: true });
    },
}));

Alpine.data('faqAccordion', () => ({
    open: null,
    toggle(i) {
        this.open = this.open === i ? null : i;
    },
}));

function initFadeUp() {
    if (document.body.dataset.theme === 'special-roma') return;

    const els = document.querySelectorAll('.fade-up');
    if (!('IntersectionObserver' in window)) {
        els.forEach((el) => el.classList.add('visible'));
        return;
    }
    const obs = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -8% 0px' });
    els.forEach((el) => obs.observe(el));
}

Alpine.data('lightbox', (images = []) => ({
    open: false,
    current: 0,
    images,
    show(i) {
        this.current = i;
        this.open = true;
    },
    next() {
        this.current = (this.current + 1) % this.images.length;
    },
    prev() {
        this.current = (this.current - 1 + this.images.length) % this.images.length;
    },
}));

window.Alpine = Alpine;
Alpine.start();

function bootUi() {
    initFadeUp();
    initSpecialRoma();
}

document.addEventListener('DOMContentLoaded', bootUi);
document.addEventListener('livewire:navigated', bootUi);
