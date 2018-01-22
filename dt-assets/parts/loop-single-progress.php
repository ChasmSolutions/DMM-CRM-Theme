<article id="post-<?php the_ID(); ?>" role="article" >

    <header class="article-header">
        <h4 class="entry-title single-title" itemprop="headline"><?php the_title_attribute(); ?></h4>
    </header> <!-- end article header -->

    <section class="entry-content" itemprop="articleBody">
        <?php the_post_thumbnail( 'full' ); ?>
        <?php the_content(); ?>
    </section> <!-- end article section -->

    <footer class="article-footer">
        <?php wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'disciple_tools' ),
            'after'  => '</div>'
        ) ); ?>
        <p class="tags"><?php the_tags( '<span class="tags-title">' . __( 'Tags:', 'disciple_tools' ) . '</span> ', ', ', '' ); ?></p>
    </footer> <!-- end article footer -->

    <?php comments_template(); ?>

</article> <!-- end article -->
