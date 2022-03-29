<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;
?>
		<!-- <div class="form-group">
			<label>Select Date</label>
			<input type="text" class="form-control" id="timesheetpicker" name="timesheetpicker" >
		</div> -->

		<?php for ($i=1; $i <= 12 ; $i++) { ?>
      <?php
      $dt = Carbon::now();
			$dt->day = 1;
      $dt->month = $i;
      ?>
				<div class="btn-group">
					<div class="dropdown">
					 <a href="#" class="btn btn-light-primary font-weight-bold dropdown-toggle" data-toggle="dropdown">
					  <?php echo $dt->format('F'); ?>
					 </a>
					 <div class="dropdown-menu dropdown-menu-sm">
					  <ul class="navi">
					   <li class="navi-item">
					    <a class="navi-link timesheet-excel" href="#" data-month="<?php echo $i; ?>" data-year="<?php echo date("Y"); ?>">
					     <span class="navi-icon"><i class="far fa-file-excel"></i></span>
					     <span class="navi-text">Excel</span>
					    </a>
					   </li>
						 <!-- <li class="navi-item">
					    <a class="navi-link timesheet-pdf" href="#" data-month="<?php echo $i; ?>" data-year="<?php echo date("Y"); ?>">
					     <span class="navi-icon"><i class="far fa-file-pdf"></i></span>
					     <span class="navi-text">PDF</span>
					    </a>
					   </li> -->
					  </ul>
					 </div>
					</div>
				</div>

		<?php } ?>
