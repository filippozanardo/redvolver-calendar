<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php
$calendar_page = get_field('calendar_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_page)
  );
	wp_login_form($args);
}else{ ?>

	<?php
		$args = array(
			'meta_query' => array(
				'relation' => 'OR',
					array(
						'key'     => 'department',
						'value'   => 'sales',
						'compare' => 'LIKE'
					),
			)
		 );
		$user_sale_query = new WP_User_Query( $args );

		if ( isset( $_REQUEST['pid'] ) ) {
			$mode = 'EDIT';
			$post_id = $_REQUEST['pid'];
			$clientname = get_the_title($post_id);
			$content = get_the_content(null,null,$post_id);
			$referent = get_field('referent',$post_id);
			$contact = get_field('contact',$post_id);
		}else{
			$mode = 'ADD';
		}

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
				  <h3 class="card-title">
				   Client Add
				  </h3>
				  <div class="card-toolbar">
				  </div>
				 </div>
				 <!--begin::Form-->
				 <form id="addclient" action="" method="POST">
				  <div class="card-body">

						<div class="form-group">
							<label>Client Name:</label>
							<input type="text" class="form-control" id="clientname" name="clientname" placeholder="Enter name" <?php if ( $mode == 'EDIT') { echo 'value="'.$clientname.'"'; } ?> required>
						</div>

						<div class="form-group">
							<label>Description:</label>
							<textarea class="form-control" id="content" name="content" rows="3"><?php if ( $mode == 'EDIT') { echo $content; } ?></textarea>
						</div>

						<div class="form-group">
							<label>Commerciale:</label>
							<select class="form-control kt-select2" id="referent" name="referent">
								<option value=""></option>
								<?php if ( ! empty( $user_sale_query->get_results() ) ) { ?>
									<?php foreach ( $user_sale_query->get_results() as $user ) { ?>
										 <?php if ( $referent ) { ?>
											 <option value="<?php echo $user->ID; ?>" <?php if ($referent->ID == $user->ID) echo 'selected="selected"'; ?>><?php echo $user->display_name; ?></option>
										 <?php }else{ ?>
											 <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
										 <?php } ?>
									 <?php } ?>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label>Contatto:</label>
							<input type="text" class="form-control" id="contact" name="contact" placeholder="" <?php if ( $mode == 'EDIT') { echo 'value="'.$contact.'"'; } ?>>
						</div>

				  </div>
				  <div class="card-footer">

						<?php
						if ( $mode == 'EDIT' ) {
						?>
							<input type="hidden" id="pid" name="pid" value="<?php echo $post_id; ?>">
						<?php
						}
						?>
						<input type="hidden" name="action" value="add_client">
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
