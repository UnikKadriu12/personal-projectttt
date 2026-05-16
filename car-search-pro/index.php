<?php get_header(); ?>

<main class="site-content">
    <section class="section-heading">
        <h2><?php _e('Latest Cars Available', 'car-search-pro'); ?></h2>
    </section>

    <?php if (have_posts()) : ?>
        <div class="car-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article class="car-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="car-card-body">
                        <h3 class="car-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="car-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 22, '...'); ?></p>
                        <div class="car-card-meta">
                            <span><?php echo get_post_meta(get_the_ID(), 'car_price', true) ? esc_html(get_post_meta(get_the_ID(), 'car_price', true)) : esc_html__('Price upon request', 'car-search-pro'); ?></span>
                            <a class="button-secondary" href="<?php the_permalink(); ?>"><?php _e('View', 'car-search-pro'); ?></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(array('mid_size' => 1, 'prev_text' => __('Previous', 'car-search-pro'), 'next_text' => __('Next', 'car-search-pro'))); ?>
    <?php else : ?>
        <p><?php _e('No car listings were found. Try a new search or add new vehicles in the admin panel.', 'car-search-pro'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
