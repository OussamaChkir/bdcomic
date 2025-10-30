document.addEventListener("DOMContentLoaded", function () {
    const anchorNav = document.querySelector(".anchor-links");

    function getOffset() {
        let offset = 0;

        const adminBar = document.getElementById("wpadminbar");
        if (adminBar) offset += adminBar.offsetHeight;

        const stickyHeader = document.querySelector("header");
        if (stickyHeader) offset += stickyHeader.offsetHeight;

        return offset;
    }

    // Sticky toggle behavior
    function handleStickyPosition() {
        if (!anchorNav) return;

        const offset = getOffset();

        const anchorTop = anchorNav.parentElement.getBoundingClientRect().top + window.scrollY;

        if (window.scrollY + offset >= anchorTop) {
            anchorNav.classList.add("sticky");
            anchorNav.style.top = `${offset}px`;
        } else {
            anchorNav.classList.remove("sticky");
            anchorNav.style.top = "";
        }
    }

    // Scroll into view on anchor click and toggle active class
    document.querySelectorAll('.anchor-links a[href^="#"]').forEach(link => {
        link.addEventListener("click", function (e) {
            const id = this.getAttribute("href").substring(1);
            const target = document.getElementById(id);

            if (target) {
                e.preventDefault();

                const offset = getOffset();

                const accordionItem = target.closest(".accordion-item");
                if (accordionItem) {
                    const button = accordionItem.querySelector(".accordion-button");
                    if (button && button.classList.contains("collapsed")) {
                        const collapse = accordionItem.querySelector(".accordion-collapse");

                        // Scroll after the collapse is fully shown
                        collapse.addEventListener("shown.bs.collapse", function onShown() {
                            const y = target.getBoundingClientRect().top + window.pageYOffset - offset;
                            window.scrollTo({ top: y, behavior: "smooth" });
                            collapse.removeEventListener("shown.bs.collapse", onShown);
                        });

                        button.click(); // triggers accordion opening
                    } else {
                        const y = target.getBoundingClientRect().top + window.pageYOffset - offset;
                        window.scrollTo({ top: y, behavior: "smooth" });
                    }
                } else {
                    const y = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top: y, behavior: "smooth" });
                }

                // Update URL hash
                history.pushState(null, "", "#" + id);

                document.querySelectorAll('.anchor-links a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    handleStickyPosition();

    window.addEventListener("scroll", handleStickyPosition);
    window.addEventListener("resize", handleStickyPosition);
});