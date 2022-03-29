<?php
	$home_url = esc_url( home_url( ) );
	$logo = get_field('logo','option');
	$current = get_permalink( get_the_ID() );

	$user = wp_get_current_user();
	$allowed_roles = array( 'editor', 'administrator' );

?>
<!--begin::Aside-->
<div class="aside aside-left d-flex flex-column" id="kt_aside">

	<!--begin::Brand-->
	<div class="aside-brand d-flex flex-column align-items-center flex-column-auto py-4 py-lg-8">

		<!--begin::Logo-->
		<a href="<?php echo $home_url; ?>">
			<img alt="Logo" src="<?php echo RVC_PLUGIN_URL; ?>img/logo.png" class="max-h-30px" />
		</a>

		<!--end::Logo-->
	</div>

	<!--end::Brand-->

	<!--begin::Nav Wrapper-->
	<div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pt-7">

		<!--begin::Nav-->
		<ul class="nav flex-column">


			<?php $calendar_page = get_field('calendar_page','option'); ?>
			<?php if ( $calendar_page ) { ?>
				<?php $pageurl = get_permalink($calendar_page); ?>
				<!--begin::Item-->
				<li class="nav-item mb-5" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Calendar">
					<a href="<?php echo $pageurl; ?>" class="nav-link btn btn-icon btn-clean btn-icon-white btn-lg <?php if ( $current == $pageurl ) echo 'active'; ?>">
						<i class="flaticon2-calendar-9 icon-lg"></i>
					</a>
				</li>
				<!--end::Item-->
			<?php } ?>

			<?php $calendar_all_page = get_field('calendar_all_page','option'); ?>
			<?php if ( $calendar_all_page ) { ?>
				<?php
				if ( array_intersect( $allowed_roles, $user->roles ) ) {
				?>
					<?php $pageurl = get_permalink($calendar_all_page); ?>
					<!--begin::Item-->
					<li class="nav-item mb-5" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Global Calendar">
						<a href="<?php echo $pageurl; ?>" class="nav-link btn btn-icon btn-clean btn-icon-white btn-lg <?php if ( $current == $pageurl ) echo 'active'; ?>">
							<i class="flaticon2-calendar-3 icon-lg"></i>
						</a>
					</li>
					<!--end::Item-->
				<?php } ?>
			<?php } ?>

			<?php $calendar_project_page = get_field('calendar_project_page','option'); ?>
			<?php if ( $calendar_project_page ) { ?>
				<?php $pageurl = get_permalink($calendar_project_page); ?>
				<!--begin::Item-->
				<li class="nav-item mb-5" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Project Calendar">
					<a href="<?php echo $pageurl; ?>" class="nav-link btn btn-icon btn-clean btn-icon-white btn-lg <?php if ( $current == $pageurl ) echo 'active'; ?>">
						<i class="flaticon-event-calendar-symbol icon-lg"></i>
					</a>
				</li>
				<!--end::Item-->
			<?php } ?>



		</ul>

		<!--end::Nav-->
	</div>

	<!--end::Nav Wrapper-->

	<!--begin::Footer-->
	<div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">

		<!--begin::Quick Panel-->
		<a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-icon btn-clean btn-lg mb-1"data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Esci">
			<i class="flaticon-logout icon-lg"></i>
		</a>

		<!--end::Quick Panel-->
	</div>

	<!--end::Footer-->
</div>

<!--end::Aside-->
