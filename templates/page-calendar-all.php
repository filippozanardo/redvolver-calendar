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

$calendar_all_page = get_field('calendar_all_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_all_page)
  );
	wp_login_form($args);


}else{ ?>


	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container-fluid">

				<!--begin::Example-->
				<!--begin::Card-->
				<div class="card card-custom">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label">Global Calendar</h3>
						</div>
						<div class="card-toolbar">
						</div>
					</div>
					<div class="card-body">

						<div class="form-group">
								<label>Utenti</label>
								<div class="checkbox-inline">
									<?php
									$users = get_users( array(
										'orderby' => 'login',
										'order' => 'ASC',
										'exclude' => array( 1 )
									));
									foreach ( $users as $user ) {
										$color = dechex(rand(0x000000, 0xFFFFFF));
									?>
									<?php if ( $user->first_name != '' && $user->last_name != '') {
										$name = $user->first_name.' '.$user->last_name;
									}else{
										$name = $user->display_name;
									}
									?>
									<label class="checkbox">
										<input class="user_calendar" type="checkbox" id="inlineCheckbox<?php echo $user->ID; ?>" data-color="#<?php echo $color; ?>" data-id="<?php echo $user->ID; ?>" name="Checkboxes2">
										<span style="background: #<?php echo $color; ?>;border: 1px solid transparent!important;"></span> <?php echo $name; ?>
									</label>
									<?php
									}
									?>
								</div>
							</div>

						<div id="rv_calendar_all"></div>
					</div>
				</div>
				<!--end::Card-->


			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

<?php } ?>

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
