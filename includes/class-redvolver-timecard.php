<?php

use Carbon\Carbon;

class Redvolver_TimeCard {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

  public function hooks() {

    add_action( 'wp_ajax_nopriv_fetch_timecard', array($this, 'fetch_timecard') );
    add_action( 'wp_ajax_fetch_timecard', array($this, 'fetch_timecard') );

    add_action( 'wp_ajax_nopriv_fetch_timecard_all', array($this, 'fetch_timecard_all') );
    add_action( 'wp_ajax_fetch_timecard_all', array($this, 'fetch_timecard_all') );

    add_action( 'wp_ajax_nopriv_edit_timecard', array($this, 'edit_timecard') );
    add_action( 'wp_ajax_edit_timecard', array($this, 'edit_timecard') );

    add_action( 'wp_ajax_nopriv_add_timecard_only', array($this, 'add_timecard_only') );
    add_action( 'wp_ajax_add_timecard_only', array($this, 'add_timecard_only') );

    add_action( 'wp_ajax_nopriv_del_timecard', array($this, 'del_timecard') );
    add_action( 'wp_ajax_del_timecard', array($this, 'del_timecard') );

  }

  function add_timecard_only() {

    $current_user = wp_get_current_user();

    $hour = $_POST['hour'];
    $start = Carbon::createFromFormat('d/m/Y H', $_POST['date'].' 9');
    $end = Carbon::createFromFormat('d/m/Y H', $_POST['date'].' 9')->addHours($hour);

    $args = array(
        'post_title'    => $_POST['title'],
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => $current_user->ID,
        'post_date' => $start->format( 'Y-m-d H:i:s' ),
        'post_type' => 'timecard',
        'meta_input' => array(
          'start' => $start->format( 'Y-m-d H:i:s' ),
          'end' => $end->format( 'Y-m-d H:i:s' ),
        )
    );

    if ( $_POST['project'] ) {
      $args['meta_input']['project'] = $_POST['project'];
    }

    if ( $_POST['type'] ) {
      $args['meta_input']['type'] = $_POST['type'];
    }

    if ( $_POST['smart_working'] ) {
      $args['meta_input']['smart_working'] = $_POST['smart_working'];
    }else{
      $args['meta_input']['smart_working'] = 0;
    }

    if ( $_POST['cig'] ) {
      $args['meta_input']['cig'] = $_POST['cig'];
    }else{
      $args['meta_input']['cig'] = 0;
    }

    if ($_POST['title']) {


      $mode = 'add';
      $post_id = wp_insert_post($args);

      if(!is_wp_error($post_id)){

        $response = array(
            'id'        => $post_id,
            'title' => $_POST['title'],
            'start' => $start->format( 'Y-m-d H:i:s' ),
            'end' => $end->format( 'Y-m-d H:i:s' )
        );
        wp_send_json_success($response);
      }else{
        wp_send_json_error();
      }

    }else{
      wp_send_json_error();
    }

  }

