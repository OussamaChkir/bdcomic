document.addEventListener('DOMContentLoaded', function() {
    headerStickyAdminbar();
    hoverslippery();
    cookieFooterItem();
    // backtotop();
    // formInputFile();

    // if (window.innerWidth <= 991) { // Mobile Part
    //     mobileMenu();
    //     mobilesearchMenu();
    //     menuStayOpen();
    // } else {
    //     megaMenu();
    //     searchMenu();
    // }
});

window.addEventListener('resize', function() {
    headerStickyAdminbar();
    // if (window.innerWidth <= 991) { // Mobile Part
    //     mobileMenu();
    //     mobilesearchMenu();
    //     menuStayOpen();
    // } else {
    //     megaMenu();
    //     searchMenu();
    // }
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



function megaMenu() {
    $(".nav-item.dropdown .nav-link").on("click", function (event) {
        event.preventDefault();

        $(".nav-item.dropdown").removeClass("selected-menu-item");
        $(this).parent().addClass("selected-menu-item");

        $("#search-form-wrapper").hide();

        // Open Mega Menu
        $("#main-menu-dropdown").show();

        // Show subs menu
        const submenuId = $(this).attr("data-dropdown-id");

        $(".main-menu-dropdown .menu > li").hide();
        $(`.main-menu-dropdown .menu > li#${submenuId}`).show();

        $('body').addClass('open-menu');
    });

    $("header .btn-close").on("click", function (event) {
        event.preventDefault();

        $(".nav-item.dropdown").removeClass("selected-menu-item");

        // Close Mega Menu
        const submenu = $(this).attr("data-bs-target");
        $(submenu).hide();

        $('body').removeClass('open-menu');
    });
}

function mobileMenu() {
    $(".main-navigation .navbar-toggler").on("click", function (event) {
        event.preventDefault();

        $(this).hide();
        $("#search-form-wrapper").hide();

        // Open Mega Menu
        $("header .main-navigation .btn-close.navigation-close").show();
        $("#main-menu-mobile").show();

        $('body').addClass('open-menu');
    });

    $("header .main-navigation .btn-close.navigation-close").on("click", function (event) {
        event.preventDefault();

        $(this).hide();
        $(".main-navigation .navbar-toggler").show();
        $("#main-menu-mobile").hide();

        $('body').removeClass('open-menu');
    });

    $(".navbar-nav .dropdown-menu .dropdown .nav-link").attr('data-bs-toggle', '');
}


function menuStayOpen() {
    var currentMenu = $("#main-menu-mobile .navbar-nav .current-menu-ancestor");

    if (currentMenu.length > 0) {
        currentMenu.find('.nav-link').attr('aria-expanded', 'true');
        currentMenu.find('.nav-link').addClass('show');
        currentMenu.find('.dropdown-menu').addClass('show');
    }
}

function backtotop() {
    const backToTop = document.querySelector(".sticky-item.backtotop");

    if (backToTop) {
        backToTop.addEventListener("click", function () {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        });
    }

    // Mobile 
    if ($(window).width() < 768) {
        const prefooter = document.querySelector(".prefooter");
        const prefooterHeight = document.querySelector(".prefooter").offsetHeight;
        const footerHeight = document.querySelector("footer").offsetHeight;

        if (prefooter) {
            $(".sticky-side").css('bottom', prefooterHeight + footerHeight + 40);
            $(".prefooter").css('margin-top', 100);
        } else {
            $(".sticky-side").css('bottom', footerHeight + 40);
            $("footer").css('margin-top', 100);
        }
    }
}