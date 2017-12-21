<?php declare(strict_types=1); ?>
<?php get_header(); ?>

<?php
dt_print_breadcrumbs( null, __( "People Groups" ) );

(function() {
    global $post;
?>

<div id="content">

    <div id="inner-content" class="grid-x grid-margin-x">

        <div class="large-3 medium-12 small-12 cell ">

            <section id="" class="medium-12 cell">

                <div class="bordered-box">

                </div>

            </section>

        </div>

        <div id="main" class="large-6 small-12 cell" role="main">

            <?php
            $args = array(
                'post_type' => 'peoplegroups',

            );
            $query1 = new WP_Query( $args );
            ?>
            <?php if ( $query1->have_posts() ) : while ( $query1->have_posts() ) : $query1->the_post(); ?>

                <!-- To see additional archive styles, visit the /parts directory -->
                <?php get_template_part( 'parts/loop', 'peoplegroups' ); ?>

            <?php endwhile; ?>

                <?php disciple_tools_page_navi(); ?>

            <?php else : ?>

                <section class="bordered-box">

                    <h3>No People Groups found in the system.</h3>

                </section>

            <?php endif; ?>

        </div> <!-- end #main -->

        <div class="large-3 small-12 cell">

            <section class="bordered-box">

                <?php include 'searchform.php'; ?>

            </section>

            <section class="bordered-box">

                <h4><?php esc_html_e( 'Recent People Groups', 'disciple_tools' )?></h4>

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
                    'post_type' => 'peoplegroups',
                    'post_status' => 'draft, publish, future, pending, private',
                    'suppress_filters' => true
                );

                $recent_posts = wp_get_recent_posts( $args, ARRAY_A );

                echo '<ul>';
                ?>
                <?php foreach ($recent_posts as $recent_post): ?>
                    <li><a href="<?php echo esc_url( $recent_post['guid'] ) ?>"><?php esc_html_e( $recent_post['post_title'], 'disciple_tools' )?></a></li>
                <?php endforeach; ?>
                <?php
                echo '</ul>';

                //                    print_r($recent_posts);?>

            </section>


        </div> <!-- end #aside -->

    </div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php

})();

get_footer();
