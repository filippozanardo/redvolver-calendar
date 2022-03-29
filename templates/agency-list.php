<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php
$calendar_page = get_field('calendar_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_page)
  );

	wp_login_form($args);
}else{ ?>
	<?php $agency_add_page = get_field('agency_add_page','option'); ?>

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
							<h3 class="card-label">Agencies</h3>
						</div>
						<div class="card-toolbar">
							<?php if ( $agency_add_page ) { ?>
							<a href="<?php echo get_permalink($agency_add_page); ?>" class="btn btn-light-primary font-weight-bold">
								<i class="ki ki-plus icon-md mr-2"></i>New Agency
							</a>
							<?php } ?>
						</div>
					</div>
					<div class="card-body">
						<div class="d-flex justify-content-end mb-6">

							<a href="#" class="btn btn-primary mr-3" id="agencyxls">
								<i class="la la-file-excel-o"></i>excel
							</a>

							<a href="#" class="btn btn-outline-primary mr-3" id="agencypdf">
								<i class="la la-file-excel-o"></i>pdf
							</a>

						</div>

						<div id="agency-tabulator"></div>
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
