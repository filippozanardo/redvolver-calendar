
<?php
  $users = get_users( array(
    'orderby' => 'login',
    'order' => 'ASC',
    'exclude' => array( 1 )
  ));

?>
<div class="form-group">
  <label>Select Date</label>
  <input type="text" class="form-control" id="timesheetpicker" name="timesheetpicker" >
</div>


<div class="form-group">
  <label>Select User:</label>
  <select class="form-control kt-select2" id="usertimesheet" name="usertimesheet">
    <option value=""></option>
    <?php if ( ! empty( $users ) ) { ?>
      <?php foreach ( $users as $user ) { ?>
          <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
       <?php } ?>
    <?php } ?>
  </select>
</div>

<div class="form-group">
  <a class="btn btn-primary" id="usertimesheetgo">Submit</a>
</div>
