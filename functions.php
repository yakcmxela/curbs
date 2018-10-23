<?php

class MakespaceChild {

	function __construct(){
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		// SETUP //
			// These are the functions to insert all curb products from the ccp_adapters table 
			// and hvac unit info from the hvac_unit_info table.
			// To run these function select the Insert Posts plugin on the dashboard.

		//$this->admin_page_for_inserts();

		// GENERAL //

		$this->curb_post_type();
		$this->curbfinder();
		$this->custom_login();
		$this->custom_user_roles();
		$this->hvac_post_type();
		$this->hvac_taxonomy();
		$this->product_forms();
		$this->product_post_type();
		$this->product_supports();
		$this->product_taxonomy();
	}

	function wp_enqueue_scripts(){
		$google_api_key = get_field( 'msw_google_map_api_key', 'option' );
		wp_enqueue_script('google_maps', 'https://maps.googleapis.com/maps/api/js?key='.$google_api_key);
		
		$msw_object = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'home_url' => home_url(),
			'show_dashboard_link' => current_user_can( 'manage_options' ) ? 1 : 0,
			'site_url' => site_url(),
			'stylesheet_directory' => get_stylesheet_directory_uri(),
			'logged_in' => is_user_logged_in(),
			'google_map_data' => get_google_map_data()
		);
		wp_enqueue_script( 'theme', get_stylesheet_directory_uri() . '/scripts.min.js' );
		wp_localize_script( 'theme', 'MSWObject', $msw_object );

		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Halant:400,500,700|Montserrat:300,400,500,700|Roboto+Slab' );
		wp_enqueue_style( 'theme', get_stylesheet_uri() );
	}

	function admin_page_for_inserts() {
		add_action( 'admin_menu', 'my_admin_menu' );
		function my_admin_menu() {
			add_menu_page( 
				'Insert Posts Plugin', 
				'Insert Posts', 
				'manage_options', 
				'insert-posts/insert_curbs.php', 
				'insert_posts', 
				'dashicons-tickets', 
				6  
			);
		}
		function insert_posts() {
			$insert_cats = plugin_dir_path( __FILE__ ) . '/insert-posts/insert_cats.php';
			$insert_curbs = plugin_dir_path( __FILE__ ) . '/insert-posts/insert_curbs.php';
			$insert_hvac = plugin_dir_path( __FILE__ ) . '/insert-posts/insert_hvac.php';
			
			$update_posts = plugin_dir_path( __FILE__ ) . '\insert-posts\update_posts.php';
			if( file_exists( $insert_cats ) || file_exists( $insert_hvac ) || file_exists( $insert_curbs ) || file_exists( $update_posts ) ) {
				//include $insert_cats;
				//include $insert_hvac;
				//include $insert_curbs;
				//include $update_posts;
			}
		}
	}

	function curb_post_type() {
		$labels = array(
			'name' => __( 'Curbs', '' ),
			'singular_name' => __( 'Curb', '' ),
			'menu_name' => __( 'Curbs', '' ),
			'all_items' => __( 'All Curbs', '' ),
			'edit_item' => __( 'Edit Product', '' ),
			'new_item' => __( 'New Product', '' ),
			'view_item' => __( 'View Product', '' ),
			'search_items' => __( 'Search Curbs', '' ),
			'not_found' => __( 'No Curbs Found', '' ),
			'not_found_in_trash' => __( 'No Curbs Found In Trash', '' ),
		);

		$args = array(
			'label' => __( 'Curbs', '' ),
			'labels' => $labels,
			'description' => '',
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => false,
			'rest_base' => '',
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => false,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'curbs', 'with_front' => false ),
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'capability_type' => array('curb','curbs'),
            'map_meta_cap' => true,
		);

		register_post_type( 'curbs', $args );
	}

	function curbfinder() {
		add_action( 'wp_ajax_get_hvac_data', 'get_hvac_data' );
		add_action( 'wp_ajax_nopriv_get_hvac_data', 'get_hvac_data' );
		function get_hvac_data() {
			global $wpdb;
			$str = str_replace( ' ' , '', sanitize_text_field($_POST['str']));
			$type = sanitize_text_field($_POST['type']);
			$manufacturer = strtolower(str_replace( ' ', '-', sanitize_text_field($_POST['manufacturer'])));
			$results = [];
			if( $type == 'manufacturer' ) {
				$cat_names = get_terms(
					'brand-names',
					array(
						'hide_empty' => false,
						'fields' => 'names',
						'name__like' => $str,
						'orderby' => 'name',
						'order' => 'ASC',
					)
				);
				foreach ($cat_names as $cat) {
					$results[] = htmlspecialchars_decode($cat);
				}
			} elseif( $type == 'unit_model_no' ) {
				$category = get_terms(
					'brand-names',
					array(
						'hide_empty' => false,
						'name__like' => $manufacturer,
						'orderby' => 'name',
						'order' => 'ASC',
					)
				);
				$term_id = $category[0]->term_id;
				$query = $wpdb->get_results("
					SELECT object_id
					FROM $wpdb->term_relationships
					WHERE term_taxonomy_id
					LIKE '$term_id'
				");
				$post_ids = [];
				foreach( $query as $post ) {
					$post_ids[] = $post->object_id;
				}
				$query = $wpdb->get_results("
					SELECT post_title, ID
					FROM $wpdb->posts 
					WHERE REPLACE(post_title, ' ', '')
					LIKE '%$str%' 
					AND post_type 
					LIKE 'hvac-units'
				");
				foreach ($query as $post) {
					if( !in_array($post->post_title, $results) && in_array($post->ID, $post_ids) ) {
						$results[] = $post->post_title;
					}
				}
			}
			usort( $results, function( $a, $b ) use ( $str ) {
				$a_str = strpos( strtolower($a), $str );
				$b_str = strpos( strtolower($b), $str );
				if( $a_str <	 $b_str) {
					return -1;
				} elseif ( $a_str > $b_str ) {
					return 1;
				} else {
					return 0;
				}
			} );
			wp_send_json($results);
		}
		add_action( 'wp_ajax_get_hvac_product_info', 'get_hvac_product_info' );
		add_action( 'wp_ajax_nopriv_get_hvac_product_info', 'get_hvac_product_info' );
		function get_hvac_product_info() {
			$make = sanitize_text_field($_POST['make']);
			$model = sanitize_text_field($_POST['model']);
			$args = array(
				'post_type' => 'hvac-units',
				's' => $model,
				'tax_query' => array(
					array(
						'taxonomy' => 'brand-names',
						'field' => 'slug',
						'terms' => strtolower($make),
						'operator' => 'IN',
					)
				)
			);
			
			$query = new WP_Query( $args );
			$results = [];
			foreach ($query->posts as $post) {
				$post_id = $post->ID;
				$unit = array(
					'ccp_top' => get_field('ccp_top', $post_id),
					'ccp_bottom' => get_field('ccp_bottom', $post_id),
					'special_notes' => get_field('special_notes', $post_id),
				);
				$results[$post_id] = $unit;
			}			
			if(count($results) == 0) {
				$results = false;
			}
			wp_send_json($results);
		}
		add_action( 'wp_ajax_get_curb', 'get_curb' );
		add_action( 'wp_ajax_nopriv_get_curb', 'get_curb' );
		function get_curb() {
			$bottom = sanitize_text_field($_POST['bottom']);
			$top = sanitize_text_field($_POST['top']);
			$current_user = wp_get_current_user();
			$user_role;
			$bottoms = explode(', ', $bottom);
			$contact_url = get_site_url() . '/contact';
			foreach ($current_user->roles as $cap) {
				switch ($cap) {
					case 'administrator':
					$user_role = 'admin';
					break;
					case 'editor':
					$user_role = 'admin';
					break;
					case 'lennox':
					$user_role = 'lennox';
					break;
					case 'contractor':
					$user_role = 'contractor';
					break;
					case 'distributor':
					$user_role = 'distrib';
					break;
				}
			}
			$bottomImgs = [];
			foreach ($bottoms as $bottom) {
				$args = array(
					'post_type' => 'hvac-units',
					'numberposts' => -1,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'ccp_bottom',
							'value' => $bottom
						)
					)
				);
				$query = new WP_Query( $args );
				$thumbnail_url = null;
				foreach ($query->posts as $post) {
					$thumbnail_url = get_the_post_thumbnail_url( $post->ID );
					$bottomImgs[$bottom] = $thumbnail_url;
				} 
				$bottom_diagrams = array(
					'sort' => 2,
					'bottom' => $bottom,
					'type' => 'diagram',
					'diagram' => $thumbnail_url,
					'status' => 'No adapter currently available for ' . $top . ' to  ' . $bottom . '.',
					'contact' => 'Please <a class="contact-url" href="' . $contact_url . '"> contact Complete Curbs </a> for a custom solution.',
				);
				$curbs[$bottom] = $bottom_diagrams;
				$args = array(
					'post_type' => 'curbs',
					'meta_query' => array(
						array(
							'key' => 'ccp_bottom',
							'value' => $bottom,
							'compare' => '='
						),
						array(
							'key' => 'ccp_top',
							'value' => $top,
							'compare' => '='
						),
					)
				);
				$query = new WP_Query( $args );
				foreach ($query->posts as $post) {
					$post_title = $post->post_title;
					$post_id = $post->ID;
					$price = '';
					if( $user_role == 'lennox' || $user_role == 'contractor' || $user_role == 'distrib' ) {
						$price = '$' . get_field($user_role . '_pricing', $post_id);
					} elseif( $user_role == 'admin') {
						$price_lennox = 'Lennox: $' . get_field('lennox_pricing', $post_id);
						$price_distrib = 'Distributor: $' . get_field('distrib_pricing', $post_id);
						$price_contractor = 'Contractor: $' . get_field('contractor_pricing', $post_id);
						$price = $price_lennox . '<br>' . $price_distrib . '<br>' . $price_contractor;
					}
					$curb_bottom = get_field('ccp_bottom', $post_id);
					$match_bottom = null;
					foreach ($bottomImgs as $key => $value) {
						if($curb_bottom == $key) {
							$match_bottom = $value;
						}
					}
					$page_number = get_field('page_number', $post_id);
					$diagram = get_the_post_thumbnail_url( $post, 'full' );
					$curb = array(
						'sort' => 1,
						'name' => $post_title,
						'type' => 'curb',
						'price' => $price,
						'page_number' => $page_number,
						'diagram' => $diagram,
						'bottom' => $match_bottom,
					);
					unset($curbs[$bottom]);
					$curbs[$bottom] = $curb;
				}
			}

			usort($curbs, function($a, $b) {
			    return $a['sort'] <=> $b['sort'];
			});


			wp_send_json($curbs);
		}
	}

	function custom_login() {
		add_action( 'wp_login_failed', 'custom_login_failed' );
		function custom_login_failed( $username ) {
			$referrer = $_SERVER['HTTP_REFERER'];
			if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
				wp_redirect( home_url() . '?login=failed' );
				exit;
			}
		}

		add_filter('authenticate','custom_authenticate', 31, 3);
		function custom_authenticate( $user, $username, $password ) {
			if ( is_wp_error( $user ) && isset( $_SERVER[ 'HTTP_REFERER' ] ) && !strpos( $_SERVER[ 'HTTP_REFERER' ], 'wp-admin' ) && !strpos( $_SERVER[ 'HTTP_REFERER' ], 'wp-login.php' ) ) {
				$referrer = $_SERVER[ 'HTTP_REFERER' ];
				foreach ( $user->errors as $key => $error ) {
					if ( in_array( $key, array( 'empty_password', 'empty_username') ) ) {
						unset( $user->errors[ $key ] );
						$user->errors[ 'custom_'.$key ] = $error;
					}
				}
			}
			return $user;
		}

		add_action( 'admin_menu', 'remove_menus_custom_users' );
		function remove_menus_custom_users() {
			$user_roles = wp_get_current_user()->roles;
			foreach ($user_roles as $role) {
				if( $role == 'lennox' || $role == 'distributor' || $role == 'contractor' ) {
					remove_menu_page( 'index.php' );
					remove_menu_page( 'edit.php?post_type=curbs' );
					remove_menu_page( 'edit.php?post_type=hvac-units' );
				}
			}
		}
	}

	function custom_user_roles() {
		add_role('lennox', __('Lennox'),
			array(
				'read' => true,
				'create_posts', false,
				'edit_posts', false,
			)
		);
		add_role('distributor', __('Distributor'),
			array(
				'read' => true,
				'create_posts', false,
				'edit_posts', false,
			)
		);
		add_role('contractor', __('Contractor'),
			array(
				'read' => true,
				'create_posts', false,
				'edit_posts', false,
			)
		);
		add_action('admin_init','hvac_role_caps',999);
	    function hvac_role_caps() {
	 		$roles = array('lennox','distributor','contractor','administrator');
		 	foreach($roles as $the_role) { 
		 
				$role = get_role($the_role);

				$role->add_cap( 'read' );
				$role->add_cap( 'read_hvac-unit');
				$role->add_cap( 'read_private_hvac-units' );
				$role->add_cap( 'edit_hvac-unit' );
				$role->add_cap( 'edit_hvac-units' );
				$role->add_cap( 'edit_others_hvac-units' );
				$role->add_cap( 'edit_published_hvac-units' );
				$role->add_cap( 'publish_hvac-units' );
				$role->add_cap( 'delete_others_hvac-units' );
				$role->add_cap( 'delete_private_hvac-units' );
				$role->add_cap( 'delete_published_hvac-units' );

				$role->add_cap( 'read' );
				$role->add_cap( 'read_curb');
				$role->add_cap( 'read_private_curbs' );
				$role->add_cap( 'edit_curb' );
				$role->add_cap( 'edit_curbs' );
				$role->add_cap( 'edit_others_curbs' );
				$role->add_cap( 'edit_published_curbs' );
				$role->add_cap( 'publish_curbs' );
				$role->add_cap( 'delete_others_curbs' );
				$role->add_cap( 'delete_private_curbs' );
				$role->add_cap( 'delete_published_curbs' );
			}
		}
	}

	function hvac_post_type() {
		$labels = array(
			'name' => __( 'HVAC Units', '' ),
			'singular_name' => __( 'HVAC Unit', '' ),
			'menu_name' => __( 'HVAC Units', '' ),
			'all_items' => __( 'All HVAC Units', '' ),
			'edit_item' => __( 'Edit Product', '' ),
			'new_item' => __( 'New Product', '' ),
			'view_item' => __( 'View Product', '' ),
			'search_items' => __( 'Search HVAC Units', '' ),
			'not_found' => __( 'No HVAC Units Found', '' ),
			'not_found_in_trash' => __( 'No HVAC Units Found In Trash', '' ),
		);

		$args = array(
			'label' => __( 'HVAC Units', '' ),
			'labels' => $labels,
			'description' => '',
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => false,
			'rest_base' => '',
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => false,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'hvac-units', 'with_front' => false ),
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'capability_type'     => array('hvac-unit','hvac-units'),
            'map_meta_cap'        => true,
		);

		register_post_type( 'hvac-units', $args );
	}

	function hvac_taxonomy() {
		$args = array(
			'hierarchical' => true,
			'label' => "Brand Names",
			'show_in_menu' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'public' => true,
			'publicly_queryable' => true,
			'rewrite' => array( 'slug' => 'brand-names' ),
		);
		register_taxonomy( 'brand-names', array( 'hvac-units' ), $args );
	}

	function product_taxonomy() {
		$args = array(
			'hierarchical' => true,
			'label' => "Product Categories",
			'show_in_menu' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'public' => true,
			'publicly_queryable' => true,
			'rewrite' => array( 'slug' => 'product-categories' ),
		);
		register_taxonomy( 'product-categories', array( 'products' ), $args );
	}

	function product_forms() {
		global $post;
		// Filter out unwanted dimensions
		add_filter( 'gform_pre_render_2', 'hide_dims' );
		add_filter( 'gform_pre_validation_2', 'hide_dims' );
		function hide_dims( $form ) {
			$dimensions = get_field('dimensions');
			$dimensions_object = get_field_object('dimensions');
			return hide_fields( $dimensions, $dimensions_object, $form );
		}
		// Filter out unwanted specs
		add_filter( 'gform_pre_render_2', 'hide_specs' );
		add_filter( 'gform_pre_validation_2', 'hide_specs' );
		function hide_specs( $form ) {
			$specs = get_field('specs');
			$specs_object = get_field_object('specs');
			return hide_fields( $specs, $specs_object, $form );
		}
		function hide_fields( $vals, $obj, $form ) {
			$wanted_fields = [];
			// Create array of unwanted form fields
			foreach ($obj['choices'] as $key => $value) {
				if($vals){
					foreach ($vals as $val) {
						if( $val == $key ) {
							unset($obj['choices'][$key]);
							$wanted_fields[] = $key;
						}
					}
				}
			}
			// Remove unwanted form fields and make them no longer required
			foreach ($form['fields'] as $key => $field) {
				$field_match = $field->cssClass;
				foreach ($obj['choices'] as $k => $v) {
					if($k == $field_match) {
						$field->isRequired = false;
						unset($form['fields'][$key]);
					}
				}
				foreach ($wanted_fields as $wanted) {
					if($wanted == $field_match) {
						$field->cssClass = 'ccp-field';
					}
				}
			}
			return $form;
		}
	}

	function product_post_type() {
		$labels = array(
			'name' => __( 'Products', '' ),
			'singular_name' => __( 'Product', '' ),
			'menu_name' => __( 'Products', '' ),
			'all_items' => __( 'All Products', '' ),
			'edit_item' => __( 'Edit Product', '' ),
			'new_item' => __( 'New Product', '' ),
			'view_item' => __( 'View Product', '' ),
			'search_items' => __( 'Search Products', '' ),
			'not_found' => __( 'No Products Found', '' ),
			'not_found_in_trash' => __( 'No Products Found In Trash', '' ),
		);

		$args = array(
			'label' => __( 'Products', '' ),
			'labels' => $labels,
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => false,
			'rest_base' => '',
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => false,
			'capability_type' => 'post',
			'map_meta_cap' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'products', 'with_front' => false ),
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'page-attributes' ),
		);

		register_post_type( 'products', $args );
	}

	function product_supports() {
		add_action( 'init', 'supports' );
		function supports() {
			add_post_type_support( 'products', 'thumbnail' );
			acf_add_options_sub_page( array(
				'page_title' => 'Product Page Settings',
				'menu_title' => 'Settings',
				'menu_slug' => 'product-archive-settings',
				'parent_slug' => 'edit.php?post_type=products'
			) );
		}
	}

}

$MakespaceChild = new MakespaceChild();