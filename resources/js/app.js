import { initializeCalendar } from './calendar.js';
import { setupModal } from './modal.js';
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
// Initialisation du calendrier et du formulaire modale
document.addEventListener('DOMContentLoaded', function () {
    initializeCalendar();
    setupModal();
    // ✅ Création d'une timeline principale
    const tl = gsap.timeline({
        defaults: { duration: 1, ease: "power2.out" } // Paramètres par défaut pour éviter les répétitions
    });

    // ✅ Animation Desktop (écran large)
    tl.from(".hero img", {
        opacity: 0,
        x: -100,
        onComplete: () => gsap.set(".hero a", { opacity: 1 })
    })
        .from(".hero h1", {
            opacity: 0,
            y: 30
        }, "-=0.5") // Commence plus tôt pour un effet fluide
        .from(".hero p", {
            opacity: 0,
            y: 30
        }, "-=0.4") // Décalage pour un effet fluide
        .from(".hero a", {
            opacity: 0,
            scale: 0.8,
            duration: 0.5,
            ease: "back.out(1.7)",
            onComplete: () => gsap.set(".hero a", { opacity: 1 }) // Force opacity: 1 après l'animation
        }, "-=0.3");

    // ✅ Animation Mobile (écrans ≤ 768px)
    gsap.matchMedia().add("(max-width: 768px)", () => {
        gsap.from(".hero img", {
            opacity: 0,
            x: -50,
            duration: 0.8,
            ease: "power2.out"
        });

        gsap.from([".hero h1", ".hero p", ".hero a"], {
            opacity: 0,
            y: 20,
            duration: 0.8,
            ease: "power2.out",
            stagger: 0.3
        });
    });

    // ✅ ScrollTrigger pour refaire l'animation si on revient en haut de la page
    ScrollTrigger.create({
        trigger: ".hero",
        start: "top 80%",
        once: true, // Joue une seule fois l'animation
        animation: tl
    });
});




gsap.registerPlugin(ScrollTrigger);
document.addEventListener("DOMContentLoaded", function () {

});
