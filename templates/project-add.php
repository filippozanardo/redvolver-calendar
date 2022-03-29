<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php
$calendar_page = get_field('calendar_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($calendar_page)
  );
	wp_login_form($args);
}else{ ?>

	<style>
	.nav .nav-link .nav-text {
    flex-grow: 1;
    font-size: 1.5rem;
	}
	</style>
	<?php

		$users = get_users( array(
			'orderby' => 'login',
			'order' => 'ASC',
			//'exclude' => array( 1 )
		));
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


		$args = array(
			'meta_query' => array(
				'relation' => 'OR',
					array(
						'key'     => 'department',
						'value'   => 'pm',
			 			'compare' => 'LIKE'
					),
			)
		 );
		$user_pm_query = new WP_User_Query( $args );

		$args = array(
			'meta_query' => array(
				'relation' => 'OR',
					array(
						'key'     => 'department',
						'value'   => 'graphic',
			 			'compare' => 'LIKE'
					),
			)
		 );
		$user_graphic_query = new WP_User_Query( $args );

		$args = array(
			'meta_query' => array(
				'relation' => 'OR',
					array(
						'key'     => 'department',
						'value'   => 'dev',
			 			'compare' => 'LIKE'
					),
			)
		 );
		$user_dev_query = new WP_User_Query( $args );

		if ( isset( $_REQUEST['pid'] ) ) {
			$mode = 'EDIT';
			$post_id = $_REQUEST['pid'];
		}else{
			$mode = 'ADD';
		}

		if ( $mode == 'EDIT') {
			$projectname = get_the_title($post_id);
			$referent = get_field('referent',$post_id);
			$pm = get_field('pm',$post_id);
			$graphic = get_field('graphic',$post_id);
			$client = get_field('client',$post_id);
			$agency = get_field('agency',$post_id);
			$timing = get_field('timing',$post_id);
			$content = get_the_content(null,null,$post_id);
			$on_air = get_field('on_air',$post_id);
			$dev = get_field('dev',$post_id);
			$start = get_field('start',$post_id);
			$end = get_field('start',$post_id);
			$code = get_field('code',$post_id);
			// $start = get_field('start',$post_id,false);
			// if ( $start) {
			// 	$dt = DateTime::createFromFormat('Ymd', $start);
			// }


			// $end = get_field('end',$post_id,false);
			// if ( $end) {
			// 	$dtend = DateTime::createFromFormat('Ymd', $end);
			// }

			$rentman = get_field('rentman',$post_id);
			$archive = get_field('archive',$post_id);

			$brief = RVC()->db->table('rv_brief')->where('project_id', $post_id)->first();
			$status = get_field('status',$post_id);
			$project_type = get_field('project_type',$post_id);
		}

	?>


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
							<h3 class="card-label">Project</h3>
						</div>
						<div class="card-toolbar">
	        	</div>
					</div>
					<!--begin::Form-->
					<form id="addproject" action="" method="POST">
					 <div class="card-body">

						 <ul class="nav nav-tabs nav-tabs-line mb-5" id="pjtab">
						    <li class="nav-item">
						        <a class="nav-link active" data-toggle="tab" href="#project_pane_1">
						            <span class="nav-icon"><i class="icon-xl fas fa-list-ul"></i></span>
						            <span class="nav-text">Project</span>
						        </a>
						    </li>
						    <li class="nav-item">
						        <a class="nav-link" data-toggle="tab" href="#project_pane_2">
						            <span class="nav-icon"><i class="icon-xl fas fa-briefcase"></i></span>
						            <span class="nav-text">Brief</span>
						        </a>
						    </li>

						</ul>

						<div class="tab-content mt-5" id="myTabContent">
						    <div class="tab-pane fade show active" id="project_pane_1" role="tabpanel" aria-labelledby="project_pane_1">

									<div class="form-group">
		 							 <label>Codice:</label>
		 							 <input type="text" class="form-control" id="projectcode" name="projectcode" placeholder="" readonly <?php if ( $mode == 'EDIT') { echo 'value="'.$code.'"'; } ?> >
		 						 </div>

									<div class="form-group">
		 							 <label>Project Name:</label>
		 							 <input type="text" class="form-control" id="projectname" name="projectname" placeholder="Enter name" <?php if ( $mode == 'EDIT') { echo 'value="'.$projectname.'"'; } ?> required>
		 						 </div>

									<?php
									 //$term_array = array();
									 $terms = get_terms('project_type',array(
										 'hide_empty' => false,
									 ));
									 //var_dump($terms);
								 ?>
								 <div class="form-group">
									 <label>Tipo:</label>
									 <select class="form-control kt-select2" id="project_type" name="project_type" required>
										 <option value=""></option>
										 <?php if ( $terms ) { ?>
											 <?php foreach ($terms as $term) { ?>
													<option value="<?php echo $term->term_id; ?>" data-slug="<?php echo strtoupper( $term->slug); ?>" <?php if ( $project_type && $project_type->term_id == $term->term_id ) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
											 <?php } ?>
										<?php } ?>
									 </select>
								 </div>

		 						 <div class="form-group">
		 							 <label>Referente:</label>
		 							 <select class="form-control kt-select2" id="referent" name="referent[]" multiple="multiple">
		 								 <option value=""></option>
										 <?php if ( ! empty( $user_sale_query->get_results() ) ) { ?>
											 <?php foreach ( $user_sale_query->get_results() as $user ) { ?>


		 										 	<?php if ( $referent ) { ?>
														<?php if ( is_array($referent) ) { ?>
															<?php if ( in_array($user->ID,array_column($referent, 'ID')) ) { ?>
																<option value="<?php echo $user->ID; ?>" selected="selected"><?php echo $user->display_name; ?></option>
															<?php }else{ ?>
																<option value="<?php echo $user->ID; ?>" ><?php echo $user->display_name; ?></option>
															<?php } ?>
														<?php }else{ ?>
		 										 			<option value="<?php echo $user->ID; ?>" <?php if ($referent->ID == $user->ID) echo 'selected="selected"'; ?>><?php echo $user->display_name; ?></option>
														<?php } ?>
		 											<?php }else{ ?>
		 												<option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
		 											<?php } ?>
		 										<?php } ?>
		 								 <?php } ?>
		 							 </select>
		 						 </div>

		 						 <div class="form-group">
		 							 <label>PM:</label>
		 							 <select class="form-control kt-select2" id="pm" name="pm" multiple="multiple">
		 								 <option value=""></option>
										 <?php if ( ! empty( $user_pm_query->get_results() ) ) { ?>
		 									 <?php foreach ( $user_pm_query->get_results() as $user ) { ?>
		 										 <?php if ( $pm ) { ?>

													<?php if ( is_array($pm) ) { ?>
														<?php if ( in_array($user->ID,array_column($pm, 'ID')) ) { ?>
																<option value="<?php echo $user->ID; ?>" selected="selected"><?php echo $user->display_name; ?></option>
															<?php }else{ ?>
																<option value="<?php echo $user->ID; ?>" ><?php echo $user->display_name; ?></option>
															<?php } ?>
													<?php }else{ ?>
		 										 		<option value="<?php echo $user->ID; ?>" <?php if ($pm->ID == $user->ID) echo 'selected="selected"'; ?>><?php echo $user->display_name; ?></option>
													<?php } ?>

		 										 <?php }else{ ?>
		 											 <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
		 										 <?php } ?>
		 										<?php } ?>
		 								 <?php } ?>
		 							 </select>
		 						 </div>

		 						 <div class="form-group">
		 							 <label>Graphic:</label>
		 							 <select class="form-control kt-select2" id="graphic" name="graphic" multiple="multiple">
		 								<option value=""></option>
										<?php if ( ! empty( $user_graphic_query->get_results() ) ) { ?>
											<?php foreach ( $user_graphic_query->get_results() as $user ) { ?>
		 										<?php if ( $graphic ) { ?>

													<?php if ( is_array($graphic) ) { ?>
														<?php if ( in_array($user->ID,array_column($graphic, 'ID')) ) { ?>
																<option value="<?php echo $user->ID; ?>" selected="selected"><?php echo $user->display_name; ?></option>
															<?php }else{ ?>
																<option value="<?php echo $user->ID; ?>" ><?php echo $user->display_name; ?></option>
															<?php } ?>
													<?php }else{ ?>
		 										 		<option value="<?php echo $user->ID; ?>" <?php if ($graphic->ID == $user->ID) echo 'selected="selected"'; ?>><?php echo $user->display_name; ?></option>
													<?php } ?>
		 										<?php }else{ ?>
		 											<option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
		 										<?php } ?>
		  								<?php } ?>
		  							<?php } ?>
		 							 </select>
		 						 </div>

								 <div class="form-group">
		 							 <label>Developer:</label>
		 							 <select class="form-control kt-select2" id="dev" name="dev" multiple="multiple">
		 								<option value=""></option>
										<?php if ( ! empty( $user_dev_query->get_results() ) ) { ?>
											<?php foreach ( $user_dev_query->get_results() as $user ) { ?>
		 										<?php if ( $dev ) { ?>

													<?php if ( is_array($dev) ) { ?>
														<?php if ( in_array($user->ID,array_column($dev, 'ID')) ) { ?>
																<option value="<?php echo $user->ID; ?>" selected="selected"><?php echo $user->display_name; ?></option>
															<?php }else{ ?>
																<option value="<?php echo $user->ID; ?>" ><?php echo $user->display_name; ?></option>
															<?php } ?>
													<?php }else{ ?>
		 										 		<option value="<?php echo $user->ID; ?>" <?php if ($dev->ID == $user->ID) echo 'selected="selected"'; ?>><?php echo $user->display_name; ?></option>
													<?php } ?>

		 										<?php }else{ ?>
		 											<option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
		 										<?php } ?>
		  								<?php } ?>
		  							<?php } ?>
		 							 </select>
		 						 </div>

		 						 <div class="form-group">
		 							 <label>Client:</label>
		 							 <select class="form-control kt-select2" id="client" name="client">
		 								 <option value=""></option>
		 								 <?php if ( $client ) { ?>
		 									 <option value="<?php echo $client->ID; ?>" selected="selected">
		 										 <?php echo $client->post_title; ?>
		 									 </option>
		 							 	 <?php } ?>
		 							 </select>
		 						 </div>

		 						 <div class="form-group">
		 							 <label>Agency:</label>
		 							 <select class="form-control kt-select2" id="agency" name="agency">
		 								 <option value=""></option>
		 								 <?php if ( $agency ) { ?>
		 									 <option value="<?php echo $agency->ID; ?>" selected="selected">
		 										 <?php echo $agency->post_title; ?>
		 									 </option>
		 							 	 <?php } ?>
		 							 </select>
		 						 </div>

		 						 <div class="form-group">
		 							 <label>Data Consegna:</label>
		 							 <input class="form-control" id="timing" name="timing" type="text" value="<?php if ( $timing ) echo $timing; ?>">
		 						 </div>

								 

		 						 <div class="form-group">
		 							 <label>Note:</label>
		 							 <textarea class="form-control" id="content" name="content" rows="3"><?php if ( $mode == 'EDIT') { echo $content; } ?></textarea>
		 						 </div>

		 						 <div class="form-group">
		 							 <label>On Air:</label>
		 							 <input class="form-control" id="on_air" name="on_air" type="text" value="<?php if ( $on_air ) echo $on_air; ?>">
		 						 </div>

		 						 <div class="form-group row">
		 								<div class="col-lg-6">
		 									<label>Start</label>
		 	 								<input class="form-control" id="datestart" name="datestart" type="text" value="<?php if ( $start ) echo $start; ?>">
		 								</div>
		 								<div class="col-lg-6">
		 									<label>End</label>
		 									<input class="form-control" id="dateend" name="dateend" type="text" value="<?php if ( $end ) echo $end; ?>">
		 								</div>
		 							</div>

									<div id="onair_repeater">
							 			<div class="form-group row" id="kt_repeater_1">

										 <div data-repeater-list="" class="col-lg-12">

											 <?php if( have_rows('onair',$post_id) ) { ?>

													<?php while ( have_rows('onair',$post_id) ) : the_row(); ?>
														<?php
															$on_air_title = get_sub_field('on_air_title');
															$on_air_start = get_sub_field('on_air_start');
															$on_air_end = get_sub_field('on_air_end');
														 ?>
														 <div data-repeater-item class="form-group row align-items-center">
																 <div class="col-md-3">
																		 <label>On Air Title:</label>
																		 <input type="text" id="onair_title" name="onair_title" class="form-control" value="<?php echo $on_air_title; ?>" placeholder="Enter Ttile"/>
																		 <div class="d-md-none mb-2"></div>
																 </div>
																 <div class="col-md-3">
																		 <label>Start:</label>
																		 <input type="text" id="onair_start" name="onair_start" class="form-control onair_start" value="<?php echo $on_air_start; ?>" placeholder="Start"/>
																		 <div class="d-md-none mb-2"></div>
																 </div>
																 <div class="col-md-3">
																		 <label>End:</label>
																		 <input type="text" id="onair_end" name="onair_end" class="form-control onair_end" value="<?php echo $on_air_end; ?>" placeholder="End"/>
																		 <div class="d-md-none mb-2"></div>
																 </div>
																 <div class="col-md-3">
																		 <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger" style="margin-top:25px;">
																				 <i class="la la-trash-o"></i>Delete
																		 </a>
																 </div>
														 </div>
														<?php endwhile; ?>
													<?php }else{ ?>
														<div data-repeater-item class="form-group row align-items-center">
																<div class="col-md-3">
																		<label>On Air Title:</label>
																		<input type="text" id="onair_title" name="onair_title" class="form-control" placeholder="Enter Ttile"/>
																		<div class="d-md-none mb-2"></div>
																</div>
																<div class="col-md-3">
																		<label>Start:</label>
																		<input type="text" id="onair_start" name="onair_start" class="form-control onair_start" placeholder="Start"/>
																		<div class="d-md-none mb-2"></div>
																</div>
																<div class="col-md-3">
																		<label>End:</label>
																		<input type="text" id="onair_end" name="onair_end" class="form-control onair_end" placeholder="End"/>
																		<div class="d-md-none mb-2"></div>
																</div>
																<div class="col-md-3">
																		<a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger" style="margin-top:25px;">
																				<i class="la la-trash-o"></i>Delete
																		</a>
																</div>
														</div>
													<?php } ?>
										 </div>
							 	 </div>
									 <div class="form-group row">
											 <div class="col-lg-12">
													 <a href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">
															 <i class="la la-plus"></i>Add
													 </a>
											 </div>
									 </div>
							 </div>


		 							<div class="form-group">
		  							 <label>Rentman:</label>
		  							 <input type="text" class="form-control" id="rentman" name="rentman" placeholder="Enter Rentman Code" <?php if ( $rentman) { echo 'value="'.$rentman.'"'; } ?>>
		  						 </div>

									 <?php
										 //$term_array = array();
										 $terms = get_terms('project_status',array(
											 'hide_empty' => false,
										 ));
										 //var_dump($terms);
									 ?>
									 <div class="form-group">
			 							 <label>Status:</label>
			 							 <select class="form-control kt-select2" id="status" name="status">
			 								 <option value=""></option>
											 <?php if ( $terms ) { ?>
												 <?php foreach ($terms as $term) { ?>
												 		<option value="<?php echo $term->term_id; ?>" <?php if ( $status && $status->term_id == $term->term_id ) echo 'selected="selected"'; ?>><?php echo $term->name; ?></option>
												 <?php } ?>
											<?php } ?>
			 							 </select>
			 						 </div>

		 						 <div class="form-group">
		 								<label></label>
		 								<div class="checkbox-list">
		 									<label class="checkbox">
		 										<input type="checkbox" name="archive" value="1" id="archive" <?php if ( $mode == 'EDIT') {  if ( $archive == 1 ) { echo 'checked'; } } ?>>
		 										<span></span>Archivia
		 									</label>
		 								</div>
		 							</div>
								</div>
						    <div class="tab-pane fade" id="project_pane_2" role="tabpanel" aria-labelledby="project_pane_2">

									<div class="form-group">
		  							 <label>Brand:</label>
		  							 <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter Brand" <?php if ( $brief) { echo 'value="'.$brief->brand.'"'; } ?>>
		  						 </div>

									 <div class="form-group">
 		  							 <label>Settore:</label>
 		  							 <input type="text" class="form-control" id="sector" name="sector" placeholder="Enter Sector" <?php if ( $brief) { echo 'value="'.$brief->sector.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Contatto:</label>
 		  							 <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->contact.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Durata:</label>
 		  							 <input type="text" class="form-control" id="duration" name="duration" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->duration.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Target:</label>
 		  							 <input type="text" class="form-control" id="target" name="target" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->target.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Objective:</label>
 		  							 <input type="text" class="form-control" id="objective" name="objective" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->objective.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Concept:</label>
 		  							 <input type="text" class="form-control" id="concept" name="concept" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->concept.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Staff:</label>
 		  							 <input type="text" class="form-control" id="staff" name="staff" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->staff.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Location:</label>
 		  							 <input type="text" class="form-control" id="location" name="location" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->location.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Output:</label>
 		  							 <input type="text" class="form-control" id="output" name="output" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->output.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Delivery:</label>
 		  							 <input type="text" class="form-control" id="delivery" name="delivery" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->delivery.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Budget:</label>
 		  							 <input type="text" class="form-control" id="budget" name="budget" placeholder="Enter Contatto" <?php if ( $brief) { echo 'value="'.$brief->budget.'"'; } ?>>
 		  						 </div>

									 <div class="form-group">
 		  							 <label>Richiesta:</label>
										 <textarea class="form-control" id="request" name="request" rows="3"><?php if ( $brief ) { echo $brief->request; } ?></textarea>
 		  						 </div>




								</div>


						</div>



					 </div>
					 <div class="card-footer">

						 <?php
						 if ( $mode == 'EDIT' ) {
						 ?>
							 <input type="hidden" name="pid" id="pid" value="<?php echo $post_id; ?>">
							 <input type="hidden" name="customid" id="customid" value="<?php echo get_field('incremental',$post_id); ?>">
						 <?php
						 }
						 ?>
						 <input type="hidden" name="action" value="add_project">
						 <button type="submit" class="btn btn-primary" id="addprojectbutton">Submit</button>

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
