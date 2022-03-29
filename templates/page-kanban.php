<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;
?>
<?php RVC()->template_loader->get_template_part( 'header' ,'rvc',true ); ?>

<?php

$kanban_page = get_field('kanban_page','option');
if (!is_user_logged_in()) {
	$args = array(
      'redirect' => get_permalink(kanban_page)
  );
	wp_login_form($args);
}else{ ?>
	<style>
	<?php
	$users = get_users( array(
		'orderby' => 'login',
		'order' => 'ASC',
		'exclude' => array( 1 )
	));
	foreach ( $users as $user ) {
		$color = get_field('color', 'user_'. $user->ID );
		if ( $color ) {
			?>
			.kanban-container .kanban-item[data-class="pm<?php echo $user->ID; ?>"] {
		  	background-color: <?php echo $color; ?>;
			}
			<?php
		}

	}
	?>
	</style>

	<!-- end:: Header -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

		<!-- begin:: Subheader -->
		<div class="kt-subheader   kt-grid__item" id="kt_subheader">
			<div class="kt-container  kt-container--fluid ">
				<div class="kt-subheader__main">
					<h3 class="kt-subheader__title">Kanban Board</h3>

				</div>
				<div class="kt-subheader__toolbar">
					<div class="kt-subheader__wrapper">

					</div>
				</div>
			</div>
		</div>

		<!-- end:: Subheader -->

		<!-- begin:: Content -->
		<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

			<div class="row">
				<div class="col-lg-12">
					<div class="kt-portlet">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">
									Kanban
								</h3>
							</div>
							<div class="kt-portlet__head-toolbar">
								<div class="kt-portlet__head-wrapper">
									<div class="kt-portlet__head-actions">

										<div class="form-group" style="display:inline;">
											<select class="form-control" id="kan-pm-select">
												<option value=""></option>
												<?php
												foreach ( $users as $user ) {
												?>
													<option value="<?php echo $user->ID; ?>"><?php echo $user->first_name. ' '.$user->last_name; ?></option>
												<?php } ?>
											</select>
										</div>

										<a href="#" id="tuttiproj" class="btn btn-brand btn-icon-sm">
											<i class="la la-plus"></i>
											Tutti
										</a>

										<a href="#" id="solomark" class="btn btn-brand btn-icon-sm">
											<i class="la la-plus"></i>
											Marcati
										</a>
										<a href="#" id="clearmarked" class="btn btn-brand btn-icon-sm">
											<i class="la la-plus"></i>
											Clear Marcati
										</a>



									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div id="jkan"></div>

		</div>

		<!-- end:: Content -->
	</div>

	<div class="modal fade" id="projmodal" role="dialog" aria-labelledby="" aria-hidden="true">
		<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="">Edit Project</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
					<form id="prjform">

						<div class="form-group">
							<label for="rvclient">PM</label>
							<select class="form-control" id="prpm" name="prpm" style="width: 100%;">
								<option value=""></option>
							</select>
						</div>

						<div class="form-group">
							<label for="kanclient">Client</label>
							<select class="form-control" id="kanclient" name="kanclient" style="width: 100%;">
								<option value=""></option>
							</select>
						</div>

						<input type="hidden" id="prid"/>
					</form>

	      </div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="pmmodsave">SAVE</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
	      </div>
	    </div>
	  </div>
	</div>


<?php } ?>

<?php RVC()->template_loader->get_template_part( 'footer' ,'rvc',true ); ?>
