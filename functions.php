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
		'family' => 'IBM+Plex+Sans:100,400,700|Oswald+Light:100,400',
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
add_action( 'gform_after_submission_1', 'increment_reviewers', 10, 2 );
add_action( 'gform_after_submission_1', 'review_next', 10, 3);

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

function increment_reviewers ($entry, $form){
  $art_id = rgar($entry, '6');
  $email = rgar($entry, '7');
  $reviewer = get_user_by( 'email', $email);
  $reviewer_id = $reviewer->ID;

  if (! $reviewer) {
    $reviewer = $reviewer_id;
    wp_set_post_tags( $art_id, 'reviewer-'. $reviewer_id, true ); 
  } else {
    wp_set_post_tags( $art_id, 'reviewer-'. $reviewer_id, true ); 
  }
}

function review_next($entry, $form){
  $art_id = rgar($entry, '6');
  $email = rgar($entry, '7');
  $reviewer = get_user_by( 'email', $email);
  $reviewer_id = $reviewer->ID;
  $current_user_tag = 'reviewer-' . $reviewer_id;
  $current_user_tag_id = get_term_by('slug', $current_user_tag, 'post_tag');
  $args = array(
    'post_type' => 'art',
    'post_status' => 'publish',
    'tag__not_in' => array($current_user_tag_id->term_id),
    'post__not_in' => array($art_id),
    'posts_per_page' => 1,
    'order'      => 'DESC',
    'meta_query' => array(
      array(
        'key'     => 'review_count',
      ),
    ),
  );
  $url = get_site_url();
  $the_query = new WP_Query( $args );
  if ( $the_query->have_posts() ) :
    while ( $the_query->have_posts() ) : $the_query->the_post();
      $url = get_the_permalink();
    endwhile;
  endif;
  header( "Location: $url" );
}


//adds reviewer ID to custom field 
function add_reviewer ($entry, $form){
  $art_id = rgar($entry, '6');
  $email = rgar($entry, '7');
  $reviewer = get_user_by( 'email', $email);
  $reviewer_id = $reviewer->ID;
  update_post_meta($art_id, 'reviewer_id', $reviewer_id, false );
}



//OLD WAY TO DO THIS WITH NUMBERS
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
  $paging          = array( 'offset' => 0, 'page_size' => 35 );
  $total_count     = 0;

  $entries = GFAPI::get_entries(1, $search_criteria, $sorting, $paging, $total_count );
  //var_dump($entries);
  $html = '';
  $html .= '<h2>Reviews</h2>';
      $html .= '<div class="review-table"><div class="row"><div class="col-4 reviewer-label"></div><div class="col-1 stat-label">drawing</div><div class="col-1 stat-label">design</div><div class="col-1 stat-label">rendering</div></div>';
    foreach ($entries as $entry) {
      $html .= '<div class="row review-row">';
      if(current_user_can('editor') || current_user_can('administrator')){
        $email = $entry[1];       
      } else {
        $email = $entry["date_created"];
      }
      $drawing = $entry[8];
      $rendering = $entry[10];
      $design = $entry[9]; 
      $art_id =  $entry[6]; 
    
      $html .= '<div class="col-4">' . $email . '</div><div class="col-1 drawing-data">' . $drawing . '</div><div class="col-1 design-data">' . $design . '</div><div class="col-1 rendering-data">' . $rendering . '</div></div>';      
    }
    $html .= '<div class="row average"><div class="col-4">Average</div><div class="col-1" id="drawing-avg"></div><div class="col-1" id="design-avg"></div><div class="col-1" id="rendering-avg"></div></div></div>';
    echo $html;
  
}

//BUILD CHART VIEW

