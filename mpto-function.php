<?php

function mpto_admin_notice__success() {
    if (!isset($_SESSION)) {
        @session_start();
    }
    if (isset($_SESSION['notices']) && !empty($_SESSION['notices']) && ($_SESSION['notices'] != '')) {
        if ($_SESSION['notices']['type'] == 'success') {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e($_SESSION['notices']['msg'], 'sample-text-domain'); ?></p>
            </div>
            <?php
            $_SESSION['notices']['type'] = '';
        }
    }
}
add_action('admin_notices', 'mpto_admin_notice__success');

function mpto_my_error_notice() {
    if (!isset($_SESSION)) {
        @session_start();
    }
    if (isset($_SESSION['notices']) && !empty($_SESSION['notices']) && ($_SESSION['notices'] != '')) {
        if ($_SESSION['notices']['type'] == 'error') {
            ?>
            <div class="error notice">
                <p><?php _e($_SESSION['notices']['msg'], 'my_plugin_textdomain'); ?></p>
            </div>
            <?php
            $_SESSION['notices']['type'] = '';
        }
    }
}
add_action('admin_notices', 'mpto_my_error_notice');

function mpto_shortcode_dis_post( $atts ) {
	$post_type = $atts['post_type'];
        if($post_type == '')
        {
            $post_type = 'post';
        }
        $posts_per_page =  $atts['posts_per_page'];
        if($posts_per_page == '')
        {
            $posts_per_page = -1;
        }
        $meta_key = $atts['meta_key'];
        if($meta_key == '')
        {
            $meta_key = 'custom_order_type_snv_1';
        }
        $more = $atts['readmore'];
        if($more == '')
        {
            $more = 'Readmore';
        }
        $limit = $atts['limit'];
        if($limit == '')
        {
            $limit = 250;
        }
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
         $wp_query = new WP_Query( 
                        array(  'post_type' => $post_type, 
                                'post_status' => array( 'publish'),
                                'posts_per_page' => $posts_per_page, 
                                'paged'	=> $paged,
                                'orderby' => 'meta_value_num', 
                                'meta_key' => $meta_key, 
                                'order' => 'ASC',   
                     ) ); ?>
        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
        <div class="mpto-post-listing">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <a href="<?php the_permalink(); ?>" title="" class="title-link"><?php the_title('<h2 class="entry-title">', '</h2>'); ?></a>
                    <?php if($post_type == 'post'){?>
                    <div class="blog-detail"> <?php //echo get_the_excerpt(); ?>
                        <span class="entry-date"><i class="fa fa-calendar"></i><?php echo get_the_date(); ?></span>
                        <span class="entry-author"><i class="fa fa-user"></i>By <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a></span>
                        <span class="entry-comment"><i class="fa  fa-comments"></i><?php comments_number('0 comment', '1 comment', '% comments'); ?></span>
                        <?php if(has_tag()) { ?><span class="entry-tag"><i class="fa fa-tag"></i><?php the_tags(''); ?></span><?php } ?>
                    </div>
                    <?php }?>
                </header>
                <!-- .entry-header -->
                <div class="mpto-row">
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="blog-image"> <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"/>
                        </div>
                    <?php }  ?>
                    <div class=" mpto-content">
                        <div class="entry-content">
                                <?php echo get_excerpt($limit,null,$more); ?>
                            <!-- entry-content -->
                        </div>
                    </div>
                </div>

            </article><!-- #post-## -->
        <?php endwhile;?>
            <?php 
        $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $wp_query->max_num_pages
        ) );?>
        </div>
        <?php wp_reset_query(); 
}
add_shortcode( 'mpto', 'mpto_shortcode_dis_post' );

// limit in content word
function get_excerpt($limit, $source = null,$more = 'more'){

    if($source == "content" ? ($excerpt = get_the_content()) : ($excerpt = get_the_excerpt()));
    if($excerpt == ''){return '';}
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
    $excerpt = $excerpt.' <a href="'.get_permalink($post->ID).'">'.$more.'</a>...';
    return $excerpt;
}

?>