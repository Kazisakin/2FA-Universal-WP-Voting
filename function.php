<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.1.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				get_template_directory_uri() . '/header-footer' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Admin notice
if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}







function create_form_access_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'form_access'; 

    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        token varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        request_count int(11) DEFAULT 0 NOT NULL,
        last_request_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        is_used tinyint(1) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('after_setup_theme', 'create_form_access_table');

function generate_one_time_link_shortcode() {
    // Whitelisted emails
    $whitelisted_emails = [
        'email_list',
        'email'
    ];

    $success_message = '';

    if (isset($_POST['submit_email'])) {
        global $wpdb;

        // Sanitize and validate email
        $email = sanitize_email($_POST['email']);
        if (!is_email($email)) {
            $success_message = "<p style='color: red;'>Please enter a valid email address.</p>";
            return '<div style="text-align: center;">' . $success_message . '</div>'; 
        }

        if (in_array(strtolower($email), array_map('strtolower', $whitelisted_emails))) {
            $existing_entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}form_access WHERE email = %s", $email ) );

            if ($existing_entry) {
                $request_count = $existing_entry->request_count;
                $last_request_time = strtotime($existing_entry->last_request_time);
                $current_time = time();
                $time_diff = $current_time - $last_request_time; 

                if ($request_count >= 2 && $time_diff < 1800) {
                    $success_message = "<p style='color: #ffffff;'>You have already requested the link twice. Please try again after 30 minutes.</p>";
                } else {
                    $new_count = ($time_diff >= 1800) ? 1 : $request_count + 1;

                    $wpdb->update(
                        $wpdb->prefix . 'form_access',
                        array(
                            'request_count' => $new_count,
                            'last_request_time' => current_time('mysql')
                        ),
                        array( 'email' => $email )
                    );
                    send_voting_email($email);
                    $success_message = "<p style='color: #ffffff; font-weight: bold;'>A one-time link and password have been sent to your email. Please check your inbox and follow the instructions carefully.</p>";
                }
            } else {
                $wpdb->insert(
                    $wpdb->prefix . 'form_access',
                    array(
                        'email' => $email,
                        'token' => bin2hex(random_bytes(16)),
                        'password' => wp_hash_password(wp_generate_password(12, false)),
                        'request_count' => 1,
                        'last_request_time' => current_time('mysql'),
                        'is_used' => 0
                    )
                );
                send_voting_email($email);
                $success_message = "<p style='color: #ffffff; font-weight: bold;'>A one-time link and password have been sent to your email. Please check your inbox and follow the instructions carefully.</p>";
            }
        } else {
            $success_message = "<p>Your email is not authorized to access this form.</p>";
        }
    }

    return '
    <div style="text-align: center;">
        ' . $success_message . ' <!-- Messages are shown here -->
        <form method="POST">
            <label for="email">Enter your email:</label><br>
            <input type="email" name="email" required style="padding: 10px; width: 80%; margin-top: 10px;"><br>
            <p style="font-size: 12px; color: #ffffff;">Please share the email in which you have received the voting invitation from the advisor.</p>
            <input type="submit" name="submit_email" value="Submit" style="padding: 10px 20px; background-color: #339c75; color: white; border: none; border-radius: 5px; cursor: pointer;">
        </form>
    </div>';
	
}
function send_voting_email($email) {
    global $wpdb;
    $token = bin2hex(random_bytes(16));
    $link = 'https://unbbss.org/access-form/?token=' . $token;
    $password = wp_generate_password(12, false);

    $wpdb->update(
        $wpdb->prefix . 'form_access',
        array(
            'token' => $token,
            'password' => wp_hash_password($password)
        ),
        array( 'email' => $email )
    );

    $subject = 'Access Your UNB BSS Voting Form - One-Time Link & Pass';
    $message = "
        <html>
        <head><title>Access Your UNB BSS Voting Form - One-Time Link & Pass</title></head>
        <body>
            <p>Dear UNB BSS Member,</p>
            <p>You have been granted access to the UNB Bangladesh Student Society (BSS) voting form.</p>
            <p><strong>One-Time Link:</strong> <a href='$link'>$link</a><br>
               <strong>Password:</strong> $password</p>
            <p style='color:red;'><strong>Important:</strong><br>
               **This link is valid for <strong>your use only</strong>. Do not share this link or password with anyone else as it is meant for your private voting.<br>**Please ensure you use the same email address this voting link was sent to. Using a different or unauthorized email will invalidate the link and prevent access to the voting form.<br>**After receiving the voting link twice consecutively, you must wait 30 minutes before requesting the link again.<br>**
               Once you submit your vote, the link will expire and cannot be reused.</p>
            <p>If you have any questions, feel free to contact us.</p>
            <p>Best regards,<br>UNB BSS Voting Committee</p>
        </body>
        </html>";

    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send the email
    wp_mail($email, $subject, $message, $headers);
}

add_shortcode('generate_link', 'generate_one_time_link_shortcode');


function validate_form_access_shortcode() {
    if (isset($_GET['token']) && isset($_POST['verify_access'])) {
        global $wpdb;
        $token = sanitize_text_field($_GET['token']);
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);

        error_log("DEBUG: Token received: " . $token);
        error_log("DEBUG: Email received: " . $email);

        $user = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}form_access WHERE token = %s AND email = %s AND is_used = 0", 
            $token, $email
        ));

        if ($user) {
            error_log("DEBUG: User found in database. Email: " . $user->email);
        } else {
            error_log("DEBUG: User not found or token has been used.");
            return "<p>Invalid email, password, or token. Please try again.</p>";
        }

        if ($user && wp_check_password($password, $user->password)) {
            error_log("DEBUG: Password verified for user: " . $email);

            $wpdb->update(
                $wpdb->prefix . 'form_access',
                array('is_used' => 1),
                array('email' => $user->email)
            );
            
            return do_shortcode('[fluentform id="4"]'); 
        } else {
            error_log("DEBUG: Password verification failed for email: " . $email);
            return "<p>Invalid email, password, or token. Please try again.</p>";
        }
    }

    return '<form method="POST">
    <label for="email">Enter your email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Enter your password:</label>
    <input type="password" name="password" id="password" required>

    <input type="submit" name="verify_access" value="Submit">
</form>
';
}
add_shortcode('validate_form_access', 'validate_form_access_shortcode');








add_filter('fluentform_submission_before_insert_data', function($insertData, $form) {

    if ($form->id == 4) { 
        
        $field_key = 'numeric_field';

        if (isset($insertData['fields'][$field_key])) {
            error_log('Original numeric field value: ' . $insertData['fields'][$field_key]);

            // Remove commas from the numeric field
            $insertData['fields'][$field_key] = str_replace(',', '', $insertData['fields'][$field_key]);

            error_log('Cleaned numeric field value: ' . $insertData['fields'][$field_key]);
        } else {
            error_log('Numeric field not found.');
        }
    }

    return $insertData;
}, 10, 2);
