<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use WeDevs\ORM\WP\Post as Post;

class Redvolver_Ajax {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}


	public function hooks() {
    add_action( 'wp_ajax_nopriv_del_post', array($this, 'del_post') );
    add_action( 'wp_ajax_del_post', array($this, 'del_post') );

    add_action( 'wp_ajax_nopriv_rv_project_report', array($this, 'rv_project_report') );
    add_action( 'wp_ajax_rv_project_report', array($this, 'rv_project_report') );

    add_action( 'wp_ajax_nopriv_rv_project_report_tax', array($this, 'rv_project_report_tax') );
    add_action( 'wp_ajax_rv_project_report_tax', array($this, 'rv_project_report_tax') );

    add_action( 'wp_ajax_nopriv_rv_client_report', array($this, 'rv_client_report') );
    add_action( 'wp_ajax_rv_client_report', array($this, 'rv_client_report') );

    add_action( 'wp_ajax_nopriv_rv_user_report', array($this, 'rv_user_report') );
    add_action( 'wp_ajax_rv_user_report', array($this, 'rv_user_report') );

    add_action( 'wp_ajax_nopriv_rv_add_asana', array($this, 'rv_add_asana') );
    add_action( 'wp_ajax_rv_add_asana', array($this, 'rv_add_asana') );

    add_action( 'wp_ajax_nopriv_rv_login', array($this, 'rv_login') );
    add_action( 'wp_ajax_rv_login', array($this, 'rv_login') );

    add_action( 'wp_ajax_nopriv_rv_get_timesheet', array($this, 'rv_get_timesheet') );
    add_action( 'wp_ajax_rv_get_timesheet', array($this, 'rv_get_timesheet') );

    add_action( 'wp_ajax_nopriv_rv_get_timesheet_excel', array($this, 'rv_get_timesheet_excel') );
    add_action( 'wp_ajax_rv_get_timesheet_excel', array($this, 'rv_get_timesheet_excel') );

    add_action( 'wp_ajax_nopriv_export_report', array($this, 'export_report') );
    add_action( 'wp_ajax_export_report', array($this, 'export_report') );

    add_action( 'wp_ajax_nopriv_rv_menu_scambio', array($this, 'rv_menu_scambio') );
    add_action( 'wp_ajax_rv_menu_scambio', array($this, 'rv_menu_scambio') );



    add_action( 'wp_ajax_nopriv_get_projects', array($this, 'get_projects') );
    add_action( 'wp_ajax_get_projects', array($this, 'get_projects') );

    add_action( 'wp_ajax_nopriv_change_password', array($this, 'change_password') );
    add_action( 'wp_ajax_change_password', array($this, 'change_password') );

    add_action( 'wp_ajax_nopriv_handle_dropped_media', array($this, 'handle_dropped_media') );
    add_action( 'wp_ajax_handle_dropped_media', array($this, 'handle_dropped_media') );

    add_action( 'wp_ajax_nopriv_delete_project_file', array($this, 'delete_project_file') );
    add_action( 'wp_ajax_delete_project_file', array($this, 'delete_project_file') );

    add_action( 'wp_ajax_nopriv_rv_download_all', array($this, 'rv_download_all') );
    add_action( 'wp_ajax_rv_download_all', array($this, 'rv_download_all') );

  }

  public function rv_download_all() {

  }

  public function delete_project_file() {
    $id = $_REQUEST['id'];
    $whoami = $_REQUEST['whoami'];

    if( have_rows('files', $whoami) ) {
      $new_repeater = array();
      while( have_rows('files', $whoami) ): the_row();

        $file = get_sub_field('file');
        if ( $file['ID'] != $id ) {
          $new_repeater[] = array(
            'file' => $file['ID']
          );
          //delete_row('files', get_row_index(), $whoami);
        }
      endwhile;
      update_field('files',$new_repeater, $whoami);
    }
    $del = wp_delete_attachment( $id, true );
    if ( $del ) {

      wp_send_json_success();
    }
    wp_send_json_error();
  }



  public function handle_dropped_media() {
    $id = $_REQUEST['id'];

    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'] . DIRECTORY_SEPARATOR;
    $num_files = count($_FILES['file']['tmp_name']);

    $newupload = 0;

    if ( !empty($_FILES) ) {
        $files = $_FILES;
        foreach ($_FILES as $file => $array) {
            if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) { // If there is some errors, during file upload
                wp_send_json_error();
            }

            // HANDLE RECEIVED FILE

            $post_id = 0; // Set post ID to attach uploaded image to specific post

            $attachment_id = media_handle_upload($file, $post_id);

            if (is_wp_error($attachment_id)) { // Check for errors during attachment creation
                wp_send_json_error();
            } else {
              $url = wp_get_attachment_url($attachment_id);
              $thumbnail_name = basename ( get_attached_file( $attachment_id ) );

              $row = array(
                  'file' => $attachment_id,
              );

              add_row('files', $row,$id);

              $out = '<li class="list-group-item" id="file_item_'.$attachment_id.'">';
                  $out .= '<div class="row">';
                      $out .= '<div class="col-md-9">';
                          $out .= '<span class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">'.$thumbnail_name.'</span>';
                      $out .= '</div>';
                      $out .= '<div class="col-md-3">';
                          $out .= '<a class="btn btn-icon btn-primary btn-circle btnpreview" data-url="'.$url.'">';
                            $out .= '<i class="fas fa-eye"></i>';
                          $out .= '</a>';
                          $out .= '<a href="'.$url.'" class="btn btn-icon btn-primary btn-circle" download>';
                            $out .= '<i class="fa fa-download"></i>';
                          $out .= '</a>';
                          $out .= '<a href="" class="btn btn-icon btn-primary btn-circle delete_file" data-id="'.$attachment_id.'">';
                            $out .= '<i class="fa fa-times"></i>';
                          $out .= '</a>';
                      $out .= '</div>';
                  $out .= '</div>';
              $out .= '</li>';

              $response = array(
                'attachment_id' => $attachment_id,
                'out' => $out
              );
              wp_send_json_success($response);

            }
        }

        // foreach($files as $file) {
        //     $newfile = array (
        //             'name' => $file['name'],
        //             'type' => $file['type'],
        //             'tmp_name' => $file['tmp_name'],
        //             'error' => $file['error'],
        //             'size' => $file['size']
        //     );
        //
        //     $_FILES = array('upload'=>$newfile);
        //     foreach($_FILES as $file => $array) {
        //         $newupload = media_handle_upload( $file, 0 );
        //     }
        // }
    }

    wp_send_json_error();

    // echo $newupload;
    // die();
  }



  public function change_password() {
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if (empty($new_password) && empty($confirm_new_password) ) {

      $response = array(
          'errors' => 'All fields are required'
      );
      wp_send_json_error($response);

    }

    if($new_password != $confirm_new_password){
      $response = array(
          'errors' => 'Password does not match'
      );
      wp_send_json_error($response);
    }
    if(strlen($new_password) < 6){
      $response = array(
          'errors' => 'Password is too short, minimum of 6 characters'
      );
      wp_send_json_error($response);
    }

    $current_user = wp_get_current_user();
    wp_set_password($new_password,$current_user->ID);
    wp_logout();
    wp_send_json_success();

  }

  public function del_post() {
    if ($_POST['id']) {
      wp_delete_post( $_POST['id'], true );
      wp_send_json_success();
    }else{
      wp_send_json_error();
    }
  }

  public function get_projects() {

    $args = array(
      'post_status'=>array('publish'),
      'post_type'=>'project',
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

  public function rv_menu_scambio() {
    $id = $_POST['id'];
    if ( $id ) {
      $value = $_POST['val'];
      RVC()->db->table('rv_botman_value')->where('id', $id)->update(['keyvalue' => $value]);

      switch ($value) {
        case 'YES':
          $btnclass = 'btn-yes';
          $btnicon = 'la la-check';
          break;
        case 'NO':
          $btnclass = 'btn-no';
          $btnicon = 'la la-close';
          break;
        case 'SELF':
          $btnclass = 'btn-self';
          $btnicon = 'la la-cutlery';
          break;
        default:
          $btnclass = 'btn-brand';
          $btnicon = '';
          break;
      }
      $out = '<button type="button" class="btn '.$btnclass.' btn-elevate-hover btn-icon btn-sm btn-icon-md btn-circle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="'.$btnicon.'"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right">

        <a class="dropdown-item scambio " href="#" data-id="'.$id.'" data-value="YES"><i class="la la-check"></i> YES</a>
        <a class="dropdown-item scambio " href="#" data-id="'.$id.'" data-value="NO"><i class="la la-close"></i> NO</a>
        <a class="dropdown-item scambio " href="#" data-id="'.$id.'" data-value="YES"><i class="la la-eur"></i> UL</a>
        <a class="dropdown-item scambio " href="#" data-id="'.$id.'" data-value="SELF"><i class="la la-cutlery"></i> SELF</a>

      </div>';
      $response = array(
        'out' => $out
      );
      wp_send_json_success($response);
    }
    wp_send_json_error();
  }

  public function export_report() {

    $user_id = $_POST['user_id'];
    $project_id = $_POST['project_id'];
    $start = $_POST['start'];
    $end = $_POST['end'];


    if ( $start ) {
      $ds =  Carbon::createFromFormat('d/m/Y H:i:s', $start.' 00:00:00');
    }

    if ( $end ) {
      $de =  Carbon::createFromFormat('d/m/Y H:i:s', $end.' 23:59:00');
    }

  	$args=array(
  		'post_status'=>array('future','publish'),
  		'post_type'=>'timecard',
  		'meta_query' => array(
  			'relation' => 'AND',
        array(
            'key'     => 'start',
            'value'   => $ds->format('Y-m-d H:i:s'),
            'compare' => '>=',
  					'type'			=> 'DATETIME'
        ),
  			array(
            'key'     => 'end',
            'value'   => $de->format('Y-m-d H:i:s'),
            'compare' => '<=',
  					'type'			=> 'DATETIME'
        ),
      ),
  		'posts_per_page' => -1,
  	);

    if( !empty($user_id) ) {
      $args['author'] = $user_id;
    }

    if ( !empty($project_id) ) {

      array_push($args['meta_query'], array(
        'key'     => 'project',
        'value'   => $project_id,
        'compare' => '=',
      ));
    }
    // if( !empty($pid) ) {
    //   $args['tax_query'] = array(
    //       array(
    //           'taxonomy' => 'rvc-tag',
    //           'field'    => 'term_id',
    //           'terms'    => $pid,
    //       ),
    //   );
    //
    // }
    // var_dump($args);
    // die();
  	$p_query= null;
  	$p_query = new WP_Query();
  	$p_query->query($args);

  	if ( $p_query->have_posts() ) {
  		while($p_query->have_posts()):$p_query->the_post();

        $id = $p_query->post->ID;
        $author = $p_query->post->post_author;
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

        $tt = '';
        $project = get_field('project');

        if ( $project) {
          if ( is_array($project) || is_object($project)  ) {
            $tt = $project->post_title;
          }else{
             $tt = get_the_title($project);
          }
        }


        $io = get_user_by('ID',$author);
        if ( $io->first_name &&  $io->last_name) {
          $ute = $io->first_name.' '.$io->last_name;
        }else{
          $ute = $io->display_name;
        }

        $fischia[$id] = array(
          'author' => $ute,
          'start' => $start,
          'end' => $end,
          'title' => $p_query->post->post_title,
          'hours' => $hours,
          'project' => $tt
        );

  		endwhile;
  		wp_reset_query();

      if ( $fischia ) {
        ob_start();
        ?>
        <table class="table table-striped- table-bordered table-hover" id="export_table">
					<thead>
				    <tr>
							<th>Utente</th>
              <th>Start</th>
              <th>End</th>
              <th>Title</th>
              <th>Hours</th>
              <th>Project</th>
				    </tr>
				  </thead>
					<tbody>
            <?php $total = 0; ?>
						<?php foreach ($fischia as $vv) { ?>
							<tr>
                <td><?php echo $vv['author']; ?></td>
                <td><?php echo $vv['start']; ?></td>
                <td><?php echo $vv['end']; ?></td>
                <td><?php echo $vv['title']; ?></td>
                <td><?php echo $vv['hours']; ?></td>
                <td><?php echo $vv['project']; ?></td>
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

    wp_send_json_error();

  }

  public function rv_get_timesheet(){

      $upload = wp_upload_dir();
      $upload_dir = $upload['basedir'];
      $upload_dir = $upload_dir . '/redvolver';
      if (! is_dir($upload_dir)) {
         mkdir( $upload_dir, 0775 );
      }
      $upurl = $upload['baseurl'].'/redvolver';

      if ( !empty( $_POST['date'] ) ) {

        $pieces = explode("/", $_POST['date']);

        $current_user = wp_get_current_user();

        $start = Carbon::createFromDate($pieces[1], $pieces[0],1);

        $end = $start->copy()->lastOfMonth();

        //wp_send_json_success($start->year.' '.$start->month.' '.$start->day. '      '.$end->year.' '.$end->month.' '.$end->day);

        $args=array(
          'post_status'=>array('future','publish'),
          'post_type'=>'rvc',
          'author' => $current_user->ID,
          'date_query' => array(
            array(
              'before'     => array(
                'year'  => $end->year,
                'month' => $end->month,
                'day'   => $end->day,
              ),
              'after'    => array(
                'year'  => $start->year,
                'month' => $start->month,
                'day'   => $start->day,
              ),
              'inclusive' => true,
            ),
          ),
          'posts_per_page' => -1,
        );
        $p_query= null;
        $p_query = new WP_Query();

        $time = array();

        $p_query->query($args);
        if ( $p_query->have_posts() ) {

          $nome = $current_user->ID.'-'.$pieces[1].'-'.$pieces[0];


          $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
          $mpdf->DefHTMLHeaderByName(
            'BHeader',
            '<div style="text-align: center; border-bottom: 1px solid #000000;font-size: 10pt;">Blossom S.n.c. di Giacomo Frigerio & C.</div>'
          );

          $mpdf->DefHTMLFooterByName(
            'BFooter',
            '<div style="text-align: center; font-weight: bold; font-size: 8pt;">
              <a href="http://www.blossoming.it">www.blossoming.it</a>
            </div>'
          );

          $stylesheet = file_get_contents(RVC_PLUGIN_URL.'/pdf/style.css');

          $mpdf->WriteHTML($stylesheet,1);

          $mpdf->SetHTMLHeaderByName('BHeader');
          $mpdf->SetHTMLFooterByName('BFooter');


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

            // $difference = $ds->diffInRealHours($de);
            // if ( $difference > 0 ) {
            //   $hours = $ds->diffInRealHours($de);
            //   //echo 'ore'.$ds->diffInRealHours($de).'<br/>';
            // }else{
            //   $hours = 0.5;
            // }

            if ( isset($time[$ds->day]) ) {
              $time[$ds->day] = $time[$ds->day] + $hours;
            }else{
              $time[$ds->day] = $hours;
            }

          endwhile;
          wp_reset_query();

          if ( $time ) {

            for ($i=1; $i <= 31 ; $i++) {
              if ( isset($time[$i]) ) {

              }else{
                $time[$i] = '-';
              }
            }

            ksort($time);

            $html = '<table>';
            $html .= '<thead>';
            $html .= '<tr>';
              $html .= '<th>Dipendente</th>';
              foreach ($time as $tkey => $tvalue) {
                $html .= '<th>'.$tkey.'</th>';
              }
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            $html .= '<tr>';
              if ( $current_user->first_name &&  $current_user->last_name) {
                $ute = $current_user->first_name.' '.$current_user->last_name;
              }else{
                $ute = $current_user->display_name;
              }
              $html .= '<td>'.$ute.'</td>';
              foreach ($time as $tkey => $tvalue) {
                $html .= '<td>'.$tvalue.'</td>';
              }
            $html .= '</tr>';
            $html .= '</tbody>';
            $html .= '</table>';
            $mpdf->WriteHTML($html);
          }
          $mpdf->Output($upload_dir.'/'.$nome.'.pdf', 'F');

      		$response = array(
            'redirect'        => $upurl.'/'.$nome.'.pdf'
          );
          wp_send_json_success($response);

        }else{

          $nome = $current_user->ID.'-'.$pieces[1].'-'.$pieces[0];
          $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
          $html = '<h1>Non ci sono dati per questo mese</h1>';
          $mpdf->WriteHTML($html);
          $mpdf->Output($upload_dir.'/'.$nome.'.pdf', 'F');
          $response = array(
            'redirect'        => $upurl.'/'.$nome.'.pdf'
          );
          wp_send_json_success($response);
        }
      }
      wp_send_json_error();
  }

  public function rv_get_timesheet_excel(){

      // $spreadsheet = new Spreadsheet();
      // $sheet = $spreadsheet->getActiveSheet();
      // $sheet->setCellValue('A1', 'Hello World !');
      // $upload = wp_upload_dir();
      // $upload_dir = $upload['basedir'];
      // $upload_dir = $upload_dir . '/redvolver';
      // $writer = new Xlsx($spreadsheet);
      // $writer->save($upload_dir.'/hello world.xlsx');


      $upload = wp_upload_dir();
      $upload_dir = $upload['basedir'];
      $upload_dir = $upload_dir . '/redvolver';
      if (! is_dir($upload_dir)) {
         mkdir( $upload_dir, 0700 );
      }
      $upurl = $upload['baseurl'].'/redvolver';

      if ( !empty( $_POST['month'] ) && !empty( $_POST['year'] ) ) {

        $pieces = explode("/", $_POST['date']);

        if ( !empty($_POST['user']) ) {
          $current_user = $user = get_user_by( 'ID', $_POST['user'] );
        }else{
          $current_user = wp_get_current_user();
        }


        $start = Carbon::createFromDate($_POST['year'], $_POST['month'],1);

        $end = $start->copy()->lastOfMonth();

        $period = CarbonPeriod::create($start, $end);
        //var_dump($period);

        $args=array(
          'post_status'=>array('future','publish'),
          'post_type'=>'timecard',
          'author' => $current_user->ID,
          'date_query' => array(
            array(
              'before'     => array(
                'year'  => $end->year,
                'month' => $end->month,
                'day'   => $end->day,
              ),
              'after'    => array(
                'year'  => $start->year,
                'month' => $start->month,
                'day'   => $start->day,
              ),
              'inclusive' => true,
            ),
          ),
          'posts_per_page' => -1,
        );
        $p_query= null;
        $p_query = new WP_Query();

        $timesheet = array();

        $p_query->query($args);
        if ( $p_query->have_posts() ) {

          $nome = $current_user->ID.'-'.$_POST['month'].'-'.$_POST['year'];

          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          $sheet->setCellValue('A2', 'DITTA:');
          $spreadsheet->getActiveSheet()->getStyle("B2")->getFont()->setSize(12)->setBold(true);
          $sheet->setCellValue('B2', 'MONTI 75 SRL');

          $spreadsheet->getActiveSheet()->getStyle("B4")->getFont()->setSize(14)->setBold(true);
          $sheet->setCellValue('B4', strtoupper( $start->locale('it')->monthName ) );

          // $sheet->setCellValue('A2', $pieces[1]);
          // $sheet->setCellValue('A3', 'Mese');
          // $sheet->setCellValue('A4', $pieces[0]);

          //$spreadsheet->getActiveSheet()->getStyle("E2")->getFont()->setSize(18)->setBold(true);

          while($p_query->have_posts()):$p_query->the_post();

            $start = get_field('start');
            $end = get_field('end');

            $type = get_field('type');
            $smart_working = get_field('smart_working');
            $cig = get_field('cig');
            //var_dump('type',$type);
            //if ( !$type) $type = 'regular';

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

            // $difference = $ds->diffInRealHours($de);
            // if ( $difference > 0 ) {
            //   $hours = $ds->diffInRealHours($de);
            //   //echo 'ore'.$ds->diffInRealHours($de).'<br/>';
            // }else{
            //   $hours = 0.5;
            // }

            if ( isset($timesheet[$ds->day]) ) {

              if ( $smart_working ) {
                $timesheet[$ds->day]['smart'] = $timesheet[$ds->day]['smart'] + $hours;
              }else if ( $cig ) {
                  $timesheet[$ds->day]['cig'] = $timesheet[$ds->day]['cig'] + $hours;
              }else{
                $timesheet[$ds->day][$type] = $timesheet[$ds->day][$type] + $hours;
              }

            }else{

              if ( $smart_working ) {
                $timesheet[$ds->day]['smart'] =  $hours;
              }else if ( $cig ) {
                  $timesheet[$ds->day]['cig'] =  $hours;
              }else{
                $timesheet[$ds->day][$type] = $hours;
              }

            }



          endwhile;
          wp_reset_query();

          if ( $timesheet ) {


            $spreadsheet->getActiveSheet()->getStyle("A6")->getFont()->setSize(11)->setBold(true);
            $sheet->setCellValue('A6', 'N. d\'ord');
            $sheet->setCellValue('A7', 1);

            $spreadsheet->getActiveSheet()->getStyle("B6")->getFont()->setSize(11)->setBold(true);
            $sheet->setCellValue('B6', 'Cognome e Nome');
            if ( $current_user->first_name &&  $current_user->last_name) {
              $ute = $current_user->first_name.' '.$current_user->last_name;
            }else{
              $ute = $current_user->display_name;
            }

            $sheet->setCellValue('B7', $ute);

            $sheet->setCellValue('C7', 'ore');

            $startcell = 'D';
            $startnumber = '6';
            $starttimesheet = 7;

            foreach ($period as $period_date) {
              $dayperiod = $period_date->format('j');
              $sheet->setCellValue($startcell.'6', $dayperiod);

              //$timesheet[$ds->day][$type] = $timesheet[$ds->day][$type] + $hours;


              if ( isset($timesheet[intval($dayperiod)]) ) {

                if ( is_array($timesheet[$dayperiod]) ) {
                  foreach ($timesheet[$dayperiod] as $typex => $tvalue ) {

                    $sheet->setCellValue($startcell.$starttimesheet, $tvalue.' '.$typex);
                    $starttimesheet++;
                  }
                  $starttimesheet = 7;
                }else{

                  $sheet->setCellValue($startcell.$starttimesheet, $timesheet[$ds->day]);
                }

              }else{
                $sheet->setCellValue($startcell.$starttimesheet, '-');
              }

              $startcell++;
              //var_dump($startcell);
              //var_dump($dayperiod);
            }

            // foreach ($time as $tkey => $tvalue) {
            //   $sheet->setCellValue($startcell.'6', $tkey);
            //   $startcell++;
            // }



            // $startcell = 'D';
            // $startnumber = '7';
            // foreach ($time as $tkey => $tvalue) {
            //   $sheet->setCellValue($startcell.'7', $tvalue);
            //   $startcell++;
            // }


            $spreadsheet->getActiveSheet()->mergeCells('B18:I18');
            $sheet->setCellValue('B19', 'LEGENDA');

            $sheet->setCellValue('B20', 'PERMESSI');
            $sheet->setCellValue('H20', 'P');

            $sheet->setCellValue('B21', 'INFORTUNIO');
            $sheet->setCellValue('H21', 'PI');

            $sheet->setCellValue('B22', 'MALATTIA');
            $sheet->setCellValue('H22', 'M');

            $sheet->setCellValue('B23', 'FERIE');
            $sheet->setCellValue('H23', 'fe');

            $sheet->setCellValue('B24', 'PERMESSO NON RETRIBUITO');
            $sheet->setCellValue('H24', 'PNR');

            $sheet->setCellValue('B25', 'PERMESSO DI STUDIO');
            $sheet->setCellValue('H25', 'PS');

            $sheet->setCellValue('B26', 'ASSENZA INGUSTIFICATA');
            $sheet->setCellValue('H26', 'AI');

            $sheet->setCellValue('B27', 'FESTIVITA\'');
            $sheet->setCellValue('H27', 'FS');

          }

          $writer = new Xlsx($spreadsheet);
          $writer->save($upload_dir.'/'.$nome.'.xlsx');

          // $writer2 = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
          // $writer2->save($upload_dir.'/'.$nome.'.pdf');

      		$response = array(
            'redirect'        => $upurl.'/'.$nome.'.xlsx'
          );
          wp_send_json_success($response);

        }else{
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          $sheet->setCellValue('A1', 'Non ci sono dati per questo mese');

          $nome = $current_user->ID.'-'.$_POST['month'].'-'.$_POST['year'];
          $writer = new Xlsx($spreadsheet);
          $writer->save($upload_dir.'/'.$nome.'.xlsx');

      		$response = array(
            'redirect'        => $upurl.'/'.$nome.'.xlsx'
          );
          wp_send_json_success($response);
        }
      }
      wp_send_json_error();
  }

  public function rv_login(){
    $return = array(); //What we send back

    parse_str($_REQUEST['form'], $output);

    //wp_send_json_error( $output );

		if( !empty($output['rvuser']) && !empty($output['rvpassword']) && trim($output['rvuser']) != '' && trim($output['rvpassword'] != '') ){

      $credentials = array(
        'user_login' => $output['rvuser'],
        'user_password'=> $output['rvpassword'],
        'remember' => !empty($output['remember'])
      );
			$loginResult = wp_signon($credentials);
      if(!is_wp_error($loginResult)){
        $redirect = wp_sanitize_redirect( get_site_url() );
        $return['redirect'] = $redirect;
        wp_send_json_success($return);
      }else{
  			$return['error'] = __('Please supply your username and password.', 'login-with-ajax');
        wp_send_json_error($return);
      }
    }else{

			$return['error'] = __('Please supply your username and password.', 'login-with-ajax');
      wp_send_json_error($return);
    }
    wp_send_json_error($return);
  }

  public function rv_add_asana(){

    //wp_send_json_success($_POST);

    $current_user = wp_get_current_user();
    $start = $_POST['asanadate'].' '.$_POST['asanastart'].':00';
    $end = $_POST['asanadate'].' '.$_POST['asanaend'].':00';

    $rv_post = array(
        'post_title'    => $_POST['asananame'],
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => $current_user->ID,
        'post_date' => $start,
        'post_type' => 'rvc',
        'meta_input' => array(
          'start' => $start,
          'end' => $end,
        )
    );

    // Insert the post into the database.
    $post_id = wp_insert_post($rv_post);
    if(!is_wp_error($post_id)){

      $term = get_term_by( 'name', $_POST['asanaproject'], 'rvc-tag'  );

      if ( $term ) {
        wp_set_post_terms( $post_id, array($term->term_id), 'rvc-tag', false);
      }else{
        $newterm = wp_insert_term(
            $_POST['asanaproject'],
            'rvc-tag'
        );
        wp_set_post_terms( $post_id, array($newterm['term_id']), 'rvc-tag', false);
      }

      $response = array(
          'id'        => $post_id
      );
      wp_send_json_success($response);
    }else{
      wp_send_json_error();
    }

  }

  public function rv_project_report_tax() {

    $tax = $_POST['tax'];

    if ( $tax ) {
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


      $reportarray = array();
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
            <tr>
              <td>TOTALE</td>
              <td><?php echo $total; ?></td>
            </tr>
					</tbody>
				</table>
        <?php
        $content = ob_get_clean();
        asort($cat);

        $newseries = array();
        foreach ($cat as $c) {
          foreach ($series as $u2 => $v2) {
            if (!isset($newseries['name'] ) ) {
              $newseries[$u2]['name'] = get_userdata($u2)->display_name;
            }
            if (isset($v2[$c]) ) {
              $newseries[$u2]['data'][$c] = $v2[$c];
            }else{
              $newseries[$u2]['data'][$c] = 0;
            }
          }
        }

        foreach ($series as $u2 => $v2) {
          $newseries[$u2]['data'] = array_values($newseries[$u2]['data']);
        }

        $response = array(
          'table' => $content,
          'cat' => array_values($cat),
          'series' => array_values($newseries)
        );
        wp_send_json_success($response);

      }else{
        wp_send_json_error();
      }
    }else{
      wp_send_json_error();
    }
  }


  public function rv_client_report() {

    $tax = $_POST['tax'];
    $farray = array();

    if ( $tax ) {
      $term_array = array();
    	$terms = get_terms('rvc-tag',array(
    		'hide_empty' => false,
        'meta_query' => array(
         array(
            'key'       => 'client',
            'value'     => $tax,
            'compare'   => '='
          )
        )
    	));

      if ( $terms) {
        foreach ($terms as $term) {
          $oggi = new Carbon();
      		$settimana = $oggi->weekOfYear;
      		$settimana = $settimana - 1;

          $priority = get_field('priority',$term);
          $budget = get_field('budget',$term);

          $start = get_field('start',$term);
          $end = get_field('end',$term);


          $farray[$term->term_id]['budget'] = $budget;
          $farray[$term->term_id]['name'] = $term->name;
          $farray[$term->term_id]['priority'] = $priority;
          $farray[$term->term_id]['start'] = $start;
          $farray[$term->term_id]['end'] = $end;
          $farray[$term->term_id]['week'] = 0;

          $args=array(
            'post_status'=>array('any'),
            'post_type'=>'rvc',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'rvc-tag',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
          );


          $totalone = 0;
          $reportarraytot = array();
          $userarraytot = array();
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

            $weekcheck = Carbon::createFromFormat('Y-m-d H:i:s', $start);
            $pweek = $weekcheck->weekOfYear;
            if ( $pweek == $settimana ) {
              $farray[$term->term_id]['week'] += $hours;
            }

            $totalone += $hours;
            $rank = get_field( 'rank', 'user_'.$p_query->post->post_author );
            if (!$rank) $rank = 'empty';

            if (isset($userarraytot[$p_query->post->post_author]) ) {
              $old = $userarraytot[$p_query->post->post_author]['hours'];
              $userarraytot[$p_query->post->post_author]['hours'] = $old + $hours;
            }else{
              $userarraytot[$p_query->post->post_author]['hours'] = $hours;
            }

            if (isset($reportarraytot[$rank]) ) {
              $old = $reportarraytot[$rank]['hours'];
              $reportarraytot[$rank]['hours'] = $old + $hours;
            }else{
              $reportarraytot[$rank]['hours'] = $hours;
            }

          endwhile;
          wp_reset_query();
          }
          $farray[$term->term_id]['totalone'] = $totalone;

          $delta = ( $totalone / $budget ) * 100;
          $farray[$term->term_id]['delta'] = $delta;

          if( have_rows('estimates',$term) ):

            // loop through the rows of data
              while ( have_rows('estimates',$term) ) : the_row();

                  $erank = get_sub_field('rank');
                  $ehour = get_sub_field('hour');

                  if (isset( $reportarraytot ) ) {
                    if ( isset($reportarraytot[$erank]) ) {
                      $perc = ($ehour / 100) * $reportarraytot[$erank]['hours'];
                      $diff = $reportarraytot[$erank]['hours'] - $ehour;
                      $farray[$term->term_id]['department'][$erank] = array(
                        'estimate' => $ehour,
                        'worked' => $reportarraytot[$erank]['hours'],
                        'perc' => $perc,
                        'diff' => $diff
                      );
                    }
                  }
              endwhile;
              if ( isset( $reportarraytot['empty']) ) {
                $farray[$term->term_id]['department']['empty'] = array(
                  'estimate' => '',
                  'worked' => $reportarraytot['empty']['hours'],
                  'perc' => '',
                  'diff' => ''
                );
              }


          endif;
        }
        ob_start();
        ?>
        <table class="table table-striped- table-bordered table-hover" id="m_table_no_pag">
            <thead>
                <tr>
                    <th>PROGETTO</th>
                    <th>PRIORITY</th>
                    <th>BUDGET</th>
    								<th>ORE LAVORATE</th>
    								<th>TIMELINE</th>
    								<th>ORE SETTIMANA</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($farray as $ff) { ?>

              <tr>
    									<td><?php echo $ff['name']; ?></td>
    	                <td><?php echo $ff['priority']; ?></td>
    									<td><?php echo $ff['$budget']; ?></td>
    									<td><?php echo $ff['totalone'];?>
    									</td>
    									<td>
    										<?php

                        if ($ff['start'] && $ff['end'] ) {

                          $inizio = Carbon::createFromFormat('d/m/Y',$ff['start']);
                          $fine = Carbon::createFromFormat('d/m/Y',$ff['end']);

                          $giorni = $fine->diffInDays($inizio);

                          $carbon = new Carbon();

                          $a1 = $carbon->diffInDays($inizio);
                          $a2 = $fine->diffInDays($carbon);

                          $a3 = ( $a1 / $giorni ) * 100;
                          echo round($a3). ' %';
                        }
    										?>
    									</td>
    									<td><?php echo $ff['week']; ?></td>

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

  public function rv_project_report() {
    $title = $_POST['title'];

    if ( $title ) {
      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'rvc',
        'posts_per_page' => -1,
        's' => $title
      );


      $reportarray = array();
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
        <table class="table table-bordered">
					<thead>
				    <tr>
				      <th scope="col">Utenti</th>
							<th scope="col">Ore</th>
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
            <tr>
              <td></td>
              <td><?php echo $total; ?></td>
            </tr>
					</tbody>
				</table>
        <?php
        $content = ob_get_clean();
        asort($cat);

        $newseries = array();
        foreach ($cat as $c) {
          foreach ($series as $u2 => $v2) {
            if (!isset($newseries['name'] ) ) {
              $newseries[$u2]['name'] = get_userdata($u2)->display_name;
            }
            if (isset($v2[$c]) ) {
              $newseries[$u2]['data'][$c] = $v2[$c];
            }else{
              $newseries[$u2]['data'][$c] = 0;
            }
          }
        }

        foreach ($series as $u2 => $v2) {
          $newseries[$u2]['data'] = array_values($newseries[$u2]['data']);
        }

        $response = array(
          'table' => $content,
          'cat' => array_values($cat),
          'series' => array_values($newseries)
        );
        wp_send_json_success($response);

      }else{
        wp_send_json_error();
      }
    }else{
      wp_send_json_error();
    }
  }


  public function rv_user_report() {

      $uid = $_POST['uid'];
      $start = $_POST['start'];
      $end = $_POST['end'];

    //if ( $uid ) {

      $reportarray = array();
      $authorarray = array();

      if ( empty($start) && empty($end) ) {
        $datestart = Carbon::now();
        $startOfWeek = $datestart->startOfWeek()->subDay();
        $weekDays = array();

        for ($i = 0; $i < Carbon::DAYS_PER_WEEK; $i++) {
            $weekDays[] = $startOfWeek->addDay()->startOfDay()->copy();
        }
      }else{

        $start = Carbon::createFromFormat('d/m/Y', $start);
        $end = Carbon::createFromFormat('d/m/Y', $end);
        $period = CarbonPeriod::create($start, $end);
        $weekDays = $period->toArray();

      }


      foreach ($weekDays as $day) {
        $d1 = $day;
        $d2 = $day->copy()->addDay();

        $args = array (
          'post_type' => 'rvc',
          'post_status' => 'any',
          'posts_per_page' => -1,
          'meta_query' => array(
              'relation' => 'AND',
              array(
                  'key'		=> 'start',
                  'compare'	=> '>=',
                  'value'     => $d1,
                  'type'			=> 'DATETIME'
              ),
              array(
                    'key'		=> 'end',
                    'compare'	=> '<=',
                    'value'     => $d2,
                    'type'			=> 'DATETIME'
              ),
           ),
           //'author' => $uid
        );
        if ($uid) $args['author'] = $uid;



        $p_query= null;
        $p_query = new WP_Query();

        $p_query->query($args);
        if ( $p_query->have_posts() ) {
          while($p_query->have_posts()):$p_query->the_post();

            $author = $p_query->post->post_author;
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


              $tt = 'noproject';
              $terms = get_the_terms( get_the_ID(), 'rvc-tag' );
              if ($terms) $tt = $terms[0]->name;

              if ( isset($reportarray[$tt]) ) {
                $reportarray[$tt] = $reportarray[$tt] + $hours;
              }else{
                $reportarray[$tt] =  $hours;
              }

              if ( isset($authorarray[$tt][$author]) ) {
    						$authorarray[$tt][$author] = $authorarray[$tt][$author] + $hours;
    					}else{
    						$authorarray[$tt][$author] =  $hours;
    					}




          endwhile;
          wp_reset_query();
        }

      }

      if ( $reportarray ) {
        ob_start();
        ?>
        <table class="table table-striped- table-bordered table-hover" id="remaketable">
					<thead>
				    <tr>
              <th></th>
							<th scope="col">Progetto</th>
              <th scope="col">Ore</th>
              <th class="none">Detail</th>
				    </tr>
				  </thead>
					<tbody>
            <?php $total = 0; ?>
						<?php foreach ($reportarray as $kk => $vv) { ?>
              <?php $total += $vv; ?>
							<tr>
                <td></td>
								<td><?php echo $kk; ?></td>
								<td><?php echo $vv; ?></td>
                <td>
                  <?php if (isset($authorarray[$kk])) { ?>
                    <?php foreach ($authorarray[$kk] as $ak => $av) { ?>
                      <?php
                        $io = get_user_by('ID',$ak);
                        if ( $io->first_name &&  $io->last_name) {
                          $ute = $io->first_name.' '.$io->last_name;
                        }else{
                          $ute = $io->display_name;
                        }
                      ?>
                      <?php echo $ute; ?> : <?php echo $av; ?><br/>
                    <?php } ?>
                  <?php } ?>
                </td>
							</tr>
						<?php } ?>
            <tr>
              <td></td>
              <td></td>
              <td><?php echo $total; ?></td>
              <td></td>
              <td></td>
            </tr>
					</tbody>
				</table>
        <?php
        $content = ob_get_clean();
        $response = array(
          'table' => $content,
        );
        wp_send_json_success($response);
      }else{
        wp_die();
      }

    //}else{
      //wp_die();
    //}
  }

}

Redvolver_Ajax::instance();
