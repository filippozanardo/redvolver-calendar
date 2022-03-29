<?php use Carbon\Carbon; ?>
<?php
$user = wp_get_current_user();
$allowed_roles = array( 'editor', 'administrator' );
if ( array_intersect( $allowed_roles, $user->roles ) ) {
}else{
	wp_safe_redirect( home_url() );
	exit;
}
?>
<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php

$pm_report_page = get_field('pm_report_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($project_report_page)
  );
	wp_login_form($args);
}else{ ?>

  <?php
  $pm_array = array();
  $chartdata = array();
  $total = 0;
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

    $pm = get_field('pm',$term);
    if ( $pm ) {
      $pm_array[$pm['ID']] = array(
        'y' => $pm_array[$pm['ID']]['y'] + 1,
        'name' => $pm['user_firstname'].' '.$pm['user_lastname']
      );
    }else{
      $pm_array['unassigned'] = array(
        'y' => $pm_array['unassigned']['y'] + 1,
        'name' => 'Unassigned'
      );
    }
    $total++;
  }

  if ( $pm_array ) {
    foreach ($pm_array as $key => $value) {
      $chartdata[] = $value;
    }
  }
  $chartdata = json_encode($chartdata);

  ?>


	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Subheader -->
		<div class="kt-subheader   kt-grid__item" id="kt_subheader">
			<div class="kt-container  kt-container--fluid ">
				<div class="kt-subheader__main">


				</div>
				<div class="kt-subheader__toolbar">
					<div class="kt-subheader__wrapper">


					</div>
				</div>
			</div>
		</div>

		<!-- end:: Subheader -->

		<!-- begin:: Content -->
		<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
			<div class="kt-portlet kt-portlet--mobile" id="port-let">
				<div class="kt-portlet__head kt-portlet__head--lg">
					<div class="kt-portlet__head-label">
						<span class="kt-portlet__head-icon">
							<i class="kt-font-brand flaticon2-line-chart"></i>
						</span>
						<h3 class="kt-portlet__head-title">
							PM REPORT
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						<div class="kt-portlet__head-wrapper">
							<div class="kt-portlet__head-actions">
							</div>
						</div>
					</div>
				</div>
				<div class="kt-portlet__body">


          <div id="pm-report-chart"></div>

          <div id="pm-report-table">
            <?php if ( $pm_array ) { ?>
            <table class="table table-bordered table-hover">
						  	<thead>
						    	<tr>
						      		<th>PM</th>
						      		<th>Projects</th>
						      		<th>Percentage</th>
						    	</tr>
						  	</thead>
						  	<tbody>
                  <?php foreach ($pm_array as $key => $value) { ?>
                    <tr>
  							      	<td><?php echo $value['name']; ?></td>
  							      	<td><?php echo $value['y']; ?></td>
  							      	<td>
                          <?php
                            $perc = ($value['y'] / 100) * $total;
                            echo $perc. '%';
                          ?>
                        </td>
  						    	</tr>
                  <?php } ?>


						  	</tbody>
						</table>
            <?php } ?>
          </div>

				</div>
			</div>
		</div>

	<!-- end:: Content -->
</div>

<?php } ?>

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
<script>
Highcharts.chart('pm-report-chart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'PM REPORT'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'PM',
        colorByPoint: true,
        data: <?php echo $chartdata; ?>
    }]
});
</script>
