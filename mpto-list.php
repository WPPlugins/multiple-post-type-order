<?php

function mpto_list() {
    $post_type = 'post';
    if (isset($_GET['post_type'])) {
        $post_type = sanitize_text_field($_GET['post_type']);
    }
    $no_order = '1';
    if (isset($_GET['page'])) {
        $page_data = explode('-', sanitize_text_field($_GET['page']));
        if (end($page_data) != '') {
            $no_order = end($page_data);
        }
    }

    //return $category_tree_array;

    function mpto_categoryParentChildTree($parent = 0, $spacing = '', $category_tree_array = '') {
        $post_type = 'post';
        if (isset($_GET['post_type'])) {
            //$post_type = sanitize_text_field($_GET['post_type']);
			$post_type  =   filter_var ( $_GET['post_type'], FILTER_SANITIZE_STRING);
        }
        $no_order = '1';
        if (isset($_GET['page'])) {
            $page_data = explode('-', filter_var ( $_GET['page'], FILTER_SANITIZE_STRING));
            if (end($page_data) != '') {
                $no_order = end($page_data);
            }
        }
        if (!is_array($category_tree_array))
            $category_tree_array = array();

        global $wpdb;
        $querystr = "SELECT   " . $wpdb->prefix . "posts.* FROM " . $wpdb->prefix . "posts  INNER JOIN " . $wpdb->prefix . "postmeta ON ( " . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "postmeta.post_id ) WHERE 1=1 AND " . $wpdb->prefix . "posts.post_parent='" . $parent . "' AND ( 
                  " . $wpdb->prefix . "postmeta.meta_key = 'custom_order_type_snv_" . $no_order . "'
                ) AND " . $wpdb->prefix . "posts.post_type = '" . $post_type . "' AND (" . $wpdb->prefix . "posts.post_status = 'publish' OR " . $wpdb->prefix . "posts.post_status = 'future' OR " . $wpdb->prefix . "posts.post_status = 'draft' OR " . $wpdb->prefix . "posts.post_status = 'pending' OR " . $wpdb->prefix . "posts.post_status = 'private') GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "postmeta.meta_value+0 ASC ";
        //echo $querystr;exit; 
        $pageposts = $wpdb->get_results($querystr, OBJECT);
        if ($pageposts == '' || Empty($pageposts)) {
            $querystr = "SELECT   " . $wpdb->prefix . "posts.* FROM " . $wpdb->prefix . "posts  INNER JOIN " . $wpdb->prefix . "postmeta ON ( " . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "postmeta.post_id ) WHERE 1=1 AND " . $wpdb->prefix . "posts.post_parent='" . $parent . "' AND " . $wpdb->prefix . "posts.post_type = '" . $post_type . "' AND (" . $wpdb->prefix . "posts.post_status = 'publish' OR " . $wpdb->prefix . "posts.post_status = 'future' OR " . $wpdb->prefix . "posts.post_status = 'draft' OR " . $wpdb->prefix . "posts.post_status = 'pending' OR " . $wpdb->prefix . "posts.post_status = 'private') GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.post_name ASC ";
            $pageposts = $wpdb->get_results($querystr, OBJECT);
        }
        if (!empty($pageposts)) {
            foreach ($pageposts as $rowCategories) {
                $category_tree_array[] = array("id" => $rowCategories->ID, "name" => $spacing . $rowCategories->post_title);
                $category_tree_array = mpto_categoryParentChildTree($rowCategories->ID, '&nbsp;&nbsp;&nbsp;&nbsp;' . $spacing . '&#8212;&nbsp;', $category_tree_array);
            }
        }
        return $category_tree_array;
    }

    $pageposts = mpto_categoryParentChildTree();
    ?>
    <div class="mpto_snv">
        <table class="widefat">
            <tr>
                <td>
                    <h2><?php
    global $wp_post_types;
    $obj = $wp_post_types[$post_type];
    echo ucwords($obj->labels->singular_name);
    ?> <?php  _e('Re Order', 'multiple-post-type-order');?>( <?php echo $no_order; ?> )</h2>      
                </td>
                <td>
                    <button onclick="order_save();" class="button button-primary button-large" type="submit" ><?php  _e('Save Order', 'multiple-post-type-order');?></button>
                    <button id="display_query_code" class="button button-primary button-large" type="button"><?php  _e('Display Query Code', 'multiple-post-type-order');?></button>
                    <button onclick="fn_reset_order();" class="button button-primary button-large" type="button" name="Reset"><?php  _e('Reset Order', 'multiple-post-type-order');?></button>
                </td>
            </tr>
            <tr class="querycode" style="display: none;">
                <td>
                    <h2><?php  _e('Query parameter', 'multiple-post-type-order');?></h2>      
                </td>
                <td>
                    <span class="shortcode"><input readonly="readonly" onfocus="this.select();" type="text" value="'orderby' => 'meta_value_num', 'meta_key' => 'custom_order_type_snv_<?php echo $no_order; ?>'" ></span>
                </td>
            </tr>
            <tr class="querycode" style="display: none;">
                <td>
                    <h2><?php  _e('Shortcode', 'multiple-post-type-order');?></h2>      
                </td>
                <td>
                    <span class="shortcode"><input readonly="readonly" onfocus="this.select();" type="text" value="[mpto post_type='<?php echo $post_type; ?>' posts_per_page='10' meta_key='custom_order_type_snv_<?php echo $no_order; ?>' limit='250' readmore='Readmore']" ></span>
                </td>
            </tr>
            <tr class="querycode" style="display: none;">
                <td>
                    <h2><?php  _e('Query Code', 'multiple-post-type-order');?></h2>      
                </td>
                <td>
                    <span class="shortcode">
                        <textarea rows="12" onfocus="this.select();" >&lt;?php $data = new WP_Query( 
                        array(  'post_type' => '<?php echo $post_type; ?>', 
                                'post_status' => array( 'publish'),
                                'posts_per_page' => -1, 
                                'orderby' => 'meta_value_num', 
                                'meta_key' => 'custom_order_type_snv_<?php echo $no_order; ?>', 
                                'order' => 'ASC',   
                     ) ); ?&gt;
    &lt;?php while ( $data->have_posts() ) : $data->the_post(); ?&gt;
    &lt;?php the_title(); ?&gt;
    &lt;?php endwhile;?>
    &lt;?php wp_reset_query(); ?&gt;</textarea></span>
                </td>
            </tr>
        </table>
        <ul id="sortable">
    <?php $i = 0; ?>
            <?php foreach ($pageposts as $value) { ?>
                <?php $i++; ?>
                <li class="row-title" id='item-<?php echo $value['id'] ?>'><span class="box"><?php echo $i; ?></span><?php echo $value['name']; ?></li>
            <?php } ?>
        </ul>
    </div>

    <script>
        jQuery('#display_query_code').click(function () {
            jQuery('.mpto_snv .querycode').toggle('slow');
            if (jQuery('.mpto_snv .querycode').attr('style') == 'display: none;')
            {
                jQuery('#display_query_code').html('Display Query Code');
            }
            else
            {
                jQuery('#display_query_code').html('Hide Query Code');
            }
        });
       jQuery(document).ready(function() {
        jQuery('#sortable').sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = jQuery(this).sortable('serialize');
                var no_order = '<?php echo $no_order; ?>';
                // POST to server using $.post or $.ajax
                var queryString = {"action": "mpto_list_update", 'data': data, 'no_order': no_order};
                //send the data through ajax
                jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: queryString,
                    cache: false,
                    dataType: "html",
                    success: function (data) {

                    },
                    error: function (html) {

                    }
                });
            }
        });
        
            var data = jQuery('#sortable').sortable('serialize');
            var no_order = '<?php echo $no_order; ?>';
            // POST to server using $.post or $.ajax
            var queryString = {"action": "mpto_list_update", 'data': data, 'no_order': no_order};
            //send the data through ajax
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: queryString,
                cache: false,
                dataType: "html",
                success: function (data) {

                },
                error: function (html) {

                }
            });

        });
        function fn_reset_order()
        {
    <?php $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>
            var no_order = '<?php echo $no_order; ?>';
            var post_type = '<?php echo $post_type; ?>';
            var queryString = {"action": "mpto_list_reset_order", 'no_order': no_order, 'post_type': post_type};
            //send the data through ajax
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: queryString,
                cache: false,
                dataType: "html",
                success: function () {
                    window.location.href = "<?php echo $url; ?>";
                },
                error: function (html) {

                }
            });
        }
        function order_save()
        {
    <?php $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>
            window.location.href = "<?php echo $url; ?>";
        }
    </script>
    <?php
}

