<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php

$calendar_page = get_field('calendar_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_page)
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
							<h3 class="card-label">Project Calendar</h3>
						</div>
						<div class="card-toolbar">
							<!-- <a href="#" class="btn btn-light-primary font-weight-bold">
								<i class="ki ki-plus icon-md mr-2"></i>Add Event
							</a> -->
						</div>
					</div>
					<div class="card-body">
						<div id="rv_calendar_project"></div>
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