  public function fetch_timecard() {

    $current_user = wp_get_current_user();
    $start = $_POST['start'];
    $end = $_POST['end'];

    //$start = '2017-02-26';
    //$end = '2017-04-09';

    if ($start && $end) {

      $startp = explode("-", $start);
      $endp = explode("-", $end);

      $start = str_replace('-', '', $start);
      $end = str_replace('-', '', $end);

      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'timecard',
        'author' => $current_user->ID,
        'date_query' => array(
          array(
            'before'     => array(
              'year'  => $endp[0],
              'month' => $endp[1],
              'day'   => $endp[2],
            ),
            'after'    => array(
              'year'  => $startp[0],
              'month' => $startp[1],
              'day'   => $startp[2],
            ),
            'inclusive' => true,
          ),
        ),


        'posts_per_page' => -1,
      );

      $arrayeventi = array();
      $p_query= null;
      $p_query = new WP_Query();

      $p_query->query($args);
    	if ( $p_query->have_posts() ) {
    		while($p_query->have_posts()):$p_query->the_post();

    			$start = get_field('start');
          $end = get_field('end');
          $project = get_field('project');
          $type = get_field('type');
          $smart_working = get_field('smart_working');
          $cig = get_field('cig');

          if (!$start) $start = get_the_time( 'Y-m-d H:i:s');
          if (!$end) $end = get_the_time( 'Y-m-d H:i:s');

  				$arrayeventi[] = array(
              'id' => $p_query->post->ID,
  						'title' => $p_query->post->post_title,
  						'start' => $start,
              'end' => $end,
              'extendedProps' => array(
                'project' => $project,
                'type' => $type,
                'smart_working' => $smart_working,
                'cig' => $cig,
              )
  				);


    		endwhile;
    	}
      wp_reset_query();

    	$out = json_encode($arrayeventi,JSON_UNESCAPED_UNICODE);

      wp_die($out);
    }else{
      wp_die();
    }
  }

  public function fetch_timecard_all() {
    $current_user = wp_get_current_user();
    $start = $_POST['start'];
    $end = $_POST['end'];

    if ($start && $end) {

      $startp = explode("-", $start);
      $endp = explode("-", $end);

      $start = str_replace('-', '', $start);
      $end = str_replace('-', '', $end);

      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'timecard',
        'date_query' => array(
          array(
            'before'     => array(
              'year'  => $endp[0],
              'month' => $endp[1],
              'day'   => $endp[2],
            ),
            'after'    => array(
              'year'  => $startp[0],
              'month' => $startp[1],
              'day'   => $startp[2],
            ),
            'inclusive' => true,
          ),
        ),


        'posts_per_page' => -1,
      );

      $arrayeventi = array();
      $p_query= null;
      $p_query = new WP_Query();

      $p_query->query($args);
    	if ( $p_query->have_posts() ) {
    		while($p_query->have_posts()):$p_query->the_post();

    			$start = get_field('start');
          $end = get_field('end');
          $project = get_field('project');

          if (!$start) $start = get_the_time( 'Y-m-d H:i:s');
          if (!$end) $end = get_the_time( 'Y-m-d H:i:s');

          $terms = get_the_terms( $p_query->post->ID ,  'rvc-tag' );
          if ( $terms) {
            $tag = $terms[0]->term_id;
          }else{
            $tag = false;
          }
  				$arrayeventi[] = array(
              'id' => $p_query->post->ID,
  						'title' => $p_query->post->post_title,
  						'start' => $start,
              'end' => $end,
  						'project' => $project,
              'color' => 'violet'
  				);

          // project
          // type
          // smart_working
          // cig



    		endwhile;
    	}
      wp_reset_query();
    	$out = json_encode($arrayeventi);


      wp_die($out);
    }else{
      wp_die();
    }
  }

  public function edit_timecard() {

    $current_user = wp_get_current_user();
    $st = new DateTime( $_POST['start'] );
    $en = new DateTime( $_POST['end'] );

    $args = array(
        'post_title'    => $_POST['title'],
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => $current_user->ID,
        'post_date' => $st->format( 'Y-m-d H:i:s' ),
        'post_type' => 'timecard',
        'meta_input' => array(
          'start' => $st->format( 'Y-m-d H:i:s' ),
          'end' => $en->format( 'Y-m-d H:i:s' ),
        )
    );

    if ( $_POST['project'] ) {
      $args['meta_input']['project'] = $_POST['project'];
    }

    if ( $_POST['type'] ) {
      $args['meta_input']['type'] = $_POST['type'];
    }

    if ( $_POST['smart_working'] ) {
      $args['meta_input']['smart_working'] = $_POST['smart_working'];
    }else{
      $args['meta_input']['smart_working'] = 0;
    }

    if ( $_POST['cig'] ) {
      $args['meta_input']['cig'] = $_POST['cig'];
    }else{
      $args['meta_input']['cig'] = 0;
    }

    if ($_POST['title']) {

      $finaltitle = $_POST['title'];

      if ( $_POST['mode'] == 'add' ) {
        $mode = 'add';
        $post_id = wp_insert_post($args);
      }elseif ( $_POST['mode'] == 'edit' ) {
        $args['ID'] = $_POST['cid'];
        $mode = 'edit';
        $post_id = wp_update_post($args);
      }
      if(!is_wp_error($post_id)){

        $response = array(
            'mode'   => $mode,
            'id'        => $post_id,
            'title' => $finaltitle,
        );
        wp_send_json_success($response);
      }else{
        wp_send_json_error();
      }

    }else{
      wp_send_json_error();
    }

  }

  public function del_timecard() {
    if ($_POST['cid']) {
      wp_delete_post( $_POST['cid'], true );
      wp_send_json_success();
    }else{
      wp_send_json_error();
    }
  }


}

Redvolver_TimeCard::instance();
