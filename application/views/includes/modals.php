<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-importTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-importTitle"><?=$modals->title?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php echo form_open_multipart($modals->link, [ 'class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
        <div class="modal-body">
            <?php var_dump($modals->link) ?>
        </div>
        <input type="file" name="file">
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>