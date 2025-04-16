<?php
/*
Plugin Name: Two Factor (2FA) Authentication via Email
Plugin URI: https://ss88.us/plugins/two-factor-2fa-authentication-via-email-plugin-for-wordpress
Description: A lightweight plugin to allow the use of two-factor authentication (2FA) through email. One-click login with this Two-Factor (2FA) Authentication plugin for WordPress.
Version: 1.9.6
Author: SS88 LLC
Author URI: https://ss88.us
Text Domain: two-factor-2fa-via-email
*/

class SS88_2FAVE {

    protected $version = '1.9.6';
	protected $email_tags = [];
	protected $expires = 15;
    protected $cipher = 'AES-256-CBC';

    public static function init() {

        $C = __CLASS__;
        new $C;

    }

    function __construct() {

        global $pagenow;

		if(defined('SS88_2FAVE_LINK_EXPIRES_MINUTES')) {
			
			$minutes = intval(SS88_2FAVE_LINK_EXPIRES_MINUTES);
			$this->expires = ($minutes>0) ? $minutes : $this->expires;

		}

        add_action('wp_login', [$this, 'wp_login'], 1, 2);
        add_action('login_init', [$this, 'processTokenLogin']);
		add_action('deactivated_plugin', [$this, 'deactivated_plugin']);

        if($pagenow == 'user-edit.php' || $pagenow == 'profile.php' || $pagenow == 'user-new.php') {

            add_action('show_user_profile', [$this, 'userOptions']);
            add_action('edit_user_profile', [$this, 'userOptions']);
            add_action('user_new_form', [$this, 'userOptions']);

            add_action('personal_options_update', [$this, 'userOptionsUpdate']);
            add_action('edit_user_profile_update', [$this, 'userOptionsUpdate']);
            add_action('user_register', [$this, 'userOptionsUpdate']);

        }

        if(is_admin()) {
            
            add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
            add_action('wp_ajax_SS88_2FAVE_DISMISS_NOTICE', [$this, 'ajax_dismiss_notice']);

        }

        if(!defined('FLUENTMAIL') && !function_exists('wp_mail_smtp') && !defined('POST_SMTP_VER') && !function_exists('is_smtp_mailer_configured')) {

            add_action('admin_notices', [$this, 'show_admin_notice']);

        }

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'plugin_action_links']);
        add_action('wp_ajax_ss882fave_is_user_logged_in', [$this, 'ss882fave_is_user_logged_in']);
        add_action('wp_ajax_nopriv_ss882fave_is_user_logged_in', [$this, 'ss882fave_is_user_logged_in']);

    }

    function random_bytes($length = 20) {

        if(!function_exists('random_bytes')) {

            return openssl_random_pseudo_bytes($length);

        }
        else {

            return random_bytes($length);

        }

    }

    function ajax_dismiss_notice() {

        $Type = sanitize_text_field($_POST['type']);

        if(!empty($Type) && $Type==='smtp') {

            update_option('SS88_2FAVE_notice_dismissed_smtp', true);

        }

    }

    function show_admin_notice() {

        if(get_option('SS88_2FAVE_notice_dismissed_smtp')) return;

        $class = 'notice notice-error is-dismissible SS88_2FAVE';
        $message = '<div style="display:flex;gap:20px;">
                        <svg style="min-width:50px;" xmlns="http://www.w3.org/2000/svg" width="31.458" height="39.198" viewBox="0 0 31.458 39.198"><g id="download" transform="translate(-18.541 -9.167)"><g id="Group_1" data-name="Group 1" transform="translate(18.541 9.167)"><path id="Path_2" data-name="Path 2" d="M47.882,33.335a2.117,2.117,0,0,1-2.117-2.117V17.634A4.239,4.239,0,0,0,41.531,13.4H38.355a4.239,4.239,0,0,0-4.234,4.234V31.218a2.117,2.117,0,1,1-4.234,0V17.634a8.477,8.477,0,0,1,8.467-8.467H41.53A8.477,8.477,0,0,1,50,17.634V31.218a2.115,2.115,0,0,1-2.116,2.116Z" transform="translate(-24.214 -9.167)" fill="#333"/><path id="Path_3" data-name="Path 3" d="M45.208,60.6H23.333a4.792,4.792,0,0,1-4.792-4.792V38.426a4.792,4.792,0,0,1,4.792-4.792H45.207A4.792,4.792,0,0,1,50,38.426V55.806A4.791,4.791,0,0,1,45.208,60.6Z" transform="translate(-18.541 -21.401)" fill="#f8b26a"/></g></g></svg>
                        <div><strong>Two Factor (2FA) Authentication via Email</strong><br>' . __('We have not detected a plugin installed that will handle your emails via SMTP. Please note, if you enable our plugin for a user you must make sure that your WordPress website sends emails correctly, otherwise the user will be locked out until email sending works on your website.', 'two-factor-2fa-via-email') . '</div>
                    </div>';
    
        printf( '<div class="%1$s" data-type="smtp"><p>%2$s</p></div>', esc_attr( $class ), $message);

    }

    function admin_enqueue_scripts() {

        wp_enqueue_style('SS88_2FAVE', plugin_dir_url( __FILE__ ) . 'assets/css/user.css', false, $this->version);
        wp_enqueue_script('SS88_2FAVE-admin', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', false, $this->version);
        wp_localize_script('SS88_2FAVE-admin', 'ss88', array('ajax_url' => admin_url( 'admin-ajax.php' )));

    }

    function processTokenLogin()
    {
        if(!isset($_GET['token'])) return;
        
        $Token = sanitize_text_field($_GET['token']);

        if(!empty($Token))
        {
            $DecodedToken = base64_decode($Token);
            $ExtractedIV = substr($DecodedToken, 0, openssl_cipher_iv_length($this->cipher));
            $ExtractedEncrypted = substr($DecodedToken, openssl_cipher_iv_length($this->cipher));

            $Token = openssl_decrypt($ExtractedEncrypted, $this->cipher, wp_salt(), OPENSSL_RAW_DATA, $ExtractedIV);
            
            if(!$Token) {

                $this->outputPage('<p><strong>'. __('Token Decryption Failure', 'two-factor-2fa-via-email') .'</strong><p><p>'. __('The token you are using is invalid and could not be decrypted. Please try logging in again.', 'two-factor-2fa-via-email') .'</p>');

            }

            parse_str($Token, $TokenA);

            $UserID = intval($TokenA['u']);
            $Token_GET = $TokenA['t'];
            $Token_UA = $TokenA['ua'];
			$Token_RM = $TokenA['rm'];
        }

        if(isset($Token_GET) && isset($UserID) && isset($Token_UA))
        {
            $U = get_userdata($UserID);
            if(!$U) die( __('User does not exist.', 'two-factor-2fa-via-email') );
            $UserID = $U->ID;

            $Token = get_user_meta($UserID, 'SS882FAEmail_token', true);
            $Timestamp = (int)get_user_meta($UserID, 'SS882FAEmail_timestamp', true);

		    if(((time() - $Timestamp) >= ($this->expires * 60))) {
                
                $this->outputPage('<p><strong>'. __('Token Expired', 'two-factor-2fa-via-email') .'</strong><p><p>'. __('The token you are using has expired.', 'two-factor-2fa-via-email') .'</p>');

            }

            if($Token_UA!==md5($_SERVER['HTTP_USER_AGENT'])) {
                
                $this->outputPage('<p><strong>'. __('Agent Mismatch', 'two-factor-2fa-via-email') .'</strong><p><p>'. __("The token's User Agent does not match.", 'two-factor-2fa-via-email') .'</p>');

            }

            if($Token_GET!==$Token) {
                
                $this->outputPage('<p><strong>'. __('Token Mismatch', 'two-factor-2fa-via-email') .'</strong><p><p>'. __('The token you are using does not match or has already been used.', 'two-factor-2fa-via-email') .'</p>');

            }

            if(wp_set_current_user($UserID)) {

                wp_set_auth_cookie($UserID, $Token_RM);

                delete_user_meta($UserID, 'SS882FAEmail_token');
                delete_user_meta($UserID, 'SS882FAEmail_timestamp');

                $redirect_to_token = isset( $TokenA['r'] ) ? $TokenA['r'] : false;
                $final_redirect = apply_filters('SS88_2FAVE_custom_redirect', $redirect_to_token);

                if($final_redirect) {
					
					wp_safe_redirect($final_redirect);

				}
				else {

					if(class_exists('UM')) {

						um_fetch_user($UserID);
						$_REQUEST['rememberme'] = $Token_RM;
						do_action('um_user_login', ['rememberme' => $Token_RM], []);
						exit;

					}
					else {

						$redirect_to = apply_filters('login_redirect', admin_url(), '', $U);

						wp_safe_redirect( $redirect_to );

					}

				}

            }
        }
    }

	public function wp_login($user_login, $U) {

        // Let's check to see if it's an API call
        if(strpos($_SERVER['REQUEST_URI'], '/wp-json/') === 0 || strpos($_SERVER['REQUEST_URI'], '?rest_route=') === 0) {

            if(!$this->isEnabled($U->ID, 'API')) return;

            wp_send_json_error([
                'message' => __('2FA is enabled on this account. Unable to authenticate.', 'two-factor-2fa-via-email')
            ], 403);
            exit;

        }

		if(!isset($_GET['token'])) {

			if(!$this->isEnabled($U->ID)) return;

			wp_destroy_current_session();
			wp_clear_auth_cookie();
			wp_set_current_user(0);
			wp_logout();
            header_remove('Set-Cookie');

			if($this->emailToken($U)) {

				$this->outputPage('
					<p><strong>'. __('Account Protected', 'two-factor-2fa-via-email') .'</strong><p>
					<p>'. __('This account has Two Factor Authentication (2FA) enabled.', 'two-factor-2fa-via-email') .'<br />'. __('Please check your email inbox (including Spam/Junk) for your unique login link.', 'two-factor-2fa-via-email') .'</p>
					<p><small id="timertext">'. sprintf( wp_kses( __('The unique link will expire in <span id="timer" data-minutes="%1$s">%1$s minutes</span>.', 'two-factor-2fa-via-email'), ['span' => ['id' => true, 'data-minutes' => true]]), $this->expires) .'</small></p>
				');
				
			}
			else {
				
				$this->outputPage('
					<p><strong>'. __('Email Error', 'two-factor-2fa-via-email') .'</strong><p>
					<p>'. __('This account has Two Factor Authentication (2FA) enabled.', 'two-factor-2fa-via-email') .'<br />'. __('The website was unable to send the verification email. Please try again or contact the website owner.', 'two-factor-2fa-via-email') .'</p>
				');
				
			}

			exit;

		}

	}

    public function isEnabled($uID, $type = 'LOGIN') {

        if($type==='API') {

            $DisabledForAll = (defined('SS88_2FAVE_API_DISABLE_ALL') && SS88_2FAVE_API_DISABLE_ALL) ? true : false;
            if($DisabledForAll) return false;

            $doesMetaExist = metadata_exists('user', $uID, 'SS88_2FAVE_Enabled_API');
            if(!$doesMetaExist) return true;

            $meta_isEnabled = get_user_meta($uID, 'SS88_2FAVE_Enabled_API', true);

        }
        else {

            $EnabledForAll = (defined('SS88_2FAVE_ENABLE_ALL') && SS88_2FAVE_ENABLE_ALL) ? true : false;
            $EnabledForAdmins = (defined('SS88_2FAVE_ENABLE_ADMINS') && SS88_2FAVE_ENABLE_ADMINS) ? true : false;
            $EnabledForEditors = (defined('SS88_2FAVE_ENABLE_EDITORS') && SS88_2FAVE_ENABLE_EDITORS) ? true : false;
            $EnabledForContributors = (defined('SS88_2FAVE_ENABLE_CONTRIBUTORS') && SS88_2FAVE_ENABLE_CONTRIBUTORS) ? true : false;
            $EnabledForSubscribers = (defined('SS88_2FAVE_ENABLE_SUBSCRIBERS') && SS88_2FAVE_ENABLE_SUBSCRIBERS) ? true : false;
    
            if($EnabledForAll) return true;
            if($EnabledForAdmins && user_can($uID, 'administrator')) return true;
            if($EnabledForEditors && user_can($uID, 'editor')) return true;
            if($EnabledForContributors && user_can($uID, 'contributor')) return true;
            if($EnabledForSubscribers && user_can($uID, 'subscriber')) return true;

            $meta_isEnabled = get_user_meta($uID, 'SS88_2FAVE_Enabled', true);

        }

        $final_isEnabled = apply_filters('SS88_2FAVE_isEnabled', $meta_isEnabled, $uID, $type);

        return $final_isEnabled;

    }

	public function generateToken($uID) {

		$Token = bin2hex($this->random_bytes(20));

		update_user_meta($uID, 'SS882FAEmail_timestamp', time());
		update_user_meta($uID, 'SS882FAEmail_token', $Token);

		return $Token;

	}

	function emailToken($U) {

		$rememberme = isset($_REQUEST['rememberme']) ? 1 : 0;

		add_filter('wp_mail', [$this, 'wp_mail_override'], PHP_INT_MAX, 1);

		$Token = $this->generateToken($U->ID);
        $redirect_to = filter_input(INPUT_POST, 'redirect_to', FILTER_SANITIZE_URL);

        $GeneratedIV = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $Encrypted = openssl_encrypt(http_build_query(['t'=> $Token, 'u'=> $U->ID, 'r'=> $redirect_to, 'ua' => md5($_SERVER['HTTP_USER_AGENT']), 'rm' => $rememberme]), $this->cipher, wp_salt(), OPENSSL_RAW_DATA, $GeneratedIV);
        $EncryptedWithIV = base64_encode($GeneratedIV . $Encrypted);

        $LoginLink = add_query_arg(['token'=>urlencode($EncryptedWithIV)], site_url('wp-login.php', 'login'));

		$this->email_tags = [
			'name' => $U->display_name,
			'url' => $LoginLink,
		];

		return wp_mail($U->user_email, __('Here is your one-click login link', 'two-factor-2fa-via-email'), '', ['Content-Type: text/html; charset=UTF-8']);

	}

	function wp_mail_override($args) {

		$Tags = $this->email_tags;

		ob_start();
		require_once $this->findEmailTemplate();
		$the_email = ob_get_clean();

		if(!empty($the_email)) {

			$args['message'] = $the_email;

		}

		return $args;
	}

	function userOptions($U) {

        $isEnabled = $this->isEnabled($U->ID);
        $isAPIEnabled = $this->isEnabled($U->ID, 'API');
        $isChecked = ($isEnabled) ? 'checked="checked"' : '';
        $isCheckedAPI = ($isAPIEnabled) ? 'checked="checked"' : '';

		?>

<h2>Two-Factor (2FA) Authentication via Email</h2>
<div id="ss882faemail-table-wrapper">
    <table class="form-table" role="presentation" id="ss882faemail-table">
        <tbody>
            <tr>
                <th><?php echo __('Enabled 2FA?', 'two-factor-2fa-via-email'); ?></th>
                <td>
                    <input type="checkbox" name="ss882fa_email_enabled" id="ss882fa_email_enabled" <?php echo esc_attr($isChecked); ?> /><label for="ss882fa_email_enabled">Toggle</label>
                </td>
            </tr>
            <tr>
                <th><?php echo __('Enable 2FA for REST API?', 'two-factor-2fa-via-email'); ?></th>
                <td>
                    <input type="checkbox" name="ss882fa_api_enabled" id="ss882fa_api_enabled" <?php echo esc_attr($isCheckedAPI); ?> /><label for="ss882fa_api_enabled">Toggle</label>
                </td>
            </tr>
        </tbody>
    </table>
</div>

		<?php

	}

	function userOptionsUpdate($uID) {

        $isEnabled = isset($_POST['ss882fa_email_enabled']) ? 1 : 0;
        $isEnabledAPI = isset($_POST['ss882fa_api_enabled']) ? 1 : 0;

        update_user_meta($uID, 'SS88_2FAVE_Enabled', $isEnabled);
        update_user_meta($uID, 'SS88_2FAVE_Enabled_API', $isEnabledAPI);

	}

    function outputPage($HTML) {

		$customPage = get_template_directory() . '/ss88-2fa-page.php';
		$customPageInThemeDirectory = get_template_directory() . '/ss88-2fa/2fa-page.php';

		$result = (file_exists($customPage)) ? $customPage : plugin_dir_path(__FILE__) . 'assets/html/2fa-page.php';
		$result = (file_exists($customPageInThemeDirectory)) ? $customPageInThemeDirectory : $result;

        include_once $result;

        exit;

    }

    function findEmailTemplate() {

		$customEmailInThemeDirectory = get_template_directory() . '/ss88-2fa/login-email.php';

		$result = (file_exists($customEmailInThemeDirectory)) ? $customEmailInThemeDirectory : plugin_dir_path(__FILE__) . 'assets/html/login-email.php';

        return $result;

    }

	function deactivated_plugin($plugin) {

		if($plugin==plugin_basename(__FILE__)) {

			$AdminEmail = defined('SS88_2FAVE_NOTIFICATION_EMAIL') ? SS88_2FAVE_NOTIFICATION_EMAIL : get_option('admin_email');
			$AdminUser = get_user_by('email', $AdminEmail);
			$User = wp_get_current_user();

			$Tags = [
				'url' => add_query_arg(['s'=>'Two Factor (2FA) Authentication via Email'], get_admin_url(NULL, 'plugins.php')),
				'name' => $User->display_name,
				'email' => $User->user_email,
				'username' => $User->user_login,
				'hello' => (!empty($AdminUser) && isset($AdminUser->first_name)) ? $AdminUser->first_name : ''
			];

			ob_start();
			require_once(plugin_dir_path(__FILE__) . 'assets/html/plugin-deactivated.php');
			$the_email = ob_get_clean();

			wp_mail($AdminEmail, __('2FA Plugin was deactivated!', 'two-factor-2fa-via-email'), $the_email, ['Content-Type: text/html; charset=UTF-8', 'X-Priority: 1 (Highest)', 'X-MSMail-Priority: High', 'Importance: High']);

		}

	}

    function ss882fave_is_user_logged_in() {
        wp_send_json(['logged_in' => is_user_logged_in()]);
    }

    function plugin_action_links($actions) {
        $mylinks = [
            '<a href="https://wordpress.org/support/plugin/two-factor-2fa-via-email/" target="_blank">'. __('Need help?', 'two-factor-2fa-via-email') .'</a>',
        ];
        return array_merge( $actions, $mylinks );
    }

	function debug($msg) {

		error_log("\n" . '[' . date('Y-m-d H:i:s') . '] ' .  $msg, 3, plugin_dir_path(__FILE__) . 'debug.log');

	}

}

add_action('plugins_loaded', ['SS88_2FAVE', 'init']);