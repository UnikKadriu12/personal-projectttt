<?php
get_header();

$rental_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rental_request_submit']) && isset($_POST['rental_request_nonce']) && wp_verify_nonce($_POST['rental_request_nonce'], 'rental_request_action')) {
    $renter_name = sanitize_text_field($_POST['renter_name'] ?? '');
    $rental_message = sprintf(
        /* translators: %s is the renter name */
        __('Thanks %s! Your rental request has been received. Our team will contact you shortly to confirm availability.', 'car-search-pro'),
        esc_html($renter_name ?: __('Guest', 'car-search-pro'))
    );
}
?>

<main class="site-content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <section class="section-heading">
            <h2><?php the_title(); ?></h2>
        </section>

        <div class="car-detail-grid">
            <div class="car-detail-summary">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="car-detail-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="car-detail-body">
                    <div class="car-detail-meta">
                        <?php
                        $brands = get_the_terms(get_the_ID(), 'car_brand');
                        if (!empty($brands) && !is_wp_error($brands)) :
                        ?>
                            <span><?php echo esc_html__('Brand:', 'car-search-pro'); ?> <?php echo esc_html(join(', ', wp_list_pluck($brands, 'name'))); ?></span>
                        <?php endif; ?>

                        <?php $car_price = get_post_meta(get_the_ID(), 'car_price', true); ?>
                        <?php if ($car_price) : ?>
                            <span><?php echo esc_html__('Price:', 'car-search-pro'); ?> <?php echo esc_html($car_price); ?></span>
                        <?php endif; ?>

                        <?php $car_year = get_post_meta(get_the_ID(), 'car_year', true); ?>
                        <?php if ($car_year) : ?>
                            <span><?php echo esc_html__('Year:', 'car-search-pro'); ?> <?php echo esc_html($car_year); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="car-detail-description">
                        <?php the_content(); ?>
                    </div>

                    <a class="button-primary rent-now-link" href="#rent-form"><?php _e('Rent this Car', 'car-search-pro'); ?></a>
                </div>
            </div>

            <aside class="car-rent-panel">
                <div class="rent-card">
                    <h3><?php _e('Request Rental', 'car-search-pro'); ?></h3>
                    <p><?php _e('Complete the form below to request this car. We will reach out with availability and pricing details.', 'car-search-pro'); ?></p>

                    <?php if ($rental_message) : ?>
                        <div class="rent-message"><?php echo wp_kses_post($rental_message); ?></div>
                    <?php endif; ?>

                    <form id="rent-form" class="rent-form" method="post" action="<?php echo esc_url(get_permalink()); ?>">
                        <?php wp_nonce_field('rental_request_action', 'rental_request_nonce'); ?>

                        <label>
                            <?php _e('Full Name', 'car-search-pro'); ?>
                            <input type="text" name="renter_name" required />
                        </label>

                        <label>
                            <?php _e('Email Address', 'car-search-pro'); ?>
                            <input type="email" name="renter_email" required />
                        </label>

                        <label>
                            <?php _e('Pickup Date', 'car-search-pro'); ?>
                            <input type="date" name="pickup_date" />
                        </label>

                        <label>
                            <?php _e('Return Date', 'car-search-pro'); ?>
                            <input type="date" name="return_date" />
                        </label>

                        <label>
                            <?php _e('Additional Details', 'car-search-pro'); ?>
                            <textarea name="renter_notes"></textarea>
                        </label>

                        <button type="submit" name="rental_request_submit" class="button-primary"><?php _e('Send Request', 'car-search-pro'); ?></button>
                    </form>
                </div>
            </aside>
        </div>
    <?php endwhile; endif; ?>
</main>

<?php get_footer();
