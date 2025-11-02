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
        $thumbnail_style = isset($settings['thumbnail_style']) ? $settings['thumbnail_style'] : 'bottom';
        $thumbnail_spacing = isset($settings['thumbnail_spacing']) ? $settings['thumbnail_spacing'] : 10;
        $show_image_title = isset($settings['show_image_title']) ? $settings['show_image_title'] : false;
        $use_medium_large_thumb = isset($settings['use_medium_large_thumb']) ? $settings['use_medium_large_thumb'] : false;
        $has_thumbnails = count($valid_gallery) > 1;
        $thumb_size = $use_medium_large_thumb ? 'medium_large' : 'thumbnail';
        // Debug: Check if medium large is enabled
        // echo '<!-- Debug: use_medium_large_thumb = ' . ($use_medium_large_thumb ? 'true' : 'false') . ', thumb_size = ' . $thumb_size . ' -->';
?>

<?php if ($thumbnail_style === 'left' && $has_thumbnails) : ?>
    <!-- Left Aligned Layout -->
    <div class="cgc-gallery-wrapper thumbnail-style-left" style="gap: <?php echo esc_attr($thumbnail_spacing); ?>px;">
        <div class="cgc-thumbnails-container">
            <div class="thumbnail-list" style="gap: <?php echo esc_attr($thumbnail_spacing); ?>px;">
            <?php 
            $max_thumbnails = 8;
            $thumbnail_count = 0;
            foreach ($valid_gallery as $index => $image) :
                if ($thumbnail_count >= $max_thumbnails) break;
                $thumb_src = wp_get_attachment_image_src($image['ID'], $thumb_size);
                if ($thumb_src && isset($thumb_src[0])) {
                    $thumb_url = esc_url($thumb_src[0]);
                    $thumb_alt = esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true));
                } else {
                    $default_image = plugin_dir_path(dirname(__FILE__)) . 'images/default.jpg';
                    if (file_exists($default_image)) {
                        $thumb_url = esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/default.jpg');
                        $thumb_alt = 'Default Image';
                    } else {
                        continue;
                    }
                }
                ?>
                <div class="thumb-item" data-index="<?php echo esc_attr($index); ?>">
                    <img src="<?php echo $thumb_url; ?>" alt="<?php echo $thumb_alt; ?>" data-index="<?php echo esc_attr($index); ?>">
                </div>
            <?php 
                $thumbnail_count++;
            endforeach; ?>
            </div>
        </div>
        <div class="cgc-main-container">
            <div class="owl-carousel main-carousel">
                <?php foreach ($valid_gallery as $index => $image) :
                    $full_image_url = esc_url(wp_get_attachment_image_src($image['ID'], 'full')[0]);
                    $alt_text = esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true));
                    $image_title = esc_html(get_the_title($image['ID']));
                    ?>
                    <div class="item" data-index="<?php echo esc_attr($index); ?>">
                        <img src="<?php echo $full_image_url; ?>" alt="<?php echo $alt_text; ?>" data-index="<?php echo esc_attr($index); ?>" class="lightbox-image">
                        <?php if ($show_image_title && !empty($image_title)) : ?>
                            <div class="cgc-image-title"><?php echo $image_title; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else : ?>
    <!-- Bottom Style Layout (Default) -->
    <div class="cgc-gallery-wrapper thumbnail-style-bottom">
        <div class="owl-carousel main-carousel">
            <?php foreach ($valid_gallery as $index => $image) :
                $full_image_url = esc_url(wp_get_attachment_image_src($image['ID'], 'full')[0]);
                $alt_text = esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true));
                $image_title = esc_html(get_the_title($image['ID']));
                ?>
                <div class="item" data-index="<?php echo esc_attr($index); ?>">
                    <img src="<?php echo $full_image_url; ?>" alt="<?php echo $alt_text; ?>" data-index="<?php echo esc_attr($index); ?>" class="lightbox-image">
                    <?php if ($show_image_title && !empty($image_title)) : ?>
                        <div class="cgc-image-title"><?php echo $image_title; ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($has_thumbnails) : ?>
        <div class="owl-carousel thumbnail-carousel">
        <?php foreach ($valid_gallery as $index => $image) :
            $thumb_src = wp_get_attachment_image_src($image['ID'], $thumb_size);
            if ($thumb_src && isset($thumb_src[0])) {
                $thumb_url = esc_url($thumb_src[0]);
                $thumb_alt = esc_attr(get_post_meta($image['ID'], '_wp_attachment_image_alt', true));
            } else {
                $default_image = plugin_dir_path(dirname(__FILE__)) . 'images/default.jpg';
                if (file_exists($default_image)) {
                    $thumb_url = esc_url(plugin_dir_url(dirname(__FILE__)) . 'images/default.jpg');
                    $thumb_alt = 'Default Image';
                } else {
                    continue;
                }
            }
            ?>
            <div class="thumb-item" data-index="<?php echo esc_attr($index); ?>">
                <img src="<?php echo $thumb_url; ?>" alt="<?php echo $thumb_alt; ?>" data-index="<?php echo esc_attr($index); ?>">
            </div>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php endif; ?>
