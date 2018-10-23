<!DOCTYPE html>
<html lang="en-US" class="no-js">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
		<!--[if lt IE 9]>
			<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body <?php body_class(); ?>>
		<?php if( 'ocn' == get_field( 'menu_type', 'option' ) ): ?>
			<div id="ocn">
				<div id="ocn-inner">
					<div id="ocn-top">
						<a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>" id="ocn-brand">
							<img src="<?php the_field( 'site_logo', 'option' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
						</a>
						<button class="nav-toggle" type="button" id="ocn-close">
							<span></span>
						</button>
					</div>
					<?php
						wp_nav_menu( array(
							'menu' => 'Top Level',
							'container' => 'nav',
							'container_id' => 'ocn-nav-top',
							'before' => '<span class="ocn-link-wrap">',
							'after' => '<span class="ocn-sub-menu-button"></span></span>'
						) );
					?>
					<?php wp_nav_menu( array(
						'container' => 'nav',
						'container_id' => 'ocn-nav-primary',
						'theme_location' => 'primary',
						'before' => '<span class="ocn-link-wrap">',
						'after' => '<span class="ocn-sub-menu-button"></span></span>'
					) ); ?>
					<?php /*
						$contact_info = get_field('contact_information', 'option')[0];
						$address = $contact_info['address'];
						$phone = $contact_info['phone_number'];*/
					?>
				</div>
			</div>
		<?php endif; ?>
		<header class="site-header">
			<div class="inner">
				<div class="left">
					<a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>" class="brand">
						<img src="<?php the_field( 'site_logo', 'option' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="main-logo">
						<?php if(is_front_page()): ?>
							<img src="<?php echo get_field( 'alternate_logo', 'option' )['url']; ?>" alt="" class="alt-logo">
						<?php endif; ?>
					</a>
				</div>
				<div class="right">
					<div class="right-top">
						<?php
							wp_nav_menu( array(
								'menu' => 'Top Level',
								'container' => 'nav',
								'container_id' => 'large-nav-top'
							) );
						?>
						<?php
							$contact_info = get_field('contact_information', 'option')[0];
							$address = $contact_info['address'];
							$phone = $contact_info['phone_number'];
						?>
						<a href="<?php echo get_field('directions_link', 6); ?>" target="_blank" class="header-address"><?php echo $address; ?></a>
						<a href="tel:<?php echo $phone; ?>" class="header-phone"><?php echo $phone; ?></a>
					</div>
					<div class="right-bottom">
						<?php
							wp_nav_menu( array(
								'container' => 'nav',
								'container_id' => 'large-nav-primary',
								'theme_location' => 'primary',
								'after' => '<i class="far fa-angle-down"></i>'
							) );
						?>
					</div>
					<button class="nav-toggle" type="button" id="nav-toggle">
						<span>menu</span>
					</button>
				</div>
			</div>
			<?php
				/* if( 'dropdown' == get_field( 'menu_type', 'option' ) ){
					wp_nav_menu( array(
						'container' => 'nav',
						'container_id' => 'dropdown-nav-primary',
						'theme_location' => 'primary'
					) );
				}*/
			?>
		</header>
		
		<?php
			if(!is_front_page()):
				$banner = '';
				if( is_home() ) {
					$queried_object = get_queried_object();
					$feature_img = get_the_post_thumbnail_url( $queried_object->ID, 'large' );
					if($feature_img) {
						$banner = $feature_img;
					} else {
						$banner = get_field('default_banner_image','option')['sizes']['large'];
					}
				}elseif( !is_home() && get_the_post_thumbnail_url() ){
					$banner = get_the_post_thumbnail_url( get_the_ID(), 'large' );
					if(get_post_type() == 'products') {
						$banner = get_field('default_banner_image','option')['sizes']['large'];
					}
				}
				else{
					$banner = get_field('default_banner_image','option')['sizes']['large'];
				}
		?>
			<div class="page-banner" style="background-image: url(<?php echo $banner; ?>)"></div>
		
		<?php
    
				if( function_exists( 'yoast_breadcrumb' ) ){
					yoast_breadcrumb( '<div class="container" id="breadcrumbs">', '</div>' );
				}
		
			endif;
		?>
		
		<div class="login-body-overlay"></div>
		<section id="account-login-modal">
			<button class="close-login"><i class="far fa-times"></i></button>
			<div class="inner">
				<img src="<?php echo get_field( 'alternate_logo', 'option' )['url']; ?>" alt="<?php bloginfo( 'name' ); ?>" class="login-brand">
				<h4 class="login-heading"><?php echo get_field('login_title', 'option'); ?></h4>
				 <?php
					wp_login_form( array(
						'redirect' => get_field('login_redirect_link', 'option'),
						'label_username' => __( 'Username' )
					) );
				?>
				<p class="login-fail">
					<?php echo get_field('login_failed_message', 'option'); ?>
				</p>
				<p class="request">
					<?php echo get_field('login_title', 'option'); ?>
					<a href="<?php echo get_field('account_request_link', 'option'); ?>">
						<?php echo get_field('account_request_link_text', 'option'); ?>
					</a>
				</p>
			</div>
		</section>