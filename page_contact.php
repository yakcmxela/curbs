<?php
/*
 * Template Name: Contact
 */
get_header(); ?>

	<?php while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="container">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
				
				<div class="contact-columns">
					<section class="contact-details">
						<?php
							$contact_info = get_field('contact_information', 'option')[0];
						?>
						<p class="phone">
							<i class="icon fas fa-phone"></i>
							<a href="tel:<?php echo $contact_info['phone_number']; ?>"><?php echo $contact_info['phone_number']; ?></a>
						</p>
						<p class="address">
							<i class="icon fas fa-location-arrow"></i>
							<?php echo $contact_info['address']; ?>
							<span>
								<a href="<?php the_field('directions_link'); ?>" target="_blank">Get Directions</a>
							</span>
						</p>
						<p class="fax">
							<i class="icon fas fa-fax"></i>
							<?php echo $contact_info['fax_number']; ?>
						</p>
						<p class="hours">
							<i class=" icon far fa-clock"></i>
							<?php the_field('hours'); ?>
						</p>
					</section>
					<section class="contact-form">
						<?php echo do_shortcode( get_field('form') ); ?>
					</section>
				</div>
			</div>
			<div class="contact-map">
				<div id="gmap"></div>
			</div>
		</article>
	<?php endwhile; ?>

<?php get_footer();