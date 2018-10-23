<?php get_header(); ?>

	<?php while( have_posts() ): the_post(); ?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<div class="container">
				<h1><?php the_title(); ?></h1>
				<ul class="post-meta">
					<li><?php the_time( 'F j, Y' ); ?></li>
					<li><?php echo MakespaceFramework::read_time(); ?></li>
					<li><?php echo get_the_author(); ?></li>
				</ul>
				<?php the_content(); ?>
				
				<div class="post-categories">
					<?php
						$cats = wp_get_object_terms(get_the_ID(), 'category');
						$cat_count = count($cats);
						
						foreach($cats as $c){
							$cat_count--;
							
							echo '<a href="' . get_term_link( $c, 'category' ) . '">' . $c->name . '</a>';
							
							if($cat_count > 0){
								echo '|';
							}
						}
					?>
				</div>
				
				<div class="post-social">
					<?php echo do_shortcode('[addtoany]'); ?>
				</div>
			
			</div>
			
			<footer class="single-nav">
				<div class="container">
					<ul>
						<li class="prev">
							<?php if( get_previous_post() ): $prev = get_previous_post(); ?>
								<a href="<?php echo get_permalink( $prev->ID ); ?>"><i class="fas fa-angle-left"></i> Previous Article</a>
							<?php endif; ?>
						</li>
						<li class="next">
							<?php if( get_next_post() ): $next = get_next_post(); ?>
							<a href="<?php echo get_permalink( $next->ID ); ?>">Next Article <i class="fas fa-angle-right"></i></a>
							<?php endif; ?>
						</li>
						<li class="all">
							<a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Back to All</a>
						</li>
					</ul>
				</div>
			</footer>
		</article>
	<?php endwhile; ?>

<?php get_footer();