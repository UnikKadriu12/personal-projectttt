<form role="search" method="get" class="search-form car-search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-fields-wrapper">
        <div class="search-field-group">
            <label for="car-search">
                <span class="screen-reader-text"><?php _x('Search for:', 'label', 'car-search-pro'); ?></span>
                <input type="search" id="car-search" class="search-field" placeholder="<?php echo esc_attr__('Search car model or brand', 'car-search-pro'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            </label>
        </div>

        <div class="search-field-group">
            <label for="car-brand"><?php _e('Brand:', 'car-search-pro'); ?></label>
            <select id="car-brand" name="car_brand" class="car-filter">
                <option value=""><?php _e('All Brands', 'car-search-pro'); ?></option>
                <?php
                $brands = get_terms('car_brand', array('hide_empty' => false));
                if (!empty($brands) && !is_wp_error($brands)) {
                    foreach ($brands as $brand) {
                        $selected = isset($_GET['car_brand']) && $_GET['car_brand'] == $brand->slug ? 'selected' : '';
                        echo '<option value="' . esc_attr($brand->slug) . '" ' . $selected . '>' . esc_html($brand->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

    </div>

    <button type="submit" class="search-submit"><?php echo esc_html__('Search Cars', 'car-search-pro'); ?></button>
    <input type="hidden" name="post_type" value="car" />
</form>
