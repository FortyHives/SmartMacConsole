@extends('layouts.layoutMaster')

@section('title', 'Agents')

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
  @vite('resources/assets/js/agents/agents.js')
@endsection

@section('content')

  <div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="me-1">
              <p class="text-heading mb-1">Agents</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-2">{{$totalAgent}}</h4>
                <p class="text-success mb-1">(100%)</p>
              </div>
              <small class="mb-0">Total Agents</small>
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
              <p class="text-heading mb-1">Verified Agents</p>
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
              <p class="text-heading mb-1">Duplicate Agents</p>
              <div class="d-flex align-items-center">
                <h4 class="mb-1 me-1">{{$agentDuplicates}}</h4>
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
  <!-- Agents List Table -->
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-agents table">
        <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone Number</th>
          <th>ID Number</th>
          <th>Role</th>
          <th>Country</th>
          <th>Active</th>
          <th>Actions</th>
        </tr>
        </thead>
      </table>
    </div>
    <!-- Offcanvas to add new agent -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddAgent" aria-labelledby="offcanvasAddAgentLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddAgentLabel" class="offcanvas-title">Add Agent</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-agent pt-0" id="addNewAgentForm">
          <input type="hidden" name="id" id="agent_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-agent-first-name" placeholder="First Name" name="first_name"
                   aria-label="First Name" />
            <label for="add-agent-first-name">Agent First Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-agent-middle-name" placeholder="Middle Name" name="middle_name"
                   aria-label="Middle Name" />
            <label for="add-agent-middle-name">Agent Middle Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-agent-last-name" placeholder="Last Name" name="last_name"
                   aria-label="Last Name" />
            <label for="add-agent-last-ame">Agent Last Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-agent-email" class="form-control" placeholder="john.doe@example.com"
                   aria-label="john.doe@example.com" name="email" />
            <label for="add-agent-email">Agent Email Address</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-agent-phone-number" class="form-control phone-mask" placeholder="+254700000000"
                   aria-label="+254700000000" name="phone_number" />
            <label for="add-agent-phone-number">Agent Phone Number</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-agent-id-number" name="id_number" class="form-control" placeholder="0000000"
                   aria-label="000000" />
            <label for="add-agent-id-number">Agent ID Number</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="agent-role" class="form-select"  name="role" >
              <option value="">Select role</option>
              <option value="0">Mapping</option>
              <option value="1">Sales</option>
              <option value="2">Survey</option>
            </select>
            <label for="agent-role">Select Agent Role</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="agent-country" class="form-select"  name="country" >
              <option value="">Select country</option>
              <option value="Kenya">Kenya</option>
              <option value="Uganda">Uganda</option>
            </select>
            <label for="agent-plan">Select Agent Country</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
  </div>
@endsection
