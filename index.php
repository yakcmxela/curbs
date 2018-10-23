<?php get_header(); ?>

	<div class="container">
		<h1><?php echo get_the_title( get_option('page_for_posts') ); ?></h1>
		<?php
			echo get_field('blog_overview', get_option('page_for_posts'));
		/*
		<div class="filter-container">
			<div class="filter-label">Filter By</div>
			<div class="filter-dropdown">
				<div class="filter-display">
					<?php
						if( single_term_title( '', false ) ){
							single_term_title();
						} else {
							echo 'Filter By';
						}
					?>
				</div>
				<ul>
					<li><a href="<?php echo get_permalink( get_option('page_for_posts' ) ); ?>">All</a></li>
					<?php
						$categories = get_categories( array(
							'orderby' => 'name',
							'order'   => 'ASC'
						) );

						foreach( $categories as $category ) {
							$caturl = get_category_link( $category->term_id );
							$catname = $category->name;

							echo '<li><a href="' . $caturl .'">' . $catname. '</a></li>';
						}
					?>
				</ul>
			</div>
		</div>*/ ?>
	</div>

	<div class="blog-list">
		<?php while( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<div class="container">
					<?php
						$thumb = '';
						if( get_the_post_thumbnail_url() ){
							$thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						}
						else{
							$thumb = get_field('default_banner_image','option')['sizes']['large'];
						}
					?>
					<figure>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="img" style="background-image: url(<?php echo $thumb; ?>);"></a>
					</figure>
					<div class="hentry-content">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						<ul class="post-meta">
							<li><?php the_time( 'm-d-y' ); ?></li>
							<li><?php echo MakespaceFramework::read_time(); ?></li>
							<li><?php echo get_the_author(); ?></li>
						</ul>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="button">Read This</a>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</div>

	<div class="container archive-nav">
		<?php
			echo paginate_links( array(
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'type' => 'list'
			) );
		?>
	</div>

<?php get_footer();