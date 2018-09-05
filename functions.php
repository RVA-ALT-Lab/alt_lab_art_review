<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Initialize theme default settings
 */
require get_template_directory() . '/inc/theme-settings.php';

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom pagination for this theme.
 */
require get_template_directory() . '/inc/pagination.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Comments file.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Load WooCommerce functions.
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * Load Editor functions.
 */
require get_template_directory() . '/inc/editor.php';


/**
 * Load custom post types.
 */
require get_template_directory() . '/inc/custom-post-types.php';



//ADD FONTS and VCU Brand Bar
add_action('wp_enqueue_scripts', 'alt_lab_scripts');
function alt_lab_scripts() {
	$query_args = array(
		'family' => 'IBM+Plex+Sans:100,400,700',
		'subset' => 'latin,latin-ext',
	);
	wp_enqueue_style ( 'google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );

	wp_enqueue_script( 'alt_lab_js', get_template_directory_uri() . '/js/alt-lab.js', array(), '1.1.1', true );
    }

//add footer widget areas
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - far left',
    'id' => 'footer-far-left',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - medium left',
    'id' => 'footer-med-left',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);


if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - medium right',
    'id' => 'footer-med-right',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer - far right',
    'id' => 'footer-far-right',
    'before_widget' => '<div class = "widgetizedArea">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
  )
);

//set a path for IMGS

  if( !defined('THEME_IMG_PATH')){
   define( 'THEME_IMG_PATH', get_stylesheet_directory_uri() . '/imgs/' );
  }

//gravity forms - increment review count as custom field 
add_action( 'gform_after_submission_1', 'add_review_count', 10, 2 );
add_action( 'gform_after_submission_1', 'add_reviewer', 10, 2 );

function add_review_count($entry, $form){
  $art_id = rgar($entry, '6');
  increment_review_count ($art_id);
}

function increment_review_count ($id){
  $count = get_post_meta($id, 'review_count', true);
  if (! $count) {
    $count = 1;
    update_post_meta($id, 'review_count', $count );
  } else {
    $count++;
    update_post_meta($id, 'review_count', $count );
  }
}


//adds reviewer ID to custom field 
function add_reviewer ($entry, $form){
  $art_id = rgar($entry, '6');
  $email = rgar($entry, '7');
  $reviewer = get_user_by( 'email', $email);
  $reviewer_id = $reviewer->ID;
  add_post_meta($art_id, 'reviewer_id', $reviewer_id, false );
}


function get_reviews($id) {
  $search_criteria = array(
    'status'        => 'active',
    'field_filters' => array(
        'mode' => 'any',       
        array(
            'key'   => '6',
            'value' => $id
        )
    )
);

  $sorting         = array();
  $paging          = array( 'offset' => 0, 'page_size' => 25 );
  $total_count     = 0;

  $entries = GFAPI::get_entries(1, $search_criteria, $sorting, $paging, $total_count );
  $html = '';
  $html .= '<h2>Reviews</h2>';
    foreach ($entries as $entry) {
      $html .= '<div class="row">';
      if(current_user_can('editor') || current_user_can('administrator')){
        $email = $entry[1];
        $html .= '<div class="col-4">' . $email . '</div>';
      } 
      $drawing = $entry[8];
      $rendering = $entry[10];
      $design = $entry[9]; 
      $art_id =  $entry[6]; 
    
      $html .= '<div class="col-1">' . $drawing . '</div><div class="col-1">' . $design . '</div><div class="col-1">' . $rendering . '</div></div>';      
    }
    echo $html;
  
}


//Featured image
function art_img_update() {
    global $content_width;

    if ( isset( $content_width ) )
    {
        $content_width = 1300;//this thing is needed but super weird
    }

    if ( function_exists( 'add_image_size' ) ) {
      add_image_size( 'grande', 1255); 
    }
}
add_action( 'after_setup_theme', 'art_img_update', 11 );

 
function art_review_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'very-grande' => __('Grande'),       
    ) );
}