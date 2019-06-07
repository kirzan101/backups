<footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © ICT <?= date('Y') ?></small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    
    
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    {{-- <script src="{{ asset('js/jquery.Chart.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/jquery.jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script> --}}
    <script src="{{ asset('js/sb-admin.min.js') }}"></script>
    {{-- <script src="{{ asset('js/sb-admin-datatables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/sb-admin-charts.min.js') }}"></script> --}}

    @yield('scripts')
</body>

  <script type="text/javascript">
    // Save toggle settings script using HTML5 API - localStorage
    (function ($) {
        // Save sidenav setting
        $('.sidenav-toggler').click(function(event) {
            event.preventDefault();
            if (Boolean(localStorage.getItem('sidenav-toggled'))) {
                localStorage.setItem('sidenav-toggled', '');
             } else {
                localStorage.setItem('sidenav-toggled', '1');
             }
         });
        })(jQuery);
    </script>

</html>