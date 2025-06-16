document.addEventListener('DOMContentLoaded', function() {
    headerStickyAdminbar();
    hoverslippery();
    // linkSeemore();
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
    hoverslippery();
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

function linkSeemore() {
    var linkSeemore = $('a[target="_blank"]');

    if (linkSeemore.length > 0) {
        linkSeemore.each(function () {
            if ($(this).attr('rel') === 'noopener') {
                $(this).addClass('linkseemore');
            }
        });
    }
}

function searchMenu() {
    $('.search-toggle').on('click', function(event) {
        event.preventDefault();

        $("#main-menu-dropdown").hide();

        const searchBlock = $(this).attr("data-bs-target");
        $(searchBlock).show();

        $('body').addClass('open-menu');
    });

    $('.search-close').on('click', function(event) {
        event.preventDefault();

        const searchBlock = $(this).attr("data-bs-target");
        $(searchBlock).hide();

        $('body').removeClass('open-menu');
    });
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

function mobilesearchMenu() {
    $('.search-toggle').on('click', function(event) {
        event.preventDefault();

        $(this).hide();
        $("#main-menu-mobile").hide();
        $(".main-navigation .navbar-toggler").hide();
        $(".main-navigation").hide();
        $("header .main-navigation .btn-close.navigation-close").hide();

        const searchBlock = $(this).attr("data-bs-target");
        $(searchBlock).show();
        $("header .header-search .btn-close.search-close-mobile").show();

        $('body').addClass('open-menu');
    });

    $('header .header-search .btn-close.search-close-mobile').on('click', function(event) {
        event.preventDefault();

        $(this).hide();
        const searchBlock = $(this).attr("data-bs-target");
        $(searchBlock).hide();

        $(".search-toggle").show();
        $(".main-navigation .navbar-toggler").show();
        $(".main-navigation").show();

        $('body').removeClass('open-menu');
    });
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