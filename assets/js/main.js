document.addEventListener('DOMContentLoaded', function() {
    headerStickyAdminbar();
    hoverslippery();
    cookieFooterItem();

    if (window.innerWidth <= 991) { // Mobile Part
        mobileMenu();
    }
});

window.addEventListener('resize', function() {
    headerStickyAdminbar();

    if (window.innerWidth <= 991) { // Mobile Part
        mobileMenu();
    }
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

function mobileMenu() {
    $(".main-navigation .navbar-toggler").on("click", function () {
        $('header').toggleClass('open');
    });
}