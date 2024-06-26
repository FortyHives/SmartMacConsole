/**
 * Edit Product
 */

'use strict';

// Select2 (jquery)
$(function () {
  const select2 = $('.select2'),
    TagifyLanguageSuggestionEl = document.querySelector('#TagifyLanguageSuggestion');

  const langaugelist = ['Portuguese', 'German', 'French', 'English'];

  let TagifyLanguageSuggestion = new Tagify(TagifyLanguageSuggestionEl, {
    whitelist: langaugelist,
    dropdown: {
      classname: '',
      enabled: 0,
      closeOnSelect: false
    }
  });

  // Select2 Country
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      select2Focus($this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }
});

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    // variables
    const modalEditProductTaxID = document.querySelector('.modal-edit-tax-id');
    const modalEditProductPhone = document.querySelector('.phone-number-mask');

    // Prefix
    if (modalEditProductTaxID) {
      new Cleave(modalEditProductTaxID, {
        prefix: 'TIN',
        blocks: [3, 3, 3, 4],
        uppercase: true
      });
    }

    // Phone Number Input Mask
    if (modalEditProductPhone) {
      new Cleave(modalEditProductPhone, {
        phone: true,
        phoneRegionCode: 'US'
      });
    }

    // Edit product form validation
    FormValidation.formValidation(document.getElementById('editProductForm'), {
      fields: {
        modalEditProductFirstName: {
          validators: {
            notEmpty: {
              message: 'Please enter your first name'
            },
            regexp: {
              regexp: /^[a-zA-Zs]+$/,
              message: 'The first name can only consist of alphabetical'
            }
          }
        },
        modalEditProductLastName: {
          validators: {
            notEmpty: {
              message: 'Please enter your last name'
            },
            regexp: {
              regexp: /^[a-zA-Zs]+$/,
              message: 'The last name can only consist of alphabetical'
            }
          }
        },
        modalEditProductName: {
          validators: {
            notEmpty: {
              message: 'Please enter your productname'
            },
            stringLength: {
              min: 6,
              max: 30,
              message: 'The name must be more than 6 and less than 30 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z0-9 ]+$/,
              message: 'The name can only consist of alphabetical, number and space'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-12'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        // Submit the form when all fields are valid
        // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
      }
    });
  })();
});
