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
				<div class="card card-custom">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label"><?php the_title(); ?></h3>
						</div>
						<div class="card-toolbar">
	        	</div>
					</div>
					<!--begin::Form-->
					<form id="changepassword" action="" method="POST">
					 <div class="card-body">

             <div class="form-group">
               <label>New Password:</label>
               <input class="form-control" id="new_password" name="new_password" type="password">
             </div>

             <div class="form-group">
               <label>Confirm Password:</label>
               <input class="form-control" id="confirm_new_password" name="confirm_new_password" type="password">
             </div>


					 </div>
					 <div class="card-footer">

						 <?php
						 if ( $mode == 'EDIT' ) {
						 ?>
							 <input type="hidden" name="pid" id="pid" value="<?php echo $post_id; ?>">
						 <?php
						 }
						 ?>
						 <input type="hidden" name="action" value="add_project">
						 <button type="submit" class="btn btn-primary">Submit</button>

					 </div>
					</form>
					<!--end::Form-->
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
