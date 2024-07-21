$(function(){var m=$(".datatables-agents"),f=$(".select2"),g=baseUrl+"apps/agents/agent",l=$("#offcanvasAddAgent");if(f.length){var d=f;select2Focus(d),d.wrap('<div class="position-relative"></div>').select2({placeholder:"Select Country",dropdownParent:d.parent()})}if($.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),m.length)var c=m.DataTable({processing:!0,serverSide:!0,ajax:{url:baseUrl+"agents-list"},columns:[{data:""},{data:"id"},{data:"name"},{data:"email"},{data:"phone_number"},{data:"id_number"},{data:"role"},{data:"country"},{data:"active"},{data:"action"}],columnDefs:[{className:"control",searchable:!1,orderable:!1,responsivePriority:2,targets:0,render:function(t,r,n,s){return""}},{searchable:!1,orderable:!1,targets:1,render:function(t,r,n,s){return`<span>${n.fake_id}</span>`}},{targets:2,responsivePriority:4,render:function(t,r,n,s){var u=n.name,e=Math.floor(Math.random()*6),o=["success","danger","warning","info","dark","primary","secondary"],a=o[e],u=n.name,i=u[0].match(/\b\w/g)||[],b;i=((i.shift()||"")+(i.pop()||"")).toUpperCase(),b='<span class="avatar-initial rounded-circle bg-label-'+a+'">'+i+"</span>";var h='<div class="d-flex justify-content-start align-items-center agent-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">'+b+'</div></div><div class="d-flex flex-column"><a href="'+g+"/"+n.id+'" class="text-truncate text-heading"><span class="fw-medium">'+u+"</span></a></div></div>";return h}},{targets:3,render:function(t,r,n,s){var e=n.email;return'<span class="agent-email">'+e+"</span>"}},{targets:4,render:function(t,r,n,s){var e=n.phone_number;return'<span class="agent-phone-number">'+e+"</span>"}},{targets:5,render:function(t,r,n,s){var e=n.id_number;return'<span class="agent-id-number">'+e+"</span>"}},{targets:6,render:function(t,r,n,s){var e=n.role;return'<span class="agent-role">'+e+"</span>"}},{targets:7,render:function(t,r,n,s){var e=n.country;return'<span class="agent-country">'+e+"</span>"}},{targets:8,render:function(t,r,n,s){var e=n.active;return`${e===2?'<i class="ri-shield-check-line ri-24px text-success"></i>':'<i class="ri-shield-line ri-24px text-danger" ></i>'}`}},{targets:-1,title:"Actions",searchable:!1,orderable:!1,render:function(t,r,n,s){var e=n.active===2?"Disable":"Activate";return`<div class="d-flex align-items-center gap-50"><button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${n.id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddAgent"><i class="ri-edit-box-line ri-20px"></i></button><button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${n.id}"><i class="ri-delete-bin-7-line ri-20px"></i></button><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button><div class="dropdown-menu dropdown-menu-end m-0"><a href="`+g+"/"+n.id+`" class="dropdown-item">View</a><a href="#" class="dropdown-item activate-record" data-id="${n.id}" data-active="${n.active}">${e}</a></div></div>`}}],order:[[2,"desc"]],dom:'<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"<"me-5 ms-n2"f><"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>>t<"row mx-1"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',lengthMenu:[10,20,50,100,250],language:{sLengthMenu:"_MENU_",search:"",searchPlaceholder:"Search",info:"Displaying _START_ to _END_ of _TOTAL_ entries"},buttons:[{extend:"collection",className:"btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light",text:'<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export </span>',buttons:[{extend:"print",title:"Agents",text:'<i class="ri-printer-line me-1" ></i>Print',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(t,r,n){if(t.length<=0)return t;var s=$.parseHTML(t),e="";return $.each(s,function(o,a){a.classList!==void 0&&a.classList.contains("agent-name")?e=e+a.lastChild.firstChild.textContent:a.innerText===void 0?e=e+a.textContent:e=e+a.innerText}),e}}},customize:function(t){$(t.document.body).css("color",config.colors.headingColor).css("border-color",config.colors.borderColor).css("background-color",config.colors.body),$(t.document.body).find("table").addClass("compact").css("color","inherit").css("border-color","inherit").css("background-color","inherit")}},{extend:"csv",title:"Agents",text:'<i class="ri-file-text-line me-1" ></i>Csv',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(t,r,n){if(t.length<=0)return t;var s=$.parseHTML(t),e="";return $.each(s,function(o,a){a.classList!==void 0&&a.classList.contains("agent-name")?e=e+a.lastChild.firstChild.textContent:a.innerText===void 0?e=e+a.textContent:e=e+a.innerText}),e}}}},{extend:"excel",title:"Agents",text:'<i class="ri-file-excel-line me-1"></i>Excel',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(t,r,n){if(t.length<=0)return t;var s=$.parseHTML(t),e="";return $.each(s,function(o,a){a.classList!==void 0&&a.classList.contains("agent-name")?e=e+a.lastChild.firstChild.textContent:a.innerText===void 0?e=e+a.textContent:e=e+a.innerText}),e}}}},{extend:"pdf",title:"Agents",text:'<i class="ri-file-pdf-line me-1"></i>Pdf',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(t,r,n){if(t.length<=0)return t;var s=$.parseHTML(t),e="";return $.each(s,function(o,a){a.classList!==void 0&&a.classList.contains("agent-name")?e=e+a.lastChild.firstChild.textContent:a.innerText===void 0?e=e+a.textContent:e=e+a.innerText}),e}}}},{extend:"copy",title:"Agents",text:'<i class="ri-file-copy-line me-1"></i>Copy',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(t,r,n){if(t.length<=0)return t;var s=$.parseHTML(t),e="";return $.each(s,function(o,a){a.classList!==void 0&&a.classList.contains("agent-name")?e=e+a.lastChild.firstChild.textContent:a.innerText===void 0?e=e+a.textContent:e=e+a.innerText}),e}}}}]},{text:'<i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Add New Agent</span>',className:"add-new btn btn-primary waves-effect waves-light",attr:{"data-bs-toggle":"offcanvas","data-bs-target":"#offcanvasAddAgent"}}],responsive:{details:{display:$.fn.dataTable.Responsive.display.modal({header:function(t){var r=t.data();return"Details of "+r.name}}),type:"column",renderer:function(t,r,n){var s=$.map(n,function(e,o){return e.title!==""?'<tr data-dt-row="'+e.rowIndex+'" data-dt-column="'+e.columnIndex+'"><td>'+e.title+":</td> <td>"+e.data+"</td></tr>":""}).join("");return s?$('<table class="table"/><tbody />').append(s):!1}}}});$(document).on("click",".delete-record",function(){var t=$(this).data("id"),r=$(".dtr-bs-modal.show");r.length&&r.modal("hide"),Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete it!",customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(n){n.value?($.ajax({type:"DELETE",url:`${baseUrl}agents-list/${t}`,success:function(){c.draw()},error:function(s){console.log(s)}}),Swal.fire({icon:"success",title:"Deleted!",text:"The agent has been deleted!",customClass:{confirmButton:"btn btn-success"}})):n.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The Agent is not deleted!",icon:"error",customClass:{confirmButton:"btn btn-success"}})})}),$(document).on("click",".edit-record",function(){var t=$(this).data("id"),r=$(".dtr-bs-modal.show");r.length&&r.modal("hide"),$("#offcanvasAddRegionLabel").html("Edit Region"),$.get(`${baseUrl}agents-list/${t}/edit`,function(n){$("#agent_id").val(n.id),$("#add-agent-first-name").val(n.name[0]),$("#add-agent-middle-name").val(n.name[1]),$("#add-agent-last-name").val(n.name[2]),$("#add-agent-email").val(n.email),$("#add-agent-phone-number").val(n.phone_number),$("#add-agent-id-number").val(n.id_number),$("#agent-role").val(n.role),$("#agent-country").val(n.country)})}),$(".add-new").on("click",function(){$("#agent_id").val(""),$("#offcanvasAddAgentLabel").html("Add Agent")});const v=document.getElementById("addNewAgentForm"),x=FormValidation.formValidation(v,{fields:{first_name:{validators:{notEmpty:{message:"Please enter agent first name"}}},middle_name:{validators:{notEmpty:{message:"Please enter agent middle name"}}},last_name:{validators:{notEmpty:{message:"Please enter agent last name"}}},email:{validators:{notEmpty:{message:"Please enter your email"},emailAddress:{message:"The value is not a valid email address"}}},phone_number:{validators:{notEmpty:{message:"Please enter agent phone number"}}},id_number:{validators:{notEmpty:{message:"Please enter agent ID number"}}},role:{validators:{notEmpty:{message:"Select agent role"}}},country:{validators:{notEmpty:{message:"Select agent country"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap5:new FormValidation.plugins.Bootstrap5({eleValidClass:"",rowSelector:function(t,r){return".mb-5"}}),submitButton:new FormValidation.plugins.SubmitButton,autoFocus:new FormValidation.plugins.AutoFocus}}).on("core.form.valid",function(){$.ajax({data:$("#addNewAgentForm").serialize(),url:`${baseUrl}agents-list`,type:"POST",success:function(t){c.draw(),l.offcanvas("hide"),Swal.fire({icon:"success",title:`Successfully ${t}!`,text:`Agent ${t} successfully.`,customClass:{confirmButton:"btn btn-success"}})},error:function(t){console.error("Error submitting form:",t),l.offcanvas("hide"),Swal.fire({title:"Error!",text:t.responseJSON?t.responseJSON.message:"An error occurred.",icon:"error",customClass:{confirmButton:"btn btn-success"}})}})});l.on("hidden.bs.offcanvas",function(){x.resetForm(!0)});const p=document.querySelectorAll(".phone-mask");p&&p.forEach(function(t){new Cleave(t,{phone:!0,phoneRegionCode:"US"})}),$(document).on("click",".activate-record",function(t){t.preventDefault();var r=$(this).data("id"),n=$(this).data("active"),s=n==2?1:2,e=s==2?"Activate":"Disable";Swal.fire({title:"Are you sure?",text:`You are about to ${e.toLowerCase()} this agent!`,icon:"warning",showCancelButton:!0,confirmButtonText:`Yes, ${e.toLowerCase()} it!`,customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(o){o.value?($.ajax({type:"PATCH",url:`${baseUrl}agents-list/${r}/activation`,data:{status:s},success:function(){c.draw()},error:function(a){console.log(a)}}),Swal.fire({icon:"success",title:"Updated!",text:`The agent has been ${e.toLowerCase()}d.`,customClass:{confirmButton:"btn btn-success"}})):o.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The agent's status remains unchanged.",icon:"error",customClass:{confirmButton:"btn btn-success"}})})})});
