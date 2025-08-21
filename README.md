# Custom Gallery Carousel

A responsive WordPress image gallery plugin built with Owl Carousel that offers seamless integration with Advanced Custom Fields (ACF) and WooCommerce product galleries.

## Description

Custom Gallery Carousel is a powerful and flexible gallery solution for WordPress that creates beautiful, responsive image carousels. It's designed to work with custom post types, standard WordPress posts/pages, and WooCommerce products, providing optimized viewing experiences across desktop, tablet, and mobile devices.

## Features

### Core Features
- **Responsive Design**: Automatically adjusts image display for mobile, tablet, and desktop devices
- **Owl Carousel Integration**: Built on the reliable Owl Carousel 2 framework
- **Lightbox Support**: Full-screen image viewing with custom lightbox functionality
- **Multiple Navigation Options**: 
  - Main navigation arrows
  - Dot pagination
  - Thumbnail carousel navigation
- **Flexible Image Sources**:
  - ACF gallery fields (recommended)
  - WooCommerce product galleries
  - WordPress featured images
  - Post attachments (fallback)

### Advanced Features
- **ACF Integration**: Seamlessly pulls images from Advanced Custom Fields gallery fields
- **WooCommerce Support**: Display product gallery images with a simple checkbox
- **Customizable Display**: Configure number of visible items per device type
- **Smart Fallbacks**: Automatically falls back to featured images or post attachments when no gallery is found
- **Admin Settings Page**: Easy-to-use interface for configuration

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Advanced Custom Fields (ACF) plugin (optional but recommended)
- WooCommerce (optional, for product gallery support)

## Installation

1. Upload the `custom-gallery-carousel` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Settings > Custom Carousel to configure the plugin
4. (Optional) Install and activate Advanced Custom Fields for full functionality

## Usage

### Basic Usage

Add the shortcode to any post, page, or custom post type:

```
[custom_gallery_carousel]
```

### ACF Gallery Setup

1. Install and activate Advanced Custom Fields
2. Create a gallery field for your post type
3. Set the field name (default: "gallery")
4. Add images to the gallery field on your posts
5. The carousel will automatically display these images

### WooCommerce Integration

1. Enable "WooCommerce Integration" in the plugin settings
2. Add the shortcode to your WooCommerce product template
3. The carousel will display product gallery images

### Configuration Options

Navigate to **Settings > Custom Carousel** to configure:

- **Gallery Field**: Custom field name for ACF gallery (default: "gallery")
- **WooCommerce Integration**: Enable/disable WooCommerce product gallery support
- **Desktop Items**: Number of images visible on desktop (1025px+)
- **Tablet Items**: Number of images visible on tablets (768-1024px)
- **Mobile Items**: Number of images visible on mobile devices (0-767px)
- **Main Nav**: Enable/disable navigation arrows
- **Pagination**: Enable/disable dot navigation
- **Thumbnail Nav**: Enable/disable thumbnail carousel navigation

## Template Integration

### In PHP Templates

```php
<?php echo do_shortcode('[custom_gallery_carousel]'); ?>
```

### With Custom Post Types

The plugin automatically detects the current post ID and displays the appropriate gallery:

```php
// In your single-{post-type}.php template
<?php if (function_exists('do_shortcode')) : ?>
    <?php echo do_shortcode('[custom_gallery_carousel]'); ?>
<?php endif; ?>
```

## Styling

The plugin includes default styling that can be customized. Key CSS classes:

- `.cgc-carousel-wrapper` - Main wrapper
- `.main-carousel` - Primary carousel container
- `.thumbnail-carousel` - Thumbnail navigation container
- `.cgc-lightbox` - Lightbox overlay
- `.owl-nav` - Navigation arrows
- `.owl-dots` - Pagination dots

## Hooks and Filters

The plugin is built with OOP principles and provides clean, extendable code structure.

## Troubleshooting

### Images Not Displaying

1. Ensure ACF is installed and activated (if using ACF fields)
2. Verify the gallery field name matches your ACF field
3. Check that images are properly uploaded to the gallery field
4. For WooCommerce, ensure product galleries have images

### Performance Issues

- Optimize images before uploading
- Use appropriate image sizes
- Consider limiting the number of images in galleries

### JavaScript Conflicts

The plugin uses jQuery and Owl Carousel. Ensure no conflicts with:
- Other carousel plugins
- Theme carousel implementations
- jQuery version compatibility

## Changelog

### Version 1.3
- Enhanced ACF detection with multiple fallback methods
- Added dismissible admin notices
- Improved notice handling for better user experience
- Added support for user preferences on dismissed notices

### Version 1.2
- Added lightbox functionality
- Improved mobile responsiveness
- Enhanced navigation options

### Version 1.1
- Added WooCommerce integration
- Improved settings page
- Added thumbnail navigation option

### Version 1.0
- Initial release
- Basic carousel functionality
- ACF integration
- Responsive design support

## Support

For support, feature requests, or bug reports, please visit:
- Author: [Jezweb](https://www.jezweb.com.au/)
- Plugin Settings: `/wp-admin/options-general.php?page=cgc_owl_custom_gallery_carousel`

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Built with [Owl Carousel 2](https://owlcarousel2.github.io/OwlCarousel2/)
- Font icons by [Font Awesome](https://fontawesome.com/)
- Developed by [Jezweb](https://www.jezweb.com.au/)