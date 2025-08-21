/**
 * Simple Lightbox for Custom Gallery Carousel
 */
(function($) {
    'use strict';

    // Create lightbox container if it doesn't exist
    if ($('#cgc-lightbox').length === 0) {
        // Create a variable to store the plugin URL path
        let pluginUrl = '';
        
        // Try to determine the plugin URL from script tags
        $('script').each(function() {
            const src = $(this).attr('src');
            if (src && src.indexOf('custom-gallery-carousel') !== -1 && src.indexOf('js/lightbox.js') !== -1) {
                pluginUrl = src.substring(0, src.indexOf('js/lightbox.js'));
                return false; // Break the loop
            }
        });
        
        // Fallback to a relative path if we couldn't determine the plugin URL
        if (!pluginUrl) {
            pluginUrl = '../';
        }
        
        $('body').append(`
            <div id="cgc-lightbox" class="cgc-lightbox">
                <div class="cgc-lightbox-content">
                    <img src="" alt="" class="cgc-lightbox-image">
                    <button class="cgc-lightbox-close">
                        <img src="${pluginUrl}images/close-icon.svg" alt="Close">
                    </button>
                    <button class="cgc-lightbox-prev">
                        <img src="${pluginUrl}images/prev-icon.svg" alt="Previous">
                    </button>
                    <button class="cgc-lightbox-next">
                        <img src="${pluginUrl}images/next-icon.svg" alt="Next">
                    </button>
                </div>
            </div>
        `);
    }

    // Global variables
    let galleryImages = [];
    let currentIndex = 0;
    let isNavigating = false;

    /**
     * Collect all images from the carousel
     * This function rebuilds the image array from scratch
     */
    function collectImages() {
        galleryImages = [];
        
        // Get all images from the main carousel
        $('.main-carousel .item').each(function(index) {
            const img = $(this).find('img');
            if (img.length && img.attr('src')) {
                galleryImages.push({
                    index: index,
                    src: img.attr('src'),
                    alt: img.attr('alt') || '',
                    element: img
                });
            }
        });
        
        console.log('Collected ' + galleryImages.length + ' images');
        return galleryImages.length > 0;
    }

    /**
     * Initialize lightbox functionality
     */
    function initLightbox() {
        // Remove any existing event handlers to prevent duplicates
        $('.main-carousel .item img').off('click.lightbox');
        $('.cgc-lightbox-close, .cgc-lightbox').off('click.lightbox');
        $('.cgc-lightbox-prev').off('click.lightbox');
        $('.cgc-lightbox-next').off('click.lightbox');
        $(document).off('keydown.lightbox');
        
        // Collect images
        if (!collectImages()) {
            console.log('No images found to initialize lightbox');
            return;
        }
        
        // Add click event to main carousel images
        $('.main-carousel .item img').on('click.lightbox', function(e) {
            e.preventDefault();
            
            // Find this image in our collected array
            const clickedSrc = $(this).attr('src');
            let foundIndex = -1;
            
            for (let i = 0; i < galleryImages.length; i++) {
                if (galleryImages[i].src === clickedSrc) {
                    foundIndex = i;
                    break;
                }
            }
            
            // If found, open lightbox
            if (foundIndex >= 0) {
                openLightbox(foundIndex);
            } else {
                // Fallback to data-index if direct match fails
                const dataIndex = parseInt($(this).data('index'), 10);
                if (!isNaN(dataIndex) && dataIndex >= 0 && dataIndex < galleryImages.length) {
                    openLightbox(dataIndex);
                } else {
                    console.error('Could not determine image index');
                }
            }
        });

        // Close lightbox when clicking outside the image
        $('.cgc-lightbox').on('click.lightbox', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });
        
        // Close button click handler - use direct binding
        $('.cgc-lightbox-close').on('click.lightbox', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeLightbox();
        });

        // Navigate to previous image - use direct binding
        $('.cgc-lightbox-prev').on('click.lightbox', function(e) {
            e.preventDefault();
            e.stopPropagation();
            navigateLightbox('prev');
        });

        // Navigate to next image - use direct binding
        $('.cgc-lightbox-next').on('click.lightbox', function(e) {
            e.preventDefault();
            e.stopPropagation();
            navigateLightbox('next');
        });

        // Keyboard navigation
        $(document).on('keydown.lightbox', function(e) {
            if ($('#cgc-lightbox').is(':visible')) {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    navigateLightbox('prev');
                } else if (e.key === 'ArrowRight') {
                    navigateLightbox('next');
                }
            }
        });
        
        console.log('Lightbox initialized with ' + galleryImages.length + ' images');
    }

    /**
     * Open lightbox with specified image index
     */
    function openLightbox(index) {
        console.log('Opening lightbox with index: ' + index);
        
        // Validate index
        if (index < 0 || index >= galleryImages.length) {
            console.error('Invalid image index: ' + index);
            return;
        }
        
        // Set current index
        currentIndex = index;
        
        // Get image data
        const image = galleryImages[currentIndex];
        console.log('Showing image: ' + image.src);
        
        // Clear any existing image first
        $('.cgc-lightbox-image').attr('src', '');
        
        // Set new image
        setTimeout(function() {
            $('.cgc-lightbox-image').attr('src', image.src).attr('alt', image.alt || '');
        }, 10);
        
        // Show lightbox
        $('#cgc-lightbox').fadeIn(300);
        $('body').addClass('cgc-lightbox-open');
    }

    /**
     * Close lightbox
     */
    function closeLightbox() {
        $('#cgc-lightbox').fadeOut(300);
        $('body').removeClass('cgc-lightbox-open');
    }

    /**
     * Navigate to previous or next image
     */
    function navigateLightbox(direction) {
        // Prevent rapid clicking
        if (isNavigating) return;
        isNavigating = true;
        
        console.log('Navigating: ' + direction + ' from index ' + currentIndex);
        
        // Calculate new index
        let newIndex;
        if (direction === 'prev') {
            newIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
        } else {
            newIndex = (currentIndex + 1) % galleryImages.length;
        }
        
        // Get new image
        const image = galleryImages[newIndex];
        console.log('New index: ' + newIndex + ', image: ' + image.src);
        
        // Update current index
        currentIndex = newIndex;
        
        // Update image with fade effect
        $('.cgc-lightbox-image').fadeOut(150, function() {
            $(this).attr('src', image.src).attr('alt', image.alt || '');
            $(this).fadeIn(150, function() {
                // Allow navigation again after fade completes
                isNavigating = false;
            });
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        console.log('Document ready, initializing lightbox');
        setTimeout(initLightbox, 500);
    });
    
    // Reinitialize when Owl Carousel is initialized or refreshed
    $(document).on('initialized.owl.carousel refreshed.owl.carousel', '.main-carousel', function() {
        console.log('Carousel initialized/refreshed, reinitializing lightbox');
        setTimeout(initLightbox, 500);
    });
    
    // Also reinitialize after carousel changes slides
    $(document).on('translated.owl.carousel', '.main-carousel', function() {
        console.log('Carousel translated, reinitializing lightbox');
        setTimeout(initLightbox, 100);
    });

})(jQuery);