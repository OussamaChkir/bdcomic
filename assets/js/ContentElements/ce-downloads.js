document.addEventListener("DOMContentLoaded", function () {
    const loadMoreBtn = document.querySelector(".load-more-btn");
    const posts = document.querySelectorAll(".block-download .download-container .download-teaser");

    if (!loadMoreBtn) return;

    loadMoreBtn.addEventListener("click", function (e) {
        e.preventDefault();

        let hiddenPosts = Array.from(posts).filter(post => post.style.display === "none");

        for (let i = 0; i < Math.min(3, hiddenPosts.length); i++) {
            hiddenPosts[i].style.display = "flex";
        }

        // Hide button if all posts are now visible
        if (hiddenPosts.length <= 3) {
            loadMoreBtn.style.display = "none";
        }
    });
});
