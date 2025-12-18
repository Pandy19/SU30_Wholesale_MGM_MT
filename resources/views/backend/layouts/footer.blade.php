

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput   = document.getElementById('productSearch');
    const brandFilter   = document.getElementById('brandFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    // Exit safely if this page does not have filters
    if (!searchInput && !brandFilter && !categoryFilter) {
        return;
    }

    function filterProducts() {
        const search   = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const brand    = brandFilter ? brandFilter.value.toLowerCase().trim() : '';
        const category = categoryFilter ? categoryFilter.value.toLowerCase().trim() : '';

        // Get rows dynamically (important)
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const rowText     = row.innerText.toLowerCase();
            const rowBrand    = (row.dataset.brand || '').toLowerCase().trim();
            const rowCategory = (row.dataset.category || '').toLowerCase().trim();

            const matchSearch   = !search || rowText.includes(search);
            const matchBrand    = !brand || rowBrand === brand;
            const matchCategory = !category || rowCategory === category;

            row.style.display =
                matchSearch && matchBrand && matchCategory ? '' : 'none';
        });
    }

    // Bind events safely
    if (searchInput) {
        searchInput.addEventListener('keyup', filterProducts);
    }
    if (brandFilter) {
        brandFilter.addEventListener('change', filterProducts);
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterProducts);
    }

});
</script>




<script>
document.addEventListener('DOMContentLoaded', function () {

    // Brand filter (used by multiple pages)
    const brandSearch = document.getElementById('brandSearch');
    const categoryFilter = document.getElementById('categoryFilter');

    if (!brandSearch && !categoryFilter) return;

    const cards = document.querySelectorAll('.brand-card');

    function filterBrands() {
        const search = brandSearch ? brandSearch.value.toLowerCase() : '';
        const category = categoryFilter ? categoryFilter.value.toLowerCase() : '';

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            const cardCategory = card.dataset.category || '';

            const matchBrand = !search || text.includes(search);
            const matchCategory = !category || cardCategory === category;

            card.style.display = (matchBrand && matchCategory) ? '' : 'none';
        });
    }

    if (brandSearch) {
        brandSearch.addEventListener('keyup', filterBrands);
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterBrands);
    }

});
</script>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('assets/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>
</body>
</html>
