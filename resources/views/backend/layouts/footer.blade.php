<script>
function previewSupplierDoc(modalId, input) {
  const wrap = document.getElementById('newDocPreviewWrap-' + modalId);
  const img  = document.getElementById('newDocImg-' + modalId);
  const link = document.getElementById('newDocLink-' + modalId);
  const name = document.getElementById('newDocName-' + modalId);

  if (!input.files || !input.files[0]) {
    wrap.style.display = 'none';
    return;
  }

  const file = input.files[0];
  const url = URL.createObjectURL(file);

  wrap.style.display = '';
  name.textContent = file.name;

  const isImage = file.type.startsWith('image/');
  const isPdf = file.type === 'application/pdf';

  // Reset
  img.style.display = 'none';
  link.style.display = 'none';

  if (isImage) {
    img.src = url;
    img.style.display = '';
  } else if (isPdf) {
    link.href = url;
    link.style.display = '';
  } else {
    // doc/docx cannot be previewed in browser easily
    link.href = url;
    link.style.display = '';
    link.innerHTML = '<i class="fas fa-download mr-1"></i> Download new';
  }
}
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('brandLogoInput');
    const preview = document.getElementById('brandLogoPreview');
    const text = document.getElementById('brandLogoText');

    if (!input || !preview) return;

    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) return;

        // Basic type guard
        if (!file.type.startsWith('image/')) {
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            if (text) text.textContent = 'Change';
        };
        reader.readAsDataURL(file);
    });
});
</script>



<style>
.brand-photo-wrapper{
    width: 120px;
    height: 120px;
    position: relative;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e9ecef;
    background: #f8f9fa;
    cursor: pointer;
    transition: 0.2s ease-in-out;
}
.brand-photo-wrapper:hover{
    border-color: #007bff;
    box-shadow: 0 0 0 4px rgba(0,123,255,.15);
}

.brand-photo-preview{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.brand-photo-overlay{
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.45);
    color: #fff;
    font-weight: 600;
    opacity: 0;
    transition: 0.2s ease-in-out;
    text-align: center;
}
.brand-photo-wrapper:hover .brand-photo-overlay{
    opacity: 1;
}

.brand-photo-input{
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}
</style>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        
        // 1. Function for Green Tick Popup & Badge Update
        // Link this to your button using: onclick="addToCart()"
        window.addToCart = function() {
            Swal.fire({
                icon: 'success',
                title: 'Item Added!',
                text: 'The product has been added to your draft.',
                showConfirmButton: false,
                timer: 1500,
                iconColor: '#28a745'
            });

            // Update the shopping cart badge count (+1)
            let badge = $('.navbar-badge');
            let currentCount = parseInt(badge.text()) || 0;
            badge.text(currentCount + 1);
        };

        // 2. Calculation Logic for the Cart Modal
        function updateCartTotals() {
            let totalQty = 0;
            let grandTotal = 0;

            $('.cart-item').each(function() {
                // Get Price (removes $ and commas)
                let priceText = $(this).find('.unit-price, .item-price').text();
                let price = parseFloat(priceText.replace(/[^0-9.-]+/g, ""));
                
                // Get Quantity
                let qty = parseInt($(this).find('.item-qty').val());
                if (qty < 1 || isNaN(qty)) {
                    qty = 1;
                    $(this).find('.item-qty').val(1);
                }

                // Update Row Subtotal
                let subtotal = price * qty;
                $(this).find('.subtotal, .item-subtotal').text(subtotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2
                }));

                totalQty += qty;
                grandTotal += subtotal;
            });

            // Update UI Totals in Modal
            $('#total-qty').text(totalQty);
            $('#grand-total').text('$' + grandTotal.toLocaleString('en-US', {
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2
            }));
        }

        // Event: Update totals when quantity changes
        $(document).on('change keyup', '.item-qty', function() {
            updateCartTotals();
        });

        // Run calculation once on page load
        updateCartTotals();
    });
