@extends('layouts.layoutMaster')

@section('title', 'Active Planograms')

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
  @vite('resources/assets/js/planograms/active.js')
@endsection

@section('content')

  <div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Active Planograms</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-2">{{$totalActivePlanograms}}</h4>
                <p class="text-success mb-1">({{$activePercentage}})</p>
              </div>
              <small class="mb-0">Total count of active planograms</small>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-primary rounded-3">
                <div class="ri-store-2-line ri-26px"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Suspended Planograms</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$totalSuspendedPlanograms}}</h4>
                <p class="text-success mb-1">({{$suspendedPercentage}})</p>
              </div>
              <small class="mb-0">Total count of suspended active planograms</small>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-success rounded-3">
                <div class="ri-store-2-fill ri-26px"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- Planograms List Table -->
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-planograms-active table">
        <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Name</th>
          <th>Shop Category</th>
          <th>Description</th>
          <th>Products</th>
          <th>Suspended</th>
          <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- Offcanvas to add new planogram -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddPlanogram" aria-labelledby="offcanvasAddPlanogramLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddPlanogramLabel" class="offcanvas-title">Add Planogram</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-planogram pt-0" id="addNewPlanogramForm">
          <input type="hidden" name="id" id="planogram_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-planogram-name" placeholder="Planogram Name" name="name"
                   aria-label="Planogram Name" />
            <label for="add-planogram-name">Planogram Name</label>
          </div>

          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-planogram-description" name="description" class="form-control" placeholder="Some description about the planogram"
                   aria-label="Some description about the planogram" />
            <label for="add-planogram-description">Planogram Description</label>
          </div>
          <div class="form-floating form-floating-outline mb-6">
            <select multiple class="form-select h-px-100" id="add-planogram-products-id" aria-label="Multiple select example" name="products_id[]">
              <option selected>Select Products</option>
              @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
              @endforeach
            </select>
            <label for="add-planogram-products-id">Select Planogram Products</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input class="form-control" type="file" id="formFile" name="photo">
            <label for="formFile">Planogram photo</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
  </div>
@endsection
