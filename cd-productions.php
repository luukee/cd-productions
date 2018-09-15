<?php
/*
Plugin Name: Comedy Dynamics Productions Post Types
Description: Custom Post Types for "Comedy Dynamics" website.
Author: Luke Carl Hartman
Author URI: http://www.lukecarlhartman.com
*/

function production_post_type()
{
    register_post_type(``
      'production',
    array(
      'labels' => array(
        'name' => __('Productions'),
        'singular_name' => __('Production')
      ),
      'public' => true,
      'has_archive' => true,
      'taxonomies' => array('post_tag', 'post_cat'),
    )
  );
}
add_action('init', 'production_post_type');


// http://blog.teamtreehouse.com/create-your-first-wordpress-custom-post-type
// add_action('init', 'cd_productions_cpt');
//
// function cd_productions_cpt()
// {
//     $labels = array(
//         'name' => _x('Productions', 'post type general name'),
//         'singular_name' => _x('Production', 'post type singular name'),
//         'add_new' => _x('Add New', 'production item'),
//         'add_new_item' => __('Add New Production Item'),
//         'edit_item' => __('Edit Production Item'),
//         'new_item' => __('New Production Item'),
//         'view_item' => __('View Production Item'),
//         'search_items' => __('Search Production'),
//         'not_found' =>  __('Nothing found'),
//         'not_found_in_trash' => __('Nothing found in Trash'),
//         'parent_item_colon' => ''
//     );
//
//     $args = array(
//         'taxonomies' => array('post_tag'),
//         'labels' => $labels,
//         'public' => true,
//         'publicly_queryable' => true,
//         'show_ui' => true,
//         'query_var' => true,
//         'rewrite' => true,
//         'capability_type' => 'post',
//         'hierarchical' => false,
//         'menu_position' => null,
//         'supports' => array('title','editor','thumbnail')
//       );

    // create categories
    // register_taxonomy("Categories", array("production"), array("hierarchical" => true, "label" => "Categories", "singular_label" => "Category", "rewrite" => true));

    // add to WP admin
    add_action("admin_init", "admin_init");

    function admin_init()
    {
        add_meta_box("youtube_trailer-meta", "YouTube Trailer", "youtube_trailer", "production", "normal", "low");
        add_meta_box("watch_now_link-meta", "Watch Now Link", "watch_now_link", "production", "normal", "low");
    }

    function youtube_trailer()
    {
        global $post;
        $custom = get_post_custom($post->ID);
        $youtube_trailer = $custom["youtube_trailer"][0]; ?>
      <input name="youtube_trailer" value="<?php echo $youtube_trailer; ?>" />
      <?php
    }

    function watch_now_link()
    {
        global $post;
        $custom = get_post_custom($post->ID);
        $watch_now_link = $custom["watch_now_link"][0]; ?>
      <input name="watch_now_link" value="<?php echo $watch_now_link; ?>" />
      <?php
    }

    // register_post_type('production', $args);
// }



// save the data
add_action('save_post', 'save_details');

function save_details()
{
    global $post;

    update_post_meta($post->ID, "youtube_trailer", $_POST["youtube_trailer"]);
    update_post_meta($post->ID, "watch_now_link", $_POST["watch_now_link"]);
}

// customize the productions feed list columns
add_action("manage_posts_custom_column", "production_custom_columns");
add_filter("manage_edit-production_columns", "production_edit_columns");

function production_edit_columns($columns)
{
    $columns = array(
    "cb" => "<input type='checkbox' />",
    "title" => "Production Title",
    "categories" => "Categories",
    "description" => "Description",
  );

    return $columns;
}
function production_custom_columns($column)
{
    global $post;

    switch ($column) {
    case "description":
      the_excerpt();
      break;
    case "categories":
      echo get_the_term_list($post->ID, 'categories', '', ', ', '');
      break;
  }
}
