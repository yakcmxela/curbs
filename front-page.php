<?php 
/*
 * Template Name: Front Page
 */
?>
<?php get_header(); ?>

<?php 
	if( have_rows('page_layout') ) :
		while( have_rows('page_layout') ) : the_row();
			if( get_row_layout() == 'content_blocks' ) :
				$custom_id = get_sub_field('custom_id');
				$min_height = get_sub_field('minimum_height');
				$logo_svg = file_get_contents( get_bloginfo('stylesheet_directory') . '/assets/curbfinder-logo-white.svg');
				$cols = count( get_sub_field('blocks') );
				?> <section id="<?php echo $custom_id; ?>" class="content-blocks columns-<?php echo $cols; ?>" style="min-height: <?php echo $min_height; ?>vh;"> <?php
				if( have_rows('blocks') ) :
					while( have_rows('blocks') ) : the_row();
						$bg_image_arr = get_sub_field('background_image');
						$background_image = 'background-image: url(' . $bg_image_arr['url'] . ');';
						$sr_text = $bg_image_arr['alt'];
						$color_overlay = get_sub_field('color_overlay');
						$content = get_sub_field('content');
						$content_orientation = get_sub_field('orientation');
						$image_or_svg = get_sub_field('image_or_svg');
						$image_arr = get_sub_field('image');
						$svg = get_sub_field('svg');
						if( $image_or_svg == 'image' ) :
							$image = '<img src="' . $image_arr['url'] . '" alt="' . $image_arr['alt'] . '">';
						elseif( $image_or_svg == 'svg' ) :
							$image = $svg;
						endif;
						$image_placement = get_sub_field('image_placement');
						$link_type = get_sub_field('link_type');
						$link_to = get_sub_field('link_to');
						$link_text = get_sub_field('link_text') ? get_sub_field('link_text') : 'Learn More';
						?>
						<div class="block" style="<?php if( $bg_image_arr ) { echo $background_image; } ?>; min-height: <?php echo $min_height; ?>vh;">
							<?php if( $image_arr) { echo $sr_text; } ?>
							<?php if( $link_type == 'full' ) : ?> 
								<a class="full-link" href="<?php echo $link_to; ?>"> 
							<?php endif; ?>
							<div class="color-overlay" style="background-color: <?php echo $color_overlay; ?>"></div>
							<div class="hover-overlay"></div>
							<div class="content orientation-<?php echo $content_orientation; ?>">
							<?php
								switch( $image_placement ) {
									case 'before':
										if( $image_arr || $svg ) { echo $image; } 
										if( $content ) { echo $content; }
										break;
									case 'after':
										if( $content ) { echo $content; }
										if( $image_arr || $svg ) { echo $image; } 
										break;
									case 'none':
										if( $content ) { echo $content; }
										break;
								}
							?>
							<?php if($link_type == 'button') : ?>
								<a class="button" href="<?php echo $link_to; ?>"><?php echo $link_text; ?></a>
							<?php endif; ?>
							</div>
							<?php if( $link_type == 'full' ) : ?>
								</a>
							<?php endif; ?>
						</div>
						<?php
					endwhile;
				endif;
				?> </section> <?php
				$custom_id = get_sub_field('custom_id');
				$min_height = get_sub_field('minimum_height');
			elseif( get_row_layout() == 'feature_post' ) :
				$custom_id = get_sub_field('custom_id');
				$min_height = get_sub_field('minimum_height');
				$alignment = get_sub_field('alignment');
				if( $alignment == 'left' ) :
					$flex_class = 'row';
					$arrow_class = 'arrow-left';
					$arrow_class_mobile = 'arrow-bottom';
				elseif( $alignment == 'right' ) :
					$flex_class = 'reverse';
					$arrow_class = 'arrow-right';
					$arrow_class_mobile = 'arrow-top';
				endif;
				$featured_post = get_sub_field('featured_post');
				$attachment_id = get_post_thumbnail_id( $featured_post->ID );
				$post_image = get_sub_field('custom_image') ? get_sub_field('custom_image')['url'] : wp_get_attachment_url( $attachment_id );
				$post_alt = get_sub_field('custom_image') ? get_sub_field('custom_image')['alt'] : get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				$post_title = get_sub_field('custom_title') ? get_sub_field('custom_title') : $featured_post->post_title;
				$post_excerpt = get_sub_field('custom_excerpt') ? get_sub_field('custom_excerpt') : wp_trim_words( $featured_post->post_content, 32 );
				$post_link_text = get_sub_field('custom_link_text') ? get_sub_field('custom_link_text') : 'Read This Article';
				$post_date = get_the_date( 'm-d-y', $featured_post->ID );
				$read_time = MakespaceFramework::read_time( $featured_post->ID );
				$post_permalink = get_permalink( $featured_post->ID );
				$blog_url = get_permalink( get_option( 'page_for_posts' ) );
				?>
				<section id="<?php echo $custom_id; ?>" class="featured-post <?php echo $flex_class; ?>" style="min-height: <?php echo $min_height; ?>">
					<div class="post-image">
						<div class="<?php echo $arrow_class_mobile; ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="200" height="30" viewBox="0 0 200 30">
								<defs>
								    <style>
								      .cls-1 {
								        fill: #fff;
								        fill-rule: evenodd;
								      }
								    </style>
								  </defs>
								  <path class="cls-1" d="M200,30L100,0,0,30H200Z"/>
							</svg>
						</div>
						<img src="<?php echo $post_image; ?>" alt="<?php echo $post_alt; ?>">
					</div>
					<div class="<?php echo $arrow_class; ?>">
						<svg viewBox="0 0 30 200" xmlns="http://www.w3.org/2000/svg">
						<defs>
							<style>.arrow {
						        fill: #fff;
						        fill-rule: evenodd;
						      }</style>
						</defs>
						<path class="arrow" d="M30,0L0,100,30,200V0Z"/>
						</svg>
					</div>
					<div class="post-text">
						<h3><?php echo $post_title; ?></h3>
						<span><?php echo $post_date; ?></span>
						<span><?php echo $read_time; ?></span>
						<p><?php echo $post_excerpt; ?></p>
						<div class="links">
							<a class="button" href="<?php echo $post_permalink; ?>"><?php echo $post_link_text; ?></a>
							<a class="button-ghost" href="<?php echo $blog_url; ?>">Read All Articles</a>
						</div>
					</div>
				</section>
				<?php
			endif;
		endwhile;
	endif;
?>

<div class="sticky-tab">
	<a href="<?php the_field('sticky_tab_link'); ?>" class="open-login">
		<img src="<?php echo get_field('sticky_tab_icon')['url']; ?>" alt="" class="sticky-tab-icon">
		<h4 class="sticky-tab-title"><?php the_field('sticky_tab_title'); ?></h4>
		<p class="sticky-tab-subtitle"><?php the_field('sticky_tab_sub_title'); ?></p>
		<span class="button"><?php the_field('sticky_tab_button_text'); ?></span>
	</a>
</div>

<?php get_footer();