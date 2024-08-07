$(function(){var m=$(".datatables-planograms-disabled"),p=$(".select2"),f=baseUrl+"apps/planograms/view/planogram",d=$("#offcanvasAddPlanogram");if(p.length){var c=p;select2Focus(c),c.wrap('<div class="position-relative"></div>').select2({placeholder:"Select Country",dropdownParent:c.parent()})}if($.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),m.length)var i=m.DataTable({processing:!0,serverSide:!0,ajax:{url:baseUrl+"disabled-planograms-list"},columns:[{data:""},{data:"id"},{data:"name"},{data:"primary_product_name"},{data:"category_title"},{data:"description"},{data:"comparison_products_id"},{data:"suspended"},{data:"action"}],columnDefs:[{className:"control",searchable:!1,orderable:!1,responsivePriority:2,targets:0,render:function(a,s,t,o){return""}},{searchable:!1,orderable:!1,targets:1,render:function(a,s,t,o){return`<span>${t.fake_id}</span>`}},{targets:2,responsivePriority:4,render:function(a,s,t,o){var u=t.name,e=Math.floor(Math.random()*6),r=["success","danger","warning","info","dark","primary","secondary"],n=r[e],u=t.name,l=u.match(/\b\w/g)||[],v;l=((l.shift()||"")+(l.pop()||"")).toUpperCase(),v='<span class="avatar-initial rounded-circle bg-label-'+n+'">'+l+"</span>";var h='<div class="d-flex justify-content-start align-items-center planogram-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">'+v+'</div></div><div class="d-flex flex-column"><a href="'+f+"/"+t.id+'" class="text-truncate text-heading"><span class="fw-medium">'+u+"</span></a></div></div>";return h}},{targets:3,render:function(a,s,t,o){var e=t.category_title;return'<span class="planogram-category">'+e+"</span>"}},{targets:4,render:function(a,s,t,o){var e=t.primary_product_name;return'<span class="planogram-primary-product">'+e+"</span>"}},{targets:4,render:function(a,s,t,o){var e=t.description;return'<span class="planogram-description">'+e+"</span>"}},{targets:5,render:function(a,s,t,o){var e=t.comparison_products_id;return'<span class="planogram-comparison-products-id">'+e.length+"</span>"}},{targets:6,render:function(a,s,t,o){var e=t.suspended;return`${e===2?'<i class="ri-shield-check-line ri-24px text-success"></i>':'<i class="ri-shield-line ri-24px text-danger" ></i>'}`}},{targets:-1,title:"Actions",searchable:!1,orderable:!1,render:function(a,s,t,o){var e=t.active===2?"Disable":"Activate",r=t.suspended===2?"Un-suspend":"Suspend";return`<div class="d-flex align-items-center gap-50"><button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${t.id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddPlanogram"><i class="ri-edit-box-line ri-20px"></i></button><button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${t.id}"><i class="ri-delete-bin-7-line ri-20px"></i></button><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button><div class="dropdown-menu dropdown-menu-end m-0"><a href="`+f+"/"+t.id+`" class="dropdown-item">View</a><a href="#" class="dropdown-item activate-record" data-id="${t.id}" data-active="${t.active}">${e}</a><a href="#" class="dropdown-item suspend-record" data-id="${t.id}" data-suspended="${t.suspended}">${r}</a></div></div>`}}],order:[[2,"desc"]],dom:'<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"<"me-5 ms-n2"f><"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>>t<"row mx-1"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',lengthMenu:[10,20,50,100,250],language:{sLengthMenu:"_MENU_",search:"",searchPlaceholder:"Search",info:"Displaying _START_ to _END_ of _TOTAL_ entries"},buttons:[{extend:"collection",className:"btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light",text:'<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export </span>',buttons:[{extend:"print",title:"Planograms",text:'<i class="ri-printer-line me-1" ></i>Print',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,t){if(a.length<=0)return a;var o=$.parseHTML(a),e="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("planogram-name")?e=e+n.lastChild.firstChild.textContent:n.innerText===void 0?e=e+n.textContent:e=e+n.innerText}),e}}},customize:function(a){$(a.document.body).css("color",config.colors.headingColor).css("border-color",config.colors.borderColor).css("background-color",config.colors.body),$(a.document.body).find("table").addClass("compact").css("color","inherit").css("border-color","inherit").css("background-color","inherit")}},{extend:"csv",title:"Planograms",text:'<i class="ri-file-text-line me-1" ></i>Csv',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,t){if(a.length<=0)return a;var o=$.parseHTML(a),e="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("planogram-name")?e=e+n.lastChild.firstChild.textContent:n.innerText===void 0?e=e+n.textContent:e=e+n.innerText}),e}}}},{extend:"excel",title:"Planograms",text:'<i class="ri-file-excel-line me-1"></i>Excel',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,t){if(a.length<=0)return a;var o=$.parseHTML(a),e="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("planogram-name")?e=e+n.lastChild.firstChild.textContent:n.innerText===void 0?e=e+n.textContent:e=e+n.innerText}),e}}}},{extend:"pdf",title:"Planograms",text:'<i class="ri-file-pdf-line me-1"></i>Pdf',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,t){if(a.length<=0)return a;var o=$.parseHTML(a),e="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("planogram-name")?e=e+n.lastChild.firstChild.textContent:n.innerText===void 0?e=e+n.textContent:e=e+n.innerText}),e}}}},{extend:"copy",title:"Planograms",text:'<i class="ri-file-copy-line me-1"></i>Copy',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,t){if(a.length<=0)return a;var o=$.parseHTML(a),e="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("planogram-name")?e=e+n.lastChild.firstChild.textContent:n.innerText===void 0?e=e+n.textContent:e=e+n.innerText}),e}}}}]},{text:'<i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Add New Planogram</span>',className:"add-new btn btn-primary waves-effect waves-light",attr:{"data-bs-toggle":"offcanvas","data-bs-target":"#offcanvasAddPlanogram"}}],responsive:{details:{display:$.fn.dataTable.Responsive.display.modal({header:function(a){var s=a.data();return"Details of "+s.name}}),type:"column",renderer:function(a,s,t){var o=$.map(t,function(e,r){return e.title!==""?'<tr data-dt-row="'+e.rowIndex+'" data-dt-column="'+e.columnIndex+'"><td>'+e.title+":</td> <td>"+e.data+"</td></tr>":""}).join("");return o?$('<table class="table"/><tbody />').append(o):!1}}}});$(document).on("click",".delete-record",function(){var a=$(this).data("id"),s=$(".dtr-bs-modal.show");s.length&&s.modal("hide"),Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete it!",customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(t){t.value?($.ajax({type:"DELETE",url:`${baseUrl}disabled-planograms-list/${a}`,success:function(){i.draw()},error:function(o){console.log(o)}}),Swal.fire({icon:"success",title:"Deleted!",text:"The planogram has been deleted!",customClass:{confirmButton:"btn btn-success"}})):t.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The Planogram is not deleted!",icon:"error",customClass:{confirmButton:"btn btn-success"}})})}),$(document).on("click",".edit-record",function(){var a=$(this).data("id"),s=$(".dtr-bs-modal.show");s.length&&s.modal("hide"),$("#offcanvasAddPlanogramLabel").html("Edit Planogram"),$.get(`${baseUrl}disabled-planograms-list/${a}/edit`,function(t){$("#planogram_id").val(t.id),$("#add-planogram-name").val(t.name),$("#add-planogram-description").val(t.description),$("#add-planogram-category-id").val(t.category_id),$("#add-planogram-primary-product-id").val(t.primary_product_id),$("#add-planogram-comparison-products-id").val(t.comparison_products_id)}).fail(function(t,o,e){console.error("Error fetching planogram data:",o,e)})}),$(".add-new").on("click",function(){$("#planogram_id").val(""),$("#offcanvasAddPlanogramLabel").html("Add Planogram")});const g=document.getElementById("addNewPlanogramForm"),x=FormValidation.formValidation(g,{fields:{name:{validators:{notEmpty:{message:"Please enter planogram name"}}},description:{validators:{notEmpty:{message:"Please enter planogram description"}}},category_id:{validators:{notEmpty:{message:"Please select planogram outlet category"}}},primary_product_id:{validators:{notEmpty:{message:"Please select planogram primary product"}}},products_id:{validators:{notEmpty:{message:"Please select planogram comparison products"}}},photo:{validators:{file:{extension:"jpeg,jpg,png,gif",type:"image/jpeg,image/png,image/gif",maxSize:2048*1024,message:"Please choose a valid image file (jpeg, jpg, png, gif) with size less than 2 MB."}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap5:new FormValidation.plugins.Bootstrap5({eleValidClass:"",rowSelector:function(a,s){return".mb-5"}}),submitButton:new FormValidation.plugins.SubmitButton,autoFocus:new FormValidation.plugins.AutoFocus}}).on("core.form.valid",function(){var a=new FormData(g);$.ajax({data:a,url:`${baseUrl}disabled-planograms-list`,type:"POST",contentType:!1,processData:!1,success:function(s){i.draw(),d.offcanvas("hide"),Swal.fire({icon:"success",title:`Successfully ${s}!`,text:`Planogram ${s} successfully.`,customClass:{confirmButton:"btn btn-success"}})},error:function(s){console.error("Error submitting form:",s),d.offcanvas("hide"),Swal.fire({title:"Error!",text:s.responseJSON?s.responseJSON.message:"An error occurred.",icon:"error",customClass:{confirmButton:"btn btn-success"}})}})});d.on("hidden.bs.offcanvas",function(){x.resetForm(!0)});const b=document.querySelectorAll(".phone-mask");b&&b.forEach(function(a){new Cleave(a,{phone:!0,phoneRegionCode:"US"})}),$(document).on("click",".activate-record",function(a){a.preventDefault();var s=$(this).data("id"),t=$(this).data("active"),o=t==2?1:2,e=o==2?"Activate":"Disable";Swal.fire({title:"Are you sure?",text:`You are about to ${e.toLowerCase()} this planogram!`,icon:"warning",showCancelButton:!0,confirmButtonText:`Yes, ${e.toLowerCase()} it!`,customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(r){r.value?($.ajax({type:"PATCH",url:`${baseUrl}disabled-planograms-list/${s}/activation`,data:{status:o},success:function(){i().draw()},error:function(n){console.log(n)}}),Swal.fire({icon:"success",title:"Updated!",text:`The planogram has been ${e.toLowerCase()}d.`,customClass:{confirmButton:"btn btn-success"}})):r.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The planogram's status remains unchanged.",icon:"error",customClass:{confirmButton:"btn btn-success"}})})}),$(document).on("click",".suspend-record",function(a){a.preventDefault();var s=$(this).data("id"),t=$(this).data("suspended"),o=t==2?1:2,e=o==2?"Un-suspend":"Suspend";Swal.fire({title:"Are you sure?",text:`You are about to ${e.toLowerCase()} this planogram!`,icon:"warning",showCancelButton:!0,confirmButtonText:`Yes, ${e.toLowerCase()} it!`,customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(r){r.value?($.ajax({type:"PATCH",url:`${baseUrl}disabled-planograms-list/${s}/suspension`,data:{status:o},success:function(){i().draw()},error:function(n){console.log(n)}}),Swal.fire({icon:"success",title:"Updated!",text:`The planogram has been ${e.toLowerCase()}ed.`,customClass:{confirmButton:"btn btn-success"}})):r.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The planogram's status remains unchanged.",icon:"error",customClass:{confirmButton:"btn btn-success"}})})})});
