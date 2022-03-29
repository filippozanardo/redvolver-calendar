<?php
use Carbon\Carbon;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class Redvolver_Project {

  private static $_instance = null;

  public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->hooks();
		}
		return self::$_instance;
	}

  public function hooks() {

    add_action( 'wp_ajax_nopriv_add_project', array($this, 'add_project') );
    add_action( 'wp_ajax_add_project', array($this, 'add_project') );

    add_action( 'wp_ajax_nopriv_get_project_pdf', array($this, 'get_project_pdf') );
    add_action( 'wp_ajax_get_project_pdf', array($this, 'get_project_pdf') );

    add_action( 'wp_ajax_nopriv_fetch_project_time', array($this, 'fetch_project_time') );
    add_action( 'wp_ajax_fetch_project_time', array($this, 'fetch_project_time') );

    add_action( 'wp_ajax_nopriv_project_report', array($this, 'project_report') );
    add_action( 'wp_ajax_project_report', array($this, 'project_report') );


  }

  public function project_report() {

    $pid = $_POST['pid'];

    if ( $pid ) {
      $field = get_field_object('field_609e36f08b9f5');

      $reportarray = array();

      // var_dump($reportarray);
      $args=array(
        'post_status'=>array('future','publish'),
        'post_type'=>'timecard',
        'posts_per_page' => -1,
        'meta_query' => array(
          array(
              'key'     => 'project',
              'value'   => $pid,
              'compare' => '=',
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


          $department = get_field('department', 'user_'.$p_query->post->post_author);

          if ( is_array($department) ) $department = $department[0];
          $label = $field['choices'][ $department ];

          // var_dump($department);
          // var_dump($field);
          // var_dump($label);

          if ( !$label ) $label = 'Unknown';

          if (!isset($reportarray[$department]['title']) ) {
            $reportarray[$department]['title'] = $label;
          }

          if (isset($reportarray[$department]) ) {
            $old = $reportarray[$department]['hours'];
            $reportarray[$department]['hours'] = $old + $hours;
          }else{
            $reportarray[$department]['hours'] = $hours;
          }
        endwhile;
        wp_reset_query();

        ob_start();
        ?>

        <div id="toolbar" class="buttons-toolbar"></div>
        <table class="table table-bordered" id="naive">
					<thead>
				    <tr>
              <th data-visible="false">ID</th>
				      <th data-sortable="true">Dipartimento</th>
							<th data-sortable="true">Ore</th>
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

  public function fetch_project_time() {

    $start = $_POST['start'];
    $end = $_POST['end'];

    if ($start && $end) {
      $startp = explode("-", $start);
      $endp = explode("-", $end);

      $start = str_replace('-', '', $start);
      $end = str_replace('-', '', $end);

      $args=array(
        'post_status'=>array('publish'),
        'post_type'=>'project',
        // 'date_query' => array(
        //   array(
        //     'before'     => array(
        //       'year'  => $endp[0],
        //       'month' => $endp[1],
        //       'day'   => $endp[2],
        //     ),
        //     'after'    => array(
        //       'year'  => $startp[0],
        //       'month' => $startp[1],
        //       'day'   => $startp[2],
        //     ),
        //     'inclusive' => true,
        //   ),
        // ),
        'tax_query' => array(
            array(
                'taxonomy' => 'project_status',
                'field'    => 'term_id',
                'terms'    => array( 5, 2 ),
                'operator' => 'NOT IN',
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

        $pstart = get_field('start');
        $pend = get_field('end');

        if ( $pstart && $pend ) {

          $pd = explode("/", $pstart);

          $s = new Carbon();
          $s->year = $pd[2];
          $s->month = $pd[1];
          $s->day = $pd[0];

          $pde = explode("/", $pend);

          $e = new Carbon();
          $e->year = $pde[2];
          $e->month = $pde[1];
          $e->day = $pde[0];

          $status = get_the_terms($p_query->post->ID,'project_status');

          if ( $status ) {
            $sta = $status[0]->slug;
          }else{
            $sta = '';
          }


          $arrayeventi[] = array(
              'id' => $p_query->post->ID,
              'title' => $p_query->post->post_title,
              'start' => $s->format('Y-m-d H:i:s'),
              'end' => $e->format('Y-m-d H:i:s'),
              'allDay' => true,
              // 'color' => '#F1F1F1',
              // 'textColor' => '#FFFFFF',
              // 'borderColor' => '#000000',
              'className' => $sta,
              // 'extendedProps' => array(
              //   'project' => $project,
              //   'type' => $type,
              //   'smart_working' => $smart_working,
              //   'cig' => $cig,
              // )
          );
        }

        endwhile;
      }
      wp_reset_query();

      $out = json_encode($arrayeventi,JSON_UNESCAPED_UNICODE);

      wp_die($out);
      }else{
        wp_die();
      }



  }

  public function add_project() {
    // var_dump($_REQUEST['content']);
    // var_dump( $_REQUEST['request'] );
    //   var_dump( esc_html(wp_kses_stripslashes($_REQUEST['request']) ));
    //   var_dump( stripslashes( $_REQUEST['request'] ) );
    //   die();

    //var_dump($_REQUEST);
    //die();



    // $args = array(
    //   'post_type' => 'project',
    //   'posts_per_page'=> -1,
    //   'date_query' => array(
    //     array(
    //       'year' => date( 'Y' ),
    //     ),
    //   ),
    //   'tax_query' => array(
    //       array(
    //           'taxonomy' => 'project_type',
    //           'field'    => 'term_id',
    //           'terms'    => $_REQUEST['project_type'],
    //       ),
    //   ),
    // );
    // $query = new WP_Query( $args );
  
    // if( $query->found_posts == 0 ) {
    //   $incremental = 1;
    // }else{
    //   $incremental = $query->found_posts + 1;
    // }

    // var_dump($query->found_posts);
    // var_dump($incremental);
    // die();
    
    

    if ( !isset($_REQUEST['projectname']) ||  $_REQUEST['projectname'] == '' ) wp_send_json_error();
    $current_user = wp_get_current_user();

    $args = array(
        'post_title'    => $_REQUEST['projectname'],
        'post_content'  => $_REQUEST['content'],
        'post_status'   => 'publish',
        'post_author'   => $current_user->ID,
        'post_type' => 'project',
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

      $t = sanitize_title(get_the_title($post_id) );
      $app = new DropboxApp("6fzczdj5u1p7sse", "kqz8gr9ly5wfvgf","Yd7zOPj_C1gAAAAAAAAAAVJey36OcLA0kwqrmoQKDD01OiDCEzcOWcn_Jkw5YXiX");
    	$dropbox = new Dropbox($app);

      try {
        $listFolderContents = $dropbox->listFolder("/JCalendar/".$t);
      } catch (Exception $e) {

        // $folder = $dropbox->createFolder("/JCalendar/".$t);
        // $folder = $dropbox->createFolder("/JCalendar/".$t.'/sviluppo');
        // $folder = $dropbox->createFolder("/JCalendar/".$t.'/pm');

        $folder = $dropbox->createFolder("/JCalendar/".$t);
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM/Preventivo');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM/Presentazione finale');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM/Preventivo fornitori');

    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Grafici');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Sviluppo');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Produzione');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Produzione/Esecutivi produzione');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Produzione/Gestione staff');

    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente/Reference cliente');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente/Brief cliente');
    		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente/Varie cliente');
        
      }

      if ( $_REQUEST['project_type'] ) {
        update_field('project_type', $_REQUEST['project_type'], $post_id);
      }


      $counter = RVC()->db->table('rv_counter')
            ->where('year', date( 'Y' ))
            ->where('term_id', $_REQUEST['project_type'])->first();

      if ( $_REQUEST['projectcode'] ) {

        $pieces = explode("-", $_REQUEST['projectcode']);

        $tipo = $pieces[0];
        $anno = $pieces[1];
        $num = $pieces[2];
        $titolo = $pieces[3];
       

        if ( isset( $_REQUEST['pid'] ) ) {

          $oldcode = get_field('code',$post_id);

          if ( $oldcode ) {
            $oldpieces = explode("-", $oldcode);
          
            $oldtipo = $oldpieces[0];
            if ( $oldtipo !== $tipo ) {
              
              if ( $counter ) {
                $incremental = $counter->counter + 1;
                RVC()->db->table('rv_counter')->where('id', $counter->id)->update([
                  'counter' => $incremental,
                ]);
    
              }else{
                $incremental = 1;
                RVC()->db->table('rv_counter')->insert([
                  'year' => date( 'Y' ),
                  'term_id' => $_REQUEST['project_type'],
                  'term_slug' => $tipo,
                  'counter' => $incremental,
                ]);
              }
              $number = str_pad($incremental, 2, '0', STR_PAD_LEFT);

              $arr = array($tipo,$anno,$number, $titolo);
              $codestring = join("-",$arr);
  
              update_field('code', $codestring, $post_id);
            }else{
              $arr = array($tipo,$anno,$oldpieces[2], $titolo);
              $codestring = join("-",$arr);
              update_field('code', $codestring, $post_id);
            }
          }else{
            
            if ( $counter ) {
              $incremental = $counter->counter + 1;
              RVC()->db->table('rv_counter')->where('id', $counter->id)->update([
                'counter' => $incremental,
              ]);
  
            }else{
              $incremental = 1;
              RVC()->db->table('rv_counter')->insert([
                'year' => date( 'Y' ),
                'term_id' => $_REQUEST['project_type'],
                'term_slug' => $tipo,
                'counter' => $incremental,
              ]);
            }

            $number = str_pad($incremental, 2, '0', STR_PAD_LEFT);

            $arr = array($tipo,$anno,$number, $titolo);
            $codestring = join("-",$arr);
            update_field('code', $codestring, $post_id);
          }
          
          
          

        }else{


          if ( $counter ) {
            $incremental = $counter->counter + 1;
            RVC()->db->table('rv_counter')->where('id', $counter->id)->update([
              'counter' => $incremental,
            ]);

          }else{
            $incremental = 1;
            RVC()->db->table('rv_counter')->insert([
              'year' => date( 'Y' ),
              'term_id' => $_REQUEST['project_type'],
              'term_slug' => $tipo,
              'counter' => $incremental,
            ]);
          }
          
          $number = str_pad($incremental, 2, '0', STR_PAD_LEFT);

          $arr = array($tipo,$anno,$number, $titolo);
          $codestring = join("-",$arr);

          update_field('code', $codestring, $post_id);
        }
        
      }

      if ( $_REQUEST['referent'] ) {
        
        $refarray = array();
        if (is_array($_REQUEST['referent']) ) {
          foreach ($_REQUEST['referent'] as $key => $ref) {
            $refarray[] = $ref;
          }
          update_field('referent', $refarray, $post_id);
        }else{
          update_field('referent', $_REQUEST['referent'], $post_id);
        }
      }else{
        update_field('referent', '', $post_id);
      }

      if ( $_REQUEST['pm'] ) {
        $pmarray = array();
        if (is_array($_REQUEST['pm']) ) {
          foreach ($_REQUEST['pm'] as $key => $ref) {
            $pmarray[] = $ref;
          }
          update_field('pm', $pmarray, $post_id);
        }else{
          update_field('pm', $_REQUEST['pm'], $post_id);
        }
      }else{
        update_field('pm', '', $post_id);
      }

      if ( $_REQUEST['graphic'] ) {

        $pmarray = array();
        if (is_array($_REQUEST['graphic']) ) {
          foreach ($_REQUEST['graphic'] as $key => $ref) {
            $pmarray[] = $ref;
          }
          update_field('graphic', $pmarray, $post_id);
        }else{
          update_field('graphic', $_REQUEST['graphic'], $post_id);
        }

      }else{
        update_field('graphic', '', $post_id);
      }

      if ( $_REQUEST['dev'] ) {

        $pmarray = array();
        if (is_array($_REQUEST['dev']) ) {
          foreach ($_REQUEST['dev'] as $key => $ref) {
            $pmarray[] = $ref;
          }
          update_field('dev', $pmarray, $post_id);
        }else{
          update_field('dev', $_REQUEST['dev'], $post_id);
        }
      }else{
        update_field('dev', '', $post_id);
      }

      if ( $_REQUEST['client'] ) {
        update_field('client', $_REQUEST['client'], $post_id);
      }

      if ( $_REQUEST['agency'] ) {
        update_field('agency', $_REQUEST['agency'], $post_id);
      }

      if ( $_REQUEST['timing'] ) {
        update_field('timing', $_REQUEST['timing'], $post_id);
      }

      if ( $_REQUEST['on_air'] ) {
        update_field('on_air', $_REQUEST['on_air'], $post_id);
      }

      if ( $_REQUEST['datestart'] ) {
        if ( $_REQUEST['datestart'] !== 'Invalid date' ) {
          update_field('start', $_REQUEST['datestart'], $post_id);
        }
      }

      if ( $_REQUEST['dateend'] ) {
        if ( $_REQUEST['dateend'] !== 'Invalid date' ) {
          update_field('start', $_REQUEST['dateend'], $post_id);
        }
      }

      if ( $_REQUEST['rentman'] ) {
        update_field('rentman', $_REQUEST['rentman'], $post_id);
      }

      if ( $_REQUEST['archive'] ) {
        update_field('archive', $_REQUEST['archive'], $post_id);
      }else{
        update_field('archive', 0, $post_id);
      }

      if ( $_REQUEST['status'] ) {
        update_field('status', $_REQUEST['status'], $post_id);
      }

      

      $onairs = array();
      if ($_REQUEST['onairs']) {
          foreach ($_REQUEST['onairs'] as $value) {
            if ( $value ) {
              $onairs[] = array(
                'on_air_title' => $value['title'],
                'on_air_start' => $value['start'],
                'on_air_end' => $value['end'],
              );
            }
          }
      }
      update_field( 'onair', $onairs, $post_id );




      $brief = RVC()->db->table('rv_brief')->where('project_id', $post_id)->first();
      

  		if ( $brief ) {

        

  			RVC()->db->table('rv_brief')->where('project_id', $post_id)->update([
          'user_id' => $current_user->ID,
          'brand' => $_REQUEST['brand'],
          'sector' => $_REQUEST['sector'],
          'contact' => $_REQUEST['contact'],
          'duration' => $_REQUEST['duration'],
          'target' => $_REQUEST['target'],
          'objective' => $_REQUEST['objective'],
          'concept' => $_REQUEST['concept'],
          'staff' => $_REQUEST['staff'],
          'location' => $_REQUEST['location'],
          'output' => $_REQUEST['output'],
          'delivery' => $_REQUEST['delivery'],
          'budget' => $_REQUEST['budget'],
          'request' => stripslashes($_REQUEST['request']),
  			]);
  		}else{
  			RVC()->db->table('rv_brief')->insert([
  				'project_id' => $post_id,
          'user_id' => $current_user->ID,
          'brand' => $_REQUEST['brand'],
          'sector' => $_REQUEST['sector'],
          'contact' => $_REQUEST['contact'],
          'duration' => $_REQUEST['duration'],
          'target' => $_REQUEST['target'],
          'objective' => $_REQUEST['objective'],
          'concept' => $_REQUEST['concept'],
          'staff' => $_REQUEST['staff'],
          'location' => $_REQUEST['location'],
          'output' => $_REQUEST['output'],
          'delivery' => $_REQUEST['delivery'],
          'budget' => $_REQUEST['budget'],
          'request' => stripslashes($_REQUEST['request']),
  			]);
  		}

      $url = '';
      if ( isset( $_REQUEST['pid'] ) ) {
      }else{
        $project_add_page = get_field('project_add_page','option');
        $url= get_permalink($project_add_page).'?pid='.$post_id;
      }



      $response = array(
         'url' => $url,
      );
      wp_send_json_success($response);
    }else{
      wp_send_json_error();
    }

    // if ( isset( $_REQUEST['pid'] ) ) {
    //   $updatearray = array(
    //     'description' => $_REQUEST['tagdescription']
    //   );
    //   $update = wp_update_term( $_REQUEST['pid'], 'rvc-tag', $updatearray );
    // }else{
    //   $updatearray = array(
    //     'description' => $_REQUEST['tagdescription']
    //   );
    //   $update = wp_insert_term( $_REQUEST['tagname'], 'rvc-tag', $updatearray );
    // }
    //
    //   if ( ! is_wp_error( $update ) ) {
    //
    //
    //     $post_id = "rvc-tag_".$update['term_id'];
    //
    //     update_field( 'client', $_REQUEST['rvclient'], $post_id );
    //     update_field( 'pm', $_REQUEST['rvpm'], $post_id );
    //     update_field( 'priority', $_REQUEST['priority'], $post_id );
    //
    //
    //     if ( $_REQUEST['datestart'] ) {
    //       $ddr = Carbon::createFromFormat('d/m/Y', $_REQUEST['datestart']);
    //       update_field( 'field_5b9903381f202', $ddr->format('Ymd'), $post_id );
    //     }
    //
    //     if ( $_REQUEST['dateend'] ) {
    //       $ddre = Carbon::createFromFormat('d/m/Y', $_REQUEST['dateend']);
    //       update_field( 'field_5b9903481f203', $ddre->format('Ymd'), $post_id );
    //     }
    //
    //     if ( isset($_REQUEST['closed']) ) {
    //       update_field( 'closed', 1, $post_id );
    //     }else{
    //       update_field( 'closed', '', $post_id );
    //     }
    //     update_field( 'summary', $_REQUEST['summary'], $post_id );
    //     update_field( 'budget', $_REQUEST['budget'], $post_id );
    //
    //     $reaper = array();
    //
    //     if ($_REQUEST['rank']) {
    //       foreach ($_REQUEST['rank'] as $value) {
    //         if ( $value ) {
    //           $reaper[] = array( 'rank' => $value['rank'] , 'hour' => $value['hour'] );
    //         }
    //       }
    //     }
    //     update_field( 'estimates', $reaper, $post_id );
    //     $url = '';
    //     if ( isset( $_REQUEST['pid'] ) ) {
    //     }else{
    //       $tag_add_page = get_field('tag_add_page','option');
    //       $url= get_permalink($tag_add_page).'?pid='.$update['term_id'];
    //     }
    //
    //     $response = array(
    //        'url' => $url,
    //     );
    //     wp_send_json_success($response);
    //   }else{
    //     wp_send_json_error();
    //   }

    wp_send_json_error();
  }

  public function get_project_pdf() {
      $upload = wp_upload_dir();
      $upload_dir = $upload['basedir'];
      $upload_dir = $upload_dir . '/redvolver';
      if (! is_dir($upload_dir)) {
         mkdir( $upload_dir, 0700 );
      }
      $upurl = $upload['baseurl'].'/redvolver';

      if ( isset( $_REQUEST['id'] ) ) {

        $args=array(
          'post_type'=>'project',
          'p' => $_REQUEST['id'],
        );
        $p_query= null;
        $p_query = new WP_Query();
        $p_query->query($args);
        if ( $p_query->have_posts() ) {

          $mpdf = new \Mpdf\Mpdf([
            //'orientation' => 'L'
          ]);
          $mpdf->DefHTMLHeaderByName(
            'BHeader',
            '<div style="text-align: center; border-bottom: 1px solid #000000;font-size: 10pt;color:#36a9e1;">Johannes Pics</div>'
          );

          $mpdf->DefHTMLFooterByName(
            'BFooter',
            '<div style="text-align: center; font-weight: bold; font-size: 8pt;">
              <a href="https://www.johannes.pics/">www.johannes.pics/</a>
            </div>'
          );
          $stylesheet = file_get_contents(RVC_PLUGIN_URL.'/pdf/style.css');

          $mpdf->WriteHTML($stylesheet,1);
          $mpdf->SetHTMLHeaderByName('BHeader');
          $mpdf->SetHTMLFooterByName('BFooter');
          while($p_query->have_posts()):$p_query->the_post();
           $name = $p_query->post->post_title;
           $html = '<h1>'.$name.'</h1>';
           $mpdf->WriteHTML($html);
          endwhile;
          $mpdf->Output($upload_dir.'/'.$name.'.pdf', 'F');
          $response = array(
            'redirect'        => $upurl.'/'.$name.'.pdf',
            'name'        => $name
          );
          wp_send_json_success($response);
        }else{

          wp_send_json_error();
        }
      }else{

        wp_send_json_error();
      }

  }



}

Redvolver_Project::instance();
