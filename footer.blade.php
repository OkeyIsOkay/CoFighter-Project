<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
<footer class="page-footer">
    <p class="mb-0">Copyright © <?php echo date('Y'); ?>. All right reserved.</p>
</footer>
</div>
<!--end wrapper-->
<!--start switcher-->

<!--end switcher-->
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<!--app JS-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        lengthChange: true,
        pageLength: 100
    });
  } );
</script>
<script>
$(document).ready(function() {
    var table = $('#example2').DataTable( {
        lengthChange: true,
        pageLength: 100
        buttons: [ 'copy', 'excel', 'pdf', 'print']
    } );

    table.buttons().container()
        .appendTo( '#example2_wrapper .col-md-6:eq(0)' );
} );
</script>
</body>
</html>
