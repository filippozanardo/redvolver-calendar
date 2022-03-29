<?php

use Carbon\Carbon;

class Redvolver_Rest {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

  public function __construct() {
		$this->hooks();
		$this->namespace = 'redvolver/v1';
	}

  public function hooks() {
    add_action( 'rest_api_init', array( $this, 'rest_init' ) );
		add_action('rest_api_init', array( $this, 'add_api_routes') );
  }

	public function add_api_routes() {

		register_rest_route($this->namespace, 'token', array(
        'methods' => 'POST',
        'callback' => array($this, 'generate_token'),

    ));

		register_rest_route($this->namespace, 'appnonce', array(
        'methods' => 'GET',
        'callback' => array($this, 'appnonce'),

    ));

		register_rest_route($this->namespace, 'creatervc', array(
        'methods' => 'POST',
        'callback' => array($this, 'creatervc'),
    ));

		register_rest_route($this->namespace, 'deletervc', array(
        'methods' => 'POST',
        'callback' => array($this, 'deletervc'),
    ));

		register_rest_route($this->namespace, 'fetch_user_event', array(
        'methods' => 'POST',
        'callback' => array($this, 'fetch_user_event'),

    ));

		register_rest_route($this->namespace, 'fetch_user_event', array(
        'methods' => 'GET',
        'callback' => array($this, 'fetch_user_event'),

    ));
	}
	public function fetch_user_event(  $request )
	{

		//var_dump($request);
		$uid = $request->get_param('uid');
		$start = $request->get_param('start');
		$end = $request->get_param('end');


		if ( empty($uid) ) {
			return new WP_Error( 'redvolver_error', 'Error', array( 'status' => 404, 'message' => 'ciao' ) );
		}

		if ($start && $end) {

      //$startp = explode("-", $start);
      //$endp = explode("-", $end);
			$startp = Carbon::parse($start);
			$endp = Carbon::parse($end);
			//return $startp;
      // $start = str_replace('-', '', $start);
      // $end = str_replace('-', '', $end);

      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'timecard',
        'author' => $uid,
        'date_query' => array(
          array(
            'before'     => array(
							'year'  => $endp->year,
              'month' => $endp->month,
              'day'   => $endp->day,
            ),
            'after'    => array(
              'year'  => $startp->year,
              'month' => $startp->month,
              'day'   => $startp->day,
            ),
            'inclusive' => true,
          ),
        ),


        'posts_per_page' => -1,
      );

			//var_dump($args);

      $arrayeventi = array();
      $p_query= null;
      $p_query = new WP_Query($args);

      //$p_query->query($args);
    	if ( $p_query->have_posts() ) {
    		while($p_query->have_posts()):$p_query->the_post();


    			$start = get_field('start');
          $end = get_field('end');

          if (!$start) $start = get_the_time( 'Y-m-d H:i:s');
          if (!$end) $end = get_the_time( 'Y-m-d H:i:s');

          // $terms = get_the_terms( $p_query->post->ID ,  'rvc-tag' );
          // if ( $terms) {
          //   $tag = $terms[0]->term_id;
          // }else{
          //   $tag = false;
          // }
  				$arrayeventi[] = array(
              'id' => $p_query->post->ID,
  						'title' => get_the_title(),
  						'start' => $start,
              'end' => $end,
  						// 'tag' => $tag,
              //'color' => $color
  				);



    		endwhile;
    	}
      //wp_reset_query();
    	$out = json_encode($arrayeventi);
			return $arrayeventi;

      //wp_die($out);
    }

		return false;

