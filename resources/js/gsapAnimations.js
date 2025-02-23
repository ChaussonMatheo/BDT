import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

export function initGSAPAnimations() {

    gsap.to("#testimonials", {
        opacity: 1,
        duration: 1.5,
        y: 0,
        ease: "power2.out",
        scrollTrigger: {
            trigger: "#testimonials",
            start: "top 80%",
            toggleActions: "play none none none"
        }
    });

    const mm = gsap.matchMedia(); // Gérer différents breakpoints

    // ✅ Remet tout visible avant de commencer l'animation
    gsap.set([".shine-svg", ".hero a"], { opacity: 1, visibility: "visible" });

    // ✅ Animation Desktop (écrans larges)
    mm.add("(min-width: 769px)", () => {
        const tl = gsap.timeline({
            defaults: { duration: 1, ease: "power2.out" }
        });

        tl.from(".hero img", { opacity: 0, x: -100 })
            .from(".shine-svg", { opacity: 0, scale: 0.8, duration: 1 }, "-=0.8")
            .from(".hero p", { opacity: 0, y: 30 }, "-=0.5")
            .from(".hero a", { opacity: 0, scale: 0.9, duration: 0.5, ease: "back.out(1.7)" }, "-=0.3");

        ScrollTrigger.create({
            trigger: ".hero",
            start: "top 80%",
            once: true,
            animation: tl
        });
    });

    // ✅ Animation Mobile (écrans ≤ 768px)
    mm.add("(max-width: 768px)", () => {
        gsap.from(".hero img", { opacity: 0, x: -50, duration: 0.8, ease: "power2.out" });

        gsap.from([".shine-svg", ".hero p", ".hero a"], {
            opacity: 0,
            y: 20,
            duration: 0.8,
            ease: "power2.out",
            stagger: 0.3
        });
    });
}
