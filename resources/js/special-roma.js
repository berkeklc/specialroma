import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

let lenisInstance = null;
let gsapContext = null;

function destroyLenis() {
    if (lenisInstance) {
        lenisInstance.destroy();
        lenisInstance = null;
    }
    document.documentElement.classList.remove('lenis', 'lenis-smooth');
}

function initLenis() {
    if (lenisInstance) return;

    lenisInstance = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        touchMultiplier: 1.5,
    });

    lenisInstance.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenisInstance.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    document.documentElement.classList.add('lenis', 'lenis-smooth');
}

function initHeroParallax(hero) {
    const inner = hero.querySelector('.roma-hero__parallax-inner');
    if (!inner) return;

    gsap.to(inner, {
        yPercent: 12,
        ease: 'none',
        scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: 'bottom top',
            scrub: 0.6,
        },
    });
}

function initHeroScoops(hero) {
    const scoops = hero.querySelectorAll('[data-roma-float]');
    if (!scoops.length) return;

    const driftPaths = [
        { endX: '-24vw', endY: '-10vh', rotate: 6 },
        { endX: '22vw',  endY: '-14vh', rotate: -5 },
        { endX: '-20vw', endY: '22vh',  rotate: 8 },
        { endX: '26vw',  endY: '18vh',  rotate: -7 },
        { endX: '-8vw',  endY: '24vh',  rotate: 4 },
        { endX: '12vw',  endY: '-22vh', rotate: -6 },
    ];

    scoops.forEach((scoop, i) => {
        const startX = scoop.dataset.startX;
        const startY = scoop.dataset.startY;
        const drift = driftPaths[i % driftPaths.length];

        gsap.set(scoop, {
            left: '50%',
            top: '50%',
            xPercent: -50,
            yPercent: -50,
            x: startX,
            y: startY,
            opacity: 0,
            scale: 0.3,
            rotation: 0,
        });

        const enterTl = gsap.timeline({
            scrollTrigger: {
                trigger: hero,
                start: 'top 80%',
                end: 'top 20%',
                scrub: 0.8,
            },
        });

        enterTl.to(scoop, {
            opacity: 0.75,
            scale: 1,
            ease: 'power2.out',
            duration: 1,
        });

        gsap.to(scoop, {
            x: drift.endX,
            y: drift.endY,
            rotation: drift.rotate,
            ease: 'none',
            scrollTrigger: {
                trigger: hero,
                start: 'top top',
                end: 'bottom top',
                scrub: 1.2,
            },
        });
    });
}

function initSectionReveals() {
    const fadeEls = document.querySelectorAll('.fade-up');
    if (!fadeEls.length) return;

    gsap.set(fadeEls, { opacity: 0, y: 28 });

    ScrollTrigger.batch(fadeEls, {
        start: 'top 88%',
        onEnter: (batch) => {
            gsap.to(batch, {
                opacity: 1,
                y: 0,
                duration: 0.65,
                stagger: 0.08,
                ease: 'power3.out',
                overwrite: 'auto',
            });
        },
        once: true,
    });
}

function initIceCreamAssembly() {
    const wrapper = document.querySelector('.sr-icecream-assembly-wrapper');
    if (!wrapper) return;

    const canvas = document.getElementById('sr-splash-canvas');
    const parts = [
        '.sr-top1',
        '.sr-top2',
        '.sr-top3',
        '.sr-top4',
    ];

    // Initial state: push tops up and hide them
    parts.forEach(selector => {
        gsap.set(selector, { y: -400, opacity: 0, scale: 0.8 });
    });

    // --- Splash Animation Setup ---
    let frameCount = 145;
    let images = [];
    let splashObj = { frame: 0 };
    let ctx = canvas ? canvas.getContext('2d') : null;

    if (canvas && ctx) {
        // Preload frames
        for (let i = 0; i < frameCount; i++) {
            const img = new Image();
            const paddedIdx = String(i).padStart(5, '0');
            img.src = `/splash/splasg_${paddedIdx}.png`;
            images.push(img);
        }

        // Set dimensions once first image loads
        images[0].onload = () => {
            canvas.width = images[0].naturalWidth;
            canvas.height = images[0].naturalHeight;
        };
    }

    const renderSplash = () => {
        if (!ctx || !images[Math.floor(splashObj.frame)]) return;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(images[Math.floor(splashObj.frame)], 0, 0);
    };

    // The timeline mapped to scroll
    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: wrapper,
            pin: '.sr-icecream-assembly-pinned',
            start: 'top top',
            end: 'bottom bottom', 
            scrub: 0.5,
            anticipatePin: 1,
            pinSpacing: true, 
            invalidateOnRefresh: true,
        }
    });

    const btn = document.querySelector('.sr-icecream-btn');

    // Animate each top dropping into place
    parts.forEach((selector, idx) => {
        tl.to(selector, {
            y: 0,
            opacity: 1,
            scale: 1,
            duration: 1,
            ease: "back.out(0.6)"
        }, idx * 0.5);

        // Show button much earlier - when top2 drops (idx 1)
        if (idx === 1 && btn) {
            tl.to(btn, {
                opacity: 1,
                y: 0,
                pointerEvents: 'auto',
                duration: 0.5
            }, idx * 0.5 + 0.2);
        }
    });

    // --- Add Splash Sequence to Timeline ---
    // Start it slightly after top4 (idx 3) starts falling
    if (canvas) {
        tl.to(splashObj, {
            frame: frameCount - 1,
            snap: "frame",
            ease: "none",
            duration: 3, // Give it more "scroll space"
            onUpdate: renderSplash
        }, 2.2); // Overlaps with top4 landing slightly
    }
}

export function initSpecialRoma() {
    if (document.body.dataset.theme !== 'special-roma') {
        destroyLenis();
        if (gsapContext) { gsapContext.revert(); gsapContext = null; }
        ScrollTrigger.refresh();
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        destroyLenis();
        if (gsapContext) { gsapContext.revert(); gsapContext = null; }
        document.querySelectorAll('.fade-up, [data-roma-float]').forEach((el) => {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
        return;
    }

    if (gsapContext) gsapContext.revert();

    initLenis();

    gsapContext = gsap.context(() => {
        const hero = document.querySelector('[data-roma-hero]');
        if (hero) {
            initHeroParallax(hero);
            initHeroScoops(hero);
        }

        initSectionReveals();
        initIceCreamAssembly();
        initScrollTop();

        // Refresh ScrollTrigger with a tiny delay to ensure all DOM measuring is accurate
        setTimeout(() => {
            ScrollTrigger.refresh();
        }, 100);
    });
}

function initScrollTop() {
    const scrollTopBtn = document.getElementById('scroll-to-top');
    if (!scrollTopBtn) return;

    // Show/hide button on scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 600) {
            scrollTopBtn.classList.add('visible');
        } else {
            scrollTopBtn.classList.remove('visible');
        }
    }, { passive: true });

    // Scroll to top on click
    scrollTopBtn.addEventListener('click', () => {
        if (lenisInstance) {
            lenisInstance.scrollTo(0);
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}
