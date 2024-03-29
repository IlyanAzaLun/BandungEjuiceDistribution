<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

</div>
<!-- ./wrapper -->

<footer class="main-footer bg-dark">
  <div class="float-right d-none d-sm-block">
    Made with <i class="fa fa-heart" style="color: red;"></i> for Developers
    &nbsp; &nbsp; &nbsp; &nbsp;
    <b>Version</b> 2.0
  </div>
  <strong>Copyright &copy; <?php echo date('Y') ?> <a href="<?php echo url('/') ?>"><?php echo setting('company_name') ?></a>.</strong> All rights
  reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- ChartJS -->
<script src="<?php echo $url->assets ?>plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo $url->assets ?>plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo $url->assets ?>plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo $url->assets ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo $url->assets ?>plugins/moment/moment.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo $url->assets ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo $url->assets ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo $url->assets ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?php echo $url->assets ?>plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?php echo $url->assets ?>plugins/toastr/toastr.min.js"></script>

<!-- DataTables -->
<script src="<?php echo $url->assets ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-colreorder/js/dataTables.colReorder.second.js"></script>
<script src="<?php echo $url->assets ?>plugins/datatables-colreorder/js/colReorder.bootstrap4.min.js"></script>
<!-- jquery-validation -->
<script src="<?php echo $url->assets ?>plugins/jszip/jszip.min.js"></script>

<!-- jquery-validation -->
<script src="<?php echo $url->assets ?>plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo $url->assets ?>plugins/jquery-validation/additional-methods.min.js"></script>

<!-- Bootstrap Switch -->
<script src="<?php echo $url->assets ?>plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<!-- Select2 -->
<script src="<?php echo $url->assets ?>plugins/select2/js/select2.full.min.js"></script>
<!-- pace-progress -->
<script src="<?php echo $url->assets ?>plugins/pace-progress/pace.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo $url->assets ?>js/adminlte.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo $url->assets ?>js/demo.js"></script>
<script src="<?php echo $url->assets ?>js/attribute.js"></script>
<script>
  $(document).ready(function(e) {

    let disableConfirmation = false;
    $(window).on('beforeunload', event => {
      const confirmationText = 'Are you sure?';
      if (!disableConfirmation) {
        event.returnValue = confirmationText; // Gecko, Trident, Chrome 34+
        disableConfirmation = false;
        return confirmationText; // Gecko, WebKit, Chrome <34
      } else {
        // Set flag back to false, just in case
        // user stops loading page after clicking a link.
        disableConfirmation = false;
      }
    });
    $('a').on('click', function() {
      disableConfirmation = true;
    });
    $('form').submit(function() {
      disableConfirmation = true;
      $('.loading').css({"display":"block"});
    });
    $('button#close-loading').on('click', function(){
      disableConfirmation = false;
      $('.loading').css({"display":"none"});
    })

  })
  // default
  $(function() {
    $("input[data-bootstrap-switch]").each(function() {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
      // $(this).prop('checked') ? $(this).val(1) : $(this).val(0);
    });

    $.validator.setDefaults({
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
    
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });

  });
  document.addEventListener('DOMContentLoaded', (e) => {
      console.log(`Document is ready!`);
      $('li.nav-item.has-treeview.menu-open').focus()
  });
  document.addEventListener("wheel", function(event){
      if(document.activeElement.type === "number"){
          document.activeElement.blur();
      }
  });
/* //disabeld right click
document.onkeydown = function(e) {
  if(event.keyCode == 123) {
    return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
    return false;  
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
   return false;
  }
  if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
    return false;
  }
}
*/
  location.base = '<?= url() ?>'
</script>
</body>

</html>