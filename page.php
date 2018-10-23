<?php get_header(); ?>

	<div class="container">
		<?php while( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php if(get_field('page_form_id')): ?>
					<div class="page-form">
						<?php echo do_shortcode('[gravityform id="' . get_field('page_form_id')  . '" title="false" description="false" ajax="true"]') ?>
					</div>
				<?php endif; ?>
			</article>
		<?php endwhile; ?>
	</div>

<?php get_footer();