
<!-- ===================================================== -->
<!-- CHART JS -->
<!-- ===================================================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* PROFIT MARGIN – CLEAN VERSION */
new Chart(document.getElementById('profitMarginChart'), {
    type: 'bar',
    data: {
        labels: ['Galaxy S24','iPhone 15','Dell XPS'],
        datasets: [{
            label: 'Profit Margin %',
            data: [25,21,22],
            backgroundColor: '#17a2b8',
            borderRadius: 6,
            barThickness: 22
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.raw + '%'
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    callback: value => value + '%'
                }
            },
            y: {
                grid: { display: false }
            }
        }
    }
});

/* PROFIT PER MONTH */
new Chart(document.getElementById('profitMonthChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May'],
        datasets: [{
            label: 'Monthly Profit',
            data: [5200,6800,4300,7200,9350],
            borderColor: '#28a745',
            backgroundColor: 'rgba(40,167,69,0.15)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        }
    }
});
</script>


<!-- ===================================================== -->
<!-- CHART.JS SCRIPTS -->
<!-- ===================================================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    new Chart(document.getElementById('salesTrendChart'), {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun'],
            datasets: [
                { label:'B2B', data:[12000,18000,15000,22000,26000,30000], borderColor:'#007bff', fill:false },
                { label:'B2C', data:[8000,10000,9500,12000,14000,16000], borderColor:'#28a745', fill:false }
            ]
        }
    });

    new Chart(document.getElementById('profitCostChart'), {
        type: 'bar',
        data: {
            labels:['Jan','Feb','Mar','Apr','May','Jun'],
            datasets:[
                { label:'Cost', data:[14000,16000,15000,18000,20000,23000], backgroundColor:'#dc3545' },
                { label:'Profit', data:[6000,9000,8000,11000,12000,14000], backgroundColor:'#28a745' }
            ]
        }
    });

    new Chart(document.getElementById('topProductsChart'), {
        type:'bar',
        data:{
            labels:['iPhone 15 Pro','Galaxy S24','Smart TV 55"','AirPods Pro'],
            datasets:[{
                label:'Units Sold',
                data:[120,95,60,40],
                backgroundColor:'#17a2b8'
            }]
        }
    });

});
</script>

<script>
function confirmSale() {
    // show success popup
    $('#saleSuccessModal').modal('show');

    // redirect after 1.5 seconds
    setTimeout(function () {
        window.location.href = "{{ route('sales_order.confirm_sale') }}";
    }, 1500);
}
</script>


<script>
function confirmAddToStock(product, qty) {
    if (confirm(`Add ${qty} units of ${product} into stock?`)) {
        alert('Stock updated successfully (UI only)');
    }
}
</script>


<script>
$(function () {
    $('[title]').tooltip();
});
</script>

<script>
function printWithStatus(status) {
    const stamp = document.getElementById('approval-stamp');

    if (!stamp) {
        window.print();
        return;
    }

    // Reset classes
    stamp.classList.remove('approved', 'rejected', 'd-none');

    if (status === 'approved') {
        stamp.textContent = 'APPROVED';
        stamp.classList.add('approved');
    } else if (status === 'rejected') {
        stamp.textContent = 'REJECTED';
        stamp.classList.add('rejected');
    }

    window.print();

    // Hide stamp after printing
    setTimeout(() => {
        stamp.classList.add('d-none');
    }, 1000);
}
</script>


<script>
document.getElementById('confirmOrderBtn').addEventListener('click', function () {
    $('#successModal').modal('show');
});

document.getElementById('continueBtn').addEventListener('click', function () {
    window.location.href = "{{ route('purchase_orders.confirm_payment') }}";
});
</script>


<script>
document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('order-qty')) return;

    const input = e.target;
    let qty = parseInt(input.value);
    const max = parseInt(input.dataset.available);
    const price = parseFloat(input.dataset.price);

    if (qty > max) {
        alert('Cannot order more than available quantity (' + max + ')');
        qty = max;
        input.value = max;
    }
    if (qty < 1 || isNaN(qty)) {
        qty = 1;
        input.value = 1;
    }

    const row = input.closest('tr');
    const subtotalCell = row.querySelector('.subtotal');
    subtotalCell.innerText = '$' + (qty * price).toFixed(2);

    // Recalculate total
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(cell => {
        total += parseFloat(cell.innerText.replace('$',''));
    });

    document.getElementById('po-total').innerText = '$' + total.toFixed(2);
    document.getElementById('po-grand-total').innerText = '$' + total.toFixed(2);
});
</script>


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

<!-- ===================================================== -->
<!-- CHART SCRIPT -->
<!-- ===================================================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('dashboardSalesChart'), {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat'],
        datasets: [
            {
                label: 'Sales',
                data: [4200, 3800, 5200, 6100, 4500, 7200],
                borderColor: '#007bff',
                tension: 0.4
            },
            {
                label: 'Profit',
                data: [1200, 900, 1600, 1900, 1300, 2400],
                borderColor: '#28a745',
                tension: 0.4
            }
        ]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
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

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('assets/dist/js/pages/dashboard.js')}}"></script>
</body>
</html>
