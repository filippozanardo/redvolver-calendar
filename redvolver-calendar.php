<?php
/**
 * Plugin Name: Redvolver Calendar
 * Plugin URI: http://redvolver.it/
 * Description: Redvolver Calendar Rocks!
 * Version: 1.0.0
 * Author: Redvolver
 * Author URI: https://redvolver.com/
 * Requires at least: 4.0
 * Tested up to: 5.7.1
 * Text Domain: redvolver
 * Domain Path: /languages/
 * License: GPL3+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( __DIR__ . '/vendor/autoload.php' );


class RV_Calendar {
	/**
	 * The single instance of the class.
	 */
	private static $_instance = null;

	public $asana;

	public $roles;

	/**
	 * Main Instance.
	 *
	 * Ensures only one instance is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();

		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {



		$this->setup_constants();
		$this->includes();
		$this->init_hooks();
		//$this->cron_setup();
		$this->roles = new Redvolver_Roles();
		$this->db = \WeDevs\ORM\Eloquent\Database::instance();
		// $params = array(
	  //   'database'  => 'calendar2020',
	  //   'username'  => 'root',
	  //   'password'  => 'root',
	  //   'prefix'    => 'wp_' // default prefix is 'wp_', you can change to your own prefix
		// );
		// $this->db = Corcel\Database::connect($params);
		//$this->asana = new Redvolver_Asana_DB();

		do_action( 'rv_loaded' );
	}

	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'redvolver' ), '1' );
	}

	/**
	 * Disable unserializing of the class.
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'redvolver' ), '1' );
	}

	private function setup_constants() {

		if ( ! defined( 'RVC_VERSION' ) ) {
			define( 'RVC_VERSION', '1.0.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'RVC_PLUGIN_DIR' ) ) {
			define( 'RVC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		//var_dump(RVC_PLUGIN_DIR);

		// Plugin Folder URL.
		if ( ! defined( 'RVC_PLUGIN_URL' ) ) {
			define( 'RVC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		//var_dump(RVC_PLUGIN_URL);
	}

	private function init_hooks() {

		// Activation - works with symlinks
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( $this, 'activate' ) );

		register_uninstall_hook(basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( 'RV_Calendar', 'deactivate' ));

		//add_filter('cron_schedules',array( $this, 'rv_cron_schedules' ) );
		add_filter('acf/settings/path', array( $this, 'rv_acf_settings_path' ) );
		add_filter('acf/settings/dir', array( $this, 'rv_acf_settings_dir') );
		add_filter('acf/settings/save_json', array( $this, 'rv_acf_json_save_point') );
		add_filter('acf/settings/load_json', array( $this, 'rv_acf_json_load_point') );

		//add_action( 'after_setup_theme', array( $this, 'carbon_boot' ) );
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action('rvc_send_notifaction', array( $this, 'rvc_send_notifaction') );
		//add_filter('acf/settings/show_admin', '__return_false');
		//add_action('wp_enqueue_scripts', array( $this, 'remove_default_styles'),100);
		add_filter('show_admin_bar', '__return_false');

		if( function_exists('acf_add_options_page') ) {

			acf_add_options_page(array(
				'page_title' 	=> 'RV Calendar Settings',
				'menu_title' 	=> 'RV Calendar Settings',
				'menu_slug' 	=> 'rvc-settings',
				'capability' 	=> 'edit_posts',
				'redirect' 	=> false,
				//'parent_slug' => 'edit.php?post_type=rvc'
			));

		}

	}
	public function rv_cron_schedules($schedules) {
		if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes'));
    }
    return $schedules;
	}

	public function remove_default_styles() {
		global $wp_styles;

		foreach ($wp_styles->registered as $handle => $data)
		{
			//var_dump($handle);
			// remove it
			//wp_deregister_style($handle);
			//wp_dequeue_style($handle);
		}
	}
	public function rv_acf_json_save_point( $path ) {

	    // update path
	    $path = RVC_PLUGIN_DIR . 'acf-json';

	    return $path;

	}

	public function rv_acf_json_load_point( $paths ) {

		$paths[] = RVC_PLUGIN_DIR . 'acf-json';

    return $paths;
	}

	public function rv_acf_settings_path() {
		 $path = RVC_PLUGIN_DIR . 'acf/';

    	// return
    	return $path;
	}

	public function rv_acf_settings_dir( $dir ) {

	    // update path
	    $dir = RVC_PLUGIN_URL . 'acf/';

	    // return
	    return $dir;

	}

	public function activate() {
		$this->post_types->register_post_types();
		//$this->cron_setup();
		$this->rvdb->insert_tables();

		$roles = new Redvolver_Roles;
		$roles->add_roles();
		// $roles->add_caps();

		flush_rewrite_rules();
	}

	public function deactivate() {
		//$this->cron_stop();
	}

	public function cron_setup( ) {

		if (! wp_next_scheduled ( 'rvc_send_notifaction' )) {
			//wp_schedule_event(time(), '5min', 'rvc_send_notifaction');
			wp_schedule_event(time(), 'daily', 'rvc_send_notifaction');
    }
		if ( ! wp_next_scheduled( 'rv_asana_import' ) ) {
			wp_schedule_event( time(), 'hourly', 'rv_asana_import' );
		}
		// wp_clear_scheduled_hook('rv_send_onesignal');

		if (! wp_next_scheduled ( 'rv_send_onesignal' )) {
			wp_schedule_event( strtotime('10:30:00'), 'daily', 'rv_send_onesignal');
    }


	}

	public function cron_stop( ) {
		wp_clear_scheduled_hook('rvc_send_notifaction');
		wp_clear_scheduled_hook('rv_asana_import');
		wp_clear_scheduled_hook('rv_send_onesignal');
	}



	public function includes() {

		include_once( 'acf/acf.php' );

		// if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
		// 	require_once dirname( __FILE__ ) . '/cmb2/init.php';
		// 	include_once( 'includes/class-redvolver-cmb2.php' );
		// } elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
		// 	require_once dirname( __FILE__ ) . '/CMB2/init.php';
		// 	include_once( 'includes/class-redvolver-cmb2.php' );
		// }

		include_once( 'includes/class-redvolver-post-types.php' );
		include_once( 'includes/class-redvolver-rvdb.php' );
		include_once( 'includes/class-redvolver-template-loader.php' );
		include_once( 'includes/class-redvolver-rest.php' );
		include_once( 'includes/class-redvolver-notification.php' );
		include_once( 'includes/class-redvolver-roles.php' );
		include_once( 'includes/class-redvolver-template.php' );

		include_once( 'includes/class-redvolver-timecard.php' );
		include_once( 'includes/class-redvolver-project.php' );
		include_once( 'includes/class-redvolver-client.php' );
		include_once( 'includes/class-redvolver-agency.php' );

		include_once( 'includes/class-redvolver-pagetemplater.php' );
		include_once( 'includes/class-redvolver-frontend.php' );
		include_once( 'includes/class-redvolver-kanban.php' );
		include_once( 'includes/class-redvolver-form.php' );
		include_once( 'includes/class-redvolver-ajax.php' );

		include_once( 'includes/class-redvolver-report.php' );
		include_once( 'includes/class-redvolver-cron.php' );
		include_once( 'includes/functions.php' );

		if ( is_admin() ) {
			include_once( 'includes/admin/class-redvolver-acf.php' );
			include_once( 'includes/admin/class-redvolver-admin.php' );
		}

		// Schedule cron jobs.

		$this->post_types = Redvolver_Post_Types::instance();
		$this->rvdb = Redvolver_RVDB::instance();
		$this->template_loader = new Redvolver_Template_Loader;
		$this->notification = new Redvolver_Notification;
	}

	public function load_textdomain() {

	}

	public function rvc_send_notifaction() {


		if( have_rows('notifications','option') ):
			while ( have_rows('notifications','option') ) : the_row();

				$to = get_sub_field('to');
				$email_subject = get_sub_field('email_subject');
				if ( !$email_subject ) $email_subject = 'Calendar';
				$email_message = get_sub_field('email_message');

				if ( $email_message) {
					if ( $to == 'All') {
						$this->notification->send_to_all($email_subject,$email_message);
					}elseif ( $to == 'User') {
						$to_user = get_sub_field('to_user');
						if ( $to_user) {
							$to = $to_user;
							$this->notification->send_to_user($to,$email_subject,$email_message);
						}
					}
				}

		  endwhile;
		endif;
	}

}


function RVC() {
	return RV_Calendar::instance();
}

$GLOBALS['rv_calendar'] = RVC();