</script>


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
<script>
    function previewImage(e) {
        const img = document.getElementById('previewImg');
        const txt = document.getElementById('imgBoxText');
        img.src = URL.createObjectURL(e.target.files[0]);
        img.style.display = 'block';
        txt.style.display = 'none';
    }

    function uploadSuccess() {
        $('#uploadModal').modal('hide');
        $('#successModal').modal('show');
        setTimeout(() => $('#successModal').modal('hide'), 1200);
    }

    function openCreateModal(){
        document.getElementById('uploadModalTitle').innerText = "Upload Product";
        document.getElementById('submitBtn').innerText = "Submit Product";

        // clear inputs
        document.getElementById('form_title').value = "";
        document.getElementById('form_desc').value = "";
        document.getElementById('form_brand').value = "Apple";
        document.getElementById('form_category').value = "Mobile Phone";
        document.getElementById('form_status').value = "Available";
        document.getElementById('form_cost').value = "";
        document.getElementById('form_qty').value = "";

        // reset preview
        document.getElementById('previewImg').style.display = "none";
        document.getElementById('imgBoxText').style.display = "block";
    }

    function openEditModal(btn){
        $('#uploadModal').modal('show');

        document.getElementById('uploadModalTitle').innerText = "Edit Product";
        document.getElementById('submitBtn').innerText = "Update Product";

        document.getElementById('form_title').value = btn.dataset.title;
        document.getElementById('form_desc').value = btn.dataset.desc || "";
        document.getElementById('form_brand').value = btn.dataset.brand;
        document.getElementById('form_category').value = btn.dataset.category;
        document.getElementById('form_status').value = btn.dataset.status;
        document.getElementById('form_cost').value = btn.dataset.cost;
        document.getElementById('form_qty').value = btn.dataset.qty;

        // show preview square
        const img = document.getElementById('previewImg');
        const txt = document.getElementById('imgBoxText');
        img.src = btn.dataset.image;
        img.style.display = "block";
        txt.style.display = "none";
    }

    function openDeleteModal(productName){
        document.getElementById('deleteText').innerHTML =
            `Are you sure you want to delete <strong>${productName}</strong>?`;
        $('#deleteModal').modal('show');
    }

    function openDetailModal(card){
    const img = card.dataset.image;
    const title = card.dataset.title;
    const brand = card.dataset.brand;
    const category = card.dataset.category;
    const status = card.dataset.status;
    const supplier = card.dataset.supplier;
    const cost = Number(card.dataset.cost || 0);
    const qty = card.dataset.qty || 0;

    document.getElementById('detailImg').src = img;
    document.getElementById('detailTitle').innerText = title;
    document.getElementById('detailName').innerText = title;

    document.getElementById('detailBrandCat').innerText = `${brand} · ${category}`;
    document.getElementById('detailBrandText').innerText = brand;
    document.getElementById('detailCategoryText').innerText = category;

    document.getElementById('detailSupplier').innerText = supplier;
    document.getElementById('detailCost').innerText = cost.toLocaleString();
    document.getElementById('detailCostBig').innerText = cost.toLocaleString();
    document.getElementById('detailQty').innerText = qty;

    // Optional: short description from card if you add data-desc on clickable area
    const desc = card.dataset.desc || "No description provided.";
    document.getElementById('detailShortDesc').innerText = desc;

    // Status badge style
    const badge = document.getElementById('detailStatus');
    badge.innerText = status;
    badge.className = "badge badge-pill px-3 py-2 mr-3";

    if(status.toLowerCase() === "available"){
        badge.classList.add("badge-success");
    }else{
        badge.classList.add("badge-secondary");
    }

    $('#detailModal').modal('show');
    }
   function addToCartSuccess(btn){
    // close supplier modal first
    $('#supplierSelectModal').modal('hide');

    // fill success modal content
    document.getElementById('cartModalProduct').innerText = btn.dataset.product || '—';
    document.getElementById('cartModalSKU').innerText = btn.dataset.sku || '—';
    document.getElementById('cartModalSupplier').innerText = btn.dataset.supplier || '—';
    document.getElementById('cartModalPrice').innerText = Number(btn.dataset.price || 0).toLocaleString();

    // show success modal
    $('#addToCartModal').modal('show');
}

