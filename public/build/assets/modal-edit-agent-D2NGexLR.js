$(function(){const a=$(".select2"),e=document.querySelector("#TagifyLanguageSuggestion"),t=["Portuguese","German","French","English"];new Tagify(e,{whitelist:t,dropdown:{classname:"",enabled:0,closeOnSelect:!1}}),a.length&&a.each(function(){var n=$(this);select2Focus(n),n.wrap('<div class="position-relative"></div>').select2({placeholder:"Select value",dropdownParent:n.parent()})})});document.addEventListener("DOMContentLoaded",function(a){(function(){const e=document.querySelector(".modal-edit-tax-id"),t=document.querySelector(".phone-number-mask");e&&new Cleave(e,{prefix:"TIN",blocks:[3,3,3,4],uppercase:!0}),t&&new Cleave(t,{phone:!0,phoneRegionCode:"US"}),FormValidation.formValidation(document.getElementById("editAgentForm"),{fields:{modalEditAgentFirstName:{validators:{notEmpty:{message:"Please enter your first name"},regexp:{regexp:/^[a-zA-Zs]+$/,message:"The first name can only consist of alphabetical"}}},modalEditAgentLastName:{validators:{notEmpty:{message:"Please enter your last name"},regexp:{regexp:/^[a-zA-Zs]+$/,message:"The last name can only consist of alphabetical"}}},modalEditAgentName:{validators:{notEmpty:{message:"Please enter your agentname"},stringLength:{min:6,max:30,message:"The name must be more than 6 and less than 30 characters long"},regexp:{regexp:/^[a-zA-Z0-9 ]+$/,message:"The name can only consist of alphabetical, number and space"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap5:new FormValidation.plugins.Bootstrap5({eleValidClass:"",rowSelector:".col-12"}),submitButton:new FormValidation.plugins.SubmitButton,autoFocus:new FormValidation.plugins.AutoFocus}})})()});
