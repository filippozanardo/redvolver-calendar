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

$project_report_page = get_field('project_report_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($project_report_page)
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
							<h3 class="card-label">Project Report</h3>
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
								<select class="form-control" id="project-report-select">
									<option value=""></option>
									<?php while($p_query->have_posts()):$p_query->the_post(); ?>
										<option value="<?php echo $p_query->post->ID; ?>"><?php echo $p_query->post->post_title; ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						<?php } ?>

						<div id="project_report">
						</div>
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