function get_reviews_chart($id) {
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
  //var_dump($entries);
  $html = '';
  $html .= '<h2>Reviews</h2>';
      $html .= '<div class="review-table"><div class="row"><div class="col-3 reviewer-label"></div><div class="col-3 stat-label">drawing</div><div class="col-3 stat-label">design</div><div class="col-3 stat-label">rendering</div></div>';
    foreach ($entries as $entry) {
      $html .= '<div class="row review-row">';
      if(current_user_can('editor') || current_user_can('administrator')){
        $email = $entry[1];       
      } else {
        $email = $entry["date_created"];
      }
      $drawing = $entry[8];
      $rendering = $entry[10];
      $design = $entry[9]; 
      $art_id =  $entry[6]; 
    
      $html .= '<div class="col-3">' . $email  . '</div><div class="col-3 drawing-data">' . buildBoxes($drawing) . '</div><div class="col-3 design-data">' . buildBoxes($design) . '</div><div class="col-3 rendering-data">' . buildBoxes($rendering) . '</div></div>';      
    }
    $html .= '<div class="row average"><div class="col-3">Average</div><div class="col-3" id="drawing-avg"></div><div class="col-3" id="design-avg"></div><div class="col-3" id="rendering-avg"></div></div></div>';
    echo $html;
  
}

function buildBoxes($number){
  if($number == 1){
    return '<div class="art-data-box full"></div><div class="art-data-box"></div><div class="art-data-box"></div><div class="art-data-box"></div>';
  }
  if ($number == 2){
    return '<div class="art-data-box full"></div><div class="art-data-box full"></div><div class="art-data-box"></div><div class="art-data-box"></div>';
  }
  if ($number == 3){
    return '<div class="art-data-box full"></div><div class="art-data-box full"></div><div class="art-data-box full"></div><div class="art-data-box"></div>';
  }
  if ($number == 4){
    return '<div class="art-data-box full"></div><div class="art-data-box full"></div><div class="art-data-box full"></div><div class="art-data-box full"></div>';
  }
}



//Featured image
function art_img_update() {
    global $content_width;

    if ( isset( $content_width ) )  
    {
        $content_width = 1300;//this thing is needed but super weird
    }

    if ( function_exists( 'add_image_size' ) ) {
      add_image_size( 'grande', 1140); 
    }
}
add_action( 'after_setup_theme', 'art_img_update', 11 );

 
function art_review_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'very-grande' => __('Grande'),       
    ) );
}



function build_rating_navigation(){
  global $post;
  $current_user = wp_get_current_user();
  $current_user_tag = 'reviewer-'.$current_user->ID;
  $current_user_tag_id = get_term_by('slug', $current_user_tag, 'post_tag');
  $avoid = $current_user_tag_id->term_id;
  $karma_args = array(
    'post_type' => 'art', //only art
    'post_status' => 'publish', //only published
    'tag__not_in' => array($avoid), //opt out of posts user has reviewed via tag
    'post__not_in' => array($post->ID), //opt out of the post you are currently on
    'posts_per_page' => 1,
    'author__not_in' => $current_user->ID, //opt out of posts submitted by user
    'order'      => 'DESC',
    'meta_query' => array(
      array(
        'key'     => 'review_count', //sort by review count
      ),
    ),
  );

  $karma_query = new WP_Query( $karma_args );
  if ( $karma_query->have_posts() ) :
    while ( $karma_query->have_posts() ) : $karma_query->the_post();
      // Do Stuff
     $posts_remain =  $karma_query->found_posts;
     $all_posts = karma_progress();
     $posts_complete = ($all_posts - $posts_remain);
     if ($posts_remain > 0){
       echo '<div class="karma-score">KARMA: ' . $posts_complete . '/' . $all_posts . '</div><div class="karma-box">';
        for ($i = 0; $i < $all_posts; $i++){
         if ($posts_complete > $i ){
          $complete = 'complete';
         } else {
          $complete = '';
         }
         echo  '<div class="karma-unit ' . $complete . '">&nbsp;</div>';
        }
      echo '</div>';
      echo '<div class="karma-nav"><a href="' . get_the_permalink() . '">Review Art//Earn Karma</a></div>';

      } else {
        echo '<div class="karma-score">reviews completed</div>';
      }
     
      endwhile;
    endif;
    
    wp_reset_postdata();

}


