/**
 * Book Slider Block JavaScript
 * Handles Swiper carousel initialization for the book slider component
 */

(function() {
    'use strict';

    // Initialize when document is ready and Swiper is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for Swiper to be available (CDN loading)
        var checkSwiper = setInterval(function() {
            if (typeof Swiper !== 'undefined') {
                clearInterval(checkSwiper);
                initBookSlider();
            }
        }, 100);
        
        // Fallback timeout after 3 seconds
        setTimeout(function() {
            clearInterval(checkSwiper);
            if (typeof Swiper === 'undefined') {
                console.error('Swiper slider failed to load from CDN. Please check your internet connection.');
            } else {
                initBookSlider();
            }
        }, 3000);
    });

    /**
     * Initialize book slider functionality
     */
    function initBookSlider() {
        var sliderElement = document.getElementById('book-slider');

        // Check if slider exists and Swiper is available
        if (!sliderElement) {
            console.warn('Book slider element not found');
            return;
        }

        if (typeof Swiper === 'undefined') {
            console.error('Swiper slider library not loaded. Please ensure swiper-bundle.min.js is properly enqueued.');
            return;
        }

        // Check if slider has slides
        var slides = sliderElement.querySelectorAll('.swiper-slide');
        if (slides.length === 0) {
            console.warn('No book slides found in slider');
            return;
        }

        // Get slider settings from localized data
        var settings = window.bookSliderData || {};
        
        // Initialize Swiper
        var swiper = new Swiper('#book-slider', {
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
                    console.log('Book slider initialized successfully');
                    addCustomStyles();
                    handleImageLoading();
                },
                slideChange: function() {
                    // Optional: Add any slide change logic here
                },
                autoplayStart: function() {
                    console.log('Book slider autoplay started');
                },
                autoplayStop: function() {
                    console.log('Book slider autoplay stopped');
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
        window.bookSwiper = swiper;
    }

    /**
     * Handle image loading and display
     */
    function handleImageLoading() {
        var images = document.querySelectorAll('#book-slider .book-cover');
        
        images.forEach(function(img) {
            // Handle lazy loading
            if (img.getAttribute('loading') === 'lazy') {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
                
                img.addEventListener('error', function() {
                    console.warn('Failed to load image:', this.src);
                    // Fallback to placeholder
                    this.src = window.location.origin + '/wp-content/themes/bdcomic_theme/assets/img/placeholder/cover.png';
                    this.classList.add('loaded');
                });
            }
        });
    }

    /**
     * Add custom styles for slider controls
     */
    function addCustomStyles() {
        if (!document.getElementById('book-slider-custom-styles')) {
            var style = document.createElement('style');
            style.id = 'book-slider-custom-styles';
            style.textContent = `
                /* Book Slider Navigation Buttons */
                .book-slider .swiper-button-next,
                .book-slider .swiper-button-prev {
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

                .book-slider .swiper-button-next:hover,
                .book-slider .swiper-button-prev:hover {
                    background: #ffffff;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    transform: translateY(-50%) scale(1.1);
                }

                .book-slider .swiper-button-prev {
                    left: 10px;
                }

                .book-slider .swiper-button-next {
                    right: 10px;
                }

                .book-slider .swiper-button-next::after,
                .book-slider .swiper-button-prev::after {
                    font-size: 20px;
                    font-weight: bold;
                }

                .book-slider .swiper-button-disabled {
                    opacity: 0.3;
                    cursor: not-allowed;
                }

                .book-slider .swiper-button-disabled:hover {
                    transform: translateY(-50%);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }

                /* Book Slider Pagination */
                .book-slider .swiper-pagination {
                    position: absolute;
                    bottom: 10px;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 10;
                }

                .book-slider .swiper-pagination-bullet {
                    width: 12px;
                    height: 12px;
                    background: rgba(0, 0, 0, 0.2);
                    border-radius: 50%;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    margin: 0 4px;
                }

                .book-slider .swiper-pagination-bullet:hover,
                .book-slider .swiper-pagination-bullet-active {
                    background: var(--color-accent, #0066cc);
                    transform: scale(1.2);
                }

                /* Book Slider Loading State */
                .book-slider.swiper-container-loading .swiper-wrapper {
                    opacity: 0.5;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .book-slider .swiper-button-next,
                    .book-slider .swiper-button-prev {
                        width: 40px;
                        height: 40px;
                    }
                    
                    .book-slider .swiper-button-next::after,
                    .book-slider .swiper-button-prev::after {
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
    window.destroyBookSlider = function() {
        if (window.bookSwiper) {
            window.bookSwiper.destroy(true, true);
            window.bookSwiper = null;
        }
    };

    /**
     * Reinitialize slider (for dynamic content)
     */
    window.reinitBookSlider = function() {
        if (window.bookSwiper) {
            window.bookSwiper.destroy(true, true);
        }
        setTimeout(function() {
            initBookSlider();
        }, 100);
    };

    /**
     * Pause autoplay
     */
    window.pauseBookSlider = function() {
        if (window.bookSwiper && window.bookSwiper.autoplay) {
            window.bookSwiper.autoplay.pause();
        }
    };

    /**
     * Resume autoplay
     */
    window.resumeBookSlider = function() {
        if (window.bookSwiper && window.bookSwiper.autoplay) {
            window.bookSwiper.autoplay.resume();
        }
    };

})();
