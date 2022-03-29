<?php
use Carbon\Carbon;

class Redvolver_Form {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}


	public function hooks() {
    // add_action( 'admin_post_nopriv_rv_addtag', array($this, 'rv_addtag') );
    // add_action( 'admin_post_rv_addtag', array($this, 'rv_addtag') );

    //add_action( 'wp_ajax_nopriv_rv_addtag', array($this, 'rv_addtag') );
    //add_action( 'wp_ajax_rv_addtag', array($this, 'rv_addtag') );

    add_action( 'admin_post_nopriv_rv_addclient', array($this, 'rv_addclient') );
    add_action( 'admin_post_rv_addclient', array($this, 'rv_addclient') );

  }
  // field_5b03d78864282
  public function rv_addtag() {
    // var_dump($_REQUEST);
    // die();
    if ( isset( $_REQUEST['pid'] ) ) {
      $updatearray = array(
        'description' => $_REQUEST['tagdescription']
      );
      $update = wp_update_term( $_REQUEST['pid'], 'rvc-tag', $updatearray );
    }else{
      $updatearray = array(
        'description' => $_REQUEST['tagdescription']
      );
      $update = wp_insert_term( $_REQUEST['tagname'], 'rvc-tag', $updatearray );
    }

      if ( ! is_wp_error( $update ) ) {


        $post_id = "rvc-tag_".$update['term_id'];

        update_field( 'client', $_REQUEST['rvclient'], $post_id );
        update_field( 'pm', $_REQUEST['rvpm'], $post_id );
        update_field( 'priority', $_REQUEST['priority'], $post_id );


        if ( $_REQUEST['datestart'] ) {
          $ddr = Carbon::createFromFormat('d/m/Y', $_REQUEST['datestart']);
          update_field( 'field_5b9903381f202', $ddr->format('Ymd'), $post_id );
        }

        if ( $_REQUEST['dateend'] ) {
          $ddre = Carbon::createFromFormat('d/m/Y', $_REQUEST['dateend']);
          update_field( 'field_5b9903481f203', $ddre->format('Ymd'), $post_id );
        }

        if ( isset($_REQUEST['closed']) ) {
          update_field( 'closed', 1, $post_id );
        }else{
          update_field( 'closed', '', $post_id );
        }
        update_field( 'summary', $_REQUEST['summary'], $post_id );
        update_field( 'budget', $_REQUEST['budget'], $post_id );

        $reaper = array();

        if ($_REQUEST['rank']) {
          foreach ($_REQUEST['rank'] as $value) {
            if ( $value ) {
              $reaper[] = array( 'rank' => $value['rank'] , 'hour' => $value['hour'] );
            }
          }
        }
        update_field( 'estimates', $reaper, $post_id );
        $url = '';
        if ( isset( $_REQUEST['pid'] ) ) {
        }else{
          $tag_add_page = get_field('tag_add_page','option');
          $url= get_permalink($tag_add_page).'?pid='.$update['term_id'];
        }

        $response = array(
           'url' => $url,
        );
        wp_send_json_success($response);
      }else{
        wp_send_json_error();
      }

    wp_send_json_error();
  }

  public function rv_addclient() {


    if ( isset( $_REQUEST['pid'] ) ) {

      $updatearray['description'] = $_REQUEST['clientdescription'];
      $updatearray['name'] = $_REQUEST['clientname'];

      $update = wp_update_term( $_REQUEST['pid'], 'rvc-client', $updatearray );

      if ( ! is_wp_error( $update ) ) {
        $client_add_page = get_field('client_add_page','option');
        wp_redirect( get_permalink($client_add_page).'?pid='.$_REQUEST['pid'] );
        exit;
      }
    }else{
      $updatearray = array(
        'description' => $_REQUEST['clientdescription']
      );
      $update = wp_insert_term( $_REQUEST['clientname'], 'rvc-client', $updatearray );

      if ( ! is_wp_error( $update ) ) {
        $client_add_page = get_field('client_add_page','option');
        wp_redirect( get_permalink($client_add_page).'?pid='.$update['term_id'] );
        exit;
      }


    }

  }


}

Redvolver_Form::instance();
