<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php
$calendar_page = get_field('calendar_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_page)
  );
	wp_login_form($args);
}else{ ?>
	<?php $project_add_page = get_field('project_add_page','option'); ?>

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
							<h3 class="card-label">Projects</h3>
						</div>
						<div class="card-toolbar">
							<?php if ( $project_add_page ) { ?>
							<a href="<?php echo get_permalink($project_add_page); ?>" class="btn btn-light-primary font-weight-bold" target="_blank">
								<i class="ki ki-plus icon-md mr-2"></i>
								New Project
							</a>
							<?php } ?>
						</div>
					</div>
					<div class="card-body">
						<?php
						$args = array(
				  		'post_status'=>array('publish'),
				  		'post_type'=>'project',
				  		'posts_per_page' => -1,
				  	);
						$my_query= null;
		        $my_query = new WP_Query();

		        $my_query->query($args);


		      	if( $my_query->have_posts() ) {
						?>

						<!--begin: Datatable -->
						<table class="table table-bordered table-checkable dataTable no-footer dtr-inline" id="project_table">
							<thead>
								<tr>
									<th>CODE</th>
									<th>Project Name</th>
									<th>REFERENTE</th>
									<th>PM</th>
									<th>GRAFICO</th>
									<th>DEVELOPER</th>
									<th>CLIENTE</th>
									<th>AGENZIA</th>
									<th>DATA CONSEGNA</th>
									<th>TIPO</th>
									<th>ON AIR</th>
									<th>RENTMAN</th>
									<th>STATUS</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php while($my_query->have_posts()):$my_query->the_post(); ?>

									<tr>
										<td>
										<?php $code = get_field('code'); ?>
											<?php if ( $code ) { ?>
												<?php echo $code; ?>
											<?php } ?>
										</td>
										<td><?php echo $my_query->post->post_title; ?></td>
										<td>
											<?php $referent = get_field('referent'); ?>
											<?php if ( $referent ) { ?>
												<?php if ( is_array($referent) ) { ?>
													<?php foreach ($referent as $ref) {
														echo $ref->display_name. ' ';
													} ?>
												<?php }else{ ?>
													<?php echo $referent->display_name; ?>
												<?php } ?>
											<?php } ?>
										</td>
										<td>
											<?php $pm = get_field('pm'); ?>
											<?php if ( $pm ) { ?>
												<?php if ( is_array($pm) ) { ?>
													<?php foreach ($pm as $ref) {
														echo $ref->display_name. ' ';
													} ?>
												<?php }else{ ?>
													<?php echo $pm->display_name; ?>
												<?php } ?>
											<?php } ?>
										</td>
										<td>
											<?php $graphic = get_field('graphic'); ?>
											<?php if ( $graphic ) { ?>
												<?php if ( is_array($graphic) ) { ?>
													<?php foreach ($graphic as $ref) {
														echo $ref->display_name. ' ';
													} ?>
												<?php }else{ ?>
													<?php echo $graphic->display_name; ?>
												<?php } ?>
											<?php } ?>
										</td>

										<td>
											<?php $dev = get_field('dev'); ?>
											<?php if ( $dev ) { ?>
												<?php if ( is_array($dev) ) { ?>
													<?php foreach ($dev as $ref) {
														echo $ref->display_name. ' ';
													} ?>
												<?php }else{ ?>
													<?php echo $dev->display_name; ?>
												<?php } ?>
											<?php } ?>
										</td>
										<td>
											<?php $client = get_field('client'); ?>
											<?php if ( $client ) { ?>
												<?php echo $client->post_title; ?>
											<?php } ?>
										</td>
										<td>
											<?php $agency = get_field('agency'); ?>
											<?php if ( $agency ) { ?>
												<?php echo $agency->post_title; ?>
											<?php } ?>
										</td>
										<td>
											<?php $timing = get_field('timing'); ?>
											<?php if ( $timing ) { ?>
												<?php echo $timing; ?>
											<?php } ?>
										</td>
										<td>
											<?php $project_type = get_field('project_type'); ?>
											<?php if ( $project_type ) { ?>
												<?php echo $project_type->name; ?>
											<?php } ?>
										</td>
										<td>
											<?php $on_air = get_field('on_air'); ?>
											<?php if ( $on_air ) { ?>
												<?php echo $on_air; ?>
											<?php } ?>
										</td>
										<td>
											<?php $rentman = get_field('rentman'); ?>
											<?php if ( $rentman ) { ?>
												<?php echo $rentman; ?>
											<?php } ?>
										</td>
										<td>
											<?php $status = get_field('status'); ?>
											<?php if ( $status ) { ?>
												<?php
													$post_id = "project_status_".$status->term_id;
													$color = get_field( 'color', $post_id );
												?>
												<span class="label label-lg font-weight-bold label-inline" style="color: #000000;background-color: <?php echo $color; ?>;"><?php echo $status->name; ?></span>
											<?php } ?>
										</td>
										<td nowrap>
											<a href="<?php echo get_permalink(); ?>" target="_blank" class="btn btn-xs btn-clean btn-icon btn-icon-m" title="View">
												<i class="fas fa-eye"></i>
											</a>
											<a href="<?php echo get_permalink($project_add_page).'?pid='.$my_query->post->ID; ?>" target="_blank" class="btn btn-xs btn-clean btn-icon btn-icon-md" title="Edit">
	                      <i class="fas fa-edit"></i>
	                  	</a>
											<a href="#" data-id="<?php echo $my_query->post->ID; ?>" class="delpost btn btn-xs btn-clean btn-icon btn-icon-md" title="Delete">
	                      <i class="fas fa-trash-alt"></i>
	                  	</a>
											<a href="#" class="getprojectpdf btn btn-xs btn-clean btn-icon btn-icon-m" data-id="<?php echo $my_query->post->ID; ?>" title="PDF">
	                      <i class="fas fa-file-pdf"></i>
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
