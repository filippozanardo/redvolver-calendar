<?php
$current = get_permalink( get_the_ID() );
$user = wp_get_current_user();
$allowed_roles = array( 'editor', 'administrator' );
?>
<!--begin::Header-->
<div id="kt_header" class="header bg-white header-fixed">

	<!--begin::Container-->
	<div class="container-fluid d-flex align-items-stretch justify-content-between">

		<!--begin::Left-->
		<div class="d-flex align-items-stretch mr-2">

			<!--begin::Page Title-->
			<h3 class="d-none text-dark d-lg-flex align-items-center mr-10 mb-0">Dashboard</h3>

			<!--end::Page Title-->

			<!--begin::Header Menu Wrapper-->
			<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">

				<!--begin::Header Menu-->
				<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">

					<!--begin::Header Nav-->
					<ul class="menu-nav">
						<?php $calendar_page = get_field('calendar_page','option'); ?>
            <?php if ( $calendar_page ) { ?>
							<?php $pageurl = get_permalink($calendar_page); ?>
						<li class="menu-item <?php if ( $current == $pageurl ) echo 'menu-item-active'; ?>" aria-haspopup="true">
							<a href="<?php echo $pageurl; ?>" class="menu-link">
								<span class="menu-text">Calendar</span>
							</a>
						</li>
						<?php } ?>

						<li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
							<a href="javascript:;" class="menu-link menu-toggle">
								<span class="menu-text">Projects</span>
								<span class="menu-desc"></span>
								<i class="menu-arrow"></i>
							</a>
							<div class="menu-submenu menu-submenu-classic menu-submenu-left">
								<ul class="menu-subnav">

									<?php $project_list_page = get_field('project_list_page','option'); ?>
									<?php if ( $project_list_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($project_list_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="fas fa-list"></i>
											</span>
											<span class="menu-text">List</span>
										</a>
									</li>
									<?php } ?>

									<?php $project_add_page = get_field('project_add_page','option'); ?>
									<?php if ( $project_add_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($project_add_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="fas fa-plus-circle"></i>
											</span>
											<span class="menu-text">Add</span>
										</a>
									</li>
									<?php } ?>


								</ul>
							</div>
						</li>

						<li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
							<a href="javascript:;" class="menu-link menu-toggle">
								<span class="menu-text">Client</span>
								<span class="menu-desc"></span>
								<i class="menu-arrow"></i>
							</a>
							<div class="menu-submenu menu-submenu-classic menu-submenu-left">
								<ul class="menu-subnav">

									<?php $client_list_page = get_field('client_list_page','option'); ?>
									<?php if ( $client_list_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($client_list_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="fas fa-list"></i>
											</span>
											<span class="menu-text">List</span>
										</a>
									</li>
									<?php } ?>

									<?php $client_add_page = get_field('client_add_page','option'); ?>
									<?php if ( $client_add_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($client_add_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="fas fa-plus-circle"></i>
											</span>
											<span class="menu-text">Add</span>
										</a>
									</li>
									<?php } ?>


								</ul>
							</div>
						</li>

						<li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
							<a href="javascript:;" class="menu-link menu-toggle">
								<span class="menu-text">Agency</span>
								<span class="menu-desc"></span>
								<i class="menu-arrow"></i>
							</a>
							<div class="menu-submenu menu-submenu-classic menu-submenu-left">
								<ul class="menu-subnav">

									<?php $agency_list_page = get_field('agency_list_page','option'); ?>
									<?php if ( $agency_list_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($agency_list_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="fas fa-list"></i>
											</span>
											<span class="menu-text">List</span>
										</a>
									</li>
									<?php } ?>

									<?php $agency_add_page = get_field('agency_add_page','option'); ?>
									<?php if ( $agency_add_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($agency_add_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="fas fa-plus-circle"></i>
											</span>
											<span class="menu-text">Add</span>
										</a>
									</li>
									<?php } ?>


								</ul>
							</div>
						</li>

						<?php
						if ( array_intersect( $allowed_roles, $user->roles ) ) {
						?>
						<li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
							<a href="javascript:;" class="menu-link menu-toggle">
								<span class="menu-text">Report</span>
								<span class="menu-desc"></span>
								<i class="menu-arrow"></i>
							</a>
							<div class="menu-submenu menu-submenu-classic menu-submenu-left">
								<ul class="menu-subnav">

									<?php $timecard_report = get_field('timecard_report','option'); ?>
									<?php if ( $timecard_report ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($timecard_report); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="far fa-chart-bar"></i>
											</span>
											<span class="menu-text">Report Compilazione</span>
										</a>
									</li>
									<?php } ?>

									<?php $project_report_page = get_field('project_report_page','option'); ?>
									<?php if ( $project_report_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($project_report_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="far fa-chart-bar"></i>
											</span>
											<span class="menu-text">Project Report</span>
										</a>
									</li>
									<?php } ?>

									<?php $client_report_page = get_field('client_report_page','option'); ?>
									<?php if ( $client_report_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($client_report_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="far fa-chart-bar"></i>
											</span>
											<span class="menu-text">Client Report</span>
										</a>
									</li>
									<?php } ?>

									<?php $export_page = get_field('export_page','option'); ?>
									<?php if ( $export_page ) { ?>
									<li class="menu-item" aria-haspopup="true">
										<a href="<?php echo get_permalink($export_page); ?>" class="menu-link">
											<span class="svg-icon menu-icon">
												<i class="far fa-chart-bar"></i>
											</span>
											<span class="menu-text">Export Report</span>
										</a>
									</li>
									<?php } ?>



								</ul>
							</div>
						</li>
						<?php } ?>

						<?php $tools_page = get_field('tools_page','option'); ?>
            <?php if ( $tools_page ) { ?>
							<?php $pageurl = get_permalink($tools_page); ?>
						<li class="menu-item <?php if ( $current == $pageurl ) echo 'menu-item-active'; ?>" aria-haspopup="true">
							<a href="<?php echo $pageurl; ?>" class="menu-link">
								<span class="menu-text">Tools</span>
							</a>
						</li>
						<?php } ?>

						<?php
						if ( array_intersect( $allowed_roles, $user->roles ) ) {
						?>
							<?php $utilities_page = get_field('utilities_page','option'); ?>
	            <?php if ( $utilities_page ) { ?>
								<?php $pageurl = get_permalink($utilities_page); ?>
							<li class="menu-item <?php if ( $current == $pageurl ) echo 'menu-item-active'; ?>" aria-haspopup="true">
								<a href="<?php echo $pageurl; ?>" class="menu-link">
									<span class="menu-text">Utilities</span>
								</a>
							</li>
							<?php } ?>
						<?php } ?>


					</ul>

					<!--end::Header Nav-->
				</div>

				<!--end::Header Menu-->
			</div>

			<!--end::Header Menu Wrapper-->
		</div>

		<!--end::Left-->

		<!--begin::Topbar-->
		<div class="topbar">



			<?php
			$current_user = wp_get_current_user();
			?>
			<!--begin::User-->
			<div class="topbar-item">


				<div class="dropdown">
					<a href="#" class="btn btn-light-primary font-weight-bold dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="symbol-label font-size-h5 font-weight-bold"><?php echo strtoupper( substr($current_user->user_login, 0,1) ); ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-md py-5" style="">
						<ul class="navi navi-hover navi-active">
							<li class="navi-item">
								<a class="navi-link" href="#">
									<span class="navi-icon">
										<i class="flaticon2-layers"></i>
									</span>
									<span class="navi-text">Profile</span>
								</a>
							</li>


							<?php $change_password_page = get_field('change_password_page','option'); ?>
	            <?php if ( $change_password_page ) { ?>
								<li class="navi-item">
									<a class="navi-link" href="<?php echo get_permalink($change_password_page); ?>">
										<span class="navi-icon">
											<i class="flaticon-lock"></i>
										</span>
										<span class="navi-text">Cambia Password</span>
									</a>
								</li>
							<?php } ?>

							<li class="navi-item">
								<a class="navi-link" href="<?php echo wp_logout_url( home_url() ); ?>">
									<span class="navi-icon">
										<i class="flaticon-logout"></i>
									</span>
									<span class="navi-text">Log Out</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<!--end::User-->
		</div>

		<!--end::Topbar-->
	</div>

	<!--end::Container-->
</div>

<!--end::Header-->
