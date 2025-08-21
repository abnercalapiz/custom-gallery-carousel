<?php
// Check if gallery is not empty and is an array
if (!empty($gallery) && is_array($gallery)) :
    // Filter out invalid images and reindex array
    $valid_gallery = array();
    foreach ($gallery as $image) {
        if (isset($image['ID']) && !empty($image['ID'])) {
            $image_data = wp_get_attachment_image_src($image['ID'], 'full');
            if ($image_data && isset($image_data[0])) {
                $valid_gallery[] = $image;
            }
        }
    }
    
    // Only proceed if we have valid images
    if (!empty($valid_gallery)) :
?>
<div class="owl-carousel main-carousel">
    <?php foreach ($valid_gallery as $index => $image) :
        $full_image_url = esc_url(wp_get_attachment_image_src($image['ID'], 'full')[0]); // Full size image
        $alt_text = esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true));
        ?>
        <div class="item" data-index="<?php echo esc_attr($index); ?>">
            <img src="<?php echo $full_image_url; ?>" alt="<?php echo $alt_text; ?>" data-index="<?php echo esc_attr($index); ?>" class="lightbox-image">
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php endif; ?>


<?php
// Only show thumbnails if there's more than one valid image
if (!empty($valid_gallery) && count($valid_gallery) > 1) :
    $valid_images = 0;
?>
<div class="owl-carousel thumbnail-carousel">
<?php foreach ($valid_gallery as $index => $image) :
    $thumb_src = wp_get_attachment_image_src($image['ID'], 'thumbnail'); // Get thumbnail size

    if ($thumb_src && isset($thumb_src[0])) {
        $thumb_url = esc_url($thumb_src[0]);
        $thumb_alt = esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true));
        $valid_images++;
    } else {
        $default_image = plugin_dir_path(dirname(__FILE__)) . 'images/default.jpg';
        if (file_exists($default_image)) {
            $thumb_url = esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/default.jpg');
            $thumb_alt = 'Default Image';
            $valid_images++;
        } else {
            continue; // Skip this image if default image doesn't exist
        }
    }
    ?>
    <div class="thumb-item" data-index="<?php echo esc_attr($index); ?>">
        <img src="<?php echo $thumb_url; ?>" alt="<?php echo $thumb_alt; ?>" data-index="<?php echo esc_attr($index); ?>">
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>


<?php if (!empty($valid_gallery)) : // Only add JavaScript if we have valid images ?>
<script>
jQuery(document).ready(function($) {
    // Check if elements exist before initializing
    if ($('.main-carousel').length) {
        // Make sure carousel is visible even with one image
        $('.main-carousel').css('display', 'block');
        $('.main-carousel .item').css('display', 'block');
        
        // Initialize carousel
        $('.main-carousel').owlCarousel({
            items: 1,
            loop: <?php echo count($valid_gallery) > 1 ? 'true' : 'false'; ?>, // Only loop if more than one image
            nav: <?php echo $settings['main_nav'] ? 'true' : 'false'; ?>,
            dots: <?php echo $settings['pagination'] ? 'true' : 'false'; ?>,
            autoHeight: true, // Maintain image aspect ratios
            autoplay: false
        });
    }

    // Only initialize thumbnail carousel if it exists and we have more than one valid image
    if ($('.thumbnail-carousel').length && <?php echo count($valid_gallery); ?> > 1) {
        $('.thumbnail-carousel').owlCarousel({
            margin: 10,
            loop: false,  // Thumbnails should not loop
            dots: false,
            nav: <?php echo $settings['thumbnail_nav'] ? 'true' : 'false'; ?>,
            responsive: {
                0: { items: <?php echo esc_js($settings['mobile_items']); ?> },
                768: { items: <?php echo esc_js($settings['tablet_items']); ?> },
                1025: { items: <?php echo esc_js($settings['desktop_items']); ?> }
            }
        });

        $('.thumbnail-carousel .thumb-item').on('click', function() {
            var index = $(this).data('index');
            $('.main-carousel').trigger('to.owl.carousel', [index, 300, true]); // Go to slide
        });
    }
    
    // Initialize lightbox after carousel is ready
    setTimeout(function() {
        if (typeof $ !== 'undefined' && $.fn.owlCarousel && $('.main-carousel').length) {
            console.log('Carousel ready, initializing lightbox');
            // This will trigger the lightbox initialization via the event handler in lightbox.js
            $('.main-carousel').trigger('refreshed.owl.carousel');
        }
    }, 1000);
});
</script>
<?php endif; ?>