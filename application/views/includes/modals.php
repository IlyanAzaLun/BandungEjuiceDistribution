<?php function content($data){
  $CI = &get_instance();
  switch ($data) {
    case 'upload': ?>
      <input type="file" id='file' name="file">
<?php break;
    case 'delete': ?>
      <input type="text" id='id' name="id">
<?php break;
    default:
      $CI->load->view($data);
      break;
  }
} ?>
<div class="modal fade" id="<?= $modals->id ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $modals->id ?>Title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?= $modals->id ?>Title"><?= $modals->title ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php echo form_open_multipart($modals->link, ['class' => 'form-validate', 'autocomplete' => 'off']); ?>
      <div class="modal-body">
        <?php var_dump($modals->link) ?>
        <?php content($modals->content) ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn <?= $modals->btn ?>"><?= $modals->submit ?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>