=== Multiple Post Type Order ===
Contributors: e2msolutions,satish98
Donate link: 
Tags: multiple post types order, multiple custom post types, multiple custom post types ordering, posts order, sort, mutliple post sort, posts sort, post type order, custom order, admin posts order
Requires at least: 3.3
Tested up to: 4.7.2
Stable tag: 4.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Multiple Post Type Order plugin will generate multiple re-ordering interface for your same post types as well as individual custom post types as many times as you want.

== Description ==

This plugin helps to generate multiple re-ordering interface for your post types as well as custom post types as many times as you required for your custom development. With such a custom ordering facility, it will be much faster and easier for the crawlers to generate multiple ordering for same post types as well as individual post types.

This plugin development aims to get solutions when you want to display **custom ordering for one post-type i.e. HomePage** AND **custom ordering for same post-type i.e. on Custom Post Page** with the help of query code in your custom templates.

In order to configure settings, navigate to **[ Wordpress Admin -> Settings -> Multiple Post Types Order ]**, where you can see two options:

1. Show/Hide Re-Ordering Interface for Post Types
2. No of times Re-Ordering required

After configuring your settings, "MPT Order #" will be displayed on your desired post types and under it, you can drag and drop Re-Order by your Post Titles, hit 'Save' and click on **Display Query Code**. You can use this Query code in your custom templates and customize look-n-feel and your desired result will be displayed.

= Example Display Query Code: =
`&lt;?php $data = new WP_Query( 
                    array(  'post_type' => 'post', 
                            'post_status' => array( 'publish'),
                            'posts_per_page' => -1, 
                            'orderby' => 'meta_value_num', 
                            'meta_key' => 'custom_order_type_snv_1', 
                            'order' => 'ASC',   
					)); ?>
<?php while ( $data->have_posts() ) : $data->the_post(); ?>
<?php the_title(); ?>
<?php endwhile;?>
<?php wp_reset_query(); ?&gt;`

= Plugin Advantages =

1. Any Post Types multiple times Re-Order
2. Supports Hierarchical Post Types Re-Order for Both - Parent & Child Posts
3. Supports individual child posts re-ordering from any parent post

= NOTE =

If desired results are not displaying on front-end after setting up re-ordering in admin, please click "Reset Order" once and set re-ordering again. This will solve your issue.


== Installation ==

1. Upload `multiple-post-types-order` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin from Admin > Plugins menu.
3. Once activated you should check with Settings > Multiple Post Types Order
4. Use MPT Order # link which appear into each post types section to re-order.

== Frequently Asked Questions ==

= Where can I find the settings configuration? =

It is under Settings > Multiple Post Types Order.

= How to apply the custom re-ordering on queries using only parameter =

Include a 'orderby' => 'meta_value_num', 'meta_key' => 'custom_order_type_snv_#' parameter within your custom query.

= When and how to use MPTO shortcode? =

You can use below mentioned shortcode in order to display any pages or posts or CPT's listings with Title, Featured Image, Content with limit and Pagination.

[mpto post_type='page' posts_per_page='10' meta_key='custom_order_type_snv_1' limit='250' readmore='Read More']

This shortcode will also available when saving on MTPO Order options.

You can change desired values for posts_per_page, character limit and custom label for readmore button.

= How to apply the custom re-ordering on queries using query code =

Check query code example below:
`&lt;?php $data = new WP_Query( 
                    array(  'post_type' => 'post', 
                            'post_status' => array( 'publish'),
                            'posts_per_page' => -1, 
                            'orderby' => 'meta_value_num', 
                            'meta_key' => 'custom_order_type_snv_1', 
                            'order' => 'ASC',   
					)); ?>
<?php while ( $data->have_posts() ) : $data->the_post(); ?>
<?php the_title(); ?>
<?php endwhile;?>
<?php wp_reset_query(); ?&gt;`

= Can I also re-order multiple child posts from within the parent post? =

Yes. When re-ordering any parent post will automatically re-orders its child posts with them after clicking "Save Order" button. Apart from this, you can also re-order child posts within in the same parent post.

= My desired result is not displaying after re-ordering in Admin. =

If desired results are not displaying on front-end after setting up re-ordering in admin, please click "Reset Order" once and set re-ordering again. This will solve your issue.

= What if some of my old posts/pages/CPT's are not displaying in MPTO Order options? =

If previously or older posts/pages/cpts are not displaying then simply save/update it again.


== Changelog ==

= 1.0 =
 - Initial Release
  = 1.1 =
	- Display Reordering.
  = 1.2 =
	- Display any pages or posts or CPT's listings with Title, Featured Image, Content with limit and Pagination.  
== Upgrade Notice ==

Make sure you get the latest version.

== Screenshots ==

1. Multiple Post Type Order General Settings
2. Drag and Drop Re-Ordering interface
3. Re-Ordering interface with Display Query Code

