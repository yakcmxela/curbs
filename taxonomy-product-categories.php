<?php 
	get_header();
	$queried_object = get_queried_object();
	$default_image_url = get_field('placeholder_image', 'option')['url'];
	$default_image = '<img class="placeholder" src="' . $default_image_url . '" alt="' . get_bloginfo('title') . '" />'; 
?>

<div class="container">
	<div class="intro">
		<h1><?php echo $queried_object->name; ?></h1>
		<?php echo $queried_object->description; ?>
	</div>
	<section id="products" class="products-list columns-3">
		<?php while( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<a href="<?php the_permalink(); ?>">
					<div class="product-image">
						<?php 
							if( get_the_post_thumbnail_url() ){
								$img_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
							}
							else{
								$img_url = $default_image_url;
							}
						?>
						<div class="img" style="background-image: url(<?php echo $img_url; ?>);"></div>
					</div>
					<h3><?php the_title(); ?></h3>
					<p class="button" href="<?php the_permalink(); ?>">Learn More</p>
				</a>
			</article>
		<?php endwhile; ?>
	</section>
	<div class="pagination">
	<?php
		global $wp_query;

		$big = 999999999; // need an unlikely integer
		$translated = __( 'Page', 'custom curbs' ); // Supply translatable string

		echo paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'before_page_number' => '<span class="screen-reader-only">' . $translated . ' </span>',
			'next_text' => __('Next &rarr;')
		) );
	?>
	</div>
</div>

<?php get_footer(); ?>