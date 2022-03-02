<?php

    // include external files

	    include 'reset.php';

    // setup theme support
    
        add_action( 'after_setup_theme', 'teamswitch_theme_setup');
        function teamswitch_theme_setup() {

            add_theme_support( 'post-thumbnails' ); // enable featured images for default post type
            add_theme_support( 'title-tag' ); // dynamic title tag in head
            add_theme_support( 'menus' );

        }

    // add scripts

        add_action( 'wp_enqueue_scripts', 'teamswitch_adding_scripts', 999 );	
        function teamswitch_adding_scripts() {
            
            wp_enqueue_script('owl', get_template_directory_uri() . '/js/vendor/owl.carousel.min.js', array('jquery'), filemtime(get_stylesheet_directory() . '/js/vendor/owl.carousel.min.js'));
            wp_enqueue_script('dotdotdot', get_template_directory_uri() . '/js/vendor/dotdotdot.js', array(), filemtime(get_stylesheet_directory() . '/js/vendor/dotdotdot.js'));
            wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js', array(), filemtime(get_stylesheet_directory() . '/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js'));
            wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDWoSM3uHPncI05dg05dAN1GGsRC80BOxE');
            wp_enqueue_script('google-maps-settings', get_template_directory_uri() . '/js/vendor/google-maps-settings.js', array(), filemtime(get_stylesheet_directory() . '/js/vendor/google-maps-settings.js'));
            wp_enqueue_script('youtube-api', 'https://www.youtube.com/iframe_api');
            wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js', array('jquery', 'owl', 'dotdotdot'), filemtime(get_stylesheet_directory() . '/js/main.js'));
        }

    // add styles	  

        add_action('wp_enqueue_scripts', 'teamswitch_adding_styles', 999 );	
        function teamswitch_adding_styles() {
            wp_enqueue_style('font-primary', 'https://use.typekit.net/pdl0tvn.css');
            wp_enqueue_style('font-secondary', 'https://fonts.googleapis.com/css?family=Barlow:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap');
            wp_enqueue_style('style', get_template_directory_uri() . '/css/style.min.css', array(), filemtime( get_stylesheet_directory() . '/css/style.min.css' ) );
        }

    // advaced custom fields

        add_action('acf/init', 'teamswitch_acf_init');
        function teamswitch_acf_init() {

            // acf - disable gutenberg

            add_filter('use_block_editor_for_post', '__return_false');

            // acf - basic wysiwyg - https://www.tiny.cloud/docs-3x/reference/buttons/

            add_filter("mce_buttons", "base_extended_editor_mce_buttons", 0);
            function base_extended_editor_mce_buttons($buttons) {
                return array('formatselect', 'bold', 'italic', 'strikethrough', 'bullist', 'link', 'unlink', 'blockquote');
            }

            add_filter('tiny_mce_before_init', 'base_custom_mce_format' );
            function base_custom_mce_format($init) {
                $init['block_formats'] = 'Paragraph=p;Title=h2;Subtitle=h3;';
                return $init;
            }
            
            // acf - google maps			
            
            acf_update_setting('google_api_key', 'AIzaSyDWoSM3uHPncI05dg05dAN1GGsRC80BOxE');

            // acf - options page

            if( function_exists('acf_add_options_page') ) {

                acf_add_options_page(array(
                    'page_title' 	=> 'Options',
                    'menu_title'	=> 'Options',
                    'menu_slug' 	=> 'theme-general-settings',
                    'capability'	=> 'edit_posts',
                    'redirect'		=> false
                ));
            
            }

            //USE ACF OPTIONS GLOBALLY FROM DEFAULT LANGUAGE
            // add_filter( 'acf/validate_post_id', function( $post_id, $original_post_id ){
            
            // 	if( strpos($post_id, 'options_') === 0 ){ //postfix detection
            // 	$post_id = 'options';
            // 	}
                
            // 	return $post_id; //FILTER! MUST RETURN!
            // }, 10, 2 );
        }

    // custom image sizes

        add_action( 'after_setup_theme', 'teamswitch_image_sizes' );
        function teamswitch_image_sizes() {

            add_image_size( '640-16-9', 640, 360, true );
            add_image_size( '640-4-3', 640, 480, true );
            add_image_size( '640-1-1', 640, 640, true );
            add_image_size( '960-16-9', 960, 540, true );
            add_image_size( '960-4-3', 960, 720, true );
            add_image_size( '960-1-1', 960, 960, true );
            add_image_size( '1280-16-9', 1280, 720, true );
            add_image_size( '1280-4-3', 1280, 960, true );
            add_image_size( '1280-1-1', 1280, 1280, true );
            add_image_size( '1920-16-9', 1920, 1080, true );
            add_image_size( '1920-4-3', 1920, 1440, true );
            add_image_size( '1920-1-1', 1920, 1920, true );

            add_image_size( '640', 640, 640 );
            add_image_size( '960', 960, 540 );
            add_image_size( '1280', 1280, 720 );
            add_image_size( '1920', 1920, 1080 );
        }

    // responsive image

        /**
         * Responsive Image Helper Function
         *
         * @param string $image_id the id of the image (from ACF or similar)
         * @param string $image_size the size of the thumbnail image or custom image size
         * @param string $max_width the max width this image will be shown to build the sizes attribute 
         */

        function awesome_acf_responsive_image($image_id,$image_size,$max_width){

            // check the image ID is not blank
            if($image_id != '') {

                // set the default src image size
                $image_src = wp_get_attachment_image_url( $image_id, $image_size );

                // set the srcset with various image sizes
                $image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

                // generate the markup for the responsive image
                echo 'src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"';

            }
        }

    // register menu

        add_action( 'init', 'teamswitch_register_menu' );
        function teamswitch_register_menu() {
            register_nav_menu('menu',__( 'Primary Menu' ));
        }

    // gravity forms

		// gravity forms - change submit input to button

			add_filter( 'gform_submit_button', 'teamswitch_form_submit_button', 10, 2 );
			function teamswitch_form_submit_button( $button, $form ) {
				return "
					<button class='button' id='gform_submit_button_{$form['id']}'>
						{$form['button']['text']}
					</button>d
				";				
			}

		// gravity forms - change submit button spinner

			add_filter( 'gform_ajax_spinner_url', 'teamswitch_custom_gforms_spinner' );
			function teamswitch_custom_gforms_spinner( $src ) {
				return get_stylesheet_directory_uri() . '/img/icons/loading.svg';
			}

    // switch login logo

        add_action('login_head', 'teamswitch_loginlogo');
        function teamswitch_loginlogo() {
            echo '<style type="text/css"> h1 a { margin: 0px !important; background-size: 100% !important; width: 280px !important; height: 120px !important; position: relative !important; left: 50% !important; margin-left: -140px !important; margin-bottom: 40px !important; background-image: url('.get_bloginfo('template_directory').'/img/template-logo.png) !important; } </style>';
        }

    // disable xmlrpc

		add_filter( 'xmlrpc_enabled', '__return_false' );

    // create custom post type

        add_action( 'init', 'teamswitch_custom_post_types' );
        function teamswitch_custom_post_types() {
            function create_post_type($name, $slug = '') {
                $slug = $slug === '' ? strtolower($name) : $slug;
                register_post_type( $name,
                    array(
                    'labels'                => array(
                        'name'                => $name,
                        'singular_name'       => $name,
                        'menu_name'           => $name,
                        'add_new'             => 'New',
                        'add_new_item'        => 'New',
                        'new_item'            => 'New',
                        'edit'                => 'Edit',
                        'edit_item'           => 'Edit',
                        'view'                => 'View',
                        'view_item'           => 'View',
                        'search_items'        => 'Search',
                        'not_found'           => 'Not found',
                        'not_found_in_trash'  => 'Not found in trash',
                    ),
                    'public'                => true,
                    'menu_position'         => 10,
                    'taxonomies'			=> array( 'category' ),
                    'supports'           	=> array( 'title', 'editor', 'revisions', 'thumbnail', 'page-attributes'),
                    'show_in_rest' 			=> true,
                    'rewrite'               => array('slug' => $slug),
                    )
                );
            }
            
            create_post_type('Facebook');
            create_post_type('Projecten', 'project'); // to edit url/slug, add second parameter
        }

    // create custom taxonomy

		add_action( 'init', 'teamswitch_custom_taxonomies', 0 );
        function teamswitch_custom_taxonomies() {

            /**
             * Create custom taxonomy for custom post type
             *
             * @param string $name the name of the taxonomy
             * @param string $post_type the post type to attach the taxonomy to
             * @param string $hidden if the taxonomy should be hidden from the admin menu
             */
            function create_taxonomy($name, $post_type, $hidden = false) {

                register_taxonomy(strtolower($name), 
                    array($post_type), 
                    array(
                    'hierarchical' => true, 
                    'label' => $en,
                    'nl' => $nl,
                    'en' => $en,
                    'query_var' => true, 
                    'rewrite' => true,
                    'show_admin_column' => !$hidden
                    ) 
                );  
            }
            // create_taxonomy('Locatie', 'Projecten', true);
       }

    // remove default post type

		// add_action( 'admin_menu', 'teamswitch_remove_post_type' );
		// function teamswitch_remove_post_type(){
		// 	remove_menu_page( 'edit.php' );
		// }

    // facebook wall

		function update_facebook() {

			// vars
			$fb_page = '233843225214839';
			$fb_access_token = 'EAAMxBP3IDlABAC9BmSZCdafrRIvyb6GYQyvjfZAz7Q97pRPsuZCpzaVWzFYonarOmD0JzzQIphCmiWyv843wWcGFSrc2H6s61GgHGa1uZBAWZCAMdpPLfXKNTje2lDnj7QVbGCdLTJVydiDwkpTxM2Agr9b7RiRHZCZCZCTJAmZAHpiCYjPNsrDKuy3rS4AvQpm9vor7JwnbhdAZDZD';

			if ($fb_page && $fb_access_token) {
				
				// vars
				$fb_json = 'https://graph.facebook.com/v12.0/' . $fb_page . '/posts?access_token=' . $fb_access_token . '&fields=id,created_time,full_picture,message,status_type,permalink_url&limit=36';
				$fb_results = json_decode(file_get_contents($fb_json),true);

				// https://graph.facebook.com/v12.0/233843225214839/posts?access_token=EAAMxBP3IDlABAC9BmSZCdafrRIvyb6GYQyvjfZAz7Q97pRPsuZCpzaVWzFYonarOmD0JzzQIphCmiWyv843wWcGFSrc2H6s61GgHGa1uZBAWZCAMdpPLfXKNTje2lDnj7QVbGCdLTJVydiDwkpTxM2Agr9b7RiRHZCZCZCTJAmZAHpiCYjPNsrDKuy3rS4AvQpm9vor7JwnbhdAZDZD&fields=id,created_time,full_picture,message,status_type,permalink_url&limit=36';

				// console_log($fb_results['data']);

				foreach ($fb_results['data'] as $fbResult) {

					// vars
					$id = $fbResult['id'];
					$created_time = new DateTime($fbResult['created_time']);
					$created_time = $created_time->setTimezone(new DateTimeZone("Europe/Amsterdam"));
					$date_time = $created_time->format('Y-m-d H:i:s');
					$type = $fbResult['status_type'];
					if (strpos($type, 'video') !== false) { $type = 'video'; }
					if (strpos($type, 'photo') !== false) { $type = 'photo'; }		
					$image = $fbResult['full_picture'] ? $fbResult['full_picture'] : null;
					$text = $fbResult['message'] ? $fbResult['message'] : '';
					$text = str_replace("\n", '<br/>', $text);
					$text = str_replace("'", "\&#39;", $text);
					$link = $fbResult['permalink_url'];

					// add post to database

					if (null == get_page_by_title($id, 'OBJECT', 'facebook') && strlen($text) > 0 && $link) {

						$post_id = wp_insert_post(
							array(
								'post_name'		=>	$id,
								'post_title'	=>	$id,
								'post_status'	=>	'publish',
								'post_type'		=>	'facebook',
								'post_date'		=>	$date_time,
							)
						);

						// upload image
						require_once(ABSPATH . 'wp-admin/includes/media.php');
						require_once(ABSPATH . 'wp-admin/includes/file.php');
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						$image_attachment = media_sideload_image($image, $post_id, $text, 'src');

						// save to post
						
						update_post_meta($post_id, 'facebook_type', $type);
						update_post_meta($post_id, 'facebook_image', $image_attachment);		
						update_post_meta($post_id, 'facebook_text', $text);
						update_post_meta($post_id, 'facebook_link', $link);
					}

				}

			} else {

				return;

			}
		}
        // add_action( 'every_ten_minutes', 'update_facebook' );
		// add_action( 'init', 'update_facebook' );

    // cron schedules - 10 minutes

		add_filter( 'cron_schedules', 'every_ten_minutes' );
		function every_ten_minutes( $schedules ) {
			$schedules['ten_minutes'] = array(
				'interval' => 600,
				'display'  => esc_html__( 'Every ten minutes' ),
			);
			return $schedules;
		}

		if ( ! wp_next_scheduled( 'every_ten_minutes' ) ) {
			wp_schedule_event( time(), 'ten_minutes', 'every_ten_minutes' );
		}

    // miscellaneous helper functions

        // ovverrides the default get_field function from ACF and returns a pretty version of the field
        function teamswitch_get_field($field, $page_id = null) {
            
            if (!class_exists('ACF')) {
                return 'ACF has not been initialized';
            }

            $field = get_field($field, $page_id);

            // \ turns into <br/>
            $pattern2 = '/\\/';
            $replacement2 = '<br/>';
            $field = preg_replace($pattern2, $replacement2, $field);

            // *word* turns into <strong>word</strong>
            $pattern = '/(.*)\*(.*)\*(.*)/';
            $replacement = '$1<strong>$2</strong>$3';
            $field = preg_replace($pattern, $replacement, $field);

            // **word** turns into <em>word</em>
            $pattern = '/(.*)\*\*(.*)\*\*(.*)/';
            $replacement = '$1<em>$2</em>$3';
            $field = preg_replace($pattern, $replacement, $field);

            return $field;
        }

        // logs a message or variable to the browser's console
        function console_log($output, $with_script_tags = true) {
			$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
			if ($with_script_tags) {
				$js_code = '<script>' . $js_code . '</script>';
			}
			echo $js_code;
		}

        // logs a message or variable to the browser's console with a yellow background
        function console_warn($output, $with_script_tags = true) {
			$js_code = 'console.warn(' . json_encode($output, JSON_HEX_TAG) . ');';
			if ($with_script_tags) {
				$js_code = '<script>' . $js_code . '</script>';
			}
			echo $js_code;
		}