		//return $uid;

	}

	public function deletervc(  $request )
	{
		$nonce = $request->get_param('nonce');

		if ( empty($nonce) ) {
			return new WP_Error( 'redvolver_error', 'Error', array( 'status' => 404, 'message' => 'ciao' ) );
		}

		if ( ! wp_verify_nonce( $nonce, 'creatervc-'.$request->get_param('author') ) ) {
		    return new WP_Error( 'redvolver_error', 'Error', array( 'status' => 404 , 'message' => 'ciao2' ) );
		} else {

			$postid =  $request->get_param('postid');
			wp_delete_post( $postid );

			return 'ok';

		}


	}

	public function creatervc(  $request )
	{
		$nonce = $request->get_param('nonce');

		if ( empty($nonce) ) {
			return new WP_Error( 'redvolver_error', 'Error', array( 'status' => 404, 'message' => 'ciao' ) );
		}

		if ( ! wp_verify_nonce( $nonce, 'creatervc-'.$request->get_param('author') ) ) {
		    return new WP_Error( 'redvolver_error', 'Error', array( 'status' => 404 , 'message' => 'ciao2' ) );
		} else {

			$my_post = array(
				'post_title'    => $request->get_param('title'),
				'post_content'  => '',
				'post_author'   => $request->get_param('author'),
				'post_status'   => 'publish',
				'post_type'   => 'rvc',
				'post_date'   => $request->get_param('start')
			);

			// Insert the post into the database
			$id = wp_insert_post( $my_post );
			if ( $id ) {

				update_field( 'start', $request->get_param('start'), $id );
   			update_field( 'end', $request->get_param('end'), $id );

				return $id;
			}else{
				return new WP_Error( 'redvolver_error', 'Error', array( 'status' => 404 , 'message' => 'ciao2' ) );
			}
		}


	}

	public function appnonce(  $request )
	{
		if ( empty( $request->get_param('author') ) ) {
			return new WP_Error( 'redvolver_error', 'Please enter username!', array( 'status' => 404 ) );
		}

		//return wp_create_nonce('wp_rest');
		return wp_create_nonce( 'creatervc-'.$request->get_param('author') );
	}

	public function generate_token(  $request )
	{
		//var_dump($request);

		$username = $request->get_param('username');
	  $password = $request->get_param('password');


		if ( empty( $username ) ) {
			return new WP_Error( 'redvolver_error', 'Please enter username!', array( 'status' => 404 ) );
		}

		if ( empty( $password ) ) {
			return new WP_Error( 'redvolver_error', 'Please enter password!', array( 'status' => 404 ) );
		}

		$user = wp_authenticate($username, $password);

		if (is_wp_error($user)) {
			return new WP_Error( 'redvolver_error', 'Invalid Login Credential', array( 'status' => 404 ) );
		}

		//$expiration = time() + apply_filters('auth_cookie_expiration', $seconds, $user->ID, true);

		$expiration = 1209600;

    $cookie = wp_generate_auth_cookie($user->ID, $expiration, 'logged_in');
		return array(
        "cookie" => $cookie,
        "cookie_name" => LOGGED_IN_COOKIE,
        "user" => array(
            "id" => "$user->ID",
        ),
				"data" => array(
					"status" => 200
				),
    );

	}

  public function rest_init() {

		register_rest_field( 'rvc',
	        'start',
	        array(
	            'get_callback'    => array( $this, 'get_start_field' ),
	            'update_callback' => null,
	            'schema'          => null,
	        )
	   );

     register_rest_field( 'rvc',
 	        'end',
 	        array(
 	            'get_callback'    => array( $this, 'get_end_field' ),
 	            'update_callback' => null,
 	            'schema'          => null,
 	        )
 	   );

  }

  public function get_start_field($object, $fieldName, $request) {
    return get_field('start',$object['id']);
  }

  public function get_end_field($object, $fieldName, $request) {
    return get_field('end',$object['id']);
  }

  public function get_acf_field($object, $fieldName, $request) {
		//return $this->_getData($object['id']);
    return get_fields($object['id']);
	}

  private function _getData($id, $type = 'post', $object = array()) {
		switch($type) {
			case 'post':
			default:
				return get_fields($id);
				break;
			case 'term':
				return get_fields($object['taxonomy'] . '_' . $id);
				break;
			case 'user':
				return get_fields('user_' . $id);
				break;
			case 'comment':
				return get_fields('comment_' . $id);
			 	break;
			case 'options':
				return get_fields('option');
				break;
		}
	}

}

Redvolver_Rest::instance();
