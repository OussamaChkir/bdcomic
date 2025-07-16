document.addEventListener("DOMContentLoaded", function () {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const posts = document.querySelectorAll(".block-blog-post .blog-container .post-teaser");
    const loadMoreBtn = document.querySelector(".load-more-btn");
    let itemsToShow = 6;

    function updateFirstThreePosts() {
        const visiblePosts = Array.from(posts).filter(post => post.style.display !== 'none');
        posts.forEach(post => post.classList.remove('post-teaser-first'));
        visiblePosts.slice(0, 3).forEach(post => {
            post.classList.add('post-teaser-first');
        });
    }

    function updateVisiblePosts() {
        let activeTag = document.querySelector(".filter-btn.active").getAttribute("data-tag");
        
        let filteredPosts = Array.from(posts).filter(post => 
            activeTag === "all" || post.className.includes("tag-" + activeTag)
        );
    
        let hiddenPosts = filteredPosts.filter(post => post.style.display === "none");
    
        loadMoreBtn.style.display = hiddenPosts.length > 0 ? "flex" : "none";
    }

    // FILTER FUNCTIONALITY
    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            let tag = this.getAttribute("data-tag");

            filterButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            let visibleCount = 0;

            posts.forEach(post => {
                let postTag = post.className;

                if (tag === "all" || postTag.includes("tag-" + tag)) {
                    if (visibleCount < itemsToShow) {
                        post.style.display = "flex";
                        visibleCount++;
                    } else {
                        post.style.display = "none";
                    }
                } else {
                    post.style.display = "none";
                }
            });

            updateVisiblePosts();
            updateFirstThreePosts();
        });
    });

    // LOAD MORE FUNCTIONALITY
    loadMoreBtn.addEventListener("click", function (e) {
        e.preventDefault();

        let activeTag = document.querySelector(".filter-btn.active").getAttribute("data-tag");

        let hiddenPosts = Array.from(posts).filter(post => {
            let postTag = post.className;
            return post.style.display === "none" && (activeTag === "all" || postTag.includes("tag-" + activeTag));
        });

        for (let i = 0; i < Math.min(5, hiddenPosts.length); i++) {
            hiddenPosts[i].style.display = "flex";
        }

        updateVisiblePosts();
        updateFirstThreePosts();
    });

    // INITIAL CHECK
    updateVisiblePosts();
    updateFirstThreePosts();
});