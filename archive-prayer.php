<?php declare(strict_types=1); ?>
<?php get_header(); ?>

<?php

dt_print_breadcrumbs( null, __( "Prayer" ) );

(function() {
    global $post;
?>

<div id="content">

    <div id="inner-content" class="grid-x grid-margin-x">

        <main id="main" class="large-8 medium-8 cell" role="main">

            <?php
            $args = array(
                'post_type' => 'prayer',

            );
            $query1 = new WP_Query( $args );
            ?>
            <?php if ( $query1->have_posts() ) : ?>
                <?php /* TODO: check permissions, and maybe move this to the disciple-tools plugin */ ?>
                <?php while ( $query1->have_posts() ) : $query1->the_post(); ?>

                    <!-- To see additional archive styles, visit the /parts directory -->
                    <?php get_template_part( 'parts/loop', 'prayer' ); ?>

                <?php endwhile; ?>

                <?php disciple_tools_page_navi(); ?>

            <?php else : ?>

                <?php get_template_part( 'parts/content', 'missing' ); ?>

            <?php endif; ?>

        </main> <!-- end #main -->

        <aside class="large-4 medium-4 cell">

            <section class="bordered-box">

                <?php include 'searchform.php'; ?>

            </section>

            <section class="bordered-box">

                <h4><?php esc_html_e( 'Recent Posts', 'disciple_tools' )?></h4>

                <?php $args = array(
                    'numberposts' => 10,
                    'offset' => 0,
                    'category' => 0,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'include' => '',
                    'exclude' => '',
                    'meta_key' => '',
                    'meta_value' =>'',
                    'post_type' => 'prayer',
                    'post_status' => 'draft, publish, future, pending, private',
                    'suppress_filters' => true
                );

                $recent_posts = wp_get_recent_posts( $args, ARRAY_A );

                echo '<ul>';
                ?>
                <?php foreach ($recent_posts as $recent_post): ?>
                    <li><a href="<?php echo esc_url( $recent_post['guid'] ); ?>"><?php esc_html_e( $recent_post['post_title'], 'disciple_tools' )?></a></li>
                <?php endforeach; ?>
                <?php
                echo '</ul>';

                //                    print_r($recent_posts);?>

            </section>

            <section class="bordered-box">

                <p><?php esc_html_e( 'Archives', 'disciple_tools' )?></p>

                <?php
                $args = array(
                    'type'            => 'monthly',
                    'limit'           => '',
                    'format'          => 'html',
                    'before'          => '',
                    'after'           => '',
                    'show_post_count' => false,
                    'echo'            => 1,
                    'order'           => 'DESC',
                    'post_type'     => 'prayer'
                );
                wp_get_archives( $args );

                ?>

            </section>


        </aside> <!-- end #aside -->

    </div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php
})();

get_footer();
