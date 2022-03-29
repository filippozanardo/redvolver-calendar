<?php

class Redvolver_Client {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

  public function hooks() {

    add_action( 'wp_ajax_nopriv_get_client_select2', array($this, 'get_client_select2') );
    add_action( 'wp_ajax_get_client_select2', array($this, 'get_client_select2') );

    add_action( 'wp_ajax_nopriv_add_client', array($this, 'add_client') );
    add_action( 'wp_ajax_add_client', array($this, 'add_client') );

    add_action( 'wp_ajax_nopriv_get_client_tabulator', array($this, 'get_client_tabulator') );
    add_action( 'wp_ajax_get_client_tabulator', array($this, 'get_client_tabulator') );



  }

  public function get_client_tabulator() {

    $tabulator = array();
    $args = array(
      'post_status'=>array('publish'),
      'post_type'=>'client',
      'posts_per_page' => -1,
    );
    $my_query= null;
    $my_query = new WP_Query();

    $client_add_page = get_field('client_add_page','option');
    $url1 = get_permalink($client_add_page);

    $my_query->query($args);


    if( $my_query->have_posts() ) {
      while($my_query->have_posts()):$my_query->the_post();
        $referent = get_field('referent');
        $contact = get_field('contact');

        $tabulator[] = array(
          'id' => $my_query->post->ID,
          'name' => $my_query->post->post_title,
          'referent' => $referent->display_name,
          'contact' => $contact,
          'action' => $url1.'?pid='.$my_query->post->ID
        );

      endwhile;
    }

    // $gigi = array(
    //   array (
    //     'id' => 1,
    //     'name' => 'bob',
    //     'ex' => 23
    //   )
    // );
    //var_dump($gigi);


    echo json_encode($tabulator);
    die();
  }

  public function get_client_select2() {
    $args = array(
      'post_status'=>array('publish'),
      'post_type'=>'client',
      'posts_per_page' => -1,
    );
    if ( !empty($_POST['q'] ) ) {
      $args['s'] = $_POST['q'];
    }
    $my_query= null;
    $my_query = new WP_Query();

    $my_query->query($args);
    $result = array();

    if( $my_query->have_posts() ) {
      while($my_query->have_posts()):$my_query->the_post();
        $temparray = array(
          'id' => $my_query->post->ID,
          'text' => $my_query->post->post_title
        );
        //$result['results'][][$my_query->post->ID] = $my_query->post->ID;
        $result['results'][] = $temparray;
        //array_push($stack, "apple", "raspberry");
      endwhile;
    }

    echo json_encode($result);
    die();
  }

  public function add_client() {
    $current_user = wp_get_current_user();

    $args = array(
        'post_title'    => $_REQUEST['clientname'],
        'post_content'  => $_REQUEST['content'],
        'post_status'   => 'publish',
        'post_author'   => $current_user->ID,
        'post_type' => 'client',
        'meta_input' => array(
        )
    );

    if ( isset( $_REQUEST['pid'] ) ) {
      $args['ID'] = $_REQUEST['pid'];
      $post_id = wp_update_post($args);
    }else{
      $post_id = wp_insert_post($args);
    }

    if(!is_wp_error($post_id)){

      update_field('referent', $_REQUEST['referent'], $post_id);

      update_field('contact', $_REQUEST['contact'], $post_id);

      $url = '';
      if ( isset( $_REQUEST['pid'] ) ) {
      }else{
        $client_add_page = get_field('client_add_page','option');
        $url= get_permalink($client_add_page).'?pid='.$post_id;
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


}

Redvolver_Client::instance();
