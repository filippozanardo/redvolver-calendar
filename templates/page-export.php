<?php use Carbon\Carbon; ?>
<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php

$export_page = get_field('export_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($export_page)
  );
	wp_login_form($args);
}else{ ?>

	<?php
	$title_array = false;
	?>

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
							<h3 class="card-label">Export Report</h3>
						</div>
						<div class="card-toolbar">

						</div>
					</div>
					<div class="card-body">

						<?php
						$args=array(
			        'post_status'=>array('publish'),
			        'post_type'=>'project',
			        'posts_per_page' => -1,
			      );

			      $arrayeventi = array();
			      $p_query= null;
			      $p_query = new WP_Query();

			      $p_query->query($args);
			    	if ( $p_query->have_posts() ) {
			    	?>


							<div class="form-group">
								<label for="">Projects</label>
								<select class="form-control" id="project-export-select">
									<option value=""></option>
									<?php while($p_query->have_posts()):$p_query->the_post(); ?>
										<option value="<?php echo $p_query->post->ID; ?>"><?php echo $p_query->post->post_title; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						<?php } ?>

						
							<div class="form-group">
								<label class="col-form-label">Seleziona Utente</label>
								<select class="form-control" id="user-export-select">
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
									<label>Date Start:</label>
									<input id="export-datestart" type="text" class="form-control" name="export-datestart" required>
								</div>
								<div class="col-lg-6">
									<label class="">Date End:</label>
									<input id="export-dateend" type="text" class="form-control" name="export-dateend" required>
								</div>
							</div>





					</div>
					<div class="card-footer d-flex justify-content-between">
						<a href="#" id="goexport" class="btn btn-primary">EXPORT</a>
					</div>
				</div>
				<!--end::Card-->

				<div class="card card-custom mt-2">
					<div class="card-body">
						<div id="export_report">
						</div>
					</div>
				</div>


			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->


<?php } ?>

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
