/**
 * Page Outlet List
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_outlet_table = $('.datatables-outlets'),
    select2 = $('.select2'),
    outletView = baseUrl + 'apps/outlets/active/outlet',
    offCanvasForm = $('#offcanvasAddOutlet');

  if (select2.length) {
    var $this = select2;
    select2Focus($this);
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
      dropdownParent: $this.parent()
    });
  }

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Outlets datatable
  if (dt_outlet_table.length) {
    var dt_outlet = dt_outlet_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'active-outlets-list'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone_number' },
        { data: 'id_number' },
        { data: 'role' },
        { data: 'country' },
        { data: 'active' },
        { data: 'action' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 1,
          render: function (data, type, full, meta) {
            return `<span>${full.fake_id}</span>`;
          }
        },
        {
          // Outlet full name
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['name'];

            // For Avatar badge
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
            var $state = states[stateNum],
              $name = full['name'],
              $initials = $name[0].match(/\b\w/g) || [],
              $output;
            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
            $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';

            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center outlet-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' + outletView + '/' + full.id + '" class="text-truncate text-heading"><span class="fw-medium">' + $name + '</span></a>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Outlet email
          targets: 3,
          render: function (data, type, full, meta) {
            var $email = full['email'];
            return '<span class="outlet-email">' + $email + '</span>';
          }
        },
        {
          // Outlet email
          targets: 4,
          render: function (data, type, full, meta) {
            var $phone_number = full['phone_number'];
            return '<span class="outlet-phone-number">' + $phone_number + '</span>';
          }
        },
        {
          // Outlet email
          targets: 5,
          render: function (data, type, full, meta) {
            var $id_number = full['id_number'];
            return '<span class="outlet-id-number">' + $id_number + '</span>';
          }
        },
        {
          // Outlet email
          targets: 6,
          render: function (data, type, full, meta) {
            var $role = full['role'];
            return '<span class="outlet-role">' + $role + '</span>';
          }
        },
        {
          // Outlet email
          targets: 7,
          render: function (data, type, full, meta) {
            var $country = full['country'];
            return '<span class="outlet-country">' + $country + '</span>';
          }
        },
        {
          // email verify
          targets: 8,
          render: function (data, type, full, meta) {
            var $active = full['active'];
            return `${
              $active === 2
                ? '<i class="ri-shield-check-line ri-24px text-success"></i>'
                : '<i class="ri-shield-line ri-24px text-danger" ></i>'
            }`;
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            var statusText = full['active'] === 2 ? 'Disable' : 'Activate';
            return (
              '<div class="d-flex align-items-center gap-50">' +
              `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddOutlet"><i class="ri-edit-box-line ri-20px"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ri-delete-bin-7-line ri-20px"></i></button>` +
              '<button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="' + outletView + '/' + full.id + '" class="dropdown-item">View</a>' +
              `<a href="#" class="dropdown-item activate-record" data-id="${full['id']}" data-active="${full['active']}">${statusText}</a>` +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 20, 50, 100, 250], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export </span>',
          buttons: [
            {
              extend: 'print',
              title: 'Outlets',
              text: '<i class="ri-printer-line me-1" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('outlet-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.body);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              title: 'Outlets',
              text: '<i class="ri-file-text-line me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('outlet-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              title: 'Outlets',
              text: '<i class="ri-file-excel-line me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('outlet-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              title: 'Outlets',
              text: '<i class="ri-file-pdf-line me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('outlet-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              title: 'Outlets',
              text: '<i class="ri-file-copy-line me-1"></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be copy
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('outlet-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        /* {
          text: '<i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Add New Outlet</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddOutlet'
          }
        } */
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var outlet_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}active-outlets-list/${outlet_id}`,
          success: function () {
            dt_outlet.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The outlet has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The Outlet is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
    var outlet_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddRegionLabel').html('Edit Region');

    // get data
    $.get(`${baseUrl}active-outlets-list\/${outlet_id}\/edit`, function (data) {
      $('#outlet_id').val(data.id);
      $('#add-outlet-first-name').val(data.name[0]);
      $('#add-outlet-middle-name').val(data.name[1]);
      $('#add-outlet-last-name').val(data.name[2]);
      $('#add-outlet-email').val(data.email);
      $('#add-outlet-phone-number').val(data.phone_number);
      $('#add-outlet-id-number').val(data.id_number);
      $('#outlet-role').val(data.role);
      $('#outlet-country').val(data.country);
    });
  });

  // changing the title
  $('.add-new').on('click', function () {
    $('#outlet_id').val(''); //reseting input field
    $('#offcanvasAddOutletLabel').html('Add Outlet');
  });

  // validating form and updating outlet's data
  const addNewOutletForm = document.getElementById('addNewOutletForm');

  // outlet form validation
  const fv = FormValidation.formValidation(addNewOutletForm, {
    fields: {
      first_name: {
        validators: {
          notEmpty: {
            message: 'Please enter outlet first name'
          }
        }
      },
      middle_name: {
        validators: {
          notEmpty: {
            message: 'Please enter outlet middle name'
          }
        }
      },
      last_name: {
        validators: {
          notEmpty: {
            message: 'Please enter outlet last name'
          }
        }
      },
      email: {
        validators: {
          notEmpty: {
            message: 'Please enter your email'
          },
          emailAddress: {
            message: 'The value is not a valid email address'
          }
        }
      },
      phone_number: {
        validators: {
          notEmpty: {
            message: 'Please enter outlet phone number'
          }
        }
      },
      id_number: {
        validators: {
          notEmpty: {
            message: 'Please enter outlet ID number'
          }
        }
      },
      role: {
        validators: {
          notEmpty: {
            message: 'Select outlet role'
          }
        }
      },
      country: {
        validators: {
          notEmpty: {
            message: 'Select outlet country'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-5';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // adding or updating outlet when form successfully validate
    $.ajax({
      data: $('#addNewOutletForm').serialize(),
      url: `${baseUrl}active-outlets-list`,
      type: 'POST',
      success: function (status) {
        dt_outlet.draw();
        offCanvasForm.offcanvas('hide');

        // SweetAlert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${status}!`,
          text: `Outlet ${status} successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (err) {
        console.error("Error submitting form:", err);
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Error!',
          text: err.responseJSON ? err.responseJSON.message : 'An error occurred.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });

  const phoneMaskList = document.querySelectorAll('.phone-mask');

  // Phone Number
  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }


  // Suspend Record
  $(document).on('click', '.activate-record', function (e) {
    e.preventDefault();
    var outlet_id = $(this).data('id');
    var current_status = $(this).data('active');
    var new_status = current_status == 2 ? 1 : 2; // Toggle status
    var actionText = new_status == 2 ? 'Activate' : 'Disable';

    // Confirmation dialog
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to ${actionText.toLowerCase()} this outlet!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: `Yes, ${actionText.toLowerCase()} it!`,
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // Update suspend status via AJAX
        $.ajax({
          type: 'PATCH',
          url: `${baseUrl}active-outlets-list/${outlet_id}/status`,
          data: { status: new_status },
          success: function () {
            dt_outlet.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // Success message
        Swal.fire({
          icon: 'success',
          title: 'Updated!',
          text: `The outlet has been ${actionText.toLowerCase()}d.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: `The outlet's status remains unchanged.`,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
});

