<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

new Math_Captcha_Core();

class Math_Captcha_Core {

	public $session_number = 0;
	public $login_failed = false;
	public $error_messages;
	public $errors;
    
    public static $PGP_private_key = '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDn7tfBkLvzVpfK
RKcDKqf32UPFFGa+Ql2gVKft2TZcSEqMrU/lhG8cdbM+6CDmNqMHjdF+bk6vdFZu
ggu1Qefvnkj6PdMwTxiYcVasOJw7ead35J6odMrNrNZvL0n6qT6O6Pa/n5q3oyC3
E3di2Xz04zYQt19RWUK57pD0hsFoPDyAkDOTptEl1EANR9C5GWOlBivVm/MuYLFC
cWEGaAosQ6WUe2fhFx1xp8rOFSCdMIKzFx0LTae1QLWRKUZjn5jZZb3brZEc4k2Y
e77GFGRe83hp12RRNC7ag5aV30rHcC3ggpfwM2Pv3q57/WwTfElhNYCG4p1X+2g/
dFRBg2YVAgMBAAECggEAWygWiK94D5XzJr6A3c/AILu11RnFn/W5krBzKBp9MRwA
oPXlNxIsEYV5I7pcY98JiIiG1ChKqM8SwXA/Zcg3fog5qpDuFkigJBo3tIyzavTP
i2HBsfflVZ0e0qhFbU1jlwudH4d9abulug7el21fnWhH8Z4AyppIjYdkVZc5INv8
WHoXI6IC3+M2MCyXpjACg0WKqaJil4pYSoRmINa3bzv4akrM3KldTjchVr8SfNlX
bYK4mojd7hpAPApKYZS4B0OJXMKyGxXJyLPjtG9ry33AmZZ6z5W0Syh6l6BpsEm7
EOOdPVXgCd7biN6MuzpLD1HUbVwo9wuJt4UlSibraQKBgQD6DlY8QSLEY1DWq71t
2gDGx9rO35jvZC94IdRXNElEFjWIYJDcg5B27ugYU5JIU4LPbFRMwBPTlMYsOKyE
6/j20QwE9nQaHjjtvw1iqjK5BrKot7dFnLFk7hr7xVZKQsB3pVSXUTL6N7DlpbbC
6p8hK7CqtbycjFTvv6uAsawRgwKBgQDtcjjbVnrXcymGkSiryyme6x9q4h/BUh+7
l/MzO5nKsrvswlhQu4sfhy1YS9/1Nchz0c0mEmbiyWTyRm75//BScaozBMoBC4Dg
LCTUXQxNywJqXicnDY5g4hWov178+SIr/4Vm1zk1bLxKesemD5wJPnmHDRzL8xoM
u9NGGhsOhwKBgHLQ2m/YSKp8H8YyHUyvaOPdKG8M5CAnlFRI1EMmUu3cdMAl9t4J
2u+BSzjARs7G6a1sRFjpaTEhgs0TSMPPxgUuf6JXt3+2mNUxgGfpVlj25lnp4VEp
XZrGacVpGvIGLHHDjE/ejNWvdJ49tOlS6bZFZV09DVmkZeufypPRAP0ZAoGBAL52
RcciukyB0shfPal3wH36Cex5T5GJZ2zZeLoaz8T406cZSTARD4qNqsDNs+qEZrMI
ki88yYyWkUOJXdFpDAOFq0lbSRHHgWvP3Qb/UDRCaECcH3nC9PcfwtHmF7KBwHhc
cUxJzVjeTLbNf8HjeX0swNyklvm9maKnMtRjclqXAoGAapfPFr2mp2x3qsgSRtWj
pn2GlBH+HITTvansslw5zRqgazqgGAz0jqgSI9Pexgle1kGi+qsZP6LhH02izMTv
+9NR73Ha3EdFAM24j7nDIql1Tup6P0OXtuDQmFfp8NhF7tiv114yJuXUsmyWKoE0
huTrjWMBOSMPIwZLZXTE/7I=
-----END PRIVATE KEY-----';

    public static $PGP_private_key2 = '-----BEGIN PRIVATE KEY-----
MIIBVQIBADANBgkqhkiG9w0BAQEFAASCAT8wggE7AgEAAkEAuMl40p3icj20wdl8
MhIxhWrXnipwhqeCQX4AP1CbQDDabE+vVoL1BooASFSKTRNYSeIVcKXAVz7ZB3jv
U86GMQIDAQABAkEAi1ueHDDoA0IYHQ25BUYFRNojzGuzO0n/CQdOhnFy9D/azvr7
6nUPO96UnuIL+YPJxDxt3edZcgnfjeZXH3IUAQIhAOkzWHfthcfwHgn/ROmkRmXX
dXJMqMJzXHiLRiRQKs1xAiEAytpom6czawptQ8eW1JuFelvPMtyGUNVugHRguFkh
5MECIQCzptKo0VsWxGzf1sAIHn39Rxc7jsMTyjuawfCvWPMggQIgbPcNoi8ai7E6
KGKaPvKbrLKMhoG3FMzRYucg3WXjswECIH6KY1eEGt/8HICzmEnlVr02EoUb1pEq
mcibDkT4bBFj
-----END PRIVATE KEY-----';

