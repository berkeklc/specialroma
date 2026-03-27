import './bootstrap';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import focus from '@alpinejs/focus';

// ── Alpine plugins ─────────────────────────────────────────────────────────
Alpine.plugin(intersect);
Alpine.plugin(focus);

// ── Header scroll state ────────────────────────────────────────────────────
Alpine.data('siteHeader', () => ({
    scrolled: false,
    mobileOpen: false,
    init() {
        this.$watch('mobileOpen', (v) => {
            document.body.style.overflow = v ? 'hidden' : '';
        });
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 48;
        }, { passive: true });
    },
}));

// ── FAQ accordion ──────────────────────────────────────────────────────────
Alpine.data('faqAccordion', () => ({
    open: null,
    toggle(i) { this.open = this.open === i ? null : i; },
}));

// ── Fade-up on scroll ──────────────────────────────────────────────────────
function initFadeUp() {
    const els = document.querySelectorAll('.fade-up');
    if (!('IntersectionObserver' in window)) {
        els.forEach(el => el.classList.add('visible'));
        return;
    }
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    els.forEach(el => obs.observe(el));
}

// ── Gallery lightbox ───────────────────────────────────────────────────────
Alpine.data('lightbox', (images = []) => ({
    open: false,
    current: 0,
    images,
    show(i) { this.current = i; this.open = true; },
    next() { this.current = (this.current + 1) % this.images.length; },
    prev() { this.current = (this.current - 1 + this.images.length) % this.images.length; },
}));

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', initFadeUp);
// re-run after Livewire navigations
document.addEventListener('livewire:navigated', initFadeUp);