function karma_progress(){
  $args = array(
    'post_type' => 'art',
    'post_status' => 'publish',
    'posts_per_page' => 1,   
  );

  $all_query = new WP_Query( $args );
  if ( $all_query->have_posts() ) :
     while ( $all_query->have_posts() ) : $all_query->the_post();
      // Do Stuff
      return $all_query->found_posts;
   
     endwhile;
  endif;

    // Reset Post Data
    wp_reset_postdata();

}


function artist_display(){
  global $post;
  $current_user = wp_get_current_user();
 
  $args = array(
    'post_type' => 'art',
    'post_status' => 'publish',
    'posts_per_page' => -1,   
    'author' => $current_user->ID
  );

  $the_query = new WP_Query( $args );
  if ( $the_query->have_posts() ) :
    while ( $the_query->have_posts() ) : $the_query->the_post();
      // Do Stuff
        echo '<div class="row single-art"><div class="col-md-12"><h2>' . get_the_title() . '</h2></div><div class="col-md-5">';       
        the_post_thumbnail( 'large',['class' => 'img-responsive responsive--full', 'title' => 'Feature image']);
        echo '</div><div class="col-md-7">';
         jason_reviews_chart($post->ID);
         echo '</div></div>';
      endwhile;
    endif;

    // Reset Post Data
    wp_reset_postdata();
}


function jason_reviews_chart($id) {
  $search_criteria = array(
    'status'        => 'active',
    'field_filters' => array(
        'mode' => 'any',       
        // array(
        //     'key'   => '6',
        //     'value' => $id
        // )
    )
);

  $sorting         = array();
  $paging          = array( 'offset' => 0, 'page_size' => 400 );
  $total_count     = 0;
  $all_drawing = [];
  $all_design = [];
  $all_rendering = [];
  $individual_drawing = [];
  $individual_design = [];
  $individual_rendering = [];
  $individual_comments = '';
  $entries = GFAPI::get_entries(1, $search_criteria, $sorting, $paging, $total_count );
    foreach ($entries as $entry) {     
      $all_drawing[] = $entry[8];
      $all_rendering[] = $entry[10];
      $all_design[] = $entry[9]; 
      $art_id =  $entry[6]; 
      if ($art_id == $id){
      $individual_drawing[] = $entry[8];
      $individual_rendering[] = $entry[10];
      $individual_design[] = $entry[9]; 
        if ($entry[5]){
          $individual_comments .= '<div class="class-note">' . $entry[5] . '</div>';
        }
      }
           
    }
    echo bar_chart_maker('design', average_ratings($individual_design), average_ratings($all_design));
    echo bar_chart_maker('drawing', average_ratings($individual_drawing), average_ratings($all_drawing));
    echo bar_chart_maker('rendering', average_ratings($individual_rendering), average_ratings($all_rendering));
    if ($individual_comments){
      echo '<div class="class-notes"><h2>Notes from the class:</h2>' . $individual_comments . '</div>';
    }
      
}


function average_ratings($a){
  if (count($a)>0){
  $average = array_sum($a)/count($a);
  return round($average,1);
  } else {
    return 'no ratings';
  }
}


function bar_chart_maker($title, $avg, $total_avg){
  if($avg > 0){
  $percent = round(((round($avg,1))/4)*100);
  $total_avg = round(((round($total_avg,1))/4)*100);
  $html = '<dl><dt>' . $title . ': ' . $avg . '</dt>';  
  $html .= '<dd class="percentage percentage-' . $percent . ' ' . $title . '"></dd><dd class="percentage total-avg percentage-' . $total_avg .'"></dd></dl>';
  return $html;
  } else {
  }
}


function homepage_karma(){
  if (is_front_page()){
  build_rating_navigation();    
  }
}

add_filter( 'the_content', 'homepage_karma' );

//set the review_count to 0 so it shows in the query and lets us sort 0 to top
function save_art_meta( $post_id, $post, $update ) {

    $post_type = get_post_type($post_id);

    if ($post_type == "art" &&  ! get_post_meta( $post_id, 'review_count')){
      update_post_meta( $post_id, 'review_count', 0 );
    }


}
add_action( 'save_post', 'save_art_meta', 10, 3 );