document.addEventListener('DOMContentLoaded', function() {
    headerStickyAdminbar();
    //hoverslippery();
    cookieFooterItem();
    backtotop();
});

window.addEventListener('resize', function() {
    headerStickyAdminbar();
});

window.addEventListener('scroll', function() {
    
});

function headerStickyAdminbar() {
    var header = document.querySelector('header');

    var adminBar = document.getElementById('wpadminbar');
    var adminBarHeight = adminBar ? adminBar.offsetHeight : 0;
    
    header.style.top = adminBarHeight + 'px';
}

function hoverslippery() {

    $('.underline').hoverSlippery({
        border: true,
        borderWidth: "2px",
        underline: true
    });

    $('.underline .slippery').css({
        width: '0',
        left: '0'
    });

    $('.underline').on('mouseleave', function () {
        $('.underline .slippery').stop(true).css({
            width: '0',
            left: '0'
        });
    });

}

function cookieFooterItem() {
    var $footerMenu = $('nav.footer-menu, .menu-footer-menu-container');
    var $cookieItem = $footerMenu.find('.menu-item.cookie-item a');
    
    if ($cookieItem.length) {
        $cookieItem.attr('data-cc', 'show-preferencesModal');
    }
}

function backtotop() {
    const backToTop = document.querySelector(".backtotop");

    if (backToTop) {
        backToTop.addEventListener("click", function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        });
    }
}