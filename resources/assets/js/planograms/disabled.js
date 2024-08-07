/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_planogram_table = $('.datatables-planograms-disabled'),
    select2 = $('.select2'),
    planogramView = baseUrl + 'apps/planograms/view/planogram',
    offCanvasForm = $('#offcanvasAddPlanogram');

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

  // Planograms datatable
  if (dt_planogram_table.length) {
    var dt_planogram = dt_planogram_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'disabled-planograms-list'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'id' },
        { data: 'name' },
        { data: 'primary_product_name' },
        { data: 'comparison_products_id' },
        { data: 'category_title' },
        { data: 'suspended' },
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
          // Planogram name
          targets: 2,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['name'];

            // For Avatar badge
            var stateNum = Math.floor(Math.random() * 6);
            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
            var $state = states[stateNum],
              $name = full['name'],
              $initials = $name.match(/\b\w/g) || [],
              $output;
            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
            $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials+ '</span>';

            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center planogram-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' + planogramView + '/' + full.id + '" class="text-truncate text-heading"><span class="fw-medium">' + $name + '</span></a>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // Planogram category
          targets: 3,
          render: function (data, type, full, meta) {
            var $category_title = full['category_title'];
            return '<span class="planogram-category">' + $category_title + '</span>';
          }
        },
        {
          // Planogram category
          targets: 4,
          render: function (data, type, full, meta) {
            var $primary_product_name = full['primary_product_name'];
            return '<span class="planogram-primary-product">' + $primary_product_name + '</span>';
          }
        },
        {
          // Planogram contact name
          targets: 4,
          render: function (data, type, full, meta) {
            var $description = full['description'];
            return '<span class="planogram-description">' + $description + '</span>';
          }
        },
        {
          // Planogram products
          targets: 5,
          render: function (data, type, full, meta) {
            var $comparison_products_id = full['comparison_products_id'];
            return '<span class="planogram-comparison-products-id">' + $comparison_products_id.length + '</span>';
          }
        },
        {
          // Planogram suspended
          targets: 6,
          render: function (data, type, full, meta) {
            var $suspended = full['suspended'];
            return `${
              $suspended === 2
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
            var suspendText = full['suspended'] === 2 ? 'Un-suspend' : 'Suspend';
            return (
              '<div class="d-flex align-items-center gap-50">' +
              `<button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddPlanogram"><i class="ri-edit-box-line ri-20px"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${full['id']}"><i class="ri-delete-bin-7-line ri-20px"></i></button>` +
              '<button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="' + planogramView + '/' + full.id + '" class="dropdown-item">View</a>' +
              `<a href="#" class="dropdown-item activate-record" data-id="${full['id']}" data-active="${full['active']}">${statusText}</a>` +
              `<a href="#" class="dropdown-item suspend-record" data-id="${full['id']}" data-suspended="${full['suspended']}">${suspendText}</a>` +
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
              title: 'Planograms',
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
                      if (item.classList !== undefined && item.classList.contains('planogram-name')) {
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
              title: 'Planograms',
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
                      if (item.classList !== undefined && item.classList.contains('planogram-name')) {
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
              title: 'Planograms',
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
                      if (item.classList !== undefined && item.classList.contains('planogram-name')) {
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
              title: 'Planograms',
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
                      if (item.classList !== undefined && item.classList.contains('planogram-name')) {
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
              title: 'Planograms',
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
                      if (item.classList !== undefined && item.classList.contains('planogram-name')) {
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
        {
          text: '<i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Add New Planogram</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddPlanogram'
          }
        }
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
    var planogram_id = $(this).data('id'),
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
          url: `${baseUrl}disabled-planograms-list/${planogram_id}`,
          success: function () {
            dt_planogram.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The planogram has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The Planogram is not deleted!',
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
    var planogram_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // Hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // Changing the title of offcanvas
    $('#offcanvasAddPlanogramLabel').html('Edit Planogram');

    // Get data
    $.get(`${baseUrl}disabled-planograms-list/${planogram_id}/edit`, function (data) {
      $('#planogram_id').val(data.id);
      $('#add-planogram-name').val(data.name);
      $('#add-planogram-description').val(data.description);
      $('#add-planogram-category-id').val(data.category_id);
      $('#add-planogram-primary-product-id').val(data.primary_product_id);
      $('#add-planogram-comparison-products-id').val(data.comparison_products_id);
    }).fail(function (jqXHR, textStatus, errorThrown) {
      console.error("Error fetching planogram data:", textStatus, errorThrown);
    });
  });

// Changing the title
  $('.add-new').on('click', function () {
    $('#planogram_id').val(''); // Reset input field
    $('#offcanvasAddPlanogramLabel').html('Add Planogram');
  });

// Validating form and updating planogram's data
  const addNewPlanogramForm = document.getElementById('addNewPlanogramForm');
// Planogram form validation
  const fv = FormValidation.formValidation(addNewPlanogramForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter planogram name'
          }
        }
      },
      description: {
        validators: {
          notEmpty: {
            message: 'Please enter planogram description'
          }
        }
      },
      category_id: {
        validators: {
          notEmpty: {
            message: 'Please select planogram outlet category'
          }
        }
      },primary_product_id: {
        validators: {
          notEmpty: {
            message: 'Please select planogram primary product'
          }
        }
      },
      products_id: {
        validators: {
          notEmpty: {
            message: 'Please select planogram comparison products'
          }
        }
      },
      photo: {
        validators: {
          file: {
            extension: 'jpeg,jpg,png,gif',
            type: 'image/jpeg,image/png,image/gif',
            maxSize: 2048 * 1024, // 2048 KB
            message: 'Please choose a valid image file (jpeg, jpg, png, gif) with size less than 2 MB.'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function (field, ele) {
          return '.mb-5';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // Create a FormData object
    var formData = new FormData(addNewPlanogramForm);

    // Adding or updating planogram when form successfully validates
    $.ajax({
      data: formData,
      url: `${baseUrl}disabled-planograms-list`,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function (status) {
        dt_planogram.draw();
        offCanvasForm.offcanvas('hide');

        // SweetAlert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${status}!`,
          text: `Planogram ${status} successfully.`,
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

  // Activate Record
  $(document).on('click', '.activate-record', function (e) {
    e.preventDefault();
    var planogram_id = $(this).data('id');
    var current_status = $(this).data('active');
    var new_status = current_status == 2 ? 1 : 2; // Toggle status
    var actionText = new_status == 2 ? 'Activate' : 'Disable';

    // Confirmation dialog
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to ${actionText.toLowerCase()} this planogram!`,
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
          url: `${baseUrl}disabled-planograms-list/${planogram_id}/activation`,
          data: { status: new_status },
          success: function () {
            dt_planogram().draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // Success message
        Swal.fire({
          icon: 'success',
          title: 'Updated!',
          text: `The planogram has been ${actionText.toLowerCase()}d.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: `The planogram's status remains unchanged.`,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // Verify Record
  $(document).on('click', '.suspend-record', function (e) {
    e.preventDefault();
    var planogram_id = $(this).data('id');
    var current_status = $(this).data('suspended');
    var new_status = current_status == 2 ? 1 : 2; // Toggle status
    var actionText = new_status == 2 ? 'Un-suspend' : 'Suspend';

    // Confirmation dialog
    Swal.fire({
      title: 'Are you sure?',
      text: `You are about to ${actionText.toLowerCase()} this planogram!`,
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
          url: `${baseUrl}disabled-planograms-list/${planogram_id}/suspension`,
          data: { status: new_status },
          success: function () {
            dt_planogram().draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // Success message
        Swal.fire({
          icon: 'success',
          title: 'Updated!',
          text: `The planogram has been ${actionText.toLowerCase()}ed.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: `The planogram's status remains unchanged.`,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
});
