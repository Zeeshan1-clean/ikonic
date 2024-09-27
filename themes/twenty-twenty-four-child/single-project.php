<?php get_header(); ?>

<div class="pr_single-container">
    <?php
    if (have_posts()) : 
        while (have_posts()) : the_post(); ?>
            <div class="pr_single-project">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="pr_single-image">
                        <?php the_post_thumbnail('large'); ?> <!-- Adjust size as needed -->
                    </div>
                <?php endif; ?>

                <h1 class="pr_project-title"><?php the_title(); ?></h1>
                
                <div class="pr_meta">
                    <span class="pr_meta-author">By <?php the_author(); ?></span>
                    <span class="pr_meta-date"> | <?php the_date(); ?></span>
                </div>

                <div class="pr_project-content">
                    <?php the_content(); ?>
                </div>

                <div class="pr_custom-fields">
                    <?php 
                    // Display custom fields if needed
                    $custom_field_value = get_post_meta(get_the_ID(), 'custom_field_key', true);
                    if ($custom_field_value) {
                        echo '<div class="pr_custom-field">Custom Field: ' . esc_html($custom_field_value) . '</div>';
                    }
                    ?>
                </div>

                <div class="pr_navigation">
                    <div class="pr_prev">
                        <?php previous_post_link('%link', '« Previous Project'); ?>
                    </div>
                    <div class="pr_next">
                        <?php next_post_link('%link', 'Next Project »'); ?>
                    </div>
                </div>

                <div class="pr_comments">
                    <?php
                    // If comments are open or at least one comment exists, load the comment template
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                </div>
            </div>
        <?php endwhile;
    else : ?>
        <p>No projects found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
