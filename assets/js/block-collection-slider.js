/**
 * Collection Slider Block JavaScript
 * Handles Swiper carousel initialization for the collection slider component
 */

(function() {
    'use strict';

    // Initialize when document is ready and Swiper is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Wait for Swiper to be available (CDN loading)
        var checkSwiper = setInterval(function() {
            if (typeof Swiper !== 'undefined') {
                clearInterval(checkSwiper);
                initCollectionSlider();
            }
        }, 100);
        
        // Fallback timeout after 3 seconds
        setTimeout(function() {
            clearInterval(checkSwiper);
            if (typeof Swiper === 'undefined') {
                console.error('Swiper slider failed to load from CDN. Please check your internet connection.');
            } else {
                initCollectionSlider();
            }
        }, 3000);
    });

    /**
     * Initialize collection slider functionality
     */
    function initCollectionSlider() {
        var sliderElement = document.getElementById('collection-slider');

        // Check if slider exists and Swiper is available
        if (!sliderElement) {
            console.warn('Collection slider element not found');
            return;
        }

        if (typeof Swiper === 'undefined') {
            console.error('Swiper slider library not loaded. Please ensure swiper-bundle.min.js is properly enqueued.');
            return;
        }

        // Check if slider has slides
        var slides = sliderElement.querySelectorAll('.swiper-slide');
        if (slides.length === 0) {
            console.warn('No collection slides found in slider');
            return;
        }

        // Get slider settings from localized data
        var settings = window.collectionSliderData || {};
        
        // Initialize Swiper
        var swiper = new Swiper('#collection-slider', {
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
                prevSlideMessage: 'Slide précédent',
                nextSlideMessage: 'Slide suivant',
                firstSlideMessage: 'C\'est le premier slide',
                lastSlideMessage: 'C\'est le dernier slide'
            },
            
            // Keyboard control
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            
            // Events
            on: {
                init: function() {
                    console.log('Collection slider initialized successfully');
                    addCustomStyles();
                    handleImageLoading();
                },
                slideChange: function() {
                    // Optional: Add any slide change logic here
                },
                autoplayStart: function() {
                    console.log('Collection slider autoplay started');
                },
                autoplayStop: function() {
                    console.log('Collection slider autoplay stopped');
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
        window.collectionSwiper = swiper;
    }

    /**
     * Handle image loading and display
     */
    function handleImageLoading() {
        var images = document.querySelectorAll('#collection-slider .collection-logo');
        
        images.forEach(function(img) {
            // Handle lazy loading
            if (img.getAttribute('loading') === 'lazy') {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
                
                img.addEventListener('error', function() {
                    console.warn('Failed to load image:', this.src);
                    // Fallback to placeholder
                    this.classList.add('loaded');
                });
            }
        });
    }

    /**
     * Add custom styles for slider controls
     */
    function addCustomStyles() {
        if (!document.getElementById('collection-slider-custom-styles')) {
            var style = document.createElement('style');
            style.id = 'collection-slider-custom-styles';
            style.textContent = `
                /* Collection Slider Navigation Buttons */
                .collection-slider .swiper-button-next,
                .collection-slider .swiper-button-prev {
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

                .collection-slider .swiper-button-next:hover,
                .collection-slider .swiper-button-prev:hover {
                    background: #ffffff;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    transform: translateY(-50%) scale(1.1);
                }

                .collection-slider .swiper-button-prev {
                    left: 10px;
                }

                .collection-slider .swiper-button-next {
                    right: 10px;
                }

                .collection-slider .swiper-button-next::after,
                .collection-slider .swiper-button-prev::after {
                    font-size: 20px;
                    font-weight: bold;
                }

                .collection-slider .swiper-button-disabled {
                    opacity: 0.3;
                    cursor: not-allowed;
                }

                .collection-slider .swiper-button-disabled:hover {
                    transform: translateY(-50%);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }

                /* Collection Slider Pagination */
                .collection-slider .swiper-pagination {
                    position: absolute;
                    bottom: 10px;
                    left: 50%;
                    transform: translateX(-50%);
                    z-index: 10;
                }

                .collection-slider .swiper-pagination-bullet {
                    width: 12px;
                    height: 12px;
                    background: rgba(0, 0, 0, 0.2);
                    border-radius: 50%;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    margin: 0 4px;
                }

                .collection-slider .swiper-pagination-bullet:hover,
                .collection-slider .swiper-pagination-bullet-active {
                    background: var(--color-accent, #0066cc);
                    transform: scale(1.2);
                }

                /* Collection Slider Loading State */
                .collection-slider.swiper-container-loading .swiper-wrapper {
                    opacity: 0.5;
                }

                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .collection-slider .swiper-button-next,
                    .collection-slider .swiper-button-prev {
                        width: 40px;
                        height: 40px;
                    }
                    
                    .collection-slider .swiper-button-next::after,
                    .collection-slider .swiper-button-prev::after {
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
    window.destroyCollectionSlider = function() {
        if (window.collectionSwiper) {
            window.collectionSwiper.destroy(true, true);
            window.collectionSwiper = null;
        }
    };

    /**
     * Reinitialize slider (for dynamic content)
     */
    window.reinitCollectionSlider = function() {
        if (window.collectionSwiper) {
            window.collectionSwiper.destroy(true, true);
        }
        setTimeout(function() {
            initCollectionSlider();
        }, 100);
    };

    /**
     * Pause autoplay
     */
    window.pauseCollectionSlider = function() {
        if (window.collectionSwiper && window.collectionSwiper.autoplay) {
            window.collectionSwiper.autoplay.pause();
        }
    };

    /**
     * Resume autoplay
     */
    window.resumeCollectionSlider = function() {
        if (window.collectionSwiper && window.collectionSwiper.autoplay) {
            window.collectionSwiper.autoplay.resume();
        }
    };

})();

