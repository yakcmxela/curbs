<?php get_header(); ?>

<div class="container narrow">
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		<h1><?php the_title(); ?></h1>
		<?php if( get_field('product_code')): ?>
			<h3 class="product-code"><?php the_field('product_code'); ?></h3>
		<?php endif; ?>
		<?php the_content(); ?>
		<?php if(get_field('product_pdf')): ?>
			<div class="download">
				<h4><a href="<?php echo get_field('product_pdf')['url']; ?>" target="_blank">Download PDF</a></h4>
			</div>
		<?php endif; ?>
		
		<?php if( get_field('standard_features') || get_field('optional_features') ) : ?>
			<div class="features">
				<?php if( have_rows('standard_features') ) : ?>
					<div class="feature">
						<h3>Standard Features</h3>
						<ul>
							<?php while( have_rows('standard_features') ) : the_row(); ?>
								<li><?php the_sub_field('feature'); ?></li>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if( have_rows('optional_features') ) : ?>
					<div class="feature">
						<h3>Optional Features</h3>
						<ul>
							<?php while( have_rows('optional_features') ) : the_row(); ?>
								<li><?php the_sub_field('feature'); ?></li>
							<?php endwhile; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
				
		<?php if(  has_term( 'vibration-isoloation-products', 'product-categories' )): ?>	
			<?php if(have_rows('pdfs')): ?>
				<h3><?php the_field('downloads_title'); ?></h3>
				<ul class="product-pdfs">
					<?php while( have_rows('pdfs') ) : the_row(); ?>
						<li>
							<a href="<?php echo get_sub_field('pdf_file')['url']; ?>" target="_blank">
								<i class="fas fa-file-pdf"></i>									
								<?php echo get_sub_field('pdf_file')['title']; ?>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>

			<?php endif; ?>

			<?php if(get_field('product_gallery')): ?>
				<h3><?php the_field('gallery_title'); ?></h3>
				<ul class="product-gallery">
					<?php foreach( get_field('product_gallery') as $gallery_img ) : ?>
						<li>
							<a href="<?php echo $gallery_img['url']; ?>" target="_blank" style="background-image: url(<?php echo $gallery_img['sizes']['medium']; ?>)">
								<?php //echo $gallery_img['url']; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>

			<?php endif; ?>

		<?php else:
			echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true"]');
		endif; ?>
	</article>
</div>

<?php get_footer(); ?>