$(function(){var f=$(".datatables-outlets-active"),m=$(".select2"),p=baseUrl+"apps/outlets/view/outlet",c=$("#offcanvasAddOutlet");if(m.length){var d=m;select2Focus(d),d.wrap('<div class="position-relative"></div>').select2({placeholder:"Select Country",dropdownParent:d.parent()})}if($.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),f.length)var i=f.DataTable({processing:!0,serverSide:!0,ajax:{url:baseUrl+"active-outlets-list"},columns:[{data:""},{data:"id"},{data:"name"},{data:"contact_name"},{data:"contact_phone_number"},{data:"category_title"},{data:"region_name"},{data:"locality_name"},{data:"country"},{data:"verified"},{data:"action"}],columnDefs:[{className:"control",searchable:!1,orderable:!1,responsivePriority:2,targets:0,render:function(a,s,e,o){return""}},{searchable:!1,orderable:!1,targets:1,render:function(a,s,e,o){return`<span>${e.fake_id}</span>`}},{targets:2,responsivePriority:4,render:function(a,s,e,o){var u=e.name,t=Math.floor(Math.random()*6),r=["success","danger","warning","info","dark","primary","secondary"],n=r[t],u=e.name,l=u.match(/\b\w/g)||[],g;l=((l.shift()||"")+(l.pop()||"")).toUpperCase(),g='<span class="avatar-initial rounded-circle bg-label-'+n+'">'+l+"</span>";var h='<div class="d-flex justify-content-start align-items-center outlet-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">'+g+'</div></div><div class="d-flex flex-column"><a href="'+p+"/"+e.id+'" class="text-truncate text-heading"><span class="fw-medium">'+u+"</span></a></div></div>";return h}},{targets:3,render:function(a,s,e,o){var t=e.contact_name;return'<span class="outlet-contact-name">'+t+"</span>"}},{targets:4,render:function(a,s,e,o){var t=e.contact_phone_number;return'<span class="outlet-contact-phone-number">'+t+"</span>"}},{targets:5,render:function(a,s,e,o){var t=e.category_title;return'<span class="outlet-category-title">'+t+"</span>"}},{targets:6,render:function(a,s,e,o){var t=e.region_name;return'<span class="outlet-region-name">'+t+"</span>"}},{targets:7,render:function(a,s,e,o){var t=e.locality_name;return'<span class="outlet-locality-name">'+t+"</span>"}},{targets:8,render:function(a,s,e,o){var t=e.country;return'<span class="outlet-country">'+t+"</span>"}},{targets:9,render:function(a,s,e,o){var t=e.verified;return`${t===2?'<i class="ri-shield-check-line ri-24px text-success"></i>':'<i class="ri-shield-line ri-24px text-danger" ></i>'}`}},{targets:-1,title:"Actions",searchable:!1,orderable:!1,render:function(a,s,e,o){var t=e.active===2?"Disable":"Activate",r=e.verified===2?"Un-verify":"Verify";return`<div class="d-flex align-items-center gap-50"><button class="btn btn-sm btn-icon edit-record btn-text-secondary rounded-pill waves-effect" data-id="${e.id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddOutlet"><i class="ri-edit-box-line ri-20px"></i></button><button class="btn btn-sm btn-icon delete-record btn-text-secondary rounded-pill waves-effect" data-id="${e.id}"><i class="ri-delete-bin-7-line ri-20px"></i></button><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ri-more-2-line ri-20px"></i></button><div class="dropdown-menu dropdown-menu-end m-0"><a href="`+p+"/"+e.id+`" class="dropdown-item">View</a><a href="#" class="dropdown-item activate-record" data-id="${e.id}" data-active="${e.active}">${t}</a><a href="#" class="dropdown-item verify-record" data-id="${e.id}" data-verified="${e.verified}">${r}</a></div></div>`}}],order:[[2,"desc"]],dom:'<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"<"me-5 ms-n2"f><"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>>t<"row mx-1"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',lengthMenu:[10,20,50,100,250],language:{sLengthMenu:"_MENU_",search:"",searchPlaceholder:"Search",info:"Displaying _START_ to _END_ of _TOTAL_ entries"},buttons:[{extend:"collection",className:"btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light",text:'<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export </span>',buttons:[{extend:"print",title:"Outlets",text:'<i class="ri-printer-line me-1" ></i>Print',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,e){if(a.length<=0)return a;var o=$.parseHTML(a),t="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("outlet-name")?t=t+n.lastChild.firstChild.textContent:n.innerText===void 0?t=t+n.textContent:t=t+n.innerText}),t}}},customize:function(a){$(a.document.body).css("color",config.colors.headingColor).css("border-color",config.colors.borderColor).css("background-color",config.colors.body),$(a.document.body).find("table").addClass("compact").css("color","inherit").css("border-color","inherit").css("background-color","inherit")}},{extend:"csv",title:"Outlets",text:'<i class="ri-file-text-line me-1" ></i>Csv',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,e){if(a.length<=0)return a;var o=$.parseHTML(a),t="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("outlet-name")?t=t+n.lastChild.firstChild.textContent:n.innerText===void 0?t=t+n.textContent:t=t+n.innerText}),t}}}},{extend:"excel",title:"Outlets",text:'<i class="ri-file-excel-line me-1"></i>Excel',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,e){if(a.length<=0)return a;var o=$.parseHTML(a),t="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("outlet-name")?t=t+n.lastChild.firstChild.textContent:n.innerText===void 0?t=t+n.textContent:t=t+n.innerText}),t}}}},{extend:"pdf",title:"Outlets",text:'<i class="ri-file-pdf-line me-1"></i>Pdf',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,e){if(a.length<=0)return a;var o=$.parseHTML(a),t="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("outlet-name")?t=t+n.lastChild.firstChild.textContent:n.innerText===void 0?t=t+n.textContent:t=t+n.innerText}),t}}}},{extend:"copy",title:"Outlets",text:'<i class="ri-file-copy-line me-1"></i>Copy',className:"dropdown-item",exportOptions:{columns:[1,2,3,4,5],format:{body:function(a,s,e){if(a.length<=0)return a;var o=$.parseHTML(a),t="";return $.each(o,function(r,n){n.classList!==void 0&&n.classList.contains("outlet-name")?t=t+n.lastChild.firstChild.textContent:n.innerText===void 0?t=t+n.textContent:t=t+n.innerText}),t}}}}]}],responsive:{details:{display:$.fn.dataTable.Responsive.display.modal({header:function(a){var s=a.data();return"Details of "+s.name}}),type:"column",renderer:function(a,s,e){var o=$.map(e,function(t,r){return t.title!==""?'<tr data-dt-row="'+t.rowIndex+'" data-dt-column="'+t.columnIndex+'"><td>'+t.title+":</td> <td>"+t.data+"</td></tr>":""}).join("");return o?$('<table class="table"/><tbody />').append(o):!1}}}});$(document).on("click",".delete-record",function(){var a=$(this).data("id"),s=$(".dtr-bs-modal.show");s.length&&s.modal("hide"),Swal.fire({title:"Are you sure?",text:"You won't be able to revert this!",icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, delete it!",customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(e){e.value?($.ajax({type:"DELETE",url:`${baseUrl}active-outlets-list/${a}`,success:function(){i.draw()},error:function(o){console.log(o)}}),Swal.fire({icon:"success",title:"Deleted!",text:"The outlet has been deleted!",customClass:{confirmButton:"btn btn-success"}})):e.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The Outlet is not deleted!",icon:"error",customClass:{confirmButton:"btn btn-success"}})})}),$(document).on("click",".edit-record",function(){var a=$(this).data("id"),s=$(".dtr-bs-modal.show");s.length&&s.modal("hide"),$("#offcanvasAddOutletLabel").html("Edit Outlet"),$.get(`${baseUrl}active-outlets-list/${a}/edit`,function(e){$("#outlet_id").val(e.id),$("#add-outlet-name").val(e.name),$("#add-outlet-contact-name").val(e.contact_name),$("#add-outlet-contact-phone-number").val(e.contact_phone_number),$("#add-outlet-remarks").val(e.remarks),$("#add-outlet-category-id").val(e.category_id)}).fail(function(e,o,t){console.error("Error fetching outlet data:",o,t)})}),$(".add-new").on("click",function(){$("#outlet_id").val(""),$("#offcanvasAddOutletLabel").html("Add Outlet")});const v=document.getElementById("addNewOutletForm"),x=FormValidation.formValidation(v,{fields:{name:{validators:{notEmpty:{message:"Please enter outlet name"}}},contact_name:{validators:{notEmpty:{message:"Please enter outlet contact name"}}},contact_phone_number:{validators:{notEmpty:{message:"Please enter outlet contact phone number"}}},photo:{validators:{file:{extension:"jpeg,jpg,png,gif",type:"image/jpeg,image/png,image/gif",maxSize:2048*1024,message:"Please choose a valid image file (jpeg, jpg, png, gif) with size less than 2 MB."}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap5:new FormValidation.plugins.Bootstrap5({eleValidClass:"",rowSelector:function(a,s){return".mb-5"}}),submitButton:new FormValidation.plugins.SubmitButton,autoFocus:new FormValidation.plugins.AutoFocus}}).on("core.form.valid",function(){var a=new FormData(v);$.ajax({data:a,url:`${baseUrl}active-outlets-list`,type:"POST",success:function(s){i.draw(),c.offcanvas("hide"),Swal.fire({icon:"success",title:`Successfully ${s}!`,text:`Outlet ${s} successfully.`,customClass:{confirmButton:"btn btn-success"}})},error:function(s){console.error("Error submitting form:",s),c.offcanvas("hide"),Swal.fire({title:"Error!",text:s.responseJSON?s.responseJSON.message:"An error occurred.",icon:"error",customClass:{confirmButton:"btn btn-success"}})}})});c.on("hidden.bs.offcanvas",function(){x.resetForm(!0)});const b=document.querySelectorAll(".phone-mask");b&&b.forEach(function(a){new Cleave(a,{phone:!0,phoneRegionCode:"US"})}),$(document).on("click",".activate-record",function(a){a.preventDefault();var s=$(this).data("id"),e=$(this).data("active"),o=e==2?1:2,t=o==2?"Activate":"Disable";Swal.fire({title:"Are you sure?",text:`You are about to ${t.toLowerCase()} this outlet!`,icon:"warning",showCancelButton:!0,confirmButtonText:`Yes, ${t.toLowerCase()} it!`,customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(r){r.value?($.ajax({type:"PATCH",url:`${baseUrl}active-outlets-list/${s}/activation`,data:{status:o},success:function(){i().draw()},error:function(n){console.log(n)}}),Swal.fire({icon:"success",title:"Updated!",text:`The outlet has been ${t.toLowerCase()}d.`,customClass:{confirmButton:"btn btn-success"}})):r.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The outlet's status remains unchanged.",icon:"error",customClass:{confirmButton:"btn btn-success"}})})}),$(document).on("click",".verify-record",function(a){a.preventDefault();var s=$(this).data("id"),e=$(this).data("verified"),o=e==2?1:2,t=o==2?"Verify":"Un-verify";Swal.fire({title:"Are you sure?",text:`You are about to ${t.toLowerCase()} this outlet!`,icon:"warning",showCancelButton:!0,confirmButtonText:`Yes, ${t.toLowerCase()} it!`,customClass:{confirmButton:"btn btn-primary me-3",cancelButton:"btn btn-label-secondary"},buttonsStyling:!1}).then(function(r){r.value?($.ajax({type:"PATCH",url:`${baseUrl}active-outlets-list/${s}/verification`,data:{status:o},success:function(){i().draw()},error:function(n){console.log(n)}}),Swal.fire({icon:"success",title:"Updated!",text:`The outlet has been ${t.toLowerCase()}ed.`,customClass:{confirmButton:"btn btn-success"}})):r.dismiss===Swal.DismissReason.cancel&&Swal.fire({title:"Cancelled",text:"The outlet's status remains unchanged.",icon:"error",customClass:{confirmButton:"btn btn-success"}})})})});
