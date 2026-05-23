<?php get_header(); ?>

<main class="site-content">
    <section class="section-heading">
        <h2><?php printf(__('Search results for "%s"', 'car-search-pro'), get_search_query()); ?></h2>
    </section>

    <?php if (have_posts()) : ?>
        <div class="car-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article class="car-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
                    <?php elseif ($car_image = get_post_meta(get_the_ID(), 'car_image', true)) : ?>
                        <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($car_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" /></a>
                    <?php endif; ?>
                    <div class="car-card-body">
                        <?php $car_brand = get_the_terms(get_the_ID(), 'car_brand'); ?>
                        <?php if (!empty($car_brand) && !is_wp_error($car_brand)) : ?>
                            <p class="car-card-brand"><?php echo esc_html($car_brand[0]->name); ?></p>
                        <?php endif; ?>
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
        <p><?php _e('No results matched your search. Try a different keyword or browse all cars instead.', 'car-search-pro'); ?></p>
        <?php get_search_form(); ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
