<?php

class Redvolver_Frontend {

	private $prefix = 'rv_';
	private static $_instance = null;
  private $currentID = 0;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

    public function isEnabled() {
				return true;
        $calendar_page = get_field('calendar_page','option');
				$calendar_all_page = get_field('calendar_all_page','option');

				$client_list_page = get_field('client_list_page','option');
				$client_add_page = get_field('client_add_page','option');

				$agency_list_page = get_field('agency_list_page','option');
				$agency_add_page = get_field('agency_add_page','option');

				$project_list_page = get_field('project_list_page','option');
				$project_add_page = get_field('project_add_page','option');

				$project_report_page = get_field('project_report_page','option');
				$client_report_page = get_field('client_report_page','option');

				$export_page = get_field('export_page','option');

        $queried_object = get_queried_object();



        if ( is_singular( 'jrr-spotlight' ) ) {
            return true;
        }
        if ( is_singular( 'jrr-recruitment' ) ) {
            return true;
        }
        if ( $calendar_page ) {
            if ( is_page( $calendar_page->ID ) ) {
                return true;
            }
        }
				if ( $calendar_all_page ) {
            if ( is_page( $calendar_all_page->ID ) ) {
                return true;
            }
        }

				if ( $project_report_page ) {
            if ( is_page( $project_report_page->ID ) ) {
                return true;
            }
        }

				if ( $project_group_report_page ) {
            if ( is_page( $project_group_report_page->ID ) ) {
                return true;
            }
        }

				if ( $client_report_page ) {
            if ( is_page( $client_report_page->ID ) ) {
                return true;
            }
        }

				if ( $project_list_page ) {
            if ( is_page( $project_list_page ) ) {
                return true;
            }
        }
				if ( $project_add_page ) {
            if ( is_page( $project_add_page->ID ) ) {
                return true;
            }
        }
				if ( $client_list_page ) {
            if ( is_page( $client_list_page->ID ) ) {
                return true;
            }
        }
				if ( $client_add_page ) {
            if ( is_page( $client_add_page->ID ) ) {
                return true;
            }
        }

				if ( $agency_list_page ) {
            if ( is_page( $agency_list_page->ID ) ) {
                return true;
            }
        }
				if ( $agency_add_page ) {
            if ( is_page( $agency_add_page->ID ) ) {
                return true;
            }
        }

				if ( $export_page ) {
            if ( is_page( $export_page->ID ) ) {
							return true;
						}
				}

        return false;
    }

		public function hooks() {
				add_action( 'wp_head', array($this, 'rvb_head') );

        add_action( 'wp_enqueue_scripts', array($this, 'rvb_enqueue_scripts') , 99);
        add_action( 'wp_footer', array($this, 'rvb_footer') );

        add_filter( 'body_class', array($this, 'rvb_body_class' ) );
    }

