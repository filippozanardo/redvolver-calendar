<?php
use Carbon\Carbon;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
?>
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
	$t = sanitize_title( get_the_title() );
	$app = new DropboxApp("6fzczdj5u1p7sse", "kqz8gr9ly5wfvgf","Yd7zOPj_C1gAAAAAAAAAAVJey36OcLA0kwqrmoQKDD01OiDCEzcOWcn_Jkw5YXiX");
	$dropbox = new Dropbox($app);

	try {
    $listFolderContents = $dropbox->listFolder("/JCalendar/".$t);
	} catch (Exception $e) {

		$folder = $dropbox->createFolder("/JCalendar/".$t);
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM/Preventivo');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM/Presentazione finale');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/PM/Preventivo fornitori');

		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Grafici');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Sviluppo');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Produzione');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Produzione/Esecutivi produzione');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Produzione/Gestione staff');

		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente/Reference cliente');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente/Brief cliente');
		$folder = $dropbox->createFolder("/JCalendar/".$t.'/Cliente/Varie cliente');

	}

	// $app = new DropboxApp("6fzczdj5u1p7sse", "kqz8gr9ly5wfvgf","HnoYjG-MdQ4AAAAAAAAAAfvu0fE-qbFya8dWC3W8ivZ3_HAW_fqSvYA-vhhzTKTd");
	// $dropbox = new Dropbox($app);
	//$folder = $dropbox->createFolder("/gianfrancozola");
	//$folder = $dropbox->createFolder("/gianfrancozola/sviluppo");
	//$folder = $dropbox->createFolder("/gianfrancozola/pm");
	 ?>
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

										<?php $code = get_field('code'); ?>
										<?php if ( $code ) { ?>
										<a href="javascript:void(0);" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
                      <?php echo $code; ?>
                    </a>
										<?php } ?>

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

							<?php $agency = get_field('agency'); ?>
							<?php if ( $agency ) { ?>
								<!--begin: Item-->
								<div class="d-flex align-items-center flex-lg-fill mr-5 my-1">
									<span class="mr-4">
										<i class="flaticon-avatar icon-2x text-muted font-weight-bold"></i>
									</span>
									<div class="d-flex flex-column text-dark-75">
										<span class="font-weight-bolder font-size-sm">Client</span>
										<span class="font-weight-bolder font-size-h5">
											<?php echo $agency->post_title; ?>
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

				<!--begin::Row-->
				<div class="row">
					<div class="col-xl-4">

						<!--begin::Card-->
						<div class="card card-custom">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">
										<?php the_title(); ?>
									<!-- <span class="d-block text-muted pt-2 font-size-sm">company profile preview</span>-->
									</h3>
								</div>
								<div class="card-toolbar">
									<!-- <a href="#" class="btn btn-sm btn-primary font-weight-bolder text-uppercase mr-2">contact</a> -->

								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-4">


								<?php $timing = get_field('timing'); ?>
								<?php if ( $timing ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Data Consegna:</label>
									<div class="col-8">
										<span class="btn btn-sm btn-text btn-primary text-uppercase font-weight-bold"><?php echo $timing; ?></span>
									</div>
								</div>
								<?php } ?>
									
								<?php $code = get_field('code'); ?>
								<?php if ( $code ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Code:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $code; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $client = get_field('client'); ?>
								<?php if ( $client ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Client:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $client->post_title; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $agency = get_field('agency'); ?>
								<?php if ( $agency ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Agency:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $agency->post_title; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $referent = get_field('referent'); ?>
								<?php if ( $referent ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Referente:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $referent->display_name; ?></span>
									</div>
								</div>
								<?php } ?>


								<?php $pm = get_field('pm'); ?>
								<?php if ( $pm ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">PM:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $pm->display_name; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $graphic = get_field('graphic'); ?>
								<?php if ( $graphic ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Grafico:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $graphic->display_name; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $dev = get_field('dev'); ?>
								<?php if ( $dev ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Developer:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $dev->display_name; ?></span>
									</div>
								</div>
								<?php } ?>


								<?php $status = get_field('status'); ?>
								<?php if ( $status ) { ?>
									<?php
										$post_id = "project_status_".$status->term_id;
										$value = get_field( 'color', $post_id );
									?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Status:</label>
									<div class="col-8">
										<span class="form-control-plaintext">
											<span class="label label-inline label-danger label-bold" style="background-color:<?php echo $value; ?>"><?php echo $status->name; ?></span>
										</span>
									</div>
								</div>
								<?php } ?>


								<?php $project_type = get_field('project_type'); ?>
								<?php if ( $project_type ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Tipo:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $project_type->name; ?></span>
									</div>
								</div>
								<?php } ?>


							</div>
							<!--end::Body-->

						</div>
						<!--end::Card-->


						<!--begin::Card-->
						<div class="card card-custom mt-2">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">
										ON AIR
									</h3>
								</div>
								<div class="card-toolbar">
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-4">

								<?php $on_air = get_field('on_air'); ?>
								<?php if ( $on_air ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">On Air:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $on_air; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $start = get_field('start'); ?>
								<?php if ( $start ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Start:</label>
									<div class="col-8">
										<span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text"><?php echo $start; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php $end = get_field('end'); ?>
								<?php if ( $end ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">End:</label>
									<div class="col-8">
										<span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text"><?php echo $end; ?></span>
									</div>
								</div>
								<?php } ?>


								<?php if( have_rows('onair') ) { ?>
									<div class="separator separator-solid my-2"></div>

									<span class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">ON AIRS: </span>


									<div class="table-responsive">
										<table class="table table-borderless table-vertical-center">
											<thead>
												<tr>
													<th class="p-0" style="min-width: 100px"></th>
													<th class="p-0" style="min-width: 100px"></th>
													<th class="p-0" style="min-width: 100px"></th>
												</tr>
											</thead>
											<tbody>

											<?php while ( have_rows('onair') ) : the_row(); ?>
												<?php
													$on_air_title = get_sub_field('on_air_title');
													$on_air_start = get_sub_field('on_air_start');
													$on_air_end = get_sub_field('on_air_end');
												 ?>
												 <tr>

												 	 <td class="pl-0">
														 <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg"><?php echo $on_air_title; ?></a>
													 </td>
													 <td class="pl-0">
														 <span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text"><?php echo $on_air_start; ?></span>

													 </td>
													 <td class="pl-0">
														 <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text"><?php echo $on_air_end; ?></span>
													 </td>
												 </tr>

											<?php endwhile; ?>
										</tbody>
									</table>
								</div>

									<?php } ?>


							</div>
							<!--end::Body-->

						</div>
						<!--end::Card-->
					</div>
					<div class="col-xl-8">
						<!--begin::Card-->
						<div class="card card-custom gutter-b">
							<!--begin::Header-->
							<div class="card-header card-header-tabs-line">
								<div class="card-toolbar">
									<ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-bold nav-tabs-line-3x" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#note_tab">
												<span class="nav-icon mr-2">
													<span class="svg-icon mr-3">
														<!--begin::Svg Icon | path:assets/media/svg/icons/General/Notification2.svg-->
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24" />
																<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
																<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
															</g>
														</svg>
														<!--end::Svg Icon-->
													</span>
												</span>
												<span class="nav-text">Note</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body px-0">
								<div class="tab-content pt-5">
									<!--begin::Tab Content-->
									<div class="tab-pane active" id="note_tab" role="tabpanel">
										<div class="container">


											<?php the_content(); ?>
										</div>
									</div>
									<!--end::Tab Content-->

								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::Card-->
					</div>
				</div>
				<!--end::Row-->

				<?php $brief = RVC()->db->table('rv_brief')->where('project_id', get_the_ID())->first(); ?>

				<?php if ( $brief ) { ?>
				<!--begin::Row-->
				<div class="row mt-4">
					<div class="col-xl-4">

						<!--begin::Card-->
						<div class="card card-custom">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">BRIEF</h3>
								</div>
								<div class="card-toolbar">
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-4">


								<?php if ( $brief->brand ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Brand:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->brand; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->sector ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Settore:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->sector; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->contact ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Contatto:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->contact; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->duration ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Durata:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->duration; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->target ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Target:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->target; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->objective ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Objective:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->objective; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->concept ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Concept:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->concept; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->staff ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Staff:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->staff; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->location ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Location:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->location; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->output ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Output:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->output; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->delivery ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Delivery:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->delivery; ?></span>
									</div>
								</div>
								<?php } ?>

								<?php if ( $brief->budget ) { ?>
								<div class="form-group row my-2">
									<label class="col-4 col-form-label">Budget:</label>
									<div class="col-8">
										<span class="form-control-plaintext font-weight-bolder"><?php echo $brief->budget; ?></span>
									</div>
								</div>
								<?php } ?>

							</div>
							<!--end::Body-->

						</div>
						<!--end::Card-->
					</div>
					<div class="col-xl-8">
						<!--begin::Card-->
						<div class="card card-custom gutter-b">
							<!--begin::Header-->
							<div class="card-header card-header-tabs-line">
								<div class="card-toolbar">
									<ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-bold nav-tabs-line-3x" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#note_tab">
												<span class="nav-icon mr-2">
													<span class="svg-icon mr-3">
														<!--begin::Svg Icon | path:assets/media/svg/icons/General/Notification2.svg-->
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24" />
																<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
																<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
															</g>
														</svg>
														<!--end::Svg Icon-->
													</span>
												</span>
												<span class="nav-text">Richiesta</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body px-0">
								<div class="tab-content pt-5">
									<!--begin::Tab Content-->
									<div class="tab-pane active" id="note_tab" role="tabpanel">
										<div class="container">


											<?php echo $brief->request; ?>
										</div>
									</div>
									<!--end::Tab Content-->

								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::Card-->
					</div>
				</div>
				<!--end::Row-->
				<?php } ?>

				<!--begin::Row-->
				<div class="row mt-4">
					<div class="col-12">


						<!--begin::Card-->
						<div class="card card-custom">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">FILES</h3>
								</div>
								<div class="card-toolbar">
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-4">
								<?php $t = sanitize_title( get_the_title() ); ?>
								<?php echo do_shortcode('[outofthebox dir="/JCalendar/'.$t.'" account="dbid:AACoRZLakPHGg1DQ7JubJRPtA-ThOqaFV9k" mode="files" viewrole="all" downloadrole="all" upload="1" upload_auto_start="1" uploadrole="all" rename="1" renamefilesrole="all" renamefoldersrole="all" move="1" moverole="all" copy="1" delete="1" deletefilesrole="all" deletefoldersrole="all" addfolder="1" addfolderrole="all"]'); ?>

							</div>
						</div>
					</div>
				</div>
				<!--end::Row-->

				<?php

				$args=array(
	        'post_status'=>array('future','publish'),
	        'post_type'=>'timecard',
	        'posts_per_page' => -1,
	        'meta_query' => array(
	          array(
	              'key'     => 'project',
	              'value'   => get_the_ID(),
	              'compare' => '=',
	          ),
	        ),
	      );
				$p_query= null;
	      $p_query = new WP_Query();

	      $p_query->query($args);
	      if ( $p_query->have_posts() ) {

				?>
				<!--begin::Row-->
				<div class="row mt-4">
					<div class="col-12">

						<!--begin::Card-->
						<div class="card card-custom">
							<!--begin::Header-->
							<div class="card-header h-auto py-4">
								<div class="card-title">
									<h3 class="card-label">ORE</h3>
								</div>
								<div class="card-toolbar">
								</div>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body py-4">

								<table class="table table-striped- table-bordered table-hover" id="single_project_table">
									<thead>
								    <tr>
											<th>Utente</th>
				              <th>Start</th>
				              <th>End</th>
				              <th>Title</th>
				              <th>Hours</th>
								    </tr>
								  </thead>
									<tbody>

										<?php  while($p_query->have_posts()):$p_query->the_post(); ?>
											<?php
												$author = $p_query->post->post_author;
												$io = get_user_by('ID',$author);
								        if ( $io->first_name &&  $io->last_name) {
								          $ute = $io->first_name.' '.$io->last_name;
								        }else{
								          $ute = $io->display_name;
								        }
												$start = get_field('start');
								        $end = get_field('end');

								        if ( $start ) {
								          $ds = new Carbon($start);
								        }

								        if ( $end ) {
								          $de = new Carbon($end);
								        }
								        $mdiff = $ds->diffInMinutes($de);

								        if ( $mdiff > 0 ) {
								          $hours = $ds->diffInMinutes($de);
								        }else{
								          $hours = 30;
								        }
								        $hours = $hours / 60;
											?>
											<tr>
				                <td><?php echo $ute; ?></td>
												<td><?php echo $start; ?></td>
												<td><?php echo $end; ?></td>
												<td><?php echo $p_query->post->post_title; ?></td>
												<td><?php echo $hours; ?></td>


											</tr>

										<?php endwhile; ?>

									</tbody>
								</table>


							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

	<!-- Modal-->
	<div class="modal fade" id="filemodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="filemodal" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title" id="filemodal">File Preview</h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <i aria-hidden="true" class="ki ki-close"></i>
	                </button>
	            </div>
	            <div class="modal-body">
								<div class="embed-responsive embed-responsive-16by9">
	                <iframe class="embed-responsive-item" src="" id="file_preview"></iframe>
								</div>
	            </div>
	            <div class="modal-footer">
	            </div>
	        </div>
	    </div>
	</div>


<?php } ?>

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
