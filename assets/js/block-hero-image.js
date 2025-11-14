/**
 * Hero Image Slider Block JavaScript
 * Handles Swiper carousel initialization for the hero banner slider
 */

(function() {
    'use strict';

    // Initialize when document is ready and Swiper is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for Swiper to be available (CDN loading)
        var checkSwiper = setInterval(function() {
            if (typeof Swiper !== 'undefined') {
                clearInterval(checkSwiper);
                initHeroSlider();
            }
        }, 100);
        
        // Fallback timeout after 3 seconds
        setTimeout(function() {
            clearInterval(checkSwiper);
            if (typeof Swiper === 'undefined') {
                console.error('Swiper slider failed to load from CDN. Please check your internet connection.');
            } else {
                initHeroSlider();
            }
        }, 3000);
    });

    /**
     * Initialize hero slider functionality
     */
    function initHeroSlider() {
        var sliderElement = document.getElementById('hero-slider');

        // Check if slider exists and Swiper is available
        if (!sliderElement) {
            console.warn('Hero slider element not found');
            return;
        }

        if (typeof Swiper === 'undefined') {
            console.error('Swiper slider library not loaded. Please ensure swiper-bundle.min.js is properly enqueued.');
            return;
        }

        // Check if slider has slides
        var slides = sliderElement.querySelectorAll('.swiper-slide');
        if (slides.length === 0) {
            console.warn('No hero slides found in slider');
            return;
        }

        // Get slider settings from localized data
        var settings = window.heroImageData || {};
        
        // Determine effect
        var effect = settings.effect || 'fade';
        var swiperConfig = {
            // Basic settings
            slidesPerView: 1,
            spaceBetween: 0,
            loop: settings.loop !== false,
            speed: settings.speed || 600,
            effect: effect
        };
        
        // Add effect-specific options
        if (effect === 'fade') {
            swiperConfig.fadeEffect = {
                crossFade: true
            };
        } else if (effect === 'cube') {
            swiperConfig.cubeEffect = {
                shadow: true,
                slideShadows: true,
                shadowOffset: 20,
                shadowScale: 0.94
            };
        }
        
        // Add remaining config options
        swiperConfig.autoplay = settings.autoplay !== false ? {
            delay: settings.autoplaySpeed || 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        } : false;
        
        swiperConfig.navigation = settings.arrows !== false ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false;
        
        swiperConfig.pagination = settings.dots !== false ? {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            }
        } : false;
        
        swiperConfig.a11y = {
            enabled: true,
            prevSlideMessage: 'Previous slide',
            nextSlideMessage: 'Next slide',
            firstSlideMessage: 'This is the first slide',
            lastSlideMessage: 'This is the last slide',
            paginationBulletMessage: 'Go to slide {{index}}'
        };
        
        swiperConfig.keyboard = {
            enabled: true,
            onlyInViewport: true
        };
        
        // Mousewheel disabled to prevent slider from interfering with page scroll
        swiperConfig.mousewheel = {
            enabled: false
        };
        
        swiperConfig.on = {
            init: function() {
                console.log('Hero slider initialized successfully');
                addCustomStyles();
                handleImageLoading();
            },
            slideChange: function() {
                // Reset animations on slide change
                var activeSlide = this.slides[this.activeIndex];
                if (activeSlide) {
                    var content = activeSlide.querySelector('.hero-slide-content');
                    if (content) {
                        content.style.animation = 'none';
                        setTimeout(function() {
                            content.style.animation = '';
                        }, 10);
                    }
                }
            },
            autoplayStart: function() {
                console.log('Hero slider autoplay started');
            },
            autoplayStop: function() {
                console.log('Hero slider autoplay stopped');
            }
        };
        
        // Initialize Swiper with full config
        var swiper = new Swiper('#hero-slider', swiperConfig);

        // Handle window resize
        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (swiper) {
                    swiper.update();
                }
            }, 250);
        });

        // Pause autoplay on hover (if not already handled)
        sliderElement.addEventListener('mouseenter', function() {
            if (swiper && swiper.autoplay) {
                swiper.autoplay.pause();
            }
        });

        sliderElement.addEventListener('mouseleave', function() {
            if (swiper && swiper.autoplay) {
                swiper.autoplay.resume();
            }
        });

        // Store swiper instance globally for external access
        if (typeof window !== 'undefined') {
            window.heroSwiper = swiper;
        }
    }

    /**
     * Handle image loading and display
     */
    function handleImageLoading() {
        var images = document.querySelectorAll('#hero-slider .hero-slide-bg');
        
        images.forEach(function(bg) {
            var img = new Image();
            var bgImage = window.getComputedStyle(bg).backgroundImage;
            var url = bgImage.match(/url\(['"]?(.+?)['"]?\)/);
            
            if (url && url[1]) {
                img.src = url[1];
                img.onload = function() {
                    bg.classList.add('loaded');
                };
                
                img.onerror = function() {
                    console.warn('Failed to load hero image:', url[1]);
                    bg.classList.add('error');
                };
            }
        });
    }

    /**
     * Add custom styles for slider controls
     */
    function addCustomStyles() {
        if (!document.getElementById('hero-slider-custom-styles')) {
            var style = document.createElement('style');
            style.id = 'hero-slider-custom-styles';
            style.textContent = `
                /* Additional hero slider styles */
                .hero-slide-bg.loaded {
                    opacity: 1;
                    transition: opacity 0.5s ease;
                }
                
                .hero-slide-bg:not(.loaded) {
                    opacity: 0;
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Destroy slider on unmount (for dynamic content)
     */
    window.destroyHeroSlider = function() {
        if (window.heroSwiper) {
            window.heroSwiper.destroy(true, true);
            window.heroSwiper = null;
        }
    };

    /**
     * Reinitialize slider (for dynamic content)
     */
    window.reinitHeroSlider = function() {
        if (window.heroSwiper) {
            window.heroSwiper.destroy(true, true);
        }
        setTimeout(function() {
            initHeroSlider();
        }, 100);
    };

    /**
     * Pause autoplay
     */
    window.pauseHeroSlider = function() {
        if (window.heroSwiper && window.heroSwiper.autoplay) {
            window.heroSwiper.autoplay.pause();
        }
    };

    /**
     * Resume autoplay
     */
    window.resumeHeroSlider = function() {
        if (window.heroSwiper && window.heroSwiper.autoplay) {
            window.heroSwiper.autoplay.resume();
        }
    };

})();

