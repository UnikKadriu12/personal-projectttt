<?php get_header(); ?>

<section class="hero">
    <div class="hero-inner">
        <p class="eyebrow" style="color: #ff9c4b; text-transform: uppercase; letter-spacing: 0.18em; font-size: 0.85rem; margin-bottom: 16px;">Luxury car rentals</p>
        <h1 class="hero-title"><?php _e('Find the perfect rental car in minutes', 'car-search-pro'); ?></h1>
        <p class="hero-subtitle"><?php _e('Choose from premium sedans, SUVs, and executive vehicles with transparent pricing and professional service. Browse the fleet and reserve the ideal car today.', 'car-search-pro'); ?></p>

        <div class="hero-actions">
            <a class="button-primary" href="<?php echo esc_url(get_post_type_archive_link('car')); ?>"><?php _e('Browse Fleet', 'car-search-pro'); ?></a>
            <a class="button-secondary" href="<?php echo esc_url(get_post_type_archive_link('car')); ?>"><?php _e('View All Rentals', 'car-search-pro'); ?></a>
        </div>

        <div class="search-panel">
            <?php get_search_form(); ?>
            <div class="search-car-list">
                <h3><?php _e('Popular Cars', 'car-search-pro'); ?></h3>
                <div class="car-grid">
                    <?php
                    $popular_cars = new WP_Query(array(
                        'post_type' => 'car',
                        'posts_per_page' => 10,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));

                    if ($popular_cars->have_posts()) :
                        while ($popular_cars->have_posts()) : $popular_cars->the_post(); ?>
                            <article class="car-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
                                <?php endif; ?>
                                <div class="car-card-body">
                                    <h3 class="car-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p class="car-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 16, '...'); ?></p>
                                    <div class="car-card-meta">
                                        <span><?php echo get_post_meta(get_the_ID(), 'car_price', true) ? esc_html(get_post_meta(get_the_ID(), 'car_price', true)) : esc_html__('Price upon request', 'car-search-pro'); ?></span>
                                        <a class="button-secondary" href="<?php the_permalink(); ?>"><?php _e('Rent Now', 'car-search-pro'); ?></a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p><?php _e('No cars are listed yet. Add vehicles in the admin panel to populate this section.', 'car-search-pro'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="site-content">
    <section class="section-heading">
        <h2><?php _e('Featured Car Collections', 'car-search-pro'); ?></h2>
    </section>

    <div class="car-grid">
        <?php
        $featured = new WP_Query(array(
            'post_type' => 'car',
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
        ));

        if ($featured->have_posts()) :
            while ($featured->have_posts()) : $featured->the_post(); ?>
                <article class="car-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
                    <?php endif; ?>
                    <div class="car-card-body">
                        <h3 class="car-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="car-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?></p>
                        <div class="car-card-meta">
                            <span><?php echo get_post_meta(get_the_ID(), 'car_price', true) ? esc_html(get_post_meta(get_the_ID(), 'car_price', true)) : esc_html__('Price upon request', 'car-search-pro'); ?></span>
                            <a class="button-secondary" href="<?php the_permalink(); ?>"><?php _e('Details', 'car-search-pro'); ?></a>
                        </div>
                    </div>
                </article>
            <?php endwhile;
            wp_reset_postdata();
        else : ?>
            <p><?php _e('No featured cars available yet. Add new vehicles from the admin panel and then refresh this page.', 'car-search-pro'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
