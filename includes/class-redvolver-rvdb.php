<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Redvolver_RVDB {

	private static $_instance = null;

	public $tables = array();

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Construct class.
	 */
	public function __construct() {
	}

	public function log_table() {
		global $wpdb;
		$rv_log = $wpdb->prefix . 'rv_log';

		// @codingStandardsIgnoreLine
		$this->tables[] = "CREATE TABLE " . $rv_log . " (
			log_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id bigint(20) NOT NULL,
			user_id bigint(20) NOT NULL,
			log_date datetime NOT NULL default '0000-00-00 00:00:00',
			log_type text NOT NULL,
			log_event text NOT NULL,
			log_text text NOT NULL,
			PRIMARY KEY  (log_id)
		)" . $this->charset_collate . ";";
	}

	public function brief_table() {
		global $wpdb;
		$rv_brief = $wpdb->prefix . 'rv_brief';

		$this->tables[] = "CREATE TABLE " . $rv_brief . " (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			project_id bigint(20) NOT NULL,
			user_id bigint(20) NOT NULL,
			brand varchar(255) DEFAULT NULL,
			sector varchar(255) DEFAULT NULL,
			contact varchar(255) DEFAULT NULL,
			duration varchar(255) DEFAULT NULL,
			target varchar(255) DEFAULT NULL,
			objective text,
			concept text,
			staff varchar(255) DEFAULT NULL,
			location varchar(255) DEFAULT NULL,
			output varchar(255) DEFAULT NULL,
			delivery varchar(255) DEFAULT NULL,
			budget varchar(255) DEFAULT NULL,
			request text,
			PRIMARY KEY  (id)
		)" . $this->charset_collate . ";";
	}

	public function counter_table() {
		global $wpdb;
		$rv_brief = $wpdb->prefix . 'rv_counter';

		$this->tables[] = "CREATE TABLE " . $rv_brief . " (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			year bigint(20) NOT NULL,
			term_id bigint(20) NOT NULL,
			term_slug varchar(200) NOT NULL,
			counter bigint(20) NOT NULL,
			PRIMARY KEY  (id)
		)" . $this->charset_collate . ";";
	}

	public function insert_tables() {
		global $wpdb;
		$this->charset_collate = ! empty( $wpdb->charset ) ? 'DEFAULT CHARACTER SET ' . $wpdb->charset : '';

		$this->log_table();
		$this->brief_table();
		$this->counter_table();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( count( $this->tables ) > 0 ) {
			foreach ( $this->tables as $table ) {
				dbDelta( $table );
			}
		}
	}

}
