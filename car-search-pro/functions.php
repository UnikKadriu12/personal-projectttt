<?php

function car_search_pro_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'car-search-pro'),
    ));
}
add_action('after_setup_theme', 'car_search_pro_setup');

function car_search_pro_scripts() {
    wp_enqueue_style('car-search-pro-style', get_stylesheet_uri(), array(), '1.0');
    wp_enqueue_style('car-search-pro-google-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
}
add_action('wp_enqueue_scripts', 'car_search_pro_scripts');

function car_search_pro_register_car_post_type() {
    $labels = array(
        'name' => __('Cars', 'car-search-pro'),
        'singular_name' => __('Car', 'car-search-pro'),
        'add_new_item' => __('Add New Car', 'car-search-pro'),
        'edit_item' => __('Edit Car', 'car-search-pro'),
        'new_item' => __('New Car', 'car-search-pro'),
        'view_item' => __('View Car', 'car-search-pro'),
        'search_items' => __('Search Cars', 'car-search-pro'),
        'not_found' => __('No cars found', 'car-search-pro'),
        'not_found_in_trash' => __('No cars found in trash', 'car-search-pro'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'cars'),
        'show_in_rest' => true,
    );

    register_post_type('car', $args);

    register_taxonomy('car_brand', 'car', array(
        'label' => __('Brands', 'car-search-pro'),
        'rewrite' => array('slug' => 'brands'),
        'hierarchical' => false,
        'show_in_rest' => true,
    ));
}
add_action('init', 'car_search_pro_register_car_post_type');

function car_search_pro_search_form($form) {
    $form = '<form role="search" method="get" class="search-form car-search-form" action="' . esc_url(home_url('/')) . '">';
    $form .= '<div class="search-fields-wrapper">';
    $form .= '<div class="search-field-group"><label><span class="screen-reader-text">' . _x('Search for:', 'label', 'car-search-pro') . '</span>';
    $form .= '<input type="search" class="search-field" placeholder="' . esc_attr__('Search car model or brand', 'car-search-pro') . '" value="' . get_search_query() . '" name="s" />';
    $form .= '</label></div>';
    
    $form .= '<div class="search-field-group"><label>' . __('Brand:', 'car-search-pro') . '</label><select name="car_brand" class="car-filter">';
    $form .= '<option value="">' . __('All Brands', 'car-search-pro') . '</option>';
    $brands = get_terms('car_brand', array('hide_empty' => false));
    if (!empty($brands) && !is_wp_error($brands)) {
        foreach ($brands as $brand) {
            $selected = isset($_GET['car_brand']) && $_GET['car_brand'] == $brand->slug ? 'selected' : '';
            $form .= '<option value="' . esc_attr($brand->slug) . '" ' . $selected . '>' . esc_html($brand->name) . '</option>';
        }
    }
    $form .= '</select></div>';
    
    $form .= '<div class="search-field-group"><label>' . __('Min Price:', 'car-search-pro') . '</label>';
    $form .= '<input type="number" name="price_min" class="car-filter" placeholder="' . esc_attr__('Min', 'car-search-pro') . '" value="' . (isset($_GET['price_min']) ? esc_attr($_GET['price_min']) : '') . '" /></div>';
    
    $form .= '<div class="search-field-group"><label>' . __('Max Price:', 'car-search-pro') . '</label>';
    $form .= '<input type="number" name="price_max" class="car-filter" placeholder="' . esc_attr__('Max', 'car-search-pro') . '" value="' . (isset($_GET['price_max']) ? esc_attr($_GET['price_max']) : '') . '" /></div>';
    
    $form .= '<div class="search-field-group"><label>' . __('Min Year:', 'car-search-pro') . '</label>';
    $form .= '<input type="number" name="year_min" class="car-filter" placeholder="' . esc_attr__('Min', 'car-search-pro') . '" value="' . (isset($_GET['year_min']) ? esc_attr($_GET['year_min']) : '') . '" /></div>';
    
    $form .= '<div class="search-field-group"><label>' . __('Max Year:', 'car-search-pro') . '</label>';
    $form .= '<input type="number" name="year_max" class="car-filter" placeholder="' . esc_attr__('Max', 'car-search-pro') . '" value="' . (isset($_GET['year_max']) ? esc_attr($_GET['year_max']) : '') . '" /></div>';
    
    $form .= '</div>';
    $form .= '<button type="submit" class="search-submit">' . esc_html__('Search Cars', 'car-search-pro') . '</button>';
    $form .= '<input type="hidden" name="post_type" value="car" />';
    $form .= '</form>';

    return $form;
}
add_filter('get_search_form', 'car_search_pro_search_form');

// Handle advanced car search filtering
function car_search_pro_modify_search_query($query) {
    if (!is_admin() && $query->is_search() && is_main_query()) {
        $query->set('post_type', 'car');
        
        // Brand filter
        if (isset($_GET['car_brand']) && !empty($_GET['car_brand'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'car_brand',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['car_brand']),
                ),
            ));
        }
        
        // Price range filter
        $meta_query = array('relation' => 'AND');
        
        if (isset($_GET['price_min']) && !empty($_GET['price_min'])) {
            $meta_query[] = array(
                'key' => 'car_price',
                'value' => intval($_GET['price_min']),
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }
        
        if (isset($_GET['price_max']) && !empty($_GET['price_max'])) {
            $meta_query[] = array(
                'key' => 'car_price',
                'value' => intval($_GET['price_max']),
                'compare' => '<=',
                'type' => 'NUMERIC',
            );
        }
        
        // Year range filter
        if (isset($_GET['year_min']) && !empty($_GET['year_min'])) {
            $meta_query[] = array(
                'key' => 'car_year',
                'value' => intval($_GET['year_min']),
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }
        
        if (isset($_GET['year_max']) && !empty($_GET['year_max'])) {
            $meta_query[] = array(
                'key' => 'car_year',
                'value' => intval($_GET['year_max']),
                'compare' => '<=',
                'type' => 'NUMERIC',
            );
        }
        
        if (count($meta_query) > 1) {
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'car_search_pro_modify_search_query');