		public function rvb_enqueue_scripts() {

			global $wp_styles;
			$wp_styles->queue = array();

			global $wp_scripts;
			$wp_scripts->queue = array();

			wp_enqueue_style('dashicons');
			wp_enqueue_style('admin-bar');

			/* STYLE */
			wp_enqueue_style( 'fullcalendar', RVC_PLUGIN_URL . 'dist/plugins/custom/fullcalendar/fullcalendar.bundle.css', false );
			wp_enqueue_style( 'plugins', RVC_PLUGIN_URL . 'dist/plugins/global/plugins.bundle.css', false );

			wp_enqueue_style( 'tabulator', RVC_PLUGIN_URL . 'ext/tabulator/tabulator.min.css', false );
			wp_enqueue_style( 'tabulatorb4', RVC_PLUGIN_URL . 'ext/tabulator/tabulator_bootstrap4.css', false );

			wp_enqueue_style( 'dropzone', RVC_PLUGIN_URL . 'ext/dropzone/dropzone.min.css', false );

			wp_enqueue_style( 'bootstraptables', RVC_PLUGIN_URL . 'ext/bootstrap-table/bootstrap-table.min.css', false );
			wp_enqueue_style( 'datatables', RVC_PLUGIN_URL . 'dist/plugins/custom/datatables/datatables.bundle.css', false );

			wp_enqueue_style( 'prismjs', RVC_PLUGIN_URL . 'dist/plugins/custom/prismjs/prismjs.bundle.css', false );
			wp_enqueue_style( 'bundle', RVC_PLUGIN_URL . 'dist/css/style.bundle.css', false );

			wp_enqueue_style( 'main', RVC_PLUGIN_URL . 'dist/css/main.css', false );

			/* JS */

			wp_register_script( 'prismjs', RVC_PLUGIN_URL . 'dist/plugins/custom/prismjs/prismjs.bundle.js', null, null, true );
			wp_enqueue_script( 'prismjs' );
			
			wp_register_script( 'plugins', RVC_PLUGIN_URL . 'dist/plugins/global/plugins.bundle.js', null, null, true );
			wp_enqueue_script( 'plugins' );



			wp_register_script( 'bundle', RVC_PLUGIN_URL . 'dist/js/scripts.bundle.js', null, null, true );
			wp_enqueue_script( 'bundle' );

			wp_register_script( 'ckeditor', RVC_PLUGIN_URL . 'dist/plugins/custom/ckeditor/ckeditor-classic.bundle.js', null, null, true );
			wp_enqueue_script( 'ckeditor' );

			wp_register_script( 'fullcalendar', RVC_PLUGIN_URL . 'dist/plugins/custom/fullcalendar/fullcalendar.bundle.js', null, null, true );
			wp_enqueue_script( 'fullcalendar' );

			wp_register_script( 'datatables', RVC_PLUGIN_URL . 'dist/plugins/custom/datatables/datatables.bundle.js', null, null, true );
			wp_enqueue_script( 'datatables' );

			wp_register_script( 'jspdf', RVC_PLUGIN_URL . 'ext/tabulator/jspdf.min.js' , null, null, true );
			wp_enqueue_script( 'jspdf' );
			wp_register_script( 'jspdfauto', RVC_PLUGIN_URL . 'ext/tabulator/jspdf.plugin.autotable.js' , null, null, true );
			wp_enqueue_script( 'jspdfauto' );
			wp_register_script( 'tabulatorxls', RVC_PLUGIN_URL . 'ext/tabulator/xlsx.full.min.js' , null, null, true );
			wp_enqueue_script( 'tabulatorxls' );

			wp_register_script( 'tabulator', RVC_PLUGIN_URL . 'ext/tabulator/tabulator.min.js' , null, null, true );
			wp_enqueue_script( 'tabulator' );

			wp_register_script( 'dropzone', RVC_PLUGIN_URL . 'ext/dropzone/dropzone.min.js' , null, null, true );
			wp_enqueue_script( 'dropzone' );

			wp_register_script( 'bootstraptable', RVC_PLUGIN_URL . 'ext/bootstrap-table/bootstrap-table.min.js', null, null, true );
      wp_enqueue_script( 'bootstraptable' );

			wp_register_script( 'bootstrap-table-export', RVC_PLUGIN_URL . 'ext/bootstrap-table/extensions/export/bootstrap-table-export.min.js', null, null, true );
      wp_enqueue_script( 'bootstrap-table-export' );

			wp_register_script( 'moment', RVC_PLUGIN_URL . 'ext/moment.min.js' , null, null, true );
			wp_enqueue_script( 'moment' );

			wp_register_script( 'datatablesmoment', RVC_PLUGIN_URL . 'ext/datetime-moment.js' , null, null, true );
			wp_enqueue_script( 'datatablesmoment' );


			wp_register_script( 'app', RVC_PLUGIN_URL . 'dist/js/app.js', null, null, true );
			wp_enqueue_script( 'app' );

			wp_register_script( 'main', RVC_PLUGIN_URL . 'dist/js/main.js', null, null, true );
			wp_localize_script( 'main', 'rvlocalize', array(
					'dataurl' => RVC_PLUGIN_URL,
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'siteurl' => get_site_url()
			));
			wp_enqueue_script( 'main' );


		}

