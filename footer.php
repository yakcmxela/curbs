		<footer class="footer-cta" style="background-image: url(<?php echo get_field('footer_cta_background', 'option')['sizes']['large']; ?>)">
			<div class="inner">
				<h5 class="footer-cta-title"><?php echo get_field('footer_cta_title', 'option'); ?></h5>
				<?php echo get_field('footer_cta_content', 'option'); ?>
				<a href="<?php echo get_field('footer_cta_button_link', 'option'); ?>" class="button"><?php echo get_field('footer_cta_button_text', 'option'); ?></a>
			</div>
		</footer>

		<footer class="site-footer">

			<div class="left">
				<div class="footer-brand">
					<img src="<?php echo get_field( 'alternate_logo', 'option' )['url']; ?>" alt="<?php bloginfo( 'name' ); ?>">
				</div>
				<div class="footer-contact-info">
					<?php
						$contact_info = get_field('contact_information', 'option')[0];
						$address = $contact_info['address'];
						$phone = $contact_info['phone_number'];
						$phone_clean = preg_replace('/\D+/', '', $phone);
						$fax = $contact_info['fax_number'];
						$fax_clean = preg_replace('/\D+/', '', $fax);
					?>
					<p class="address"><?php echo $address; ?></p>
					<p class="phone-fax">
						<span>Ph: <a href="tel:<?php echo $phone_clean; ?>"><?php echo $phone; ?></a></span><br>
						<span>Fx:<a href="tel:<?php echo $fax_clean; ?>"> <?php echo $fax; ?></a></span>
					</p>
				</div>
			</div>

			<div class="right">
				<?php
					$social_links = $contact_info['social_media_links'];
					
					if($social_links):
				?>
					<ul class="footer-social">
						<?php foreach($social_links as $link): ?>
							<li>
								<a href="<?php echo $link['url']; ?>" class="<?php echo $link['class']; ?>"></a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				
				<nav class="footer-inlinks">
					<p class="copyright">&copy;<?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.</p>
					<ul>
						<li><a href="<?php echo home_url( 'privacy-policy' ); ?>" title="Privacy Policy">Privacy Policy</a></li>
						<li><a href="<?php echo home_url( 'site-info' ); ?>" title="Site Info">Site Info</a></li>
						<li><a href="<?php echo home_url( 'site-map' ); ?>" title="Site Map">Site Map</a></li>
						<?php if( is_user_logged_in() ) : ?>
							<li><a href="<?php echo wp_logout_url(); ?>">Log Out</a></li>
						<?php endif; ?>
					</ul>
				</nav>
				
				<a href="https://www.makespaceweb.com" title="Louisville Web Design" target="_blank" id="makespace-bee" class="bee-color bee-flutter">
					<span class="makespace-bee-group">
						<span class="makespace-bee-body">
							<svg xmlns="http://www.w3.org/2000/svg" width="42.9" height="81.1" viewBox="0 0 42.9 81.1" preserveAspectRatio="xMinYMin meet">
							<path class="makespace-bee-head makespace-orange" d="M7.2,32.2c-1.4,0-2.8-0.6-3.8-1.6S1.9,28.2,2,26.7c0.1-2,0.2-4.2,0.3-5.7l0.1-0.8c0.1-0.6,0.2-1.9,1.2-2.9	C4.5,16.4,17.7,6.4,21.5,6.4c3.8,0,17,10,17.9,10.9c1.1,1,1.2,2.3,1.3,2.9l0.1,0.8c0.1,1.5,0.2,3.8,0.3,5.7c0.1,1.4-0.4,2.8-1.4,3.9
								c-1,1-2.4,1.6-3.8,1.6H7.2z"/>
							<path class="makespace-bee-body-1 makespace-blue" d="M1.5,42.5v-5c0-2.9,2.4-5.2,5.3-5.2h29.4c2.9,0,5.2,2.3,5.3,5.2v5H1.5z"/>
							<path class="makespace-bee-body-2 makespace-red" d="M1.7,52.7L1.5,42.5h39.9l-0.1,10.2H1.7z"/>
							<path class="makespace-bee-body-3 makespace-green" d="M1.9,62.9L1.7,52.7h39.6l-0.2,10.2H1.9z"/>
							<path class="makespace-bee-body-4 makespace-purple" d="M20.6,78.5C18,77,4.8,69.4,3.2,67.9c-1.2-1.1-1.3-2.6-1.3-3.1V63l1.8-0.1h37.4L41,64.7c0,0.6-0.1,2.1-1.3,3.2
								C38.2,69.4,25,77,22.4,78.5L21.5,79L20.6,78.5z"/>
							<path class="makespace-bee-body-outline makespace-brown" d="M3.3,40.7v-3.3c0-1.9,1.6-3.4,3.5-3.4h29.4c1.9,0,3.5,1.6,3.5,3.5v3.2C39.7,40.7,3.3,40.7,3.3,40.7z M4,21.2l0.1-0.7
								c0-0.4,0-1.3,0.7-2C6.3,17.1,18.7,8.1,21.5,8.1s15.2,9,16.7,10.4c0.7,0.7,0.7,1.6,0.8,2l0.1,0.7c0.1,1.4,0.2,3.7,0.3,5.6
								c0.1,2-1.5,3.7-3.5,3.7H7.2c-2,0-3.6-1.7-3.5-3.7C3.8,24.9,3.9,22.7,4,21.2z M0,58.1l0.1,3.1v3.6c0.1,1.9,0.7,3.4,1.8,4.4
								c1.6,1.5,11.8,7.4,17.7,10.9l1.7,1l1.7-1c5.9-3.4,16.2-9.4,17.7-10.9c1.2-1.1,1.8-2.6,1.8-4.4l0.1-3.6V58c0,0,0.2-10.2,0.2-11.9
								s0.1-17.9,0.1-20.1s-0.5-6-0.5-6c-0.1-0.7-0.3-2.5-1.8-4c-0.1-0.1-3.6-2.9-7.7-5.7V5.7l3,2C36.8,8.3,38,8,38.6,7.1S39,4.9,38,4.3
								l-6.1-4c-0.6-0.4-1.4-0.4-2-0.1s-1.1,1-1.1,1.8v5.8C26,6,23.2,4.7,21.5,4.7S16.9,6.1,14,7.9V2c0-0.7-0.4-1.4-1.1-1.8
								c-0.6-0.3-1.4-0.3-2,0.1l-6.1,4C3.9,4.9,3.6,6.1,4.2,7.1C4.8,8,6,8.3,7,7.7l3-2v4.8c-3.9,2.7-7.3,5.4-7.6,5.6
								c-1.5,1.5-1.7,3.3-1.8,4c0,0-0.5,2.9-0.5,5.5S0,47.8,0,47.8L0,58.1z M38.6,66.6C37.1,68,21.5,77,21.5,77S5.9,68,4.4,66.6
								c-0.7-0.7-0.7-1.6-0.7-2h35.6C39.3,65,39.3,66,38.6,66.6z M39.4,61.2H3.6l-0.1-6.7h36C39.5,54.5,39.4,61.2,39.4,61.2z M39.6,50.9
								H3.4l-0.1-6.7h36.3V50.9z"/>
							</svg>
						</span>
						<span class="makespace-bee-wing wing-left">
							<svg xmlns="http://www.w3.org/2000/svg" width="33.1" height="45.1" viewBox="0 0 33.1 45.1" preserveAspectRatio="xMinYMin meet">
								<path class="makespace-orange wing-fill" d="M31,28.2c0,8.6-7.1,14.7-14.7,14.7c-2.7,0-5.3-0.8-7.9-2.3c-0.1,0-0.1-0.1-0.2-0.1c-8.4-5.4-8.5-17.7-0.1-23.2
									C16,12.1,24.8,6.5,31,3.2C31,3.2,31,28.2,31,28.2z"/>
								<path class="makespace-brown wing-outline"  d="M29.9,28.3c0,7.8-6.4,13.3-13.3,13.3c-2.4,0-4.8-0.7-7.1-2.1c-0.1,0-0.1-0.1-0.2-0.1c-7.6-4.9-7.7-16-0.1-20.9
									c7.1-4.7,15.1-9.8,20.7-12.8C29.9,5.7,29.9,28.3,29.9,28.3z M28.2,2.5c-4.8,2.6-12,7-21.2,13.1C2.6,18.6,0,23.7,0,29.1
									c0,5.4,2.8,10.4,7.4,13.3l0.2,0.1c2.7,1.7,5.8,2.6,9,2.6h0.1c8.8,0,16.5-6,16.5-16C33.1,29.2,33,0,33,0L28.2,2.5z"/>
							</svg>
						</span>
						<span class="makespace-bee-wing wing-right">
							<svg xmlns="http://www.w3.org/2000/svg" width="33.1" height="45.1" viewBox="0 0 33.1 45.1" preserveAspectRatio="xMinYMin meet">
								<path class="makespace-orange wing-fill"  d="M23.8,16.1c7.3,4.6,8.7,13.9,4.6,20.3c-1.4,2.3-3.5,4.1-6.2,5.4c-0.1,0.1-0.2,0-0.2,0.1
									c-9.1,4.2-19.5-2.2-19.7-12.3c-0.2-9.4-0.3-19.9,0.2-27C2.6,2.8,23.8,16.1,23.8,16.1z"/>
								<path class="makespace-brown wing-outline"  d="M3.2,5.7c5.6,3,13.6,8.1,20.7,12.8c7.6,4.9,7.5,16-0.1,20.9c-0.1,0-0.1,0.1-0.2,0.1c-2.3,1.4-4.7,2.1-7.1,2.1
									c-6.9,0-13.3-5.5-13.3-13.3C3.2,28.3,3.2,5.7,3.2,5.7z M0.1,0C0.1,0,0,29.2,0,29.2c0,10,7.6,16,16.5,16h0.1c3.2,0,6.3-0.9,9-2.6
									l0.2-0.1c4.6-2.9,7.4-7.9,7.4-13.3s-2.6-10.5-7.1-13.5C16.9,9.5,9.7,5.1,4.9,2.5L0.1,0z"/>
							</svg>
						</span>
					</span>
				</a>
				
			</div>

		</footer>
		<?php wp_footer(); ?>
	</body>
</html>