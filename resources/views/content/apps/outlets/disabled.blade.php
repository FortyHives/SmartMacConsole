@extends('layouts.layoutMaster')

@section('title', 'Disabled Outlets')

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
  @vite('resources/assets/js/outlets/disabled.js')
@endsection

@section('content')

  <div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Outlets</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-2">{{$totalDisabledOutlets}}</h4>
                <p class="text-success mb-1">({{$disabledPercentage}})</p>
              </div>
              <small class="mb-0">Total count of disabled outlets</small>
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
              <p class="text-heading mb-1">Verified Outlets</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$totalVerifiedOutlets}}</h4>
                <p class="text-success mb-1">({{$verifiedPercentage}})</p>
              </div>
              <small class="mb-0">Total count of disabled verified outlets</small>
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
  <!-- Outlets List Table -->
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-outlets-disabled table">
        <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Name</th>
          <th>Contact Name</th>
          <th>Contact Phone Number</th>
          <th>Category</th>
          <th>Region</th>
          <th>Locality</th>
          <th>Country</th>
          <th>Verified</th>
          <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- Offcanvas to add new outlet -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddOutlet" aria-labelledby="offcanvasAddOutletLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddOutletLabel" class="offcanvas-title">Add Outlet</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-outlet pt-0" id="addNewOutletForm">
          <input type="hidden" name="id" id="outlet_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-outlet-name" placeholder="Outlet Name" name="name"
                   aria-label="Outlet Name" />
            <label for="add-outlet-name">Outlet Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-outlet-contact-name" class="form-control" placeholder="Contact Name"
                   aria-label="Contact Name" name="contact_name" />
            <label for="add-outlet-contact-name">Outlet Contact Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-outlet-contact-phone-number" class="form-control phone-mask" placeholder="+254700000000"
                   aria-label="+254700000000" name="contact-phone_number" />
            <label for="add-outlet-contact-phone-number">Outlet Contact Phone Number</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-outlet-remarks" name="remarks" class="form-control" placeholder="Some remarks about the outlet"
                   aria-label="Some remarks about the outlet" />
            <label for="add-outlet-remarks">Outlet Remarks</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="add-outlet-category-id" class="form-select"  name="category_id" >
              <option value="">Select category</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->title }}</option>
              @endforeach
            </select>
            <label for="add-outlet-category-id">Select Outlet Category</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input class="form-control" type="file" id="formFile" name="photo">
            <label for="formFile">Shop photo</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
  </div>
@endsection
