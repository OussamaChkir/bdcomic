document.addEventListener('DOMContentLoaded', function() {
    headerStickyAdminbar();
    //hoverslippery();
    cookieFooterItem();
    backtotop();
    initHeaderDropdowns();
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

function initHeaderDropdowns() {
    // Handle user dropdown and login dropdown
    const userDropdownToggle = document.querySelector('.user-dropdown-toggle');
    const loginDropdownToggle = document.querySelector('.login-dropdown-toggle');
    const userDropdownMenu = document.querySelector('.user-dropdown-menu');
    const loginDropdownMenu = document.querySelector('.login-dropdown-menu');

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        // Close user dropdown if clicking outside
        if (userDropdownToggle && userDropdownMenu) {
            if (!userDropdownToggle.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                const dropdown = new bootstrap.Dropdown(userDropdownToggle);
                dropdown.hide();
            }
        }

        // Close login dropdown if clicking outside
        if (loginDropdownToggle && loginDropdownMenu) {
            if (!loginDropdownToggle.contains(event.target) && !loginDropdownMenu.contains(event.target)) {
                const dropdown = new bootstrap.Dropdown(loginDropdownToggle);
                dropdown.hide();
            }
        }
    });

    // Handle dropdown toggle clicks
    if (userDropdownToggle) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }

    if (loginDropdownToggle) {
        loginDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }

    // Handle successful login - close dropdown and refresh page
    if (loginDropdownMenu) {
        loginDropdownMenu.addEventListener('click', function(e) {
            // Check if login was successful by looking for success messages
            const successMessage = loginDropdownMenu.querySelector('.um-notice-success');
            if (successMessage) {
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });
    }
}