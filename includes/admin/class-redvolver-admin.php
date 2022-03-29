<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Redvolver_Admin {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

	public function hooks() {
  }

}

Redvolver_Admin::instance();
