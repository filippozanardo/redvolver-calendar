<?php
use Carbon\Carbon;

class Redvolver_Cron {

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

Redvolver_Cron::instance();