function openCartAfterAdded(){
    // When success modal is fully closed -> open cart modal (prevents scroll bug)
    $('#addToCartModal').one('hidden.bs.modal', function () {
        $('#cartModal').modal('show');

        // reset cart scroll to top
        setTimeout(() => {
            $('#cartModal .modal-body').scrollTop(0);
        }, 50);
    });

    // close success modal now
    $('#addToCartModal').modal('hide');
}

  let currentItem = null;

  function money(n) {
    const val = Number(n || 0);
    return '$' + val.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 });
  }

  function syncTotals() {
    if (!currentItem) return;

    const qty = Number(document.getElementById('m_qty').value || 0);
    const unit = Number(currentItem.unitCost || 0);
    document.getElementById('m_total').textContent = money(qty * unit);
  }

  function clampQty() {
    if (!currentItem) return;

    const max = Number(currentItem.approvedQty || 1);
    const input = document.getElementById('m_qty');
    let qty = Number(input.value || 1);

    if (qty < 1) qty = 1;
    if (qty > max) qty = max;

    input.value = qty;
    syncTotals();
  }

  function openAddToStockModal(item) {
    currentItem = item;

    // Fill UI
    document.getElementById('m_image').src = item.image || '';
    document.getElementById('m_name').textContent = item.name || '—';
    document.getElementById('m_sku').textContent = item.sku || '—';
    document.getElementById('m_po').textContent = item.po || '—';
    document.getElementById('m_brand').textContent = item.brand || '—';
    document.getElementById('m_supplier').textContent = item.supplier || '—';

    document.getElementById('m_approvedQty').textContent = item.approvedQty ?? 0;
    document.getElementById('m_qtyMax').textContent = item.approvedQty ?? 0;

    document.getElementById('m_unitCost').textContent = money(item.unitCost || 0);

    // Defaults
    document.getElementById('m_qty').value = item.approvedQty || 1;
    document.getElementById('m_location').value = '';
    document.getElementById('m_notes').value = '';
    document.getElementById('m_warn').classList.add('d-none');

    clampQty();

    $('#addToStockModal').modal('show');
  }

  // Stepper buttons
  document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('m_qty');
    const minus = document.getElementById('qtyMinus');
    const plus = document.getElementById('qtyPlus');
    const confirmBtn = document.getElementById('m_confirmBtn');

    minus.addEventListener('click', () => {
      qtyInput.value = (Number(qtyInput.value || 1) - 1);
      clampQty();
    });

    plus.addEventListener('click', () => {
      qtyInput.value = (Number(qtyInput.value || 1) + 1);
      clampQty();
    });

    qtyInput.addEventListener('input', clampQty);

    confirmBtn.addEventListener('click', async () => {
      const warn = document.getElementById('m_warn');
      const spinner = document.getElementById('m_spinner');

      const qty = Number(document.getElementById('m_qty').value || 0);
      const location = document.getElementById('m_location').value;
      const notes = document.getElementById('m_notes').value || '';

      const max = Number(currentItem?.approvedQty || 0);
      const valid = location && qty >= 1 && qty <= max;

      if (!valid) {
        warn.classList.remove('d-none');
        return;
      }
      warn.classList.add('d-none');

      // Build payload (send to backend)
      const payload = {
        sku: currentItem.sku,
        po: currentItem.po,
        qty: qty,
        location: location,
        notes: notes
      };

      // Loading state
      spinner.classList.remove('d-none');
      confirmBtn.disabled = true;

      try {
        // TODO: replace with your real endpoint + CSRF
        // Example with fetch:
        // await fetch("{{ route('stock.addApproved') }}", {
        //   method: "POST",
        //   headers: {
        //     "Content-Type": "application/json",
        //     "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //   },
        //   body: JSON.stringify(payload)
        // });

        console.log('Submitting payload:', payload);

        $('#addToStockModal').modal('hide');

        // Optionally show toast / reload / update row status
        // location.reload();

      } catch (e) {
        warn.textContent = "Something went wrong. Please try again.";
        warn.classList.remove('d-none');
      } finally {
        spinner.classList.add('d-none');
        confirmBtn.disabled = false;
      }
    });
  });
document.querySelectorAll('.payment-card').forEach(card => {
    card.addEventListener('click', function () {

        document.querySelectorAll('.payment-card')
            .forEach(c => c.classList.remove('active'));

        this.classList.add('active');

        document.getElementById('payment_method').value =
            this.getAttribute('data-value');
    });
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
