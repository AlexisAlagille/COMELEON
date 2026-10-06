document.addEventListener("DOMContentLoaded", () => {
    // La piste est la zone qui contient les cartes et défile horizontalement.
    const track = document.querySelector("#avis-carousel-track");
    const card = track?.querySelector(".avis-card");
    const buttons = [
        document.querySelector(".avis-carousel-button--previous"),
        document.querySelector(".avis-carousel-button--next"),
    ];

    // Le carrousel n'est pas présent ou n'a pas assez d'éléments pour fonctionner.
    if (!track || !card || buttons.some((button) => !button)) {
        return;
    }

    // Chaque bouton fait défiler la piste d'une largeur de carte.
    buttons.forEach((button, index) => {
        const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;

        button.addEventListener("click", () => {
            track.scrollBy({
                left: (index === 0 ? -1 : 1) * (card.getBoundingClientRect().width + gap),
                behavior: "smooth",
            });
        });
    });

    // Grise le bouton précédent au début et le bouton suivant à la fin.
    const updateButtons = () => {
        buttons[0].disabled = track.scrollLeft <= 2;
        buttons[1].disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1;
    };

    track.addEventListener("scroll", updateButtons, { passive: true });
    window.addEventListener("resize", updateButtons);
    updateButtons();
});
