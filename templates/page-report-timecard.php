<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;
?>
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
$report_page = get_field('report_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink($report_page)
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
				<div class="card card-custom" id="cardcontent">
					<div class="card-header">
						<div class="card-title">
							<h3 class="card-label"><?php the_title(); ?></h3>
						</div>
						<div class="card-toolbar">
							<!-- <a href="#" class="btn btn-light-primary font-weight-bold">
								<i class="ki ki-plus icon-md mr-2"></i>Add Event
							</a> -->
						</div>
					</div>
					<div class="card-body">
						<form class="form">

						  <div class="form-group row">
						   <div class="col-lg-3">
						    <label>User:</label>
								<select class="form-control" id="userselect">
									<option value=""></option>
									<?php
									$users = get_users( array(
										'orderby' => 'login',
										'order' => 'ASC',
										'exclude' => array( 1 )
									));
									foreach ( $users as $user ) {
										if ( $user->first_name ) {
											$nome = $user->first_name.' '.$user->last_name;
										}else{
											$nome = $user->display_name;
										}
									?>
										<option value="<?php echo $user->ID; ?>"><?php echo $nome; ?></option>
									<?php } ?>
								</select>

						   </div>
						   <div class="col-lg-3">
						    <label>Month:</label>
								<select class="form-control" id="monthselect">
									<option value=""></option>
									<option value="1">January</option>
									<option value="2">February</option>
									<option value="3">March</option>
									<option value="4">April</option>
									<option value="5">May</option>
									<option value="6">June</option>
									<option value="7">July</option>
									<option value="8">August</option>
									<option value="9">September</option>
									<option value="10">October</option>
									<option value="11">November</option>
									<option value="12">December</option>
								</select>

						   </div>
						   <div class="col-lg-3">
						    <label>Year:</label>
 							 <select class="form-control" id="yearselect">
 								 <option value=""></option>
								 <option value="2019">2019</option>
								 <option value="2020">2020</option>
								 <option value="2021">2021</option>
								 <option value="2022">2022</option>
								 <option value="2023">2023</option>
								 <option value="2024">2024</option>
								 <option value="2025">2025</option>
 							 </select>

						   </div>
							 <div class="col-lg-3">
								 <label>Action:</label>
								 <div class="input-group">
								 	<button id="search_time_card_rep" class="btn btn-primary mr-2">Search</button>
									</div>
						   </div>
						  </div>


						</form>

						<?php

						$startDate = Carbon::today()->startOfMonth();
						$endDate = Carbon::today()->endOfMonth();
						$todayDate = Carbon::today();

						$datearray = array();

						$users = get_users( array(
							'orderby' => 'login',
							'order' => 'ASC',
							//'include' => array(3),
							'exclude' => array( 1 ),
						));

						$period = CarbonPeriod::create($startDate, $endDate);
						foreach ($period as $date) {
								$datearray[] = $date->format('d');
						    //echo $date->format('Y-m-d').'</br>';
						}

						$uarray = array();
						foreach ( $users as $user ) {

							$hide_user = get_field('hide_user',$user);
							if ( !$hide_user ) {

								if ( $user->first_name ) {
									$nome = $user->first_name.' '.$user->last_name;
								}else{
									$nome = $user->display_name;
								}

								$uarray[$user->ID] = array(
									'nome' => $nome
								);

								$args = array (
							    'post_type' => 'timecard',
									'post_status' => 'any',
									'posts_per_page' => -1,
							    'meta_query' => array(
											array(
													'key' => 'start',
													'value'   => array( $startDate->toDateTimeString(), $endDate->toDateTimeString() ),
													// 'type'    => 'DATETIME',
													'compare' => 'BETWEEN',
											),
								   ),
									 'author' => $user->ID
								);


								$my_query= null;
								$my_query = new WP_Query();

								$my_query->query($args);


							if( $my_query->have_posts() ) {
									while($my_query->have_posts()):$my_query->the_post();

										$hours = 0;
										$start = false;
										$end = false;

										$start = get_field('start');
										$end = get_field('end');



										if ( $start ) {
											$ds = new Carbon($start);

											$cday = $ds->format('d');

											if ( $end ) {
												$de = new Carbon($end);
											}




											$mdiff = $ds->diffInMinutes($de);

											if ( $mdiff > 0 ) {
												$hours = $mdiff;
											}else{
												$hours = 30;
											}

											$hours = $hours/60;

											if ( isset($uarray[$user->ID][$cday]) ) {
												$uarray[$user->ID][$cday] += $hours;
											}else{
												$uarray[$user->ID][$cday] = $hours;
											}



										}



									endwhile;

									wp_reset_query();
							}


							}
						}

						?>



		    		<div class="table-responsive tableFixHead" id="datareport_table">
							<h1 id="datareport"><?php echo $todayDate->format('F Y'); ?></h1>

						<?php if ( $datearray ) { ?>
		        <table class="table table-nowrap mb-0">
		            <thead>
		                <tr>
		                    <th>Utente</th>
												<?php foreach ($datearray as $da) { ?>
													  <th><?php echo $da; ?></th>
												<?php } ?>
		                                    </tr>
		            </thead>
		            <tbody>
									<?php foreach ($uarray as $u) { ?>
										<tr>
											<td><?php echo $u['nome']; ?></td>
											<?php foreach ($datearray as $da) { ?>
												<td class="text-center">
												<?php if ( isset($u[$da]) ) { ?>
													<?php
													if ( $u[$da] <= 4 ) {
														echo '<span class="label label-rounded label-danger">'.$u[$da].'</span>';
													}elseif( $u[$da] <= 6 ) {
														echo '<span class="label label-rounded label-warning">'.$u[$da].'</span>';
													}else{
														echo '<span class="label label-rounded label-success">'.$u[$da].'</span>';
													}
													?>
												<?php }else{ ?>
													-
												<?php } ?>
												</td>
											<?php } ?>
										</tr>
									<?php } ?>

		          </tbody>
		        </table>
						<?php } ?>
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
