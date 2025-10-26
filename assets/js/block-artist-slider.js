/**
 * Artist Slider Block JavaScript
 * Handles Swiper carousel initialization for the artist slider component
 */

(function() {
    'use strict';

    // Initialize when document is ready and Swiper is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for Swiper to be available (CDN loading)
        var checkSwiper = setInterval(function() {
            if (typeof Swiper !== 'undefined') {
                clearInterval(checkSwiper);
                initArtistSlider();
            }
        }, 100);
        
        // Fallback timeout after 3 seconds
        setTimeout(function() {
            clearInterval(checkSwiper);
            if (typeof Swiper === 'undefined') {
                console.error('Swiper slider failed to load from CDN. Please check your internet connection.');
            } else {
                initArtistSlider();
            }
        }, 3000);
    });

    /**
     * Initialize artist slider functionality
     */
    function initArtistSlider() {
        var sliderElement = document.getElementById('artist-slider');

        // Check if slider exists and Swiper is available
        if (!sliderElement) {
            console.warn('Artist slider element not found');
            return;
        }

        if (typeof Swiper === 'undefined') {
            console.error('Swiper slider library not loaded. Please ensure swiper-bundle.min.js is properly enqueued.');
            return;
        }

        // Check if slider has slides
        var slides = sliderElement.querySelectorAll('.swiper-slide');
        if (slides.length === 0) {
            console.warn('No artist slides found in slider');
            return;
        }

        // Get slider settings from localized data
        var settings = window.artistSliderData || {};
        
        // Initialize Swiper
        var swiper = new Swiper('#artist-slider', {
            // Basic settings
            slidesPerView: settings.slidesToShow || 4,
            spaceBetween: 20,
            loop: settings.infinite || true,
            speed: settings.speed || 500,
            
            // Autoplay
            autoplay: settings.autoplay ? {
                delay: settings.autoplaySpeed || 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            } : false,
            
            // Navigation
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true
            },
            
            // Responsive breakpoints
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                480: {
                    slidesPerView: 1,
                    spaceBetween: 15
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1200: {
                    slidesPerView: settings.slidesToShow || 4,
                    spaceBetween: 20
                }
            },
            
            // Effects
            effect: 'slide',
            
            // Accessibility
            a11y: {
                enabled: true,
                prevSlideMessage: 'Previous slide',
                nextSlideMessage: 'Next slide',
                firstSlideMessage: 'This is the first slide',
                lastSlideMessage: 'This is the last slide'
            },
            
            // Keyboard control
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            
            // Mouse wheel control
            mousewheel: {
                invert: false,
            },
            
            // Events
            on: {
                init: function() {
                    console.log('Artist slider initialized successfully');
                    addCustomStyles();
                },
                slideChange: function() {
                    // Optional: Add any slide change logic here
                },
                autoplayStart: function() {
                    console.log('Autoplay started');
                },
                autoplayStop: function() {
                    console.log('Autoplay stopped');
                }
            }
        });

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

        // Store swiper instance globally for external access
        window.artistSwiper = swiper;
    }

    /**
     * Add custom styles for slider controls
     */
    function addCustomStyles() {
        if (!document.getElementById('artist-slider-custom-styles')) {
            var style = document.createElement('style');
            style.id = 'artist-slider-custom-styles';
            style.textContent = `
                /* Artist Slider Navigation Buttons */
                .artist-slider .swiper-button-next,
                .artist-slider .swiper-button-prev {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    z-index: 10;
                    width: 48px;
                    height: 48px;
                    background: rgba(255, 255, 255, 0.9);
                    border: none;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    color: var(--color-accent, #0066cc);
                }

                .artist-slider .swiper-button-next:hover,
                .artist-slider .swiper-button-prev:hover {
                    background: #ffffff;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    transform: translateY(-50%) scale(1.1);
                }

                .artist-slider .swiper-button-prev {
                    left: 10px;
                }

                .artist-slider .swiper-button-next {
                    right: 10px;
                }

                .artist-slider .swiper-button-next::after,
                .artist-slider .swiper-button-prev::after {
                    font-size: 20px;
                    font-weight: bold;
                }

                .artist-slider .swiper-button-disabled {
                    opacity: 0.3;
                    cursor: not-allowed;
                }

                .artist-slider .swiper-button-disabled:hover {
                    transform: translateY(-50%);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }

                /* Artist Slider Pagination */
                .artist-slider .swiper-pagination {
                    position: absolute;
                    bottom: 10px;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 10;
                }

                .artist-slider .swiper-pagination-bullet {
                    width: 12px;
                    height: 12px;
                    background: rgba(0, 0, 0, 0.2);
                    border-radius: 50%;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    margin: 0 4px;
                }

                .artist-slider .swiper-pagination-bullet:hover,
                .artist-slider .swiper-pagination-bullet-active {
                    background: var(--color-accent, #0066cc);
                    transform: scale(1.2);
                }

                /* Artist Slider Loading State */
                .artist-slider.swiper-container-loading .swiper-wrapper {
                    opacity: 0.5;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .artist-slider .swiper-button-next,
                    .artist-slider .swiper-button-prev {
                        width: 40px;
                        height: 40px;
                    }
                    
                    .artist-slider .swiper-button-next::after,
                    .artist-slider .swiper-button-prev::after {
                        font-size: 16px;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Destroy slider on unmount (for dynamic content)
     */
    window.destroyArtistSlider = function() {
        if (window.artistSwiper) {
            window.artistSwiper.destroy(true, true);
            window.artistSwiper = null;
        }
    };

    /**
     * Reinitialize slider (for dynamic content)
     */
    window.reinitArtistSlider = function() {
        if (window.artistSwiper) {
            window.artistSwiper.destroy(true, true);
        }
        setTimeout(function() {
            initArtistSlider();
        }, 100);
    };

    /**
     * Pause autoplay
     */
    window.pauseArtistSlider = function() {
        if (window.artistSwiper && window.artistSwiper.autoplay) {
            window.artistSwiper.autoplay.pause();
        }
    };

    /**
     * Resume autoplay
     */
    window.resumeArtistSlider = function() {
        if (window.artistSwiper && window.artistSwiper.autoplay) {
            window.artistSwiper.autoplay.resume();
        }
    };

})();