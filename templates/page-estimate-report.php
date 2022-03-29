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

$estimate_report_page = get_field('estimate_report_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($estimate_report_page)
  );
	wp_login_form($args);
}else{ ?>

	<?php

	$term_array = array();
	$terms = get_terms('rvc-tag',array(
		'hide_empty' => false,
	));
	if ( $terms ) {
		foreach ($terms as $term) {
			$closed = get_field('closed',$term);
			if ($closed) continue;
			$term_array[$term->term_id] = $term->name;
		}
	}
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
							Estimate Report
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
					<?php if ( $term_array) { ?>

						<div class="form-group">
							<label for="">Projects</label>
							<select class="form-control" id="tax-select-estimate">
								<option value=""></option>
								<?php foreach ($term_array as $k2 => $t) { ?>
									<option value="<?php echo $k2; ?>"><?php echo $t; ?></option>
								<?php } ?>
							</select>
						</div>
					<?php } ?>

					<div class="form-group">
						<label class="col-form-label">Seleziona PM</label>
						<select class="form-control" id="pm-select">
							<option value=""></option>
							<?php
							$users = get_users( array(
								'orderby' => 'login',
								'order' => 'ASC',
								'exclude' => array( 1 )
							));
							foreach ( $users as $user ) {
								$rank = get_field('rank', 'user_'.$user->ID);
			          if ( $rank != 'pm' ) continue;
							?>
								<option value="<?php echo $user->ID; ?>"><?php echo $user->first_name. ' '.$user->last_name; ?></option>
							<?php } ?>
						</select>
					</div>

					<div id="estimate-report-container"></div>
					<div id="estimate-chart-container"></div>

				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-lg-6">
							</div>
							<div class="col-lg-6 kt-align-right">
								<button id="goestimate_report" class="btn btn-success">GO</button>
							</div>
						</div>
					</div>
				</div>
				</div>
				</div>

				<!-- end:: Content -->
	</div>


<?php } ?>

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
