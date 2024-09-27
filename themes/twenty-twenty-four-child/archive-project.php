<?php get_header(); ?>

<div class="pro-container">
    <div class="project-archive">
        <h1 class="pr_title">Projects Archive</h1>
        <?php
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;

        $args = array(
            'post_type'      => 'project',
            'posts_per_page' => 6,
            'paged'          => $paged,
            'order'          => 'DESC',
        );

        $project_query = new WP_Query($args);
        
        if ($project_query->have_posts()) : ?>
            <div class="pr_project-list">
                <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
                    <div class="pr_project-item">
                        <div class="pr-w-30">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="pr_project-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?> <!-- Adjust size as needed -->
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pr-w-70">
                            <h2 class="pr_project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="pr_project-excerpt"><?php the_excerpt(); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="pr_custom-pagination">
                <?php
                $total_pages = $project_query->max_num_pages;
                $current_page = max(1, get_query_var('page'));
                if ($total_pages > 1) {
                    if ($current_page > 1) {
                        echo '<a href="' . add_query_arg('page', $current_page - 1) . '" class="pr_prev">« Previous</a>';
                    } else {
                        echo '<span class="pr_prev pr_disabled">« Previous</span>';
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i === $current_page) {
                            echo '<span class="pr_current">' . $i . '</span>';
                        } else {
                            echo '<a href="' . add_query_arg('page', $i) . '" class="pr_page">' . $i . '</a>';
                        }
                    }
                    if ($current_page < $total_pages) {
                        echo '<a href="' . add_query_arg('page', $current_page + 1) . '" class="pr_next">Next »</a>';
                    } else {
                        echo '<span class="pr_next pr_disabled">Next »</span>';
                    }
                }
                ?>
            </div>

        <?php else : ?>
            <p>No projects found.</p>
        <?php endif; ?>
        <?php
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php get_footer(); ?>
