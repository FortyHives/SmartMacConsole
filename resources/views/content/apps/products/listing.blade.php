@extends('layouts.layoutMaster')

@section('title', 'Products')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
  ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite('resources/assets/js/products/products.js')
@endsection

@section('content')

  <div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Products</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-2">{{$totalProducts}}</h4>
                <p class="text-success mb-1">(100%)</p>
              </div>
              <small class="mb-0">All Products</small>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-primary rounded-3">
                <div class="ri-user-line ri-26px"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Verified Products</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$verified}}</h4>
                <p class="text-success mb-1">(+95%)</p>
              </div>
              <small class="mb-0">Recent analytics</small>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-success rounded-3">
                <div class="ri-user-follow-line ri-26px"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Duplicate Products</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$productDuplicates}}</h4>
                <p class="text-danger mb-1">(0%)</p>
              </div>
              <small class="mb-0">Recent analytics</small>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-danger rounded-3">
                <div class="ri-group-line ri-26px"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Verification Pending</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$notVerified}}</h4>
                <p class="text-success mb-1">(+6%)</p>
              </div>
              <small class="mb-0">Recent analytics</small>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-warning rounded-3">
                <div class="ri-user-unfollow-line ri-26px"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- Products List Table -->
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-products table">
        <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Title</th>
          <th>Brand</th>
          <th>Manufacturer</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>

    <!-- Offcanvas to add new product -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddProduct" aria-labelledby="offcanvasAddProductLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddProductLabel" class="offcanvas-title">Add Product</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-product pt-0" id="addNewProductForm">
          <input type="hidden" name="id" id="product_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-product-name" placeholder="Product Title" name="name" aria-label="Product Name" required />
            <label for="add-product-name">Product Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-product-brand" name="brand" class="form-control" placeholder="Product Brand" aria-label="Product Brand" required />
            <label for="add-product-brand">Product Brand</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-product-manufacturer" name="manufacturer" class="form-control" placeholder="Product Manufacturer" aria-label="Product Manufacturer" required />
            <label for="add-product-manufacturer">Product Manufacturer</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-product-description" name="description" class="form-control" placeholder="Product Description" aria-label="Product Description" required />
            <label for="add-product-description">Product Description</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            {{--<input class="form-control" type="file" id="formFile" name="icon">
            <label for="formFile">Product icon</label>--}}

            <input class="form-control" type="file" id="formFileMultiple" name="photos[]" multiple>
            <label for="formFileMultiple">Product photos</label>
            <div id="fileError" class="error"></div>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
@endsection

