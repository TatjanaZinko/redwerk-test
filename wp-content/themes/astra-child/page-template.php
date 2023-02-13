<?php
/*
 * Template Name: Publication Template
 *
 * 
 */
get_header();

?>

<div id="primary" class="content-area success-template">
    <main id="main" class="site-main" role="main">
        <div class="container">
        <div class="post-wrapper">

            <?php $publication = new WP_Query( array( 
                'post_type' => 'publication', 
                'posts_per_page' => -1,
                ) );

            if($publication->have_posts()) {
                 ?>

                <ul class="publication-list">

                <?php  while ( $publication->have_posts() ) { 
                $publication->the_post(); 
                $id = get_the_ID();
                $img_url = get_post_meta( $id, 'publication_img', true );; 
                $terms = wp_get_post_terms( $id, 'publication-type' );
                $type_list = '';
                foreach ( $terms as $term ) {
                    $type_list .= $term->name . " ";
                } ?>
                
                    <li class="publication-list-item">
                        <div class="publication-img">
                            <img src="<?php echo $img_url; ?>" alt="">
                        </div>
                        <p class="publication-title"><?php echo the_title(); ?></p>
                        <p class="publication-type"><?php echo $type_list; ?></p>                        
                    </li>

                <?php } ?>

                </ul>

            <?php }

            wp_reset_postdata();
            ?>
                
            </div>
        </div>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer();