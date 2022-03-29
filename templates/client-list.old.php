<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php
$calendar_page = get_field('calendar_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_page)
  );

	wp_login_form($args);
}else{ ?>
	<?php $client_add_page = get_field('client_add_page','option'); ?>

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
							<h3 class="card-label">Clients</h3>
						</div>
						<div class="card-toolbar">
							<?php if ( $client_add_page ) { ?>
							<a href="<?php echo get_permalink($client_add_page); ?>" class="btn btn-light-primary font-weight-bold">
								<i class="ki ki-plus icon-md mr-2"></i>New Client
							</a>
							<?php } ?>
						</div>
					</div>
					<div class="card-body">
						<?php
						$args = array(
				  		'post_status'=>array('publish'),
				  		'post_type'=>'client',
				  		'posts_per_page' => -1,
				  	);
						$my_query= null;
		        $my_query = new WP_Query();

		        $my_query->query($args);


		      	if( $my_query->have_posts() ) {
						?>
						<!--begin: Datatable -->
						<table class="table table-bordered table-checkable dataTable no-footer dtr-inline" id="client_table">
							<thead>
								<tr>
									<th>Client Name</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

								<?php while($my_query->have_posts()):$my_query->the_post(); ?>
									<tr>
											<td><?php echo $my_query->post->post_title; ?></td>
											<td nowrap>
												<a href="<?php echo get_permalink($client_add_page).'?pid='.$my_query->post->ID; ?>" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
		                      <i class="la la-edit"></i>
		                  	</a>
												<a href="#" data-id="<?php echo $my_query->post->ID; ?>" class="delpost btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
		                      <i class="la la-trash"></i>
		                  	</a>
											</td>
									</tr>
								<?php endwhile; ?>

								</tbody>
							</table>
					<?php } ?>
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
