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
        'archives' => __('Car Archives', 'car-search-pro'),
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
add_action('init', 'car_search_pro_insert_demo_cars');

function car_search_pro_attach_image_from_url($post_id, $image_url) {
    if (empty($image_url)) {
        return;
    }
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $remote_get = wp_remote_get($image_url);
    if (is_wp_error($remote_get)) {
        return;
    }
    $image_content = wp_remote_retrieve_body($remote_get);
    $filename = basename(parse_url($image_url, PHP_URL_PATH));
    if (empty($filename)) {
        $filename = 'car-' . $post_id . '.jpg';
    }
    $upload_file = wp_upload_bits($filename, null, $image_content);
    if ($upload_file['error']) {
        return;
    }
    $file_path = $upload_file['file'];
    $file_type = wp_check_filetype($file_path);
    $attachment = array(
        'post_mime_type' => $file_type['type'],
        'post_title' => preg_replace('/\\.[^.]+$/', '', basename($file_path)),
        'post_content' => '',
        'post_status' => 'inherit',
    );
    $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
    if (!is_wp_error($attach_id)) {
        wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $file_path));
        set_post_thumbnail($post_id, $attach_id);
    }
}

function car_search_pro_insert_demo_cars() {
    if (!post_type_exists('car')) {
        return;
    }

    if (!get_option('car_search_pro_demo_cars_created')) {
        update_option('car_search_pro_demo_cars_created', 1);
    }

    $demo_brands = array(
        'bmw' => 'BMW',
        'mercedes-benz' => 'Mercedes-Benz',
        'audi' => 'Audi',
        'porsche' => 'Porsche',
        'tesla' => 'Tesla',
        'range-rover' => 'Range Rover',
        'lexus' => 'Lexus',
        'jaguar' => 'Jaguar',
        'cadillac' => 'Cadillac',
        'aston-martin' => 'Aston Martin',
        'bentley' => 'Bentley',
        'volvo' => 'Volvo',
        'maserati' => 'Maserati',
        'infiniti' => 'Infiniti',
        'ferrari' => 'Ferrari',
        'mclaren' => 'McLaren',
        'lamborghini' => 'Lamborghini',
    );

    foreach ($demo_brands as $slug => $name) {
        if (!term_exists($slug, 'car_brand')) {
            wp_insert_term($name, 'car_brand', array('slug' => $slug));
        }
    }

    $demo_cars = array(
        array(
            'title' => 'BMW X5',
            'content' => 'Spacious SUV with premium comfort and advanced safety features.',
            'brand' => 'bmw',
            'price' => 'From $105/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1552820728-8ac41f1ce891?w=800&q=80',
        ),
        array(
            'title' => 'Mercedes-Benz E-Class',
            'content' => 'Luxury executive sedan ideal for business travelers and city drives.',
            'brand' => 'mercedes-benz',
            'price' => 'From $89/day',
            'year' => '2022',
            'image' => 'https://images.unsplash.com/photo-1553882900-f2b06423fffa?w=800&q=80',
        ),
        array(
            'title' => 'Audi A6',
            'content' => 'Refined sedan with a smooth ride for airport transfers and weekend trips.',
            'brand' => 'audi',
            'price' => 'From $95/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1606611283684-ace0d36b4e6c?w=800&q=80',
        ),
        array(
            'title' => 'Porsche Panamera',
            'content' => 'Sporty luxury saloon with exceptional handling and premium interiors.',
            'brand' => 'porsche',
            'price' => 'From $135/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=800&q=80',
        ),
        array(
            'title' => 'Tesla Model S',
            'content' => 'Electric performance sedan with cutting-edge range and smart technology.',
            'brand' => 'tesla',
            'price' => 'From $120/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1560958089-b8a63c50c8f1?w=800&q=80',
        ),
        array(
            'title' => 'Range Rover Velar',
            'content' => 'Elegant midsize SUV with refined design and first-class comfort.',
            'brand' => 'range-rover',
            'price' => 'From $115/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
        ),
        array(
            'title' => 'Lexus RX 350',
            'content' => 'Luxury crossover with premium comfort, smooth handling, and advanced safety.',
            'brand' => 'lexus',
            'price' => 'From $110/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1606611283684-ace0d36b4e6c?w=800&q=80',
        ),
        array(
            'title' => 'Jaguar F-Type',
            'content' => 'Sporty coupe with thrilling performance and sleek British design.',
            'brand' => 'jaguar',
            'price' => 'From $145/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1567818735868-e71b99932e29?w=800&q=80',
        ),
        array(
            'title' => 'Cadillac Escalade',
            'content' => 'Full-size luxury SUV with spacious seating and premium amenities for groups.',
            'brand' => 'cadillac',
            'price' => 'From $155/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1605559424843-9e4c3feb3a9f?w=800&q=80',
        ),
        array(
            'title' => 'Aston Martin DB11',
            'content' => 'High-performance grand tourer with elegant styling and refined craftsmanship.',
            'brand' => 'aston-martin',
            'price' => 'From $225/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1614162692741-3fc627cfc372?w=800&q=80',
        ),
        array(
            'title' => 'Bentley Bentayga',
            'content' => 'Ultra-luxury SUV with exceptional comfort, power, and bespoke interior details.',
            'brand' => 'bentley',
            'price' => 'From $320/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1605559424843-9e4c3feb3a9f?w=800&q=80',
        ),
        array(
            'title' => 'Volvo XC90',
            'content' => 'Premium family SUV with Scandinavian luxury, safety, and intelligent design.',
            'brand' => 'volvo',
            'price' => 'From $98/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
        ),
        array(
            'title' => 'BMW M5',
            'content' => 'High-performance luxury sedan with dynamic handling and cutting-edge technology.',
            'brand' => 'bmw',
            'price' => 'From $165/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1619405399517-d4dc2ebe6e73?w=800&q=80',
        ),
        array(
            'title' => 'Mercedes-Benz GLE',
            'content' => 'Bold SUV combining luxury, performance, and intelligent engineering.',
            'brand' => 'mercedes-benz',
            'price' => 'From $125/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1552820728-8ac41f1ce891?w=800&q=80',
        ),
        array(
            'title' => 'Audi Q8',
            'content' => 'Avant-garde luxury SUV with sleek design and premium technology.',
            'brand' => 'audi',
            'price' => 'From $135/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
        ),
        array(
            'title' => 'Porsche 911',
            'content' => 'Iconic sports car with legendary performance and timeless appeal.',
            'brand' => 'porsche',
            'price' => 'From $155/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1580274455191-1c62238fa333?w=800&q=80',
        ),
        array(
            'title' => 'Tesla Model X',
            'content' => 'Futuristic electric SUV with impressive acceleration and smart features.',
            'brand' => 'tesla',
            'price' => 'From $140/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1605559424843-9e4c3feb3a9f?w=800&q=80',
        ),
        array(
            'title' => 'Range Rover Sport',
            'content' => 'Dynamic SUV blending sporty performance with sophisticated refinement.',
            'brand' => 'range-rover',
            'price' => 'From $135/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
        ),
        array(
            'title' => 'Lexus LC 500',
            'content' => 'Grand tourer with stunning design, powerful engine, and bespoke luxury.',
            'brand' => 'lexus',
            'price' => 'From $180/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1618405959076-a08ab6604b20?w=800&q=80',
        ),
        array(
            'title' => 'Jaguar XJ',
            'content' => 'Elegant sedan with contemporary design and refined driving experience.',
            'brand' => 'jaguar',
            'price' => 'From $155/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1553882900-f2b06423fffa?w=800&q=80',
        ),
        array(
            'title' => 'BMW 5 Series',
            'content' => 'Executive sedan with advanced driver assistance and comfortable luxury.',
            'brand' => 'bmw',
            'price' => 'From $112/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800&q=80',
        ),
        array(
            'title' => 'BMW 7 Series',
            'content' => 'Flagship luxury sedan with executive rear seating and premium amenities.',
            'brand' => 'bmw',
            'price' => 'From $185/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1549921296-3f4d7eec9b30?w=800&q=80',
        ),
        array(
            'title' => 'BMW M3',
            'content' => 'Performance sedan with agile handling and track-ready power.',
            'brand' => 'bmw',
            'price' => 'From $148/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?w=800&q=80',
        ),
        array(
            'title' => 'BMW X3',
            'content' => 'Versatile luxury SUV with strong performance and flexible cargo space.',
            'brand' => 'bmw',
            'price' => 'From $118/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
        ),
        array(
            'title' => 'Audi A4',
            'content' => 'Compact premium sedan with refined interior and smooth handling.',
            'brand' => 'audi',
            'price' => 'From $99/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
        ),
        array(
            'title' => 'Audi A8',
            'content' => 'Full-size luxury sedan with cutting-edge driver assistance and premium comfort.',
            'brand' => 'audi',
            'price' => 'From $158/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800&q=80',
        ),
        array(
            'title' => 'Audi Q7',
            'content' => 'Spacious three-row SUV with luxury finishes and advanced technology.',
            'brand' => 'audi',
            'price' => 'From $130/day',
            'year' => '2023',
            'image' => 'https://images.unsplash.com/photo-1518696954-17d0a6c2590d?w=800&q=80',
        ),
        array(
            'title' => 'Audi RS7',
            'content' => 'High-performance luxury sportback with aggressive styling and thrilling power.',
            'brand' => 'audi',
            'price' => 'From $175/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1549921296-3f4d7eec9b30?w=800&q=80',
        ),
        array(
            'title' => 'Audi e-tron',
            'content' => 'Premium electric SUV delivering quiet luxury and impressive range.',
            'brand' => 'audi',
            'price' => 'From $148/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
        ),
        array(
            'title' => 'Ferrari Roma',
            'content' => 'Italian grand tourer with breathtaking performance and exquisite design.',
            'brand' => 'ferrari',
            'price' => 'From $420/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1522940414-246c41c74ad3?w=800&q=80',
        ),
        array(
            'title' => 'McLaren GT',
            'content' => 'Luxury supercar with exceptional speed, comfort, and dynamic precision.',
            'brand' => 'mclaren',
            'price' => 'From $520/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
        ),
        array(
            'title' => 'Lamborghini Huracan',
            'content' => 'Supercar with dramatic design and adrenaline-fueled performance.',
            'brand' => 'lamborghini',
            'price' => 'From $620/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1505188136650-733655f86fee?auto=format&fit=crop&w=800&q=80',
        ),
        array(
            'title' => 'BMW X7',
            'content' => 'Full-size luxury SUV with three-row seating and signature BMW refinement.',
            'brand' => 'bmw',
            'price' => 'From $198/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?auto=format&fit=crop&w=800&q=80',
        ),
        array(
            'title' => 'BMW 4 Series',
            'content' => 'Sporty coupe and convertible lines for a stylish everyday luxury drive.',
            'brand' => 'bmw',
            'price' => 'From $122/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&w=800&q=80',
        ),
        array(
            'title' => 'BMW Z4',
            'content' => 'Open-top roadster delivering athletic performance and premium comfort.',
            'brand' => 'bmw',
            'price' => 'From $136/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1474142705834-491a558c9b76?auto=format&fit=crop&w=800&q=80',
        ),
        array(
            'title' => 'Audi S5 Sportback',
            'content' => 'Performance liftback with elegant design and exhilarating power.',
            'brand' => 'audi',
            'price' => 'From $145/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1518696954-17d0a6c2590d?w=800&q=80',
        ),
        array(
            'title' => 'Audi SQ5',
            'content' => 'Sporty luxury SUV tuned for dynamic handling and premium cabin comfort.',
            'brand' => 'audi',
            'price' => 'From $142/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800&q=80',
        ),
        array(
            'title' => 'Audi e-tron GT',
            'content' => 'Electric grand tourer combining sophisticated luxury with instant acceleration.',
            'brand' => 'audi',
            'price' => 'From $175/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1549921296-3f4d7eec9b30?w=800&q=80',
        ),
        array(
            'title' => 'Maserati Levante',
            'content' => 'Italian luxury SUV with exotic style and a thrilling V6 engine.',
            'brand' => 'maserati',
            'price' => 'From $235/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1605559424843-9e4c3feb3a9f?w=800&q=80',
        ),
        array(
            'title' => 'Infiniti Q60',
            'content' => 'Sleek coupe blending sporty performance with refined luxury design.',
            'brand' => 'infiniti',
            'price' => 'From $128/day',
            'year' => '2024',
            'image' => 'https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=800&q=80',
        ),
    );

    foreach ($demo_cars as $car) {
        $existing_car = get_page_by_title($car['title'], OBJECT, 'car');
        if ($existing_car) {
            $post_id = $existing_car->ID;
            update_post_meta($post_id, 'car_price', $car['price']);
            update_post_meta($post_id, 'car_year', $car['year']);
            update_post_meta($post_id, 'car_image', isset($car['image']) ? $car['image'] : '');
        } else {
            $post_id = wp_insert_post(array(
                'post_title' => $car['title'],
                'post_content' => $car['content'],
                'post_excerpt' => $car['content'],
                'post_status' => 'publish',
                'post_type' => 'car',
                'meta_input' => array(
                    'car_price' => $car['price'],
                    'car_year' => $car['year'],
                    'car_image' => isset($car['image']) ? $car['image'] : '',
                ),
            ));
        }

        if (!is_wp_error($post_id)) {
            wp_set_post_terms($post_id, array($car['brand']), 'car_brand', false);
        }
    }

    update_option('car_search_pro_demo_cars_created', 1);
}

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
    }
}
add_action('pre_get_posts', 'car_search_pro_modify_search_query');