/*  MPTO update list */
add_action('wp_ajax_mpto_list_update', 'mpto_list_update');
add_action('wp_ajax_nopriv_mpto_list_update', 'mpto_list_update');
function mpto_list_update() {
    $data = str_replace('&', '', sanitize_text_field($_POST['data']));
    $data = explode('item[]=', $data);
    $no_order = intval($_POST['no_order']);
    // key = order number and value =  post id
    foreach ($data as $key => $value) {
        update_post_meta($value, 'custom_order_type_snv_' . $no_order, $key);
    }
}

/*  MPTO reset order */
add_action('wp_ajax_mpto_list_reset_order', 'mpto_list_reset_order');
add_action('wp_ajax_nopriv_mpto_list_reset_order', 'mpto_list_reset_order');
function mpto_list_reset_order() {
    global $wpdb;
    if (isset($_POST['post_type']) && (sanitize_text_field($_POST['post_type'])) != '') {
        $post_type = sanitize_text_field($_POST['post_type']);
    }
    $querystr = "SELECT   " . $wpdb->prefix . "posts.ID FROM " . $wpdb->prefix . "posts  INNER JOIN " . $wpdb->prefix . "postmeta ON ( " . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "postmeta.post_id ) WHERE 1=1 AND " . $wpdb->prefix . "posts.post_type = '" . $post_type . "' AND (" . $wpdb->prefix . "posts.post_status = 'publish' OR " . $wpdb->prefix . "posts.post_status = 'future' OR " . $wpdb->prefix . "posts.post_status = 'draft' OR " . $wpdb->prefix . "posts.post_status = 'pending' OR " . $wpdb->prefix . "posts.post_status = 'private') GROUP BY " . $wpdb->prefix . "posts.ID ORDER BY " . $wpdb->prefix . "posts.post_name ASC ";
    $pageposts = $wpdb->get_results($querystr, OBJECT);
    $no_order = 1;
    if (isset($_POST['no_order']) && (intval($_POST['no_order']) != '')) {
        $no_order = intval($_POST['no_order']);
    }
    if (!isset($_SESSION)) {
        @session_start();
    }
    if (!empty($pageposts)) {
        foreach ($pageposts as $key => $rowCategories) {
            update_post_meta($rowCategories->ID, 'custom_order_type_snv_' . $no_order, $key);
        }
        $_SESSION['notices'] = array('type' => 'success', 'msg' => 'Order reset successfully.');
    } else {
        $_SESSION['notices'] = array('type' => 'error', 'msg' => 'Order reset data not found.');
    }
}
?>