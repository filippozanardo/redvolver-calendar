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

        <!--begin::Card-->
				<div class="card card-custom gutter-b">
					<div class="card-body">
						<div class="d-flex">
							<!--begin: Info-->
							<div class="flex-grow-1">
								<!--begin: Title-->
								<div class="d-flex align-items-center flex-wrap justify-content-between">
									<div class="flex-grow-1 font-weight-bold py-5 py-lg-2 mr-5">
										<!--begin::Name-->
										<a href="javascript:void(0);" class="d-flex align-items-center text-dark text-hover-primary font-size-h1 font-weight-bold mr-3">
                      <?php the_title(); ?>
                    </a>
										<!--end::Name-->

									</div>
								</div>
								<!--end: Title-->
								<div class="separator separator-solid my-7"></div>
								<!--begin: Content-->
								<div class="d-flex align-items-center flex-wrap justify-content-between">
									<div class="flex-grow-1 font-weight-bold py-5 py-lg-2 mr-5">
                    <?php the_content(); ?>
                  </div>

									<!-- <div class="d-flex flex-wrap align-items-center py-2">
										<div class="d-flex align-items-center mr-10">
											<div class="mr-6">
												<div class="font-weight-bold mb-2">Start Date</div>
												<span class="btn btn-sm btn-text btn-light-primary text-uppercase font-weight-bold">07 May, 2020</span>
											</div>
											<div class="">
												<div class="font-weight-bold mb-2">Due Date</div>
												<span class="btn btn-sm btn-text btn-light-danger text-uppercase font-weight-bold">10 June, 2021</span>
											</div>
										</div>
										<div class="flex-grow-1 flex-shrink-0 w-150px w-xl-300px mt-4 mt-sm-0">
											<span class="font-weight-bold">Progress</span>
											<div class="progress progress-xs mt-2 mb-2">
												<div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											<span class="font-weight-bolder text-dark">78%</span>
										</div>
									</div> -->
								</div>
								<!--end: Content-->
							</div>
							<!--end: Info-->
						</div>
						<div class="separator separator-solid my-7"></div>
						<!--begin: Items-->
						<div class="d-flex align-items-center flex-wrap">

							<?php $referent = get_field('referent'); ?>
							<?php if ( $referent ) { ?>
							<!--begin: Item-->
							<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
								<span class="mr-4">
									<i class="flaticon-avatar icon-2x text-muted font-weight-bold"></i>
								</span>
								<div class="d-flex flex-column text-dark-75">
									<span class="font-weight-bolder font-size-sm">Referente</span>
									<span class="font-weight-bolder font-size-h5">

										<?php echo $referent->display_name; ?>
									</span>
								</div>
							</div>
							<!--end: Item-->
							<?php } ?>

							<?php $pm = get_field('pm'); ?>
							<?php if ( $pm ) { ?>
							<!--begin: Item-->
							<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
								<span class="mr-4">
									<i class="flaticon-avatar icon-2x text-muted font-weight-bold"></i>
								</span>
								<div class="d-flex flex-column text-dark-75">
									<span class="font-weight-bolder font-size-sm">PM</span>
									<span class="font-weight-bolder font-size-h5">

										<?php echo $pm->display_name; ?>
									</span>
								</div>
							</div>
							<!--end: Item-->
							<?php } ?>

							<?php $client = get_field('client'); ?>
							<?php if ( $client ) { ?>
							<!--begin: Item-->
							<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
								<span class="mr-4">
									<i class="flaticon-avatar icon-2x text-muted font-weight-bold"></i>
								</span>
								<div class="d-flex flex-column text-dark-75">
									<span class="font-weight-bolder font-size-sm">Client</span>
									<span class="font-weight-bolder font-size-h5">
										<?php echo $client->post_title; ?>
									</span>
								</div>
							</div>
							<!--end: Item-->
							<?php } ?>

							
						</div>
						<!--begin: Items-->
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
