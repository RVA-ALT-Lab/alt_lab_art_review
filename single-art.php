<?php
/**
 * The template for displaying all single ART posts.
 *
 * @package understrap
 */

get_header();
$container   = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="single-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<main class="site-main" id="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<div class="col-md-12">
						<?php build_rating_navigation();?>
				    </div>

					<?php get_template_part( 'loop-templates/content', 'art' ); ?>
					
					<?php 
					//$reviewers = get_post_meta($post->ID, 'reviewer_id', false);
					$reviewers = wp_get_post_tags($post->ID,array( 'fields' => 'slugs' ));
					$current_user = wp_get_current_user();
					$user_id = 'reviewer-'.$current_user->ID;
					if (!in_array($user_id ,$reviewers)){
							echo '<div class="col-md-9 reviews"><h2>Review It</h2><div class="review-land">';
							echo do_shortcode('[gravityform id="1" title="false" description="false"]'); 
							echo '</div></div>';
						}
						else {
							build_rating_navigation(); //NEXT NAV	
							//get_reviews_chart($post->ID); //RETURN RATINGS DATA
						}
					;?>
						
						<?php //understrap_post_nav(); ?>

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
