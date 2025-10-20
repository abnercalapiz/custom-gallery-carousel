<div class="cgc-wrapper">
<h2>Custom Carousel Settings</h2>
<form method="post" action="" class="form-settings">
    <?php
    settings_fields('cgc_owl_custom_gallery_carousel_options_group');
    do_settings_sections('cgc_owl_custom_gallery_carousel_options_group'); // Important for settings to save correctly
    wp_nonce_field('cgc_owl_custom_gallery_carousel_options_verify'); // Add nonce for security
    ?>

    <div class="cgc-custom">
        <div class="inner-container">
            <label for="<?php echo $this->get_option_name('field'); ?>" class="label font-bold">Gallery Field (custom field)</label>
            <input type="text" id="<?php echo $this->get_option_name('field'); ?>" name="<?php echo $this->get_option_name('field'); ?>" value="<?php echo esc_attr(get_option($this->get_option_name('field'), 'gallery')); ?>" class="cgc-field">
        </div>
		<div class="cgc-note">
			<p>
				<strong>Note:</strong> This field is primarily for use with custom post types.  However, if your website uses WooCommerce and you want to use this carousel with WooCommerce products, you can enable the "Enable WooCommerce Integration" option in the plugin settings.
			</p>
		</div>

    </div>

    <div class="cgc-woocommerce">
	    <div class="inner-container">
		    <input type="checkbox" id="<?php echo $this->get_option_name('woocommerce_integration'); ?>" class="cgc-tick" name="<?php echo $this->get_option_name('woocommerce_integration'); ?>" value="1" <?php checked(1, get_option($this->get_option_name('woocommerce_integration'), 0)); ?>>
		    <label for="<?php echo $this->get_option_name('woocommerce_integration'); ?>" class="label font-bold">Enable WooCommerce Integration</label>						
	    </div>
    </div>

    <!-- Items Options -->
	<div class="inner-container">
		<h3 class="font-bold">Thumbnail Options</h3>
	</div>

    <div class="inner-container">
        <label for="<?php echo $this->get_option_name('desktop_items'); ?>" class="label font-bold">Desktop Items (1025px and up)</label>
        <input type="number" id="<?php echo $this->get_option_name('desktop_items'); ?>" name="<?php echo $this->get_option_name('desktop_items'); ?>" value="<?php echo esc_attr(get_option($this->get_option_name('desktop_items'), 5)); ?>" class="cgc-field">
    </div>

    <div class="inner-container">
        <label for="<?php echo $this->get_option_name('tablet_items'); ?>" class="label font-bold">Tablet Items (768-1024px)</label>
        <input type="number" id="<?php echo $this->get_option_name('tablet_items'); ?>" name="<?php echo $this->get_option_name('tablet_items'); ?>" value="<?php echo esc_attr(get_option($this->get_option_name('tablet_items'), 5)); ?>" class="cgc-field">
    </div>

    <div class="inner-container">
        <label for="<?php echo $this->get_option_name('mobile_items'); ?>" class="label font-bold">Mobile Items (0-767px)</label>
        <input type="number" id="<?php echo $this->get_option_name('mobile_items'); ?>" name="<?php echo $this->get_option_name('mobile_items'); ?>" value="<?php echo esc_attr(get_option($this->get_option_name('mobile_items'), 3)); ?>" class="cgc-field">
    </div>
	<!-- Carousel Options -->
	<div class="inner-container">
		<h3 class="font-bold">Carousel Options</h3>
	</div>
    <div class="inner-container">
		<input type="checkbox" id="<?php echo $this->get_option_name('main_nav'); ?>" class="cgc-tick" name="<?php echo $this->get_option_name('main_nav'); ?>" value="1" <?php checked(1, get_option($this->get_option_name('main_nav'), 0)); ?>>
		<label for="<?php echo $this->get_option_name('main_nav'); ?>" class="label font-bold">Enable Main Nav</label>
	</div>
	<div class="inner-container">
		<input type="checkbox" id="<?php echo $this->get_option_name('pagination'); ?>" class="cgc-tick" name="<?php echo $this->get_option_name('pagination'); ?>" value="1" <?php checked(1, get_option($this->get_option_name('pagination'), 0)); ?>>
		<label for="<?php echo $this->get_option_name('pagination'); ?>" class="label font-bold">Enable Pagination</label>
	</div>
	<div class="inner-container">
		<input type="checkbox" id="<?php echo $this->get_option_name('thumbnail_nav'); ?>" class="cgc-tick" name="<?php echo $this->get_option_name('thumbnail_nav'); ?>" value="1" <?php checked(1, get_option($this->get_option_name('thumbnail_nav'), 0)); ?>>
		<label for="<?php echo $this->get_option_name('thumbnail_nav'); ?>" class="label font-bold">Enable Thumbnail Carousel Nav</label>
	</div>
	<div class="inner-container">
		<label for="<?php echo $this->get_option_name('thumbnail_style'); ?>" class="label font-bold">Thumbnail Style</label>
		<select id="<?php echo $this->get_option_name('thumbnail_style'); ?>" name="<?php echo $this->get_option_name('thumbnail_style'); ?>" class="cgc-field">
			<option value="bottom" <?php selected('bottom', get_option($this->get_option_name('thumbnail_style'), 'bottom')); ?>>Bottom Style</option>
			<option value="left" <?php selected('left', get_option($this->get_option_name('thumbnail_style'), 'bottom')); ?>>Left Align</option>
		</select>
	</div>


    <?php submit_button(); ?>
</form>
<div class="documentation">
		<h3 class="font-bold">
			Documentation
		</h3>
		<ul>
			<li>Install Advanced Custom Fields (ACF) plugin https://wordpress.org/plugins/advanced-custom-fields/</li>
			<li>Create a gallery field using Advanced Custom Fields (ACF) and ensure the field name matches the meta field "gallery". This allows the carousel to automatically pull images from the custom gallery field for each post or custom post type.</li>
			<li>You can use the `[custom_gallery_carousel]` shortcode in your single template, and it also works with any custom post type.</li>
			<li>To use WooCommerce product gallery images, enable the "Enable WooCommerce Integration" checkbox in the plugin settings.</li>
		</ul>
	</div>
</div>