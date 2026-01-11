@extends('backend.layouts.master')
@section('title','Supplier Products (No DB) | Wholesale MGM')

@section('main-content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Supplier Products</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- SEARCH + ADD BUTTON --}}
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search product name or SKU">
                </div>

                <div class="col-md-8 text-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                        <i class="fas fa-plus mr-1"></i> Add Product
                    </button>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0" id="productTable">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Lead Time</th>
                            <th>Status</th>
                            <th width="130" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                        {{-- rows inserted by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-2 text-muted">
                <small>* Data is stored in browser LocalStorage (no DB). Clear browser storage to reset.</small>
            </div>
        </div>
    </section>
</div>

<!-- ========================= ADD MODAL ========================= -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus mr-1"></i> Add Supplier Product</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="addForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" id="add_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" id="add_sku" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" id="add_brand" class="form-control" placeholder="Apple, Samsung..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" id="add_category" class="form-control" placeholder="Mobile Phone..." required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Price ($)</label>
                                <input type="number" step="0.01" id="add_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stock Qty</label>
                                <input type="number" id="add_stock" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lead Time (days)</label>
                                <input type="number" id="add_leadtime" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="add_status" class="form-control">
                                    <option value="available">Available</option>
                                    <option value="limited">Limited</option>
                                    <option value="out_of_stock">Out of stock</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="addBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================= EDIT MODAL ========================= -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit mr-1"></i> Edit Supplier Product</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" id="edit_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" id="edit_sku" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Brand</label>
                                <input type="text" id="edit_brand" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" id="edit_category" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Price ($)</label>
                                <input type="number" step="0.01" id="edit_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stock Qty</label>
                                <input type="number" id="edit_stock" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lead Time (days)</label>
                                <input type="number" id="edit_leadtime" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="edit_status" class="form-control">
                                    <option value="available">Available</option>
                                    <option value="limited">Limited</option>
                                    <option value="out_of_stock">Out of stock</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn btn-warning" id="updateBtn">Update</button>
            </div>
        </div>
    </div>
</div>

{{-- ========================= JAVASCRIPT ========================= --}}
<script>
    // Storage key
    const KEY = "supplier_products_demo_v1";

    // Default demo data (if empty storage)
    const defaultData = [
        {id: 1, name: "iPhone 15 Pro 256GB", sku: "IP15P-256", brand: "Apple", category: "Mobile Phone", price: 950, stock: 15, leadtime: 5, status: "available"},
        {id: 2, name: "Samsung Galaxy S24", sku: "SGS24", brand: "Samsung", category: "Mobile Phone", price: 720, stock: 3, leadtime: 7, status: "limited"},
    ];

    // Load
    function loadData(){
        const raw = localStorage.getItem(KEY);
        if(!raw){
            localStorage.setItem(KEY, JSON.stringify(defaultData));
            return [...defaultData];
        }
        return JSON.parse(raw);
    }

    // Save
    function saveData(data){
        localStorage.setItem(KEY, JSON.stringify(data));
    }

    // Badge
    function statusBadge(status){
        if(status === "available") return `<span class="badge badge-success">Available</span>`;
        if(status === "limited") return `<span class="badge badge-warning">Limited</span>`;
        return `<span class="badge badge-danger">Out of stock</span>`;
    }

    // Render table
    function renderTable(filterText=""){
        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";

        const data = loadData();
        const q = filterText.trim().toLowerCase();

        const filtered = data.filter(item =>
            item.name.toLowerCase().includes(q) ||
            item.sku.toLowerCase().includes(q) ||
            item.brand.toLowerCase().includes(q)
        );

        filtered.forEach(item => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td><strong>${item.name}</strong></td>
                <td>${item.sku}</td>
                <td>${item.brand}</td>
                <td>${item.category}</td>
                <td>$${Number(item.price).toFixed(2)}</td>
                <td>${item.stock}</td>
                <td>${item.leadtime} days</td>
                <td>${statusBadge(item.status)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning mr-1" onclick="openEdit(${item.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Add
    document.getElementById("addBtn").addEventListener("click", function(){
        const name = document.getElementById("add_name").value.trim();
        const sku = document.getElementById("add_sku").value.trim();
        const brand = document.getElementById("add_brand").value.trim();
        const category = document.getElementById("add_category").value.trim();
        const price = parseFloat(document.getElementById("add_price").value);
        const stock = parseInt(document.getElementById("add_stock").value);
        const leadtime = parseInt(document.getElementById("add_leadtime").value);
        const status = document.getElementById("add_status").value;

        if(!name || !sku || !brand || !category || isNaN(price) || isNaN(stock) || isNaN(leadtime)){
            alert("Please fill all required fields.");
            return;
        }

        const data = loadData();
        const maxId = data.length ? Math.max(...data.map(x => x.id)) : 0;

        // prevent duplicate SKU (optional)
        if(data.some(x => x.sku.toLowerCase() === sku.toLowerCase())){
            alert("SKU already exists in list.");
            return;
        }

        data.push({id: maxId+1, name, sku, brand, category, price, stock, leadtime, status});
        saveData(data);

        // reset form
        document.getElementById("addForm").reset();
        $("#addModal").modal("hide");

        renderTable(document.getElementById("searchInput").value);
    });

    // Open edit
    function openEdit(id){
        const data = loadData();
        const item = data.find(x => x.id === id);
        if(!item) return;

        document.getElementById("edit_id").value = item.id;
        document.getElementById("edit_name").value = item.name;
        document.getElementById("edit_sku").value = item.sku;
        document.getElementById("edit_brand").value = item.brand;
        document.getElementById("edit_category").value = item.category;
        document.getElementById("edit_price").value = item.price;
        document.getElementById("edit_stock").value = item.stock;
        document.getElementById("edit_leadtime").value = item.leadtime;
        document.getElementById("edit_status").value = item.status;

        $("#editModal").modal("show");
    }

    // Update
    document.getElementById("updateBtn").addEventListener("click", function(){
        const id = parseInt(document.getElementById("edit_id").value);
        const name = document.getElementById("edit_name").value.trim();
        const sku = document.getElementById("edit_sku").value.trim();
        const brand = document.getElementById("edit_brand").value.trim();
        const category = document.getElementById("edit_category").value.trim();
        const price = parseFloat(document.getElementById("edit_price").value);
        const stock = parseInt(document.getElementById("edit_stock").value);
        const leadtime = parseInt(document.getElementById("edit_leadtime").value);
        const status = document.getElementById("edit_status").value;

        if(!name || !sku || !brand || !category || isNaN(price) || isNaN(stock) || isNaN(leadtime)){
            alert("Please fill all required fields.");
            return;
        }

        const data = loadData();

        // prevent SKU duplicate to another record
        if(data.some(x => x.id !== id && x.sku.toLowerCase() === sku.toLowerCase())){
            alert("Another item already uses this SKU.");
            return;
        }

        const idx = data.findIndex(x => x.id === id);
        if(idx === -1) return;

        data[idx] = {id, name, sku, brand, category, price, stock, leadtime, status};
        saveData(data);

        $("#editModal").modal("hide");
        renderTable(document.getElementById("searchInput").value);
    });

    // Delete
    function removeItem(id){
        if(!confirm("Remove this product from supplier list?")) return;

        let data = loadData();
        data = data.filter(x => x.id !== id);
        saveData(data);

        renderTable(document.getElementById("searchInput").value);
    }

    // Search
    document.getElementById("searchInput").addEventListener("input", function(){
        renderTable(this.value);
    });

    // Initial render
    document.addEventListener("DOMContentLoaded", function(){
        renderTable();
    });
</script>
@endsection
