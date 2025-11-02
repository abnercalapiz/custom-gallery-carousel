# Changelog

All notable changes to Custom Gallery Carousel will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5] - 2025-01-02

### Added
- Medium Large Thumbnail option to use WordPress's 768px wide images
- New settings option "Enable Medium Large Thumbnail" for better image quality
- Dynamic thumbnail size selection based on user preference

### Fixed
- Bottom style layout not respecting thumbnail size setting
- Checkbox settings not saving properly on form submission

### Improved
- Thumbnail display quality with option to use larger images
- Settings handling for all checkbox options

## [1.4] - 2025-01-01

### Added
- Thumbnail style options: Bottom Style (default) and Left Align
- Customizable thumbnail spacing (0-50px)
- Show/hide image titles option
- Left-aligned thumbnail layout for desktop view

### Improved
- Thumbnail styling with uncropped display (object-fit: contain)
- Responsive thumbnail layouts for different screen sizes
- CSS organization and styling enhancements

## [1.3] - 2025-01-21

### Added
- Enhanced ACF (Advanced Custom Fields) detection with multiple verification methods
- Dismissible admin notices with user preference storage
- AJAX-powered notice dismissal with proper nonce verification
- Comprehensive README.md documentation
- CHANGELOG.md for version tracking

### Improved
- ACF detection now checks multiple conditions: `function_exists`, `class_exists`, and plugin activation status
- Admin notice only appears when ACF is truly not installed/activated
- Better error handling and fallback mechanisms
- Plugin initialization moved to `plugins_loaded` hook for better compatibility

### Fixed
- False positive ACF notices when the plugin is actually installed
- Notice persistence issues for users who dismiss the warning

## [1.2] - Previous Release

### Added
- Lightbox functionality for full-screen image viewing
- Custom lightbox with smooth animations
- Keyboard navigation support in lightbox (arrow keys and ESC)
- Touch gesture support for mobile devices

### Improved
- Mobile responsiveness
- Navigation controls styling
- Image loading performance

## [1.1] - Previous Release

### Added
- WooCommerce integration for product galleries
- Thumbnail navigation carousel option
- Enhanced settings page with grouped options

### Improved
- Settings organization and user interface
- Code structure with OOP principles

## [1.0] - Initial Release

### Added
- Core carousel functionality using Owl Carousel 2
- ACF gallery field integration
- Responsive design with configurable items per device
- Admin settings page
- Shortcode `[custom_gallery_carousel]` support
- Main navigation arrows option
- Pagination dots option
- Fallback to featured images and post attachments
- Custom styling with included CSS
- Font Awesome icon integration