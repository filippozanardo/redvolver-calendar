<?php use Carbon\Carbon;
use Carbon\CarbonPeriod; ?>
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
							User Report
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

					<div class="form-group">
						<label class="col-form-label">Seleziona Utente</label>
						<select class="form-control" id="u-select">
							<option value=""></option>
							<?php
							$users = get_users( array(
								'orderby' => 'login',
								'order' => 'ASC',
								'exclude' => array( 1 )
							));
							foreach ( $users as $user ) {
							?>
								<option value="<?php echo $user->ID; ?>"><?php echo $user->first_name. ' '.$user->last_name; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group row">
						<div class="col-lg-6">
							<label>Data Inizio:</label>
							<input type="text" class="form-control" placeholder="Data Inizio" id="datestart" name="datestart" readonly>
						</div>
						<div class="col-lg-6">
							<label class="">Data Fine:</label>
							<input type="text" class="form-control" placeholder="Data Fine" id="dateend" name="dateend" readonly>
						</div>
					</div>

					<div id="user-report-container"></div>

				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-lg-6">
							</div>
							<div class="col-lg-6 kt-align-right">
								<button id="gouser_report" class="btn btn-success">GO</button>
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
