<?php

class Redvolver_Kanban {

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

  public function hooks() {
      add_action( 'wp_head', array($this, 'kanban_head') );

      add_action( 'wp_ajax_nopriv_rv_move_kan', array($this, 'rv_move_kan') );
      add_action( 'wp_ajax_rv_move_kan', array($this, 'rv_move_kan') );

      add_action( 'wp_ajax_nopriv_rv_clear_board', array($this, 'rv_clear_board') );
      add_action( 'wp_ajax_rv_clear_board', array($this, 'rv_clear_board') );

      add_action( 'wp_ajax_nopriv_rv_clear_proj', array($this, 'rv_clear_proj') );
      add_action( 'wp_ajax_rv_clear_proj', array($this, 'rv_clear_proj') );

      add_action( 'wp_ajax_nopriv_rv_check_proj', array($this, 'rv_check_proj') );
      add_action( 'wp_ajax_rv_check_proj', array($this, 'rv_check_proj') );

      add_action( 'wp_ajax_nopriv_rv_clear_marked', array($this, 'rv_clear_marked') );
      add_action( 'wp_ajax_rv_clear_marked', array($this, 'rv_clear_marked') );

      add_action( 'wp_ajax_nopriv_rv_kan_pm', array($this, 'rv_kan_pm') );
      add_action( 'wp_ajax_rv_kan_pm', array($this, 'rv_kan_pm') );

  }

  public function rv_kan_pm() {

    $prid = $_POST['prid'];
    $pmid = $_POST['pmid'];
    $clientid = $_POST['clientid'];

    if ( $prid ) {
      $term = get_term_by('id', $prid, 'rvc-tag');

      // var_dump( $terms );
      if ( $term ) {


        if ( $pmid ) {
          update_field( 'pm', $pmid, $term );
        }

        if ( $clientid ) {
          update_field( 'client', $clientid, $term );
        }

        $pm = get_field('pm',$term);
        $marked = get_field('marked',$term);
        $client = get_field('client',$term);

        $itemclass= '';
        $pmname= '';
        $colorbg = '';
        $pmid = '';
        $class = '';
        if ( $pm ) {
          $itemclass = 'pm'.$pm['ID'];
          $pmid = $pm['ID'];
          $pmname = $pm['user_firstname'].' '.$pm['user_lastname'];
          $color = get_field('color', 'user_'. $pm['ID'] );
          $class = 'pm'.$pm['ID'];
          if ( $color ) {
            $colorbg = 'style="background:'.$color.';"';
          }
        }

        // $defitem = '<div class="kt-kanban__badge"><div class="kt-kanban__image kt-media kt-media--dark"><span>BF</span></div><div class="kt-kanban__content"><div class="kt-kanban__title">Bug Fixes</div><span class="kt-badge kt-badge--dark kt-badge--inline">Backlog</span></div></div>';


        $defitem = '<div class="rv-widget29 '.$itemclass.'">';
        $defitem .= '<div class="rv-widget29__actions kt-align-right">';
          $defitem .= '<a href="javascript:;" class="kt-menu__link edit-proj" data-id="'.$term->term_id.'" data-pm="'.$pmid.'" data-cid="'.$client.'"><i class="kt-menu__link-icon flaticon2-edit"></i></a>';
          $defitem .= '<a href="javascript:;" class="kt-menu__link close-proj" data-id="'.$term->term_id.'"><i class="kt-menu__link-icon flaticon2-trash"></i></a>';

          if ( $marked ) {
            $defitem .= '<a href="javascript:;" class="kt-menu__link check-proj" data-id="'.$term->term_id.'"><i class="kt-menu__link-icon fa fa-check-circle"></i></a>';
          }else{
            $defitem .= '<a href="javascript:;" class="kt-menu__link check-proj" data-id="'.$term->term_id.'"><i class="kt-menu__link-icon fa fa-check"></i></a>';
          }
        $defitem .= '</div>';
        $defitem .= '<div class="rv-widget29__content">';
          $defitem .= '<h3 class="rv-widget29__title">'.$term->name.'</h3>';
          $defitem .= '<div class="rv-widget29__item">';
            $defitem .= '<div class="rv-widget29__info">';
              $defitem .= '<span class="rv-widget29__subtitle">PM</span>';
              $defitem .= '<span class="rv-widget29__stats">'.$pmname.'</span>';
            $defitem .= '</div>';
          $defitem .= '</div>';
        $defitem .= '</div>';
      $defitem .= '</div>';


          $pmarray = array(
            'tid' => $term->term_id,
            'id' => '_p'.$term->term_id,
            'title' => $defitem
          );

          $pmarray = json_encode($pmarray,JSON_UNESCAPED_UNICODE);
          $response = array(
            'out'=> $pmarray,
            'pmid'=>$pmid,
          );

          wp_send_json_success($response);
      }

    }

  }

