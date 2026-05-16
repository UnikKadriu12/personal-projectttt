<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="site-branding">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    </div>
    <nav class="primary-navigation" aria-label="Primary Navigation">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'items_wrap' => '%3$s',
                'depth' => 1,
            ));
        } else {
            echo '<a href="' . esc_url(home_url('/')) . '">Home</a>';
            echo '<a href="' . esc_url(home_url('/?post_type=car')) . '">Browse Cars</a>';
        }
        ?>
    </nav>
</header>
