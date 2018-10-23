<?php 
/*
 * Template Name: Curbfinder
 */
?>
<?php
	$roles = array('lennox','distributor','contractor','administrator');
	$current_user = wp_get_current_user();
	$user_should_exit = false;
	foreach( $current_user->roles as $role ) {
		if( !in_array($role, $roles) ) {
			$user_should_exit = true;
		}
	}
	if(!is_user_logged_in() || $user_should_exit == true){
		wp_redirect( home_url() );
		exit;
	}

?>
<?php get_header(); ?>
	<div class="container">
		<?php while( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
		<form class="curbfinder">
			<div id="current" class="form-section">
				<div class="step">
					<span>1</span>
				</div>
				<h3 class="step-label">Step 1:</h3>
				<h3 class="step-heading"><?php the_field('step_1_heading_text'); ?></h3>
				<label for="manufacturer-old">Manufacturer</label>
				<input class="typeahead" type="text" name="manufacturer-old" id="manufacturer-old">
				<label for="model-old">Model #</label>
				<input type="text" name="model-old" id="model-old">
				<label for="notes-old">Notes</label>
				<select name="notes-old" id="notes-old"></select>
				<label for="part-number-old">Part #</label>
				<textarea type="text" name="part-number-old" id="part-number-old"></textarea>
			</div>
			<div id="new" class="form-section">
				<div class="step">
					<span>2</span>
				</div>
				<h3 class="step-label">Step 2:</h3>
				<h3 class="step-heading"><?php the_field('step_2_heading_text'); ?></h3>
				<label for="manufacturer-new">Manufacturer</label>
				<input type="text" name="manufacturer-new" id="manufacturer-new">
				<label for="model-new">Model #</label>
				<input type="text" name="model-new" id="model-new">
				<label for="notes-new">Notes</label>
				<select name="notes-new" id="notes-new"></select>
				<label for="part-number-new">Part #</label>
				<textarea type="text" name="part-number-new" id="part-number-new"></textarea>
			</div>
			<div class="submit">
				<button class="button disabled" type="submit" id="search-curbs">Search Curbs <i class="fa fa-search"></i></button>
				<span class="error">Please enter a valid manufacturer/model for both sections.</span>
			</div>
			<div id="verify" class="form-section">
				<div class="step">
					<span>3</span>
				</div>
				<h3 class="step-label">Step 3:</h3>
				<h3 class="step-heading"><?php the_field('step_3_heading_text'); ?></h3>
				<div class="loading-icon"><i class="fas fa-spinner-third"></i></div>
				<p class="fill-in">Fill out the fields above to verify your curb.</p>
				<div class="adapter-info">
				</div>
			</div>
		</form>
	</div>
	<div class="form">
		<div class="container narrow">
			<div class="triangle over">
				<svg xmlns="http://www.w3.org/2000/svg" width="957" height="83" viewBox="0 0 957 83">
					<defs>
					    <style>
					      .cls-1 {
					        fill: #fff;
					        fill-rule: evenodd;
					      }
					    </style>
				  	</defs>
				 	<path class="cls-1" d="M479,63L957,0H0Z"/>
				</svg>
			</div>
			<div class="triangle under">
				<svg xmlns="http://www.w3.org/2000/svg" width="957" height="83" viewBox="0 0 957 83">
					<defs>
					    <style>
					      .cls-2 {
					        fill: #c85424;
					        fill-rule: evenodd;
					      }
					    </style>
					</defs>
					<path class="cls-2" d="M479,83L957,0H0Z"/>
				</svg>
			</div>
			<div class="form-contents">
				<h3><?php the_field('contact_form_heading'); ?></h3>
				<!-- <p><?php the_field('order_number_heading'); ?></p>
				<div class="order-number"></div> -->
				<?php $form_id = intval(get_field('cf_gform')); ?>
				<?php echo do_shortcode('[gravityform id="' . $form_id  . '" title="false" description="false" ajax="true"]') ?>
			</div>
		</div>
	</div>

<?php get_footer(); ?>