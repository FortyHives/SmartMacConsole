@extends('layouts.layoutMaster')

@section('title', 'Localities')

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
  @vite('resources/assets/js/places/localities.js')
@endsection

@section('content')

  <div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Localities</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-2">{{$totalLocality}}</h4>
                <p class="text-success mb-1">(100%)</p>
              </div>
              <small class="mb-0">Total Localities</small>
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
              <p class="text-heading mb-1">Verified Localities</p>
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
              <p class="text-heading mb-1">Duplicate Localities</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$localityDuplicates}}</h4>
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
  <!-- Localities List Table -->
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-localities table">
        <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Locality</th>
          <th>Region</th>
          <th>Country</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Attitude</th>
          <th>Proximity Radius</th>
          <th>Population</th>
          <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- Offcanvas to add new locality -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddLocality" aria-labelledby="offcanvasAddLocalityLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddLocalityLabel" class="offcanvas-title">Add Locality</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-locality pt-0" id="addNewLocalityForm">
          <input type="hidden" name="id" id="locality_id">
          <input type="hidden" name="region_id" id="region_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-locality-name" placeholder="Locality Name" name="name" aria-label="Locality Name" required />
            <label for="add-locality-name">Locality Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-locality-latitude" class="form-control" placeholder="0.0" aria-label="0.0" name="latitude" required />
            <label for="add-locality-latitude">Locality Latitude</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-locality-longitude" class="form-control" placeholder="0.0" aria-label="0.0" name="longitude" required />
            <label for="add-locality-longitude">Locality Longitude</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-locality-attitude" name="attitude" class="form-control" placeholder="0.0" aria-label="0.0" required />
            <label for="add-locality-attitude">Locality Attitude</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-locality-proximity-radius" name="proximity_radius" class="form-control" placeholder="0.0" aria-label="0.0" required />
            <label for="add-locality-proximity-radius">Locality Proximity Radius</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="add-locality-country" name="country" class="select2 form-select" required>
              <option value="">Select</option>
              <option value="Kenya">Kenya</option>
              <option value="Uganda">Uganda</option>
            </select>
            <label for="add-locality-country">Locality Country</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-locality-population" name="population" class="form-control" placeholder="0.0" aria-label="0.0" required />
            <label for="add-locality-population">Locality Population</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
@endsection