    public function rvb_footer() {

        $enabled = $this->isEnabled();

        if($enabled) {

        }
    }


		public function rvb_head() {


				$terms = get_terms('project_status',array(
					'hide_empty' => false,
				));
				if ( $terms ) {
					?>
					<style>
					<?php
					foreach ($terms as $term) {
						$post_id = "project_status_".$term->term_id;
						$value = get_field( 'color', $post_id );
						?>
						.fc-event.<?php echo $term->slug; ?> {
							background: <?php echo $value; ?>;
						}
						<?php
					}
					?>
					</style>
					<?php
				}

        $enabled = $this->isEnabled();
				$data = array();

        if($enabled) {

					$projects = array();
					$args = array(
						'post_status'=>array('publish'),
						'post_type'=>'project',
						'posts_per_page' => -1,
					);
					$my_query= null;
					$my_query = new WP_Query();

					$my_query->query($args);


					if( $my_query->have_posts() ) {
						while($my_query->have_posts()):$my_query->the_post();
							$code = get_field('code',$my_query->post->ID);
							if ( $code ) {
								$pname = $code;
							}else{
								$pname = $my_query->post->post_title;
							}
							$projects[] = array(
								'id' => $my_query->post->ID,
								'text' => $pname,
							);
						endwhile;
						wp_reset_query();
					}
					?>
					<script>
						var projects = <?php echo json_encode( array_values($projects) ); ?>;
					</script>
					<?php
					$clients = array();
					$args = array(
						'post_status'=>array('publish'),
						'post_type'=>'client',
						'posts_per_page' => -1,
					);
					$my_query= null;
					$my_query = new WP_Query();

					$my_query->query($args);


					if( $my_query->have_posts() ) {
						while($my_query->have_posts()):$my_query->the_post();
							$clients[] = array(
								'id' => $my_query->post->ID,
								'text' => $my_query->post->post_title,
							);
						endwhile;
						wp_reset_query();
					}
					?>
					<script>
						var clients = <?php echo json_encode( array_values($clients) ); ?>;
					</script>
					<?php
					$data = array();
					$terms = get_terms( 'rvc-tag', array(
					    'orderby' => 'name',
							'order' => 'ASC',
					    'hide_empty' => false
					) );
					if ( $terms ) {
						foreach ($terms as $term) {
							$closed = get_field('closed',$term);
							if (!$closed) {
								$data[] = array(
									'id' => $term->term_id,
									'text' => $term->name,
								);
							}
						}

					}
					?>
					<script>
						var rvdata = <?php echo json_encode( array_values($data) ); ?>;
					</script>
					<?php
					$clientdata = array();
					$terms = get_terms( 'rvc-client', array(
					    'orderby' => 'name',
							'order' => 'ASC',
					    'hide_empty' => false
					) );
					if ( $terms ) {
						foreach ($terms as $term) {
							$clientdata[] = array(
								'id' => $term->term_id,
								'text' => $term->name,
							);
						}

					}
					?>
					<script>
						var rvclientdata = <?php echo json_encode( array_values($clientdata) ); ?>;
					</script>
					<?php
					$userdata = array();

					$users = get_users( array(
						'orderby' => 'login',
						'order' => 'ASC',
						'exclude' => array( 1 )
					));
					foreach ( $users as $user ) {
						$userdata[] = array(
							'id' => $user->ID,
							'text' => $user->first_name. ' '.$user->last_name,
						);
					}
					?>
					<script>
						var rvpm = <?php echo json_encode( array_values($userdata) ); ?>;
					</script>
					<?php
        }
    }

    public function rvb_body_class() {
        $enabled = $this->isEnabled();


        if($enabled) {
    	   //$classes[] = 'rvc';
				 	$classes[] = 'header-fixed';
				 	$classes[] = 'header-mobile-fixed';
					$classes[] = 'page-loading';

        	return $classes;
        }

    }

}

Redvolver_Frontend::instance();
