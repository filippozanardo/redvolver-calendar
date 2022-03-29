<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Redvolver_Report {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

  public function hooks() {

    add_action( 'wp_ajax_nopriv_rv_group_report_tax', array($this, 'rv_group_report_tax') );
    add_action( 'wp_ajax_rv_group_report_tax', array($this, 'rv_group_report_tax') );

    add_action( 'wp_ajax_nopriv_user_by_project_report', array($this, 'user_by_project_report') );
    add_action( 'wp_ajax_user_by_project_report', array($this, 'user_by_project_report') );

    add_action( 'wp_ajax_nopriv_rv_get_attendance', array($this, 'rv_get_attendance') );
    add_action( 'wp_ajax_rv_get_attendance', array($this, 'rv_get_attendance') );

    add_action( 'wp_ajax_nopriv_client_report', array($this, 'client_report') );
    add_action( 'wp_ajax_client_report', array($this, 'client_report') );


  }

  public function client_report() {

    $agency = $_POST['agency'];
    $client = $_POST['client'];

    $reportarray = array();

    $args = array(
      'post_status'=>array('publish'),
      'post_type'=>'project',
      'posts_per_page' => -1,
    );

    if ( $agency && $client ) {
      $args['meta_query'] = array(
        'relation' => 'AND',
        array(
          'key'     => 'agency',
          'value'   => $agency,
          'compare' => '=',
        ),
        array(
          'key'     => 'client',
          'value'   => $client,
          'compare' => '=',
        )
      );
    }else if ( $agency ) {
      $args['meta_query'] = array(
        array(
          'key'     => 'agency',
          'value'   => $agency,
          'compare' => '=',
        ),
      );
    }else if ( $client ) {
      $args['meta_query'] = array(
        array(
          'key'     => 'client',
          'value'   => $client,
          'compare' => '=',
        )
      );
    }else{
      wp_send_json_error();
    }

    //var_dump($args);

    $my_query= null;
    $my_query = new WP_Query();

    // 'meta_query' => array(
    //     array(
    //         'key'     => 'color',
    //         'value'   => 'blue',
    //         'compare' => 'NOT LIKE',
    //     ),
    // ),

    $my_query->query($args);


    if( $my_query->have_posts() ) {
      while($my_query->have_posts()):$my_query->the_post();
        $project_id = $my_query->post->ID;
        $reportarray[$project_id]['name'] = $my_query->post->post_title;

        $timing = get_field('timing',$project_id);
        $reportarray[$project_id]['timing'] = $timing;

        $client = get_field('client',$project_id);
        $reportarray[$project_id]['client'] = '';
        //var_dump($client);
        if ( $client ) $reportarray[$project_id]['client'] = $client->post_title;

        $agency = get_field('agency',$project_id);
        $reportarray[$project_id]['agency'] = '';
        //var_dump($client);
        if ( $agency ) $reportarray[$project_id]['agency'] = $agency->post_title;


        $args=array(
          'post_status'=>array('any'),
          'post_type'=>'timecard',
          'posts_per_page' => -1,
          'meta_query' => array(
              array(
                'key'     => 'project',
                'value'   => $project_id,
                'compare' => '=',
              ),
          ),
        );

        $totalone = 0;
        $p_query= null;
        $p_query = new WP_Query();

        $p_query->query($args);
        if ( $p_query->have_posts() ) {
          while($p_query->have_posts()):$p_query->the_post();

          $start = get_field('start');
          $end = get_field('end');

          if ( $start ) {
            $ds = new Carbon($start);
          }

          if ( $end ) {
            $de = new Carbon($end);
          }
          $mdiff = $ds->diffInMinutes($de);

          if ( $mdiff > 0 ) {
            $hours = $ds->diffInMinutes($de);
          }else{
            $hours = 30;
          }
          $hours = $hours / 60;
          $totalone += $hours;

          endwhile;

          wp_reset_query();
        }
        $reportarray[$project_id]['totalone'] = $totalone;

      endwhile;
      wp_reset_query();
      ob_start();
        ?>
        <table class="table table-striped- table-bordered table-hover" id="m_table_no_pag">
            <thead>
                <tr>
                    <th>PROGETTO</th>
                    <th>AGENZIA</th>
                    <th>CLIENTE</th>
                    <th>CONSEGNA</th>
    								<th>ORE LAVORATE</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($reportarray as $rr) { ?>
                <tr>
                  <td><?php echo $rr['name']; ?></td>
                  <td><?php echo $rr['agency']; ?></td>
                  <td><?php echo $rr['client']; ?></td>
      	          <td><?php echo $rr['timing']; ?></td>
      						<td><?php echo $rr['totalone'];?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        <?php
        $content = ob_get_clean();

        $response = array(
          'table' => $content,
        );
        wp_send_json_success($response);
    }else{
      wp_send_json_error();
    }

  }

  public function user_by_project_report() {

    $pid = $_POST['pid'];
    $department = $_POST['department'];

    if ( $pid ) {
      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'timecard',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'project',
                'value'    => $pid,
                'compare'    => '=',
            ),
        ),
      );

      //var_dump($args);


      $reportarray = array();
      $series = array();
      $cat = array();
      $p_query= null;
      $p_query = new WP_Query();

      $p_query->query($args);
      //var_dump($p_query);
      if ( $p_query->have_posts() ) {
        while($p_query->have_posts()):$p_query->the_post();

          $dep = get_field('department', 'user_'.$p_query->post->post_author);
          if ( !in_array($department,$dep) ) continue;
          //var_dump($department);
          //var_dump($dep);
          // if ( $rank != $_rank ) continue;

          $start = get_field('start');
          $end = get_field('end');

          if ( $start ) {
            $ds = new Carbon($start);
          }

          if ( $end ) {
            $de = new Carbon($end);
          }

          // $difference = $ds->diffInRealHours($de);
          // if ( $difference > 0 ) {
          //   $hours = $ds->diffInRealHours($de);
          //   //echo 'ore'.$ds->diffInRealHours($de).'<br/>';
          // }else{
          //   $hours = 0.5;
          // }

          $mdiff = $ds->diffInMinutes($de);

          if ( $mdiff > 0 ) {
            $hours = $ds->diffInMinutes($de);
          }else{
            $hours = 30;
          }
          $hours = $hours / 60;

          if (isset($reportarray[$p_query->post->post_author]) ) {
            $old = $reportarray[$p_query->post->post_author]['hours'];
            $reportarray[$p_query->post->post_author]['hours'] = $old + $hours;
          }else{
            $reportarray[$p_query->post->post_author]['hours'] = $hours;
          }

          $cat[$ds->weekOfYear] = $ds->weekOfYear;


          //$series[$p_query->post->post_author]['name'] = get_userdata($p_query->post->post_author)->display_name;
          //$series[$p_query->post->post_author]['data'][$ds->weekOfYear] = $hours;

          if (isset($series[$p_query->post->post_author][$ds->weekOfYear]) ) {

            $old = $series[$p_query->post->post_author][$ds->weekOfYear];
            $series[$p_query->post->post_author][$ds->weekOfYear] = $old + $hours;

          }else{
            $series[$p_query->post->post_author][$ds->weekOfYear] = $hours;
          }
        endwhile;
        wp_reset_query();

        ob_start();
        ?>

        <div class="buttons-toolbar"></div>
        <table class="table table-bordered" id="naive">
          <thead>
            <tr>
              <th data-sortable="true">Utenti</th>
              <th data-sortable="true">Ore</th>
            </tr>
          </thead>
          <tbody>
            <?php $total = 0; ?>
            <?php foreach ($reportarray as $kk => $vv) { ?>
              <?php $user_info = get_userdata($kk); ?>
              <?php if ( $user_info ) { ?>
              <?php $total += $vv['hours']; ?>
              <tr>
                <td><?php echo $user_info->display_name; ?></td>
                <td><?php echo $vv['hours']; ?></td>
              </tr>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <?php
        $content = ob_get_clean();


        $response = array(
          'table' => $content,
        );
        wp_send_json_success($response);

      }else{
        wp_send_json_error();
      }
    }else{
      wp_send_json_error();
    }

  }


  public function rv_group_report_tax2() {

    $tax = $_POST['tax'];

    if ( $tax ) {

      $tag = get_term_by( 'id', $tax, 'rvc-tag' );

    }else{
      wp_send_json_error();
    }

  }

  public function rv_group_report_tax() {

    $tax = $_POST['tax'];

    if ( $tax ) {
      $field = get_field_object('field_5b6476291f0aa');


      $tag = get_term_by( 'id', $tax, 'rvc-tag' );
      $reportarray = array();
      if( have_rows('estimates',$tag)  && $tag) {
        while ( have_rows('estimates',$tag) ) : the_row();
          $rankz = get_sub_field('rank');
          $hourz = get_sub_field('hour');
          $reportarray[$rankz]['budget'] = $hourz;
          $reportarray[$rankz]['budget'] = $hourz;
          $label = $field['choices'][ $rankz ];
          $reportarray[$rankz]['title'] = $label;
          $reportarray[$rankz]['hours'] = 0;
        endwhile;
      }

      // var_dump($reportarray);

      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'rvc',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'rvc-tag',
                'field'    => 'term_id',
                'terms'    => $tax,
            ),
        ),
      );


      $series = array();
      $cat = array();
      $p_query= null;
      $p_query = new WP_Query();

      $p_query->query($args);
      if ( $p_query->have_posts() ) {
        while($p_query->have_posts()):$p_query->the_post();

          $start = get_field('start');
          $end = get_field('end');

          if ( $start ) {
            $ds = new Carbon($start);
          }

          if ( $end ) {
            $de = new Carbon($end);
          }

          $mdiff = $ds->diffInMinutes($de);

  				if ( $mdiff > 0 ) {
  					$hours = $ds->diffInMinutes($de);
  				}else{
  					$hours = 30;
  				}
  				$hours = $hours / 60;

          $rank = get_field('rank', 'user_'.$p_query->post->post_author);


          $label = $field['choices'][ $rank ];
          // var_dump($label);
          // var_dump($rank);

          if (!isset($reportarray[$rank]['budget']) ) {
            if( have_rows('estimates',$tag)  && $tag) {
              while ( have_rows('estimates',$tag) ) : the_row();
                $rankz = get_sub_field('rank');
                // var_dump($rankz);
                if ( $rank == $rankz ) {
                  $hourz = get_sub_field('hour');
                  $reportarray[$rank]['budget'] = $hourz;
                  break;
                }else{
                  $reportarray[$rank]['budget'] = '0';
                }
              endwhile;
            }else{
              $reportarray[$rank]['budget'] = '0';
            }
          }else{

          }

          if ( !$label ) $label = 'Unknown';

          if (!isset($reportarray[$rank]['title']) ) {
            $reportarray[$rank]['title'] = $label;
          }

          if (isset($reportarray[$rank]) ) {
            $old = $reportarray[$rank]['hours'];
            $reportarray[$rank]['hours'] = $old + $hours;
          }else{
            $reportarray[$rank]['hours'] = $hours;
          }
        endwhile;
        wp_reset_query();

        ob_start();
        ?>

        <div class="buttons-toolbar"></div>
        <table class="table table-bordered" id="naive">
					<thead>
				    <tr>
              <th data-visible="false">ID</th>
				      <th data-sortable="true">Rank</th>
							<th data-sortable="true">Ore</th>
              <th data-sortable="true">Budget</th>
				    </tr>
				  </thead>
					<tbody>
            <?php $total = 0; ?>
						<?php foreach ($reportarray as $kk => $vv) { ?>
							<?php $user_info = get_userdata($kk); ?>
							<tr>
                <td><?php echo $kk; ?></td>
								<td><?php echo $vv['title']; ?></td>
								<td><?php echo $vv['hours']; ?></td>
                <td><?php echo $vv['budget']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
        <?php
        $content = ob_get_clean();

        $response = array(
          'table' => $content,
        );
        wp_send_json_success($response);

      }else{
        wp_send_json_error();
      }
    }else{
      wp_send_json_error();
    }
  }

  public function rv_get_attendance() {

    $uid = $_POST['uid'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $startDate = Carbon::today();
    $endDate = Carbon::today();

    if ( !empty($month) ) {
      $startDate->month = $month;
      $endDate->month = $month;
    }

    if ( !empty($year) ) {
      $startDate->year = $year;
      $endDate->year = $year;
    }


    $startDate = $startDate->startOfMonth();
    $endDate = $endDate->endOfMonth();

    $datearray = array();

    $period = CarbonPeriod::create($startDate, $endDate);
    foreach ($period as $date) {
        $datearray[] = $date->format('d');
        //echo $date->format('Y-m-d').'</br>';
    }


    $args = array(
      'orderby' => 'login',
      'order' => 'ASC',
      // 'include' => array(24),
      'exclude' => array( 1 ),
    );
    if ( !empty($uid) ) {
      $args['include'] = array($uid);
    }
    $users = get_users( $args );

    $uarray = array();
    foreach ( $users as $user ) {

      $hide_user = get_field('hide_user',$user);

      if ( !$hide_user ) {

        $uarray[$user->ID] = array(
          'nome' => $user->first_name.' '.$user->last_name,
        );

        $args = array (
          'post_type' => 'timecard',
          'post_status' => 'any',
          'posts_per_page' => -1,
          'meta_query' => array(
              array(
                  'key' => 'start',
                  'value'   => array( $startDate->toDateTimeString(), $endDate->toDateTimeString() ),
                  // 'type'    => 'DATETIME',
                  'compare' => 'BETWEEN',
              ),
           ),
           'author' => $user->ID
        );

        $my_query= null;
        $my_query = new WP_Query();

        $my_query->query($args);


      if( $my_query->have_posts() ) {
          while($my_query->have_posts()):$my_query->the_post();

            $hours = 0;
            $start = false;
            $end = false;

            $start = get_field('start');
            $end = get_field('end');



            if ( $start ) {
              $ds = new Carbon($start);

              $cday = $ds->format('d');

              if ( $end ) {
                $de = new Carbon($end);
              }




              $mdiff = $ds->diffInMinutes($de);

              if ( $mdiff > 0 ) {
                $hours = $mdiff;
              }else{
                $hours = 30;
              }

              $hours = $hours/60;

              if ( isset($uarray[$user->ID][$cday]) ) {
                $uarray[$user->ID][$cday] += $hours;
              }else{
                $uarray[$user->ID][$cday] = $hours;
              }



            }



          endwhile;

          wp_reset_query();
      }


      }
    }

    if ( $datearray ) {
      ob_start();
    ?>
    <h1 id="datareport"><?php echo $startDate->format('F Y'); ?></h1>
    <table class="table table-nowrap mb-0">
        <thead>
            <tr>
                <th>Utente</th>
                <?php foreach ($datearray as $da) { ?>
                    <th><?php echo $da; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
          <?php foreach ($uarray as $u) { ?>
            <tr>
              <td><?php echo $u['nome']; ?></td>
              <?php foreach ($datearray as $da) { ?>
                <td class="text-center">
                <?php if ( isset($u[$da]) ) { ?>
                  <?php
                  if ( $u[$da] <= 4 ) {
                    echo '<span class="label label-rounded label-danger">'.$u[$da].'</span>';
                  }elseif( $u[$da] <= 6 ) {
                    echo '<span class="label label-rounded label-warning">'.$u[$da].'</span>';
                  }else{
                    echo '<span class="label label-rounded label-success">'.$u[$da].'</span>';
                  }
                  ?>
                <?php }else{ ?>
                  -
                <?php } ?>
                </td>
              <?php } ?>
            </tr>
          <?php } ?>

      </tbody>
    </table>
    <?php
    $content = ob_get_clean();
    $response = array(
       'table' => $content,
    );
    wp_send_json_success($response);
  }else{
    wp_send_json_error();
  }

  }

}

Redvolver_Report::instance();