  public function rv_clear_marked() {


    $terms = get_terms( array(
        'taxonomy' => 'rvc-tag',
        'hide_empty' => false,
        'meta_query' => array(
            array(
              'key'     => 'marked',
              'value'   => 1,
              'compare' => '=',
            ),
        ),
    ) );
    // var_dump( $terms );
    if ( $terms ) {
      foreach ($terms as $term) {
        update_field( 'marked', 0, $term );
      }
    }
    wp_send_json_success();


  }

  public function rv_clear_proj(){

    $tid = $_POST['tid'];
    if ( $tid ) {
      $term = get_term_by('id', $tid, 'rvc-tag');

      // var_dump( $terms );
      if ( $term ) {

          update_field( 'closed', 1, $term );

      }
      wp_send_json_success();
    }
    wp_send_json_error();

  }

  public function rv_check_proj(){

    $mark = 0;
    $tid = $_POST['tid'];
    if ( $tid ) {
      $term = get_term_by('id', $tid, 'rvc-tag');

      // var_dump( $terms );
      if ( $term ) {
          $marked = get_field('marked', $term );
          if ( $marked == 1 ) {

            update_field( 'marked', 0, $term );
          }else{
            $mark = 1;
            update_field( 'marked', 1, $term );
          }

      }
      $response = array(
        'mark' => $mark
      );
      wp_send_json_success( $response );
    }
    wp_send_json_error();

  }



  public function rv_clear_board(){

    $pid = $_POST['pid'];
    if ( $pid ) {
      $terms = get_terms( array(
          'taxonomy' => 'rvc-tag',
          'hide_empty' => false,
          'meta_query' => array(
              array(
                'key'     => '_board',
                'value'   => $pid,
                'compare' => '=',
              ),
          ),
      ) );
      // var_dump( $terms );
      if ( $terms ) {
        foreach ($terms as $term) {
          update_field( 'closed', 1, $term );
        }
      }
      wp_send_json_success();
    }
    wp_send_json_error();

  }

  public function rv_move_kan() {

    $pid = $_POST['pid'];
    $tid = $_POST['tid'];

    if ( $tid ) {
      update_term_meta( $tid, '_board', $pid );
      wp_send_json_success();
    }
    wp_send_json_error();
    // var_dump($tid);
  }

  public function kanban_head() {

    $kanban_page = get_field('kanban_page','option');
    if ( $kanban_page ) {
      if ( is_page( $kanban_page->ID ) ) {
        $kanban = $this->get_kan();
        if ( $kanban ) {
          ?>
           <script>
            var boards = <?php echo $kanban; ?>;
           </script>
          <?php
        }
        // var_dump($kanban);
      }
    }
  }




  public function get_kan() {

    $backlog_board = array(
      'id' => '_backlog',
      'title' => 'Backlog <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_backlog"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $proposals_board = array(
      'id' => "_proposals",
      'title' => 'Proposals <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_proposals"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $tostart_board = array(
      'id' => "_tostart",
      'title' => 'To Start <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_tostart"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $ontrack_board = array(
      'id' => "_ontrack",
      'title' => 'On Track <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_ontrack"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $feedback_board = array(
      'id' => "_feedback",
      'title' => 'Feedback <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_feedback"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $critical_board = array(
      'id' => "_critical",
      'title' => 'Critical <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_critical"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $closing_board = array(
      'id' => "_closing",
      'title' => 'Closing <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_closing"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $closed_board = array(
      'id' => "_closed",
      'title' => 'Closed <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_closed"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );

    $idle_board = array(
      'id' => "_idle",
      'title' => 'Idle <div class="button_new" style="float: right;"><button class="close-board" type="button" data-id="_idle"><i class="fa fa-trash-alt"></i></button></div>',
      'item' => array()
    );


      $args = array(
          'taxonomy' => 'rvc-tag',
          'hide_empty' => false,
          'meta_query' => array(
              'relation' => 'OR',
              array(
                'key'     => 'closed',
                'value'   => 0,
                'compare' => '=',
              ),
              array(
                'key'     => 'closed',
                'value'   => '',
                'compare' => '=',
              ),
              array(
                'key'     => 'closed',
                'compare' => 'NOT EXISTS',
              ),

          ),
      );

      $kanban_exclude = get_field('kanban_exclude','options');
      if ( $kanban_exclude ) {
        $args['exclude'] = $kanban_exclude;
      }
      $terms = get_terms( $args );



    foreach ($terms as $term) {
      $board = get_term_meta( $term->term_id, '_board', true);
      $pm = get_field('pm',$term);
      $marked = get_field('marked',$term);
      $client = get_field('client',$term);

      $itemclass= '';
      $pmname= '';
      $colorbg = '';
      $pmid = '';
      $class = '';
      if ( $pm ) {
        $itemclass = 'pm'.$pm['ID'];
        $pmid = $pm['ID'];
        $pmname = $pm['user_firstname'].' '.$pm['user_lastname'];
        $color = get_field('color', 'user_'. $pm['ID'] );
        $class = 'pm'.$pm['ID'];
        if ( $color ) {
          $colorbg = 'style="background:'.$color.';"';
        }
      }


        $defitem = '<div class="rv-widget29 '.$itemclass.'">';
        $defitem .= '<div class="rv-widget29__actions kt-align-right">';
  				$defitem .= '<a href="javascript:;" class="kt-menu__link edit-proj" data-id="'.$term->term_id.'" data-pm="'.$pmid.'" data-cid="'.$client.'"><i class="kt-menu__link-icon flaticon2-edit"></i></a>';
          $defitem .= '<a href="javascript:;" class="kt-menu__link close-proj" data-id="'.$term->term_id.'"><i class="kt-menu__link-icon flaticon2-trash"></i></a>';

          if ( $marked ) {
            $defitem .= '<a href="javascript:;" class="kt-menu__link check-proj" data-id="'.$term->term_id.'"><i class="kt-menu__link-icon fa fa-check-circle"></i></a>';
          }else{
            $defitem .= '<a href="javascript:;" class="kt-menu__link check-proj" data-id="'.$term->term_id.'"><i class="kt-menu__link-icon fa fa-check"></i></a>';
          }
  			$defitem .= '</div>';
  			$defitem .= '<div class="rv-widget29__content">';
  				$defitem .= '<h3 class="rv-widget29__title">'.$term->name.'</h3>';
  				$defitem .= '<div class="rv-widget29__item">';
  				 	$defitem .= '<div class="rv-widget29__info">';
  				 		$defitem .= '<span class="rv-widget29__subtitle">PM</span>';
  				 		$defitem .= '<span class="rv-widget29__stats">'.$pmname.'</span>';
  					$defitem .= '</div>';
  					// $defitem .= '<div class="rv-widget29__info">';
  				 	// 	$defitem .= '<span class="rv-widget29__subtitle">Change</span>';
  				 	// 	$defitem .= '<span class="rv-widget29__stats kt-font-brand">+15%</span>';
  					// $defitem .= '</div>';
  					// $defitem .= '<div class="rv-widget29__info">';
  				 	// 	$defitem .= '<span class="rv-widget29__subtitle">Licenses</span>';
  				 	// 	$defitem .= '<span class="rv-widget29__stats kt-font-danger">29</span>';
  					// $defitem .= '</div>';
  				$defitem .= '</div>';
  			$defitem .= '</div>';
      $defitem .= '</div>';

      if ( $board ) {

        switch ($board) {
          case '_backlog':
            $backlog_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_proposals':
            $proposals_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_tostart':
            $tostart_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_ontrack':
            $ontrack_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_feedback':
            $feedback_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_critical':
            $critical_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_closing':
            $closing_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_closed':
            $closed_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          case '_idle':
            $idle_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;

          default:
            $backlog_board['item'][] = array(
              'tid' => $term->term_id,
              'id' => '_p'.$term->term_id,
              'title' => $defitem,
              'class' => $class,
            );
            break;
        }
      }else{

        $backlog_board['item'][] = array(
          'tid' => $term->term_id,
          'id' => '_p'.$term->term_id,
          'title' => $defitem,
          'class' => $class,
        );
      }
    }

    $board_array = array(
      $backlog_board,
      $proposals_board,
      $tostart_board,
      $ontrack_board,
      $feedback_board,
      $critical_board,
      $closing_board,
      $closed_board,
      $idle_board,
    );

    $board_array = json_encode($board_array,JSON_UNESCAPED_UNICODE);

    return $board_array;
  }



}
Redvolver_Kanban::instance();