<?php endif; ?>

<?php if (!empty($valid_gallery)) : ?>
<script>
jQuery(document).ready(function($) {
    var thumbnailStyle = '<?php echo esc_js($thumbnail_style); ?>';
    var hasMultipleImages = <?php echo count($valid_gallery); ?> > 1;
    
    // Check if elements exist before initializing
    if ($('.main-carousel').length) {
        // Make sure carousel is visible even with one image
        $('.main-carousel').css('display', 'block');
        $('.main-carousel .item').css('display', 'block');
        
        // Initialize carousel
        $('.main-carousel').owlCarousel({
            items: 1,
            loop: hasMultipleImages,
            nav: <?php echo $settings['main_nav'] ? 'true' : 'false'; ?>,
            dots: <?php echo $settings['pagination'] ? 'true' : 'false'; ?>,
            autoHeight: true,
            autoplay: false
        });
    }

    // Initialize thumbnail carousel for bottom style only
    if ($('.thumbnail-carousel').length && hasMultipleImages && thumbnailStyle !== 'left') {
        var thumbnailOptions = {
            margin: <?php echo esc_js($thumbnail_spacing); ?>,
            loop: false,
            dots: false,
            nav: <?php echo $settings['thumbnail_nav'] ? 'true' : 'false'; ?>,
            responsive: {
                0: { items: <?php echo esc_js($settings['mobile_items']); ?> },
                768: { items: <?php echo esc_js($settings['tablet_items']); ?> },
                1025: { items: <?php echo esc_js($settings['desktop_items']); ?> }
            }
        };
        
        $('.thumbnail-carousel').owlCarousel(thumbnailOptions);
    }
    
    // Handle thumbnail clicks for both styles
    if (hasMultipleImages) {
        $(document).on('click', '.thumb-item', function() {
            var index = $(this).data('index');
            $('.main-carousel').trigger('to.owl.carousel', [index, 300, true]);
            
            // Update active thumbnail
            $('.thumb-item').removeClass('active');
            $(this).addClass('active');
        });
        
        // Set initial active thumbnail
        $('.thumb-item').first().addClass('active');
        
        // Update active thumbnail when main carousel changes
        $('.main-carousel').on('changed.owl.carousel', function(event) {
            var currentIndex = event.item.index;
            $('.thumb-item').removeClass('active');
            $('.thumb-item[data-index="' + currentIndex + '"]').addClass('active');
        });
    }
    
    // Initialize lightbox after carousel is ready
    setTimeout(function() {
        if (typeof $ !== 'undefined' && $.fn.owlCarousel && $('.main-carousel').length) {
            console.log('Carousel ready, initializing lightbox');
            $('.main-carousel').trigger('refreshed.owl.carousel');
        }
    }, 1000);
});
</script>
<?php endif; ?>