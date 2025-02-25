import Swiper from "swiper";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
export function initSwiper() {
    console.log('OK')
    const swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true
        }
    });

    // Cacher l'indication après un vrai swipe/touch
    const swipeHint = document.getElementById("swipe-hint");

    // ✅ Attendre 3 secondes avant d'écouter les événements (évite la disparition immédiate sur mobile)
    setTimeout(() => {
        swiper.on('slideChange', () => {
            swipeHint.classList.add("hidden-hint");
        });

        swiper.el.addEventListener("touchmove", () => {
            swipeHint.classList.add("hidden-hint");
        });

        swiper.el.addEventListener("mousedown", () => {  // Pour desktop
            swipeHint.classList.add("hidden-hint");
        });
    }, 3000);
}
