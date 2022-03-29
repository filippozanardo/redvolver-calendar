<?php

class Redvolver_Template {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

  public function hooks() {
    add_filter( 'template_include' , array($this, 'template_include') );
  }

  public function template_include( $template ) {

		if (!is_user_logged_in()) {
			$template = RVC()->template_loader->get_template_part( 'page' ,'login',false );
		}else{

			$calendar_page = get_field('calendar_page','option');

			if ( $calendar_page ) {
				if ( is_page( $calendar_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'calendar',false );
				}
			}

			$calendar_all_page = get_field('calendar_all_page','option');

			if ( $calendar_all_page ) {
				if ( is_page( $calendar_all_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'calendar-all',false );
				}
			}

      $calendar_project_page = get_field('calendar_project_page','option');

			if ( $calendar_project_page ) {
				if ( is_page( $calendar_project_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'calendar-project',false );
				}
			}

			$timecard_report = get_field('timecard_report','option');

			if ( $timecard_report ) {
				if ( is_page( $timecard_report->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'report-timecard',false );
				}
			}

			$change_password_page = get_field('change_password_page','option');

			if ( $change_password_page ) {
				if ( is_page( $change_password_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'password',false );
				}
			}

			$project_report_page = get_field('project_report_page','option');

			if ( $project_report_page ) {
  			if ( is_page( $project_report_page->ID ) ) {
  				$template = RVC()->template_loader->get_template_part( 'page' ,'project-report',false );
  			}
			}

			$project_group_report_page = get_field('project_group_report_page','option');
			if ( $project_group_report_page ) {
				if ( is_page( $project_group_report_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'group-report',false );
				}
			}

			$client_report_page = get_field('client_report_page','option');
			if ( $client_report_page ) {
				if ( is_page( $client_report_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'client-report',false );
				}
			}

			$user_report_page = get_field('user_report_page','option');

			if ( $user_report_page ) {
				if ( is_page( $user_report_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'user-report',false );
				}
			}

			$project_list_page = get_field('project_list_page','option');

			if ( $project_list_page ) {
				if ( is_page( $project_list_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'project' ,'list',false );
				}
			}

			$project_add_page = get_field('project_add_page','option');

			if ( $project_add_page ) {
				if ( is_page( $project_add_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'project' ,'add',false );
				}
			}

			//Client

			$client_list_page = get_field('client_list_page','option');

			if ( $client_list_page ) {
				if ( is_page( $client_list_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'client' ,'list',false );
				}
			}

			$client_add_page = get_field('client_add_page','option');

			if ( $client_add_page ) {
				if ( is_page( $client_add_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'client' ,'add',false );
				}
			}

			//Agency

			$agency_list_page = get_field('agency_list_page','option');

			if ( $agency_list_page ) {

				if ( is_page( $agency_list_page->ID ) ) {

					$template = RVC()->template_loader->get_template_part( 'agency' ,'list',false );
				}
			}

			$agency_add_page = get_field('agency_add_page','option');

			if ( $agency_add_page ) {
				if ( is_page( $agency_add_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'agency' ,'add',false );
				}
			}

			$tools_page = get_field('tools_page','option');

			if ( $tools_page ) {
				if ( is_page( $tools_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'tools',false );
				}
			}

      $utilities_page = get_field('utilities_page','option');

			if ( $utilities_page ) {
				if ( is_page( $utilities_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'utilities',false );
				}
			}

			$export_page = get_field('export_page','option');
			if ( $export_page ) {
				if ( is_page( $export_page->ID ) ) {
					$template = RVC()->template_loader->get_template_part( 'page' ,'export',false );
				}
			}

			if ( is_singular('project') ) {
				$template = RVC()->template_loader->get_template_part( 'single' ,'project',false );
			}

		}
		return $template;
	}

}

Redvolver_Template::instance();
