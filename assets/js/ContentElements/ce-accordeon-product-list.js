document.addEventListener("DOMContentLoaded", function () {
    // Turn to back (Quick view)
    document.querySelectorAll(".btn-turn-back").forEach(function(button) {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            const productCard = button.closest(".product-card");
            productCard.classList.add("turned");
        });
    });

    // Turn to front
    document.querySelectorAll(".btn-turn-front").forEach(function(button) {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            const productCard = button.closest(".product-card");
            productCard.classList.remove("turned");
        });
    });
});