<div class="modal fade" id="rvmodaledit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="rvmodaledit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="rvmodallabel">Edit Timecard</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
          </div>
          <div class="modal-body">
            <form id="rvcform">

              <div class="form-group">
                <label for="rvtitle">Titolo</label>
                <input type="text" name="rvtitle" class="form-control" id="rvtitle" aria-describedby="titleHelp" placeholder="" required>
                <small id="titleHelp" class="form-text text-muted">Dscrizione attività</small>
              </div>
              <div class="form-group">
                <label for="project">Select a project</label>
                <select class="form-control" id="project" name="project" style="width: 100%;">
                  <option value=""></option>
                  <!-- <option value="86" selected="selected">86</option> -->
                </select>
              </div>

              <div class="form-group">
                <label for="type">Select Type</label>
                <select class="form-control" id="type" name="type" style="width: 100%;">
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
                    <input type="checkbox" name="smart_working" value="" id="smart_working" <?php if ( $mode == 'EDIT') {  if ($closed) { echo 'checked'; } } ?>>
                    <span></span>Smart Working
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label></label>
                <div class="checkbox-list">
                  <label class="checkbox">
                    <input type="checkbox" name="cig" value="" id="cig" <?php if ( $mode == 'EDIT') {  if ($cig) { echo 'checked'; } } ?>>
                    <span></span>CIG
                  </label>
                </div>
              </div>

              <input type="hidden" id="rvstart"/>
              <input type="hidden" id="rvend"/>
              <input type="hidden" id="rvcid"/>
              <input type="hidden" id="rvmode"/>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="rvmoddel">DELETE</button>
            <button type="submit" class="btn btn-success" id="rvmodsave">SAVE</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            <!-- <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary font-weight-bold">Save changes</button> -->
          </div>
        </div>
    </div>
</div>
