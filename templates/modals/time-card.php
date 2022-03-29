<div class="modal fade" id="rvtimecard" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="rvtimecard" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" >Add Activity</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
					<form id="rvcform2">
          <div class="modal-body">


              <div class="form-group">
                <label for="rvtitle2">Descrizione Attività</label>
                <input type="text" name="rvtitle2" class="form-control" id="rvtitle2" aria-describedby="title2Help" placeholder="" required>
                <small id="title2Help" class="form-text text-muted">Please Enter a Title.</small>
              </div>

							<div class="form-group">
                <label for="rv_date">Data Attività</label>
                <input type="text" name="rv_date" class="form-control" id="rv_date" aria-describedby="rvdateHelp" value="<?php echo date("d/m/Y"); ?>" placeholder="" required>
                <small id="rvdateHelp" class="form-text text-muted">Data Attività</small>
              </div>

							<div class="form-group">
                <label for="rvhour">Ore Attività</label>
                <input type="number" name="rvhour" class="form-control" id="rvhour" aria-describedby="rvhourHelp" placeholder="" min="1" required>
                <small id="rvhourHelp" class="form-text text-muted">Ore Attività</small>
              </div>



              <div class="form-group">
                <label for="project">Select a project</label>
                <select class="form-control" id="project2" name="project2" style="width: 100%;">
                  <option value=""></option>
                  <!-- <option value="86" selected="selected">86</option> -->
                </select>
              </div>

              <div class="form-group">
                <label for="type2">Select Type</label>
                <select class="form-control" id="type" name="type2" style="width: 100%;">
                  <option value=""></option>
                  <option value="p">Permessi</option>
                  <option value="i">Infortunio</option>
                  <option value="m">Malattia</option>
                  <option value="fe">Ferie</option>
                  <option value="pnr">Permesso non retribuito</option>
                  <option value="ai">Assenza Ingiustificata</option>
                  <option value="fs">Festività</option>
                </select>
              </div>

              <div class="form-group">
                <label></label>
                <div class="checkbox-list">
                  <label class="checkbox">
                    <input type="checkbox" name="smart_working2" value="" id="smart_working2" />
                    <span></span>Smart Working
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label></label>
                <div class="checkbox-list">
                  <label class="checkbox">
                    <input type="checkbox" name="cig2" value="" id="cig" />
                    <span></span>CIG
                  </label>
                </div>
              </div>


          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="rvmodsave2">SAVE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
          </div>
					</form>
        </div>
    </div>
</div>
