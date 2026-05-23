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
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Mercedes-Benz E-Class', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Luxury executive sedan ideal for business travelers and city drives.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $89/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('BMW X5', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Spacious SUV with premium comfort and advanced safety features.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $105/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Audi A6', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Refined sedan with a smooth ride for airport transfers and weekend trips.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $95/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Porsche Panamera', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Sporty luxury saloon with exceptional handling and premium interiors.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $135/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Tesla Model S', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Electric performance sedan with cutting-edge range and smart technology.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $120/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Range Rover Velar', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Elegant midsize SUV with refined design and first-class comfort.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $115/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Lexus RX 350', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Luxury crossover with premium comfort, smooth handling, and advanced safety.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $110/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Jaguar F-Type', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Sporty coupe with thrilling performance and sleek British design.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $145/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
                    <article class="car-card">
                        <div class="car-card-body">
                            <h3 class="car-card-title"><?php _e('Cadillac Escalade', 'car-search-pro'); ?></h3>
                            <p class="car-card-excerpt"><?php _e('Full-size luxury SUV with spacious seating and premium amenities for groups.', 'car-search-pro'); ?></p>
                            <div class="car-card-meta">
                                <span><?php _e('From $155/day', 'car-search-pro'); ?></span>
                            </div>
                        </div>
                    </article>
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
