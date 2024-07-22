@extends('layouts.layoutMaster')

@section('title', 'User View - Pages')

@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/tagify/tagify.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
  ])
@endsection

@section('page-style')
  @vite([
    'resources/assets/vendor/scss/pages/page-user-view.scss'
  ])
@endsection

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/tagify/tagify.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])
@endsection

@section('page-script')
  @vite([
    'resources/assets/js/agents/modal-edit-agent.js',
    'resources/assets/js/agents/agent-view.js',
  ])
@endsection

@section('content')
  <div class="row gy-6 gy-md-0">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
      <!-- User Card -->
      <div class="card mb-6">
        <div class="card-body pt-12">
          <div class="user-avatar-section">
            <div class=" d-flex align-items-center flex-column">
              <img class="img-fluid rounded-3 mb-4" src="{{asset('assets/img/avatars/1.png')}}" height="120" width="120"
                   alt="User avatar" />
              <div class="user-info text-center">
                <h5>{{ $agent->name[0] }} {{ $agent->name[1] }} {{ $agent->name[2] }}</h5>
                <span class="badge bg-label-danger rounded-pill">Subscriber</span>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
            <div class="d-flex align-items-center me-5 gap-4">
              <div class="avatar">
                <div class="avatar-initial bg-label-primary rounded-3">
                  <i class='ri-check-line ri-24px'></i>
                </div>
              </div>
              <div>
                <h5 class="mb-0">1.23k</h5>
                <span>Visits Done</span>
              </div>
            </div>
            <div class="d-flex align-items-center gap-4">
              <div class="avatar">
                <div class="avatar-initial bg-label-primary rounded-3">
                  <i class='ri-briefcase-line ri-24px'></i>
                </div>
              </div>
              <div>
                <h5 class="mb-0">568</h5>
                <span>Sales Done</span>
              </div>
            </div>
          </div>
          <h5 class="pb-4 border-bottom mb-4">Details</h5>
          <div class="info-container">
            <ul class="list-unstyled mb-6">
              <li class="mb-2">
                <span class="fw-medium text-heading me-2">Email:</span>
                <span>{{$agent->email}}</span>
              </li>
              <li class="mb-2">
                <span class="fw-medium text-heading me-2">Status:</span>
                <span class="badge bg-label-success rounded-pill">Active</span>
              </li>
              <li class="mb-2">
                <span class="fw-medium text-heading me-2">Role:</span>
                <span>{{$agent->role}}</span>
              </li>
              <li class="mb-2">
                <span class="fw-medium text-heading me-2">ID Number:</span>
                <span>{{$agent->id_number}}</span>
              </li>
              <li class="mb-2">
                <span class="fw-medium text-heading me-2">Phone number:</span>
                <span>{{$agent->phone_number}}</span>
              </li>
              <li class="mb-2">
                <span class="fw-medium text-heading me-2">Country:</span>
                <span>{{$agent->country}}</span>
              </li>
            </ul>
            <div class="d-flex justify-content-center">
              <a href="javascript:;" class="btn btn-primary me-4" data-bs-target="#editAgent"
                 data-bs-toggle="modal">Edit</a>
              <a href="javascript:;" class="btn btn-outline-danger suspend-agent">Suspend</a>
            </div>
          </div>
        </div>
      </div>
      <!-- /User Card -->
    </div>
    <!--/ User Sidebar -->


    <!-- User Content -->
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">

      <!-- Activity Timeline -->
      <div class="card mb-6">
        <h5 class="card-header">User Activity Timeline</h5>
        <div class="card-body pt-0">
          <ul class="timeline mb-0 mt-2">
            <li class="timeline-item timeline-item-transparent">
              <span class="timeline-point timeline-point-primary"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-3">
                  <h6 class="mb-0">12 Invoices have been paid</h6>
                  <small class="text-muted">12 min ago</small>
                </div>
                <p class="mb-2">
                  Invoices have been paid to the company
                </p>
                <div class="d-flex align-items-center mb-1">
                  <div class="badge bg-lighter rounded-3 mb-1_5">
                    <img src="{{asset('assets/img/icons/misc/pdf.png')}}" alt="img" width="15" class="me-2">
                    <span class="h6 mb-0 text-secondary">invoices.pdf</span>
                  </div>
                </div>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent">
              <span class="timeline-point timeline-point-success"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-3">
                  <h6 class="mb-0">Client Meeting</h6>
                  <small class="text-muted">45 min ago</small>
                </div>
                <p class="mb-2">
                  Project meeting with john @10:15am
                </p>
                <div class="d-flex justify-content-between flex-wrap gap-2 mb-1_5">
                  <div class="d-flex flex-wrap align-items-center">
                    <div class="avatar avatar-sm me-2">
                      <img src="{{asset('assets/img/avatars/4.png')}}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div>
                      <p class="mb-0 small fw-medium">Lester McCarthy (Client)</p>
                      <small>CEO of {{ config('variables.creatorName') }}</small>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="timeline-item timeline-item-transparent">
              <span class="timeline-point timeline-point-info"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-3">
                  <h6 class="mb-0">Create a new project for client</h6>
                  <small class="text-muted">2 Day Ago</small>
                </div>
                <p class="mb-2">
                  6 team members in a project
                </p>
                <ul class="list-group list-group-flush">
                  <li
                    class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                    <div class="d-flex flex-wrap align-items-center">
                      <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 me-2">
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                            title="Vinnie Mostowy" class="avatar pull-up">
                          <img class="rounded-circle" src="{{asset('assets/img/avatars/5.png')}}" alt="Avatar" />
                        </li>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                            title="Allen Rieske" class="avatar pull-up">
                          <img class="rounded-circle" src="{{asset('assets/img/avatars/12.png')}}" alt="Avatar" />
                        </li>
                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                            title="Julee Rossignol" class="avatar pull-up">
                          <img class="rounded-circle" src="{{asset('assets/img/avatars/6.png')}}" alt="Avatar" />
                        </li>
                        <li class="avatar">
                          <span class="avatar-initial rounded-circle pull-up text-heading" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="3 more">+3</span>
                        </li>
                      </ul>
                    </div>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <!-- /Activity Timeline -->

    </div>
    <!--/ User Content -->
  </div>

  <!-- Modal -->
  <!-- Edit Agent Modal -->
  <div class="modal fade" id="editAgent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-agent">
      <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body p-0">
          <div class="text-center mb-6">
            <h4 class="mb-2">Edit Agent Information</h4>
            <p class="mb-6">Updating agent details will receive a privacy audit.</p>
          </div>
          <form id="editAgentForm" class="row g-5" onsubmit="return false">
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="modalEditAgentFirstName" name="modalEditAgentFirstName" class="form-control" value="Oliver" placeholder="Oliver" />
                <label for="modalEditAgentFirstName">First Name</label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="modalEditAgentLastName" name="modalEditAgentLastName" class="form-control" value="Queen" placeholder="Queen" />
                <label for="modalEditAgentLastName">Last Name</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating form-floating-outline">
                <input type="text" id="modalEditAgentName" name="modalEditAgentName" class="form-control" value="oliver.queen" placeholder="oliver.queen" />
                <label for="modalEditAgentName">Agentname</label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="modalEditAgentEmail" name="modalEditAgentEmail" class="form-control" value="oliverqueen@gmail.com" placeholder="oliverqueen@gmail.com" />
                <label for="modalEditAgentEmail">Email</label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select id="modalEditAgentStatus" name="modalEditAgentStatus" class="form-select" aria-label="Default select example">
                  <option value="1" selected>Active</option>
                  <option value="2">Inactive</option>
                  <option value="3">Suspended</option>
                </select>
                <label for="modalEditAgentStatus">Status</label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input type="text" id="modalEditTaxID" name="modalEditTaxID" class="form-control modal-edit-tax-id" placeholder="123 456 7890" />
                <label for="modalEditTaxID">Tax ID</label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="input-group input-group-merge">
                <span class="input-group-text">US (+1)</span>
                <div class="form-floating form-floating-outline">
                  <input type="text" id="modalEditAgentPhone" name="modalEditAgentPhone" class="form-control phone-number-mask" value="+1 609 933 4422" placeholder="+1 609 933 4422" />
                  <label for="modalEditAgentPhone">Phone Number</label>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <input id="TagifyLanguageSuggestion" name="TagifyLanguageSuggestion" class="form-control h-auto" placeholder="select language" value="English">
                <label for="TagifyLanguageSuggestion">Language</label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-floating form-floating-outline">
                <select id="modalEditAgentCountry" name="modalEditAgentCountry" class="select2 form-select" data-allow-clear="true">
                  <option value="">Select</option>
                  <option value="Australia">Australia</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Brazil">Brazil</option>
                  <option value="Canada">Canada</option>
                  <option value="China">China</option>
                  <option value="France">France</option>
                  <option value="Germany">Germany</option>
                  <option value="India" selected>India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Japan">Japan</option>
                  <option value="Korea">Korea, Republic of</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Russia">Russian Federation</option>
                  <option value="South Africa">South Africa</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                </select>
                <label for="modalEditAgentCountry">Country</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="editBillingAddress" />
                <label for="editBillingAddress" class="text-heading">Use as a billing address?</label>
              </div>
            </div>
            <div class="col-12 text-center d-flex flex-wrap justify-content-center gap-4 row-gap-4">
              <button type="submit" class="btn btn-primary">Submit</button>
              <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--/ Edit Agent Modal -->
  <!-- /Modal -->
@endsection