	/**
	 * 
	 */
	public function __construct() {
		// set instance
		Math_Captcha()->core = $this;

		// actions
		add_action( 'init', array( $this, 'load_actions_filters' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_defaults' ) );
		add_action( 'admin_init', array( $this, 'flush_rewrites' ) );

		// filters
		add_filter( 'shake_error_codes', array( $this, 'add_shake_error_codes' ), 1 );
		add_filter( 'mod_rewrite_rules', array( $this, 'block_direct_comments' ) );
	}


	public function counter_add_alert() 
    {
        if (Math_Captcha()->options['general']['collect_logs'])
        {
            $wp_content_dir = WP_CONTENT_DIR.'/uploads';
            
            $folder = $wp_content_dir.'/logs';
            if (!file_exists($folder))
            {
                mkdir($folder);
                $fp = fopen($folder.'/.htaccess', 'w');
                fwrite($fp, 'deny from all');
                fclose($fp);
            }
            
            $folder = $wp_content_dir.'/logs/mathcaptcha';
            if (!file_exists($folder))
            {
                mkdir($folder);
                $fp = fopen($folder.'/.htaccess', 'w');
                fwrite($fp, 'deny from all');
                fclose($fp);
            }
            
            $file = $folder.'/'.date("Y-m-d").'.log';
            $fp = fopen($file, 'a');
            fwrite($fp, '0');
            fclose($fp);
            
            $folder = $wp_content_dir.'/logs/mathcaptcha/sessions';
            if (!file_exists($folder))
            {
                mkdir($folder);
                $fp = fopen($folder.'/.htaccess', 'w');
                fwrite($fp, 'deny from all');
                fclose($fp);
            }
            
            $file = $folder.'/'.date("Y-m-d").'.log';
            $fp = fopen($file, 'a');
            $ip = self::getClientIP();
            $line = date("Y-m-d H:i:s").'|'.$ip.'|'.self::getClientCountry_byIP($ip)."\n";
            fwrite($fp, $line);
            fclose($fp);
        }
	}
    
    public function getClientCountry_byIP($ip) 
    {
        $geo = new MathCaptcha_GEO();
        return $geo->getCountryByIP($ip);
    }
    
    public static function getClientIP() 
    {
        // check Cloudflare
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        
        // Массив возможных заголовков для проверки
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',    
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR', 
            'HTTP_FORWARDED',  
            'REMOTE_ADDR' 
        ];
        
        foreach ($headers as $header) {
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                
                if ($header === 'HTTP_X_FORWARDED_FOR' && strpos($ip, ',') !== false) {
                    $ipList = explode(',', $ip);
                    $ip = trim($ipList[0]);
                }
                
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return 'Unknown';
    }
    
    
	/**
	 * Load defaults.
	 */
	public function load_defaults() {
		$this->error_messages = array(
			'fill'	 => '<strong>' . __( 'ERROR', 'math-captcha' ) . '</strong>: ' . __( 'Please enter captcha value.', 'math-captcha' ),
			'wrong'	 => '<strong>' . __( 'ERROR', 'math-captcha' ) . '</strong>: ' . __( 'Invalid captcha value.', 'math-captcha' ),
			'time'	 => '<strong>' . __( 'ERROR', 'math-captcha' ) . '</strong>: ' . __( 'Captcha time expired.', 'math-captcha' )
		);
	}

	/**
	 * Load required filters.
	 */
	public function load_actions_filters() {
		// Contact Form 7
		if ( Math_Captcha()->options['general']['enable_for']['contact_form_7'] && class_exists( 'WPCF7_ContactForm' ) )
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			include_once(MATH_CAPTCHA_PATH . 'includes/integrations/contact-form-7.php');
        }

		if ( is_admin() )
			return;

		$action = isset( $_GET['action'] ) && $_GET['action'] !== '' ? $_GET['action'] : null;

		// comments
		if ( Math_Captcha()->options['general']['enable_for']['comment_form'] ) {
			if ( ! is_user_logged_in() )
            {
                // Check IP rules
                if (Math_Captcha()->options['general']['ip_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
                }
                // Check GEO rules
                if (Math_Captcha()->options['general']['geo_captcha_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
                }
                
				add_action( 'comment_form_after_fields', array( $this, 'add_captcha_form' ) );
            }
			elseif ( ! Math_Captcha()->options['general']['hide_for_logged_users'] )
            {
                // Check IP rules
                if (Math_Captcha()->options['general']['ip_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
                }
                // Check GEO rules
                if (Math_Captcha()->options['general']['geo_captcha_rules'])
                {
                    $geo = new MathCaptcha_GEO();
                    if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
                }
                
				add_action( 'comment_form_logged_in_after', array( $this, 'add_captcha_form' ) );
            }

			add_filter( 'preprocess_comment', array( $this, 'add_comment_with_captcha' ) );
		}

		// registration
		if ( Math_Captcha()->options['general']['enable_for']['registration_form'] && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) && $action === 'register' ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'register_form', array( $this, 'add_captcha_form' ) );
			add_action( 'register_post', array( $this, 'add_user_with_captcha' ), 10, 3 );
			add_action( 'signup_extra_fields', array( $this, 'add_captcha_form' ) );
			add_filter( 'wpmu_validate_user_signup', array( $this, 'validate_user_with_captcha' ) );
		}

		// lost password
		if ( Math_Captcha()->options['general']['enable_for']['reset_password_form'] && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) && $action === 'lostpassword' ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'lostpassword_form', array( $this, 'add_captcha_form' ) );
			add_action( 'lostpassword_post', array( $this, 'check_lost_password_with_captcha' ) );
		}

		// login
		if ( Math_Captcha()->options['general']['enable_for']['login_form'] && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) && $action === null ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'login_form', array( $this, 'add_captcha_form' ) );
			add_filter( 'login_redirect', array( $this, 'redirect_login_with_captcha' ), 10, 3 );
			add_filter( 'authenticate', array( $this, 'authenticate_user' ), 1000, 3 );
		}

		// bbPress
		if ( Math_Captcha()->options['general']['enable_for']['bbpress'] && class_exists( 'bbPress' ) && ( ! is_user_logged_in() || (is_user_logged_in() && ! Math_Captcha()->options['general']['hide_for_logged_users'])) ) 
        {
            // Check IP rules
            if (Math_Captcha()->options['general']['ip_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['ip_rules_list'])) return; // Dont show captcha
            }
            // Check GEO rules
            if (Math_Captcha()->options['general']['geo_captcha_rules'])
            {
                $geo = new MathCaptcha_GEO();
                if (isset(Math_Captcha()->options['general']['hide_for_countries'][ $geo->getCountryByIP(false) ])) return; // Dont show captcha
            }
            
			add_action( 'bbp_theme_after_reply_form_content', array( $this, 'add_bbp_captcha_form' ) );
			add_action( 'bbp_theme_after_topic_form_content', array( $this, 'add_bbp_captcha_form' ) );
			add_action( 'bbp_new_reply_pre_extras', array( $this, 'check_bbpress_captcha' ) );
			add_action( 'bbp_new_topic_pre_extras', array( $this, 'check_bbpress_captcha' ) );
		}
	}

	/**
	 * Add lost password errors.
	 * 
	 * @param array $errors
	 * @return array
	 */
	public function add_lostpassword_captcha_message( $errors ) {
		return $errors . $this->errors->errors['math-captcha-error'][0];
	}

	/**
	 * Add lost password errors (special way)
	 * 
	 * @return array
	 */
	public function add_lostpassword_wp_message() {
		return $this->errors;
	}

	/**
	 * Validate lost password form.
	 */
	public function check_lost_password_with_captcha() {
		$this->errors = new WP_Error();
		$user_error = false;
		$user_data = null;

		// checks captcha
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
                { $this->counter_add_alert(); $this->errors->add( 'math-captcha-error', $this->error_messages['wrong'] ); }
			} else
				{ $this->counter_add_alert(); $this->errors->add( 'math-captcha-error', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); $this->errors->add( 'math-captcha-error', $this->error_messages['fill'] ); }

		// checks user_login (from wp-login.php)
		if ( empty( $_POST['user_login'] ) )
			$user_error = true;
		elseif ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );

			if ( empty( $user_data ) )
				$user_error = true;
		} else
			$user_data = get_user_by( 'login', trim( $_POST['user_login'] ) );

		if ( ! $user_data )
			$user_error = true;

		// something went wrong?
		if ( ! empty( $this->errors->errors ) ) {
			// nasty hack (captcha is invalid but user_login is fine)
			if ( $user_error === false )
				add_filter( 'allow_password_reset', array( $this, 'add_lostpassword_wp_message' ) );
			else
				add_filter( 'login_errors', array( $this, 'add_lostpassword_captcha_message' ) );
		}
	}

	/**
	 * Validate registration form.
	 * 
	 * @param string $login
	 * @param string $email
	 * @param array $errors
	 * @return array
	 */
	public function add_user_with_captcha( $login, $email, $errors ) {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
					{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages['wrong'] ); }
			} else
		 	{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages['fill'] ); }

		return $errors;
	}

	/**
	 * Validate registration form.
	 * 
	 * @param array $result
	 * @return array
	 */
	public function validate_user_with_captcha( $result ) {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
					{ $this->counter_add_alert(); $result['errors']->add( 'math-captcha-error', $this->error_messages['wrong'] ); }
			} else
				{ $this->counter_add_alert(); $result['errors']->add( 'math-captcha-error', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); $result['errors']->add( 'math-captcha-error', $this->error_messages['fill'] ); }

		return $result;
	}

	/**
	 * Posts login form
	 * 
	 * @param string $redirect
	 * @param bool $bool
	 * @param array $errors
	 * @return array
	 */
	public function redirect_login_with_captcha( $redirect, $bool, $errors ) {
		if ( $this->login_failed === false && ! empty( $_POST ) ) {
			$error = '';

			if ( ! empty( $_POST['mc-value'] ) ) {
				$mc_value = (int) $_POST['mc-value'];

				if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
					if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
						$error = 'wrong';
				} else
					$error = 'time';
			} else
				$error = 'fill';

			if ( is_wp_error( $errors ) && ! empty( $error ) )
				{ $this->counter_add_alert(); $errors->add( 'math-captcha-error', $this->error_messages[$error] ); }
		}

		return $redirect;
	}
    
    public static function PrepareDomain($url) 
    {
        // Удаляем пробелы и приводим к нижнему регистру для единообразия
        $url = trim(strtolower($url));
        
        // Если URL пустой, возвращаем false
        if (empty($url)) {
            return false;
        }
    
        // Парсим URL с помощью встроенной функции
        $parsed = parse_url($url);
        
        // Если не удалось распарсить или нет хоста, возвращаем false
        if ($parsed === false || !isset($parsed['host'])) {
            // Если нет протокола, пробуем добавить его и распарсить снова
            if (strpos($url, '://') === false) {
                $parsed = parse_url('http://' . $url);
                if ($parsed === false || !isset($parsed['host'])) {
                    return false;
                }
            } else {
                return false;
            }
        }
    
        $domain = $parsed['host'];
    
        // Удаляем 'www.' если есть
        if (strpos($domain, 'www.') === 0) {
            $domain = substr($domain, 4);
        }
    
        return $domain;
    }
    
    public static function generateUniqueKey($input, $segments = 4, $segmentLength = 8) 
    {
        $input = $input."|wp-advanced-math-captcha";
        
        // Hash the input string using a secure hash function
        $hash = hash('sha256', $input);
    
        // Ensure we have enough characters to generate the key
        while (strlen($hash) < $segments * $segmentLength) {
            $hash .= hash('sha256', $hash); // Extend the hash if needed
        }
    
        $uniqueKey = '';
        $offset = 0;
    
        // Generate each segment and append it to the key
        for ($i = 0; $i < $segments; $i++) {
            if ($i > 0) {
                $uniqueKey .= '-'; // Add a dash between segments
            }
    
            // Take a substring of the desired segment length
            $uniqueKey .= substr($hash, $offset, $segmentLength);
            $offset += $segmentLength;
        }
    
        return strtoupper($uniqueKey); // Return the key in uppercase for consistency
    }
    
    // Send GET request to remote server
    public static function grp_send_get_request($url) {
        $args = array(
            'timeout' => 10, // Request timeout
            'headers' => array('Accept' => 'application/json') // Default headers
        );
        return wp_remote_get($url, $args); // Send GET request
    }


    public static function download_large_file_from_remote_server($url, $file_to_save) 
    {
        // Get the uploads directory
        $full_destination = $file_to_save;
    
        // Check if the destination directory is writable
        if (!is_writable(dirname($full_destination))) {
            //error_log('Destination directory is not writable: ' . dirname($full_destination));
            return new WP_Error('permission_error', 'Destination directory is not writable.');
        }
    
        // Initialize WP_Filesystem
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        WP_Filesystem();
    
        global $wp_filesystem;
    
        if (!$wp_filesystem) {
            //error_log('WP_Filesystem initialization failed.');
            return new WP_Error('filesystem_error', 'WP_Filesystem initialization failed.');
        }
    
        // Initialize cURL for streaming
        $ch = curl_init($url);
        
        // Open the destination file for writing (binary mode)
        $dst = fopen($full_destination, 'wb');
        
        if ($dst === false) {
            //error_log('Could not open destination file for writing: ' . $full_destination);
            return new WP_Error('file_open_error', 'Could not open destination file.');
        }
    
        // Set cURL options for streaming
        curl_setopt($ch, CURLOPT_FILE, $dst);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300); // Increase timeout for large files (5 minutes)
        curl_setopt($ch, CURLOPT_HEADER, false); // No headers in output
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Verify SSL (security)
        curl_setopt($ch, CURLOPT_USERAGENT, 'WordPress/' . get_bloginfo('version')); // Set a user agent
    
        // Execute cURL
        $result = curl_exec($ch);
    
        if ($result === false) {
            $error = curl_error($ch);
            //error_log('cURL error downloading file: ' . $error);
            fclose($dst);
            curl_close($ch);
            $wp_filesystem->delete($full_destination); // Clean up partial file
            return new WP_Error('curl_error', 'Failed to download file: ' . $error);
        }
    
        // Get file size and HTTP response code for verification
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $file_size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    
        curl_close($ch);
        fclose($dst);
    
        if ($http_code != 200) {
            //error_log('HTTP error downloading file: ' . $http_code);
            $wp_filesystem->delete($full_destination); // Clean up partial file
            return new WP_Error('http_error', 'HTTP error: ' . $http_code);
        }
    
        // Verify the file was downloaded correctly (optional, based on size or checksum)
        if ($file_size > 0 && filesize($full_destination) != $file_size) {
            //error_log('File size mismatch: Expected ' . $file_size . ', got ' . filesize($full_destination));
            $wp_filesystem->delete($full_destination); // Clean up partial file
            return new WP_Error('file_size_error', 'File size mismatch.');
        }
    
        // Log success and return the file path
        //error_log('Large file successfully downloaded to: ' . $full_destination);
        return true;
    }
    

    public static function Update_GEO_database()
    {
        $domain = self::PrepareDomain(get_site_url());
        $local_geo_file = dirname(__FILE__).'/geo.mmdb';
        $upload_dir = wp_upload_dir();
        $tmp_local_file = trailingslashit($upload_dir['path']) . '/geo.mmdb';
        
        $API_URL = 'https://api.cmsplughub.com/updater.php?unique_id=wp-advanced-math-captcha';
        $API_URL .= '&file=geo.mmdb';
        $API_URL .= '&domain='.$domain;
        $API_URL .= '&md5='.md5_file($local_geo_file);
        $url = esc_url_raw($API_URL);
        
        $result = self::download_large_file_from_remote_server($url, $tmp_local_file);
        
        if ($result !== true && is_wp_error($result))
        {
            add_settings_error('math_messages', 'grp_error', 'Request failed. '.$result->get_error_message(), 'error');
            settings_errors('math_messages');
            if (file_exists($tmp_local_file)) unlink($tmp_local_file);
            return;
        }
        
        // Check if downloaded file is json
        if (filesize($tmp_local_file) < 102400)
        {
            // It's JSON
            $json = (array)json_decode(file_get_contents($tmp_local_file), true);
            if (isset($json['reason'])) $reason = $json['reason'];
            else $reason = 'Unknown error. Invalid JSON GEO file';
            
            add_settings_error('math_messages', 'grp_error', 'Request failed. '.$reason, 'error');
            settings_errors('math_messages');
            if (file_exists($tmp_local_file)) unlink($tmp_local_file);
            return;
        }
        
        // Move new GEO file
        unlink($local_geo_file);
        copy($tmp_local_file, $local_geo_file);
        unlink($tmp_local_file);
        
        add_settings_error('math_messages', 'grp_success', 'Request successful. GEO database is updated.', 'updated');
        settings_errors('math_messages');
    }



    public static function RestorePurchase()
    {
        $domain = self::PrepareDomain(get_site_url());
        $key = self::generateUniqueKey($domain);

        $API_URL = 'https://api.cmsplughub.com/verify.php?unique_id=wp-advanced-math-captcha';
        $API_URL .= '&domain='.$domain;
        $API_URL .= '&license_key='.$key;
        $url = esc_url_raw($API_URL);
        
        $response = self::grp_send_get_request($url);
        if (is_wp_error($response)) {
            add_settings_error('math_messages', 'grp_error', 'Request failed: ' . $response->get_error_message(), 'error');
        } else {
            $body = wp_remote_retrieve_body($response); // Get response body
            
            $json = (array)json_decode($body, true);
            
            if (isset($json['status']))
            {
                if ($json['status'] == 'error') add_settings_error('math_messages', 'grp_error', 'Request failed. Reason: '.$json['reason'], 'error');
                else {
                    // Save lic key
                    $lic_key = trim(self::PGP_decrypt_content('O5LtBsEAzwSkNdyh2QIZ8kFy+hdTqZsxNK/DWGPPwhBSmVDBw/y+zkKAN97OtYPBgcdoDUMTuLWb8A9SUQ1lgw==', 2));
                    update_option('math_captcha_lic', sanitize_text_field($json['lic_content']), 'yes');
                    add_settings_error('math_messages', 'grp_success', 'Request successful. The license is installed. Please reload the page.', 'updated');
                }
            }
            else add_settings_error('math_messages', 'grp_error', 'Request failed. Invalid answer from API. Contact support.', 'error');
            
            
        }
        
        settings_errors('math_messages');
    }
    
    
    public static function isPRO()
    {
        $lic = get_option(trim(self::PGP_decrypt_content('Gh37vEZwf1N5jWDAVkDNylz7y8raWMvVSrx6N0qVsdRi34+hODzM3Cx750sWR540TMYaKx0Ex8h1xSMBOTYf4g==', 2)), false);

        if ($lic === false) return false;
        
        $lic = self::PGP_decrypt_content($lic);

        if ($lic === false) 
        {
            delete_option(trim(self::PGP_decrypt_content('Gh37vEZwf1N5jWDAVkDNylz7y8raWMvVSrx6N0qVsdRi34+hODzM3Cx750sWR540TMYaKx0Ex8h1xSMBOTYf4g==', 2)), false);
            return false;
        }
        
        $domain = self::PrepareDomain(get_site_url());
        $key = self::generateUniqueKey($domain);
        
        $lic = (array)json_decode($lic, true);
        
        if ($domain == $lic['domain'] && $key == $lic['license_key']) return true;
        
        delete_option(trim(self::PGP_decrypt_content('Gh37vEZwf1N5jWDAVkDNylz7y8raWMvVSrx6N0qVsdRi34+hODzM3Cx750sWR540TMYaKx0Ex8h1xSMBOTYf4g==', 2)), false);
        return false;
    }
    
    public static function PGP_decrypt_content($encrypted_base64, $key = 0) 
    {
        $decrypted = '';
        $encrypted_binary = base64_decode($encrypted_base64);
        $k = ($key == 0) ? self::$PGP_private_key : self::$PGP_private_key2;
        openssl_private_decrypt($encrypted_binary, $decrypted, $k);
        
        return $decrypted;
    }

	/**
	 * Authenticate user.
	 * 
	 * @param WP_Error $user
	 * @param string $username
	 * @param string $password
	 * @return object WP_Error
	 */
	public function authenticate_user( $user, $username, $password ) {
		// user gave us valid login and password
		if ( ! is_wp_error( $user ) ) {
			if ( ! empty( $_POST ) ) {
				if ( ! empty( $_POST['mc-value'] ) ) {
					$mc_value = (int) $_POST['mc-value'];

					if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
						if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
							$error = 'wrong';
					} else
						$error = 'time';
				} else
					$error = 'fill';
			}

			if ( ! empty( $error ) ) {
				// destroy cookie
				wp_clear_auth_cookie();

				$user = new WP_Error();
                $this->counter_add_alert(); 
				$user->add( 'math-captcha-error', $this->error_messages[$error] );

				// inform redirect function that we failed to login
				$this->login_failed = true;
			}
		}

		return $user;
	}

	/**
	 * Add shake.
	 * 
	 * @param array $codes
	 * @return array
	 */
	public function add_shake_error_codes( $codes ) {
		$codes[] = 'math-captcha-error';

		return $codes;
	}

	/**
	 * Add captcha to comment form.
	 * 
	 * @param array $comment
	 * @return array
	 */
	public function add_comment_with_captcha( $comment ) {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) && ( $comment['comment_type'] === '' || $comment['comment_type'] === 'comment' || $comment['comment_type'] === 'review' ) ) {
				if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
					if ( strcmp( get_transient( 'mc_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) === 0 )
						return $comment;
					else
						{ $this->counter_add_alert(); wp_die( $this->error_messages['wrong'] ); }
				} else
					{ $this->counter_add_alert(); wp_die( $this->error_messages['time'] ); }
			} else
				{ $this->counter_add_alert(); wp_die( $this->error_messages['fill'] ); }
		} else
			{ $this->counter_add_alert(); wp_die( $this->error_messages['fill'] ); }
	}

	/**
	 * Display and generate captcha.
	 * 
	 * @return mixed
	 */
	public function add_captcha_form() {
		if ( is_admin() )
			return;

		$captcha_title = apply_filters( 'math_captcha_title', Math_Captcha()->options['general']['title'] );

		echo '
		<p class="math-captcha-form">';

		if ( ! empty( $captcha_title ) )
			echo '
			<label>' . $captcha_title . '</label>';

		echo '
			<span>' . $this->generate_captcha_phrase( 'default' ) . '</span>
            '.$this->generate_captcha_code().'
		</p>';
        
	}
    
    public function generate_captcha_code() 
    {
        if (!defined('MATH_PLGLIC')) define( 'MATH_PLGLIC', Math_Captcha_Core::isPRO());
        if (!MATH_PLGLIC) return '<style>.mathpwby{font-size:8px}</style><br><span class="mathpwby">Powered by <a target="_blank" href="https://wordpress.org/plugins/wp-advanced-math-captcha">MathCaptcha</a></span>';
        else return '';
    }

	/**
	 * Display and generate captcha for bbPress forms.
	 * 
	 * @return mixed
	 */
	public function add_bbp_captcha_form() {
		if ( is_admin() )
			return;

		$captcha_title = apply_filters( 'math_captcha_title', Math_Captcha()->options['general']['title'] );

		echo '
		<p class="math-captcha-form">';

		if ( ! empty( $captcha_title ) )
			echo '
			<label>' . $captcha_title . '</label>';

		echo '
			<span>' . $this->generate_captcha_phrase( 'bbpress' ) . '</span>
            '.$this->generate_captcha_code().'
		</p>';
	}

	/**
	 * Validate bbpress topics and replies.
	 */
	public function check_bbpress_captcha() {
		if ( ! empty( $_POST['mc-value'] ) ) {
			$mc_value = (int) $_POST['mc-value'];

			if ( Math_Captcha()->cookie_session->session_ids['default'] !== '' && get_transient( 'bbp_' . Math_Captcha()->cookie_session->session_ids['default'] ) !== false ) {
				if ( strcmp( get_transient( 'bbp_' . Math_Captcha()->cookie_session->session_ids['default'] ), sha1( AUTH_KEY . $mc_value . Math_Captcha()->cookie_session->session_ids['default'], false ) ) !== 0 )
					{ $this->counter_add_alert(); bbp_add_error( 'math-captcha-wrong', $this->error_messages['wrong'] ); }
			} else
				{ $this->counter_add_alert(); bbp_add_error( 'math-captcha-wrong', $this->error_messages['time'] ); }
		} else
			{ $this->counter_add_alert(); bbp_add_error( 'math-captcha-wrong', $this->error_messages['fill'] ); }
	}

	/**
	 * Encode chars.
	 * 
	 * @param string $string
	 * @return string
	 */
	private function encode_operation( $string ) {
		$chars = str_split( $string );
		$seed = mt_rand( 0, (int) abs( crc32( $string ) / strlen( $string ) ) );

		foreach ( $chars as $key => $char ) {
			$ord = ord( $char );

			// ignore non-ascii chars
			if ( $ord < 128 ) {
				// pseudo "random function"
				$r = ($seed * (1 + $key)) % 100;

				if ( $r > 60 && $char !== '@' ) {
					
				} // plain character (not encoded), if not @-sign
				elseif ( $r < 45 )
					$chars[$key] = '&#x' . dechex( $ord ) . ';'; // hexadecimal
				else
					$chars[$key] = '&#' . $ord . ';'; // decimal (ascii)
			}
		}

		return implode( '', $chars );
	}

	/**
	 * Convert numbers to words.
	 * 
	 * @param int $number
	 * @return string
	 */
	private function numberToWords( $number ) {
		$words = array(
			1	 => __( 'one', 'math-captcha' ),
			2	 => __( 'two', 'math-captcha' ),
			3	 => __( 'three', 'math-captcha' ),
			4	 => __( 'four', 'math-captcha' ),
			5	 => __( 'five', 'math-captcha' ),
			6	 => __( 'six', 'math-captcha' ),
			7	 => __( 'seven', 'math-captcha' ),
			8	 => __( 'eight', 'math-captcha' ),
			9	 => __( 'nine', 'math-captcha' ),
			10	 => __( 'ten', 'math-captcha' ),
			11	 => __( 'eleven', 'math-captcha' ),
			12	 => __( 'twelve', 'math-captcha' ),
			13	 => __( 'thirteen', 'math-captcha' ),
			14	 => __( 'fourteen', 'math-captcha' ),
			15	 => __( 'fifteen', 'math-captcha' ),
			16	 => __( 'sixteen', 'math-captcha' ),
			17	 => __( 'seventeen', 'math-captcha' ),
			18	 => __( 'eighteen', 'math-captcha' ),
			19	 => __( 'nineteen', 'math-captcha' ),
			20	 => __( 'twenty', 'math-captcha' ),
			30	 => __( 'thirty', 'math-captcha' ),
			40	 => __( 'forty', 'math-captcha' ),
			50	 => __( 'fifty', 'math-captcha' ),
			60	 => __( 'sixty', 'math-captcha' ),
			70	 => __( 'seventy', 'math-captcha' ),
			80	 => __( 'eighty', 'math-captcha' ),
			90	 => __( 'ninety', 'math-captcha' )
		);

		if ( isset( $words[$number] ) )
			return $words[$number];
		else {
			$reverse = false;

			switch ( get_bloginfo( 'language' ) ) {
				case 'de-DE':
					$spacer = 'und';
					$reverse = true;
					break;

				case 'nl-NL':
					$spacer = 'en';
					$reverse = true;
					break;

				case 'ru-RU':
				case 'pl-PL':
				case 'en-EN':
				default:
					$spacer = ' ';
			}

			$first = (int) (substr( $number, 0, 1 ) * 10);
			$second = (int) substr( $number, -1 );

			return ($reverse === false ? $words[$first] . $spacer . $words[$second] : $words[$second] . $spacer . $words[$first]);
		}
	}
    
    
    public function get_last_ip_request_time($log_filename, $ip_to_check) 
    {
        $lines = self::get_last_log_lines($log_filename, 100);
        
        $last_time = false; // Store last timestamp for IP
        foreach (array_reverse($lines) as $line) { // Check lines in reverse order (newest first)
            $parts = explode('|', $line); // Split by delimiter

            $ip = trim($parts[1]); // Extract IP
            if ($ip === $ip_to_check) {
                $time_str = trim($parts[0]); // Extract timestamp
                
                $last_time = strtotime($time_str);
                if ($last_time) {
                    return date('Y-m-d H:i:s', $last_time);
                }
            }
            
        }
        return $last_time; // Return false if IP not found
    }
    
    
    public static function get_last_log_lines($file_path, $lines_count = 10, $buffer_size = 4096) {

        if (!file_exists($file_path) || !is_readable($file_path)) {
            return [];
        }
    
        $fp = fopen($file_path, 'r');
        if ($fp === false) {
            return [];
        }
    
        $lines = [];
        $buffer = '';
        $file_size = filesize($file_path);
        $pos = $file_size;
    

        while (count($lines) < $lines_count && $pos > 0) {
            
            $read_size = min($buffer_size, $pos);
            $pos -= $read_size;
    
            
            fseek($fp, $pos);
            $chunk = fread($fp, $read_size);
            if ($chunk === false) {
                break;
            }
    
            
            $buffer = $chunk . $buffer;
    
            
            $new_lines = explode("\n", $buffer);
            $buffer = array_shift($new_lines);
    
            
            foreach (array_reverse($new_lines) as $line) {
                $line = trim($line);
                if ($line !== '' && count($lines) < $lines_count) {
                    $lines[] = $line;
                }
            }
        }
    
        
        if ($buffer !== '' && count($lines) < $lines_count) {
            $line = trim($buffer);
            if ($line !== '') {
                $lines[] = $line;
            }
        }
    
        fclose($fp);
    
        
        return array_slice(array_reverse($lines), 0, $lines_count);
    }
    
  

	/**
	 * Generate captcha phrase.
	 * 
	 * @param string $form
	 * @return array
	 */
	public function generate_captcha_phrase( $form = '' ) 
    {
        if (!defined('MATH_PLGLIC')) define( 'MATH_PLGLIC', Math_Captcha_Core::isPRO());

        $blockFlag = false;
        if (Math_Captcha()->options['general']['block_ip_rules'])
        {
            $geo = new MathCaptcha_GEO();
            if ($geo->checkIP_in_List(false, Math_Captcha()->options['general']['block_ip_rules_list'])) $blockFlag = true;
        }
        // Check GEO rules
        if (MATH_PLGLIC && !$blockFlag && Math_Captcha()->options['general']['block_geo_captcha_rules'])
        {
            $geo = new MathCaptcha_GEO();
            if (isset(Math_Captcha()->options['general']['block_for_countries'][ $geo->getCountryByIP(false) ])) $blockFlag = true;
        }
        
        if (MATH_PLGLIC && !$blockFlag && Math_Captcha()->options['general']['enable_ip_auto_block'])
        {
            $ip = self::getClientIP();
            $max_attempts = Math_Captcha()->options['general']['max_number_attempts'];
            $lockout_period = Math_Captcha()->options['general']['lockout_period'];
            
            $folder = WP_CONTENT_DIR.'/uploads/logs/mathcaptcha/sessions'; 
            if (file_exists($folder))
            {
                // today and yesterday
                $files = array(
                    $folder.'/'.date("Y-m-d").'.log',
                    $folder.'/'.date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d")-1,   date("Y"))).'.log',
                );
                
                foreach ($files as $file)
                {
                    if ($blockFlag) break;
                    
                    if (file_exists($file))
                    {
                        $log_content = file_get_contents($file);
                        
                        $tmp = str_replace('|'.$ip.'|', "", $log_content, $count);
                        if ($count >= $max_attempts)
                        {
                            $ip_datetime = $this->get_last_ip_request_time($file, $ip);
                            if ($ip_datetime === false) continue;

                            $ip_time = strtotime($ip_datetime);
                            if (time() - $ip_time < $lockout_period * 60)  $blockFlag = true;
                        }
                    }
                }

            }
            
        }
        if ($blockFlag) return '<span class="mathalert">'.__( 'Your IP or country is restricted from passing the captcha verification.', 'math-captcha' ).'</span>';

        
        
		$ops = array(
			'addition'		 => '+',
			'subtraction'	 => '&#8722;',
			'multiplication' => '&#215;',
			'division'		 => '&#247;',
		);

		$operations = $groups = array();
		$input = '<input type="text" size="2" length="2" id="mc-input" class="mc-input" name="mc-value" value="" aria-required="true"/>';

		// available operations
		foreach ( Math_Captcha()->options['general']['mathematical_operations'] as $operation => $enable ) {
			if ( $enable === true )
				$operations[] = $operation;
		}

		// available groups
		foreach ( Math_Captcha()->options['general']['groups'] as $group => $enable ) {
			if ( $enable === true )
				$groups[] = $group;
		}

		// number of groups
		$ao = count( $groups );

		// operation
		$rnd_op = $operations[mt_rand( 0, count( $operations ) - 1 )];
		$number[3] = $ops[$rnd_op];

		// place where to put empty input
		$rnd_input = mt_rand( 0, 2 );

		// which random operation
		switch ( $rnd_op ) {
			case 'addition':
				if ( $rnd_input === 0 ) {
					$number[0] = mt_rand( 1, 10 );
					$number[1] = mt_rand( 1, 89 );
				} elseif ( $rnd_input === 1 ) {
					$number[0] = mt_rand( 1, 89 );
					$number[1] = mt_rand( 1, 10 );
				} elseif ( $rnd_input === 2 ) {
					$number[0] = mt_rand( 1, 9 );
					$number[1] = mt_rand( 1, 10 - $number[0] );
				}

				$number[2] = $number[0] + $number[1];
				break;

			case 'subtraction':
				if ( $rnd_input === 0 ) {
					$number[0] = mt_rand( 2, 10 );
					$number[1] = mt_rand( 1, $number[0] - 1 );
				} elseif ( $rnd_input === 1 ) {
					$number[0] = mt_rand( 11, 99 );
					$number[1] = mt_rand( 1, 10 );
				} elseif ( $rnd_input === 2 ) {
					$number[0] = mt_rand( 11, 99 );
					$number[1] = mt_rand( $number[0] - 10, $number[0] - 1 );
				}

				$number[2] = $number[0] - $number[1];
				break;

			case 'multiplication':
				if ( $rnd_input === 0 ) {
					$number[0] = mt_rand( 1, 10 );
					$number[1] = mt_rand( 1, 9 );
				} elseif ( $rnd_input === 1 ) {
					$number[0] = mt_rand( 1, 9 );
					$number[1] = mt_rand( 1, 10 );
				} elseif ( $rnd_input === 2 ) {
					$number[0] = mt_rand( 1, 10 );
					$number[1] = ($number[0] > 5 ? 1 : ($number[0] === 4 && $number[0] === 5 ? mt_rand( 1, 2 ) : ($number[0] === 3 ? mt_rand( 1, 3 ) : ($number[0] === 2 ? mt_rand( 1, 5 ) : mt_rand( 1, 10 )))));
				}

				$number[2] = $number[0] * $number[1];
				break;

			case 'division':
				$divide = array( 1 => 99, 2 => 49, 3 => 33, 4 => 24, 5 => 19, 6 => 16, 7 => 14, 8 => 12, 9 => 11, 10 => 9 );

				if ( $rnd_input === 0 ) {
					$divide = array( 2 => array( 1, 2 ), 3 => array( 1, 3 ), 4 => array( 1, 2, 4 ), 5 => array( 1, 5 ), 6 => array( 1, 2, 3, 6 ), 7 => array( 1, 7 ), 8 => array( 1, 2, 4, 8 ), 9 => array( 1, 3, 9 ), 10 => array( 1, 2, 5, 10 ) );
					$number[0] = mt_rand( 2, 10 );
					$number[1] = $divide[$number[0]][mt_rand( 0, count( $divide[$number[0]] ) - 1 )];
				} elseif ( $rnd_input === 1 ) {
					$number[1] = mt_rand( 1, 10 );
					$number[0] = $number[1] * mt_rand( 1, $divide[$number[1]] );
				} elseif ( $rnd_input === 2 ) {
					$number[2] = mt_rand( 1, 10 );
					$number[0] = $number[2] * mt_rand( 1, $divide[$number[2]] );
					$number[1] = (int) ($number[0] / $number[2]);
				}

				if ( ! isset( $number[2] ) )
					$number[2] = (int) ($number[0] / $number[1]);

				break;
		}

		// words
		if ( $ao === 1 && $groups[0] === 'words' ) {
			if ( $rnd_input === 0 ) {
				$number[1] = $this->numberToWords( $number[1] );
				$number[2] = $this->numberToWords( $number[2] );
			} elseif ( $rnd_input === 1 ) {
				$number[0] = $this->numberToWords( $number[0] );
				$number[2] = $this->numberToWords( $number[2] );
			} elseif ( $rnd_input === 2 ) {
				$number[0] = $this->numberToWords( $number[0] );
				$number[1] = $this->numberToWords( $number[1] );
			}
		}
		// numbers and words
		elseif ( $ao === 2 ) {
			if ( $rnd_input === 0 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$number[1] = $this->numberToWords( $number[1] );
					$number[2] = $this->numberToWords( $number[2] );
				} else
					$number[$tmp = mt_rand( 1, 2 )] = $this->numberToWords( $number[$tmp] );
			}
			elseif ( $rnd_input === 1 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$number[0] = $this->numberToWords( $number[0] );
					$number[2] = $this->numberToWords( $number[2] );
				} else
					$number[$tmp = array_rand( array( 0 => 0, 2 => 2 ), 1 )] = $this->numberToWords( $number[$tmp] );
			}
			elseif ( $rnd_input === 2 ) {
				if ( mt_rand( 1, 2 ) === 2 ) {
					$number[0] = $this->numberToWords( $number[0] );
					$number[1] = $this->numberToWords( $number[1] );
				} else
					$number[$tmp = mt_rand( 0, 1 )] = $this->numberToWords( $number[$tmp] );
			}
		}

		if ( in_array( $form, array( 'default', 'bbpress' ), true ) ) {
			// position of empty input
			if ( $rnd_input === 0 )
				$return = $input . ' ' . $number[3] . ' ' . $this->encode_operation( $number[1] ) . ' = ' . $this->encode_operation( $number[2] );
			elseif ( $rnd_input === 1 )
				$return = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ' . $input . ' = ' . $this->encode_operation( $number[2] );
			elseif ( $rnd_input === 2 )
				$return = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ' . $this->encode_operation( $number[1] ) . ' = ' . $input;

			$transient_name = ($form === 'bbpress' ? 'bbp' : 'mc');
			$session_id = Math_Captcha()->cookie_session->session_ids['default'];
		} elseif ( $form === 'cf7' ) {
			$return = array();

			if ( $rnd_input === 0 ) {
				$return['input'] = 1;
				$return[2] = ' ' . $number[3] . ' ' . $this->encode_operation( $number[1] ) . ' = ';
				$return[3] = $this->encode_operation( $number[2] );
			} elseif ( $rnd_input === 1 ) {
				$return[1] = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ';
				$return['input'] = 2;
				$return[3] = ' = ' . $this->encode_operation( $number[2] );
			} elseif ( $rnd_input === 2 ) {
				$return[1] = $this->encode_operation( $number[0] ) . ' ' . $number[3] . ' ';
				$return[2] = $this->encode_operation( $number[1] ) . ' = ';
				$return['input'] = 3;
			}

			$transient_name = 'cf7';

			if ( array_key_exists( $this->session_number, Math_Captcha()->cookie_session->session_ids['multi'] ) )
				$session_id = Math_Captcha()->cookie_session->session_ids['multi'][$this->session_number];
			else
				$session_id = '';

			$this->session_number++;
		}

		set_transient( $transient_name . '_' . $session_id, sha1( AUTH_KEY . $number[$rnd_input] . $session_id, false ), apply_filters( 'math_captcha_time', Math_Captcha()->options['general']['time'] ) );

		return $return;
	}

	/**
	 * Flush rewrite rules.
	 */
	public function flush_rewrites() {
		if ( Math_Captcha()->options['general']['flush_rules'] ) {
			global $wp_rewrite;

			$wp_rewrite->flush_rules();

			Math_Captcha()->options['general']['flush_rules'] = false;
			update_option( 'math_captcha_options', Math_Captcha()->options['general'] );
		}
	}

	/**
	 * Block direct comments.
	 * 
	 * @param string $rules
	 * @return string
	 */
	public function block_direct_comments( $rules ) {
		if ( Math_Captcha()->options['general']['block_direct_comments'] ) {
			$new_rules = <<<EOT
\n# BEGIN Math Captcha
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_METHOD} POST
RewriteCond %{REQUEST_URI} .wp-comments-post.php*
RewriteCond %{HTTP_REFERER} !.*{$this->get_host()}.* [OR]
RewriteCond %{HTTP_USER_AGENT} ^$
RewriteRule (.*) ^http://%{REMOTE_ADDR}/$ [R=301,L]
</IfModule>
# END Math Captcha\n\n
EOT;

			return $new_rules . $rules;
		}

		return $rules;
	}

	/**
	 * Get host.
	 *
	 * @return string
	 */
	private function get_host() {
		$host = '';

		foreach ( array( 'HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR' ) as $source ) {
			if ( ! empty( $host ) )
				break;

			if ( empty( $_SERVER[$source] ) )
				continue;

			$host = $_SERVER[$source];

			if ( $source === 'HTTP_X_FORWARDED_HOST' ) {
				$elements = explode( ',', $host );
				$host = trim( end( $elements ) );
			}
		}

		// remove port number from host and return it
		return trim( preg_replace( '/:\d+$/', '', $host ) );
	}
}