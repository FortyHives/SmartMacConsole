(function(){let n;isDarkStyle?n=config.colors_dark.textMuted:n=config.colors.textMuted;const b=document.querySelector("#swiper-weekly-sales-with-bg");b&&new Swiper(b,{loop:!0,autoplay:{delay:2500,disableOnInteraction:!1},pagination:{clickable:!0,el:".swiper-pagination"}});const l=document.querySelector("#saleThisMonth"),g={chart:{height:97,type:"line",parentHeightOffset:0,toolbar:{show:!1},dropShadow:{top:14,blur:4,left:0,enabled:!0,opacity:.12,color:config.colors.primary}},tooltip:{enabled:!1},grid:{xaxis:{lines:{show:!1}},yaxis:{lines:{show:!1}},padding:{top:-12,left:-2,right:8,bottom:-10}},colors:[config.colors.primary],stroke:{width:5,lineCap:"round"},series:[{data:[200,200,500,500,300,300,100,100,450,450,650,650]}],xaxis:{labels:{show:!1},axisTicks:{show:!1},axisBorder:{show:!1}},yaxis:{min:0,labels:{show:!1}}};typeof l!==void 0&&l!==null&&new ApexCharts(l,g).render();function m(e,o,t){return{chart:{height:90,width:90,type:"radialBar",sparkline:{enabled:!0}},plotOptions:{radialBar:{hollow:{size:"52%",image:t,imageWidth:24,imageHeight:24,imageClipped:!1},dataLabels:{name:{show:!1},value:{show:!1}},track:{background:config.colors_label.secondary}}},states:{hover:{filter:{type:"none"}},active:{filter:{type:"none"}}},stroke:{lineCap:"round"},colors:[e],grid:{padding:{bottom:0}},series:[o],labels:["Progress"],responsive:[{breakpoint:1400,options:{chart:{height:100}}},{breakpoint:1380,options:{chart:{height:96}}},{breakpoint:1354,options:{chart:{height:93}}},{breakpoint:1336,options:{chart:{height:88}}},{breakpoint:1286,options:{chart:{height:84}}},{breakpoint:1258,options:{chart:{height:80}}},{breakpoint:1200,options:{chart:{height:98}}}]}}const u=document.querySelectorAll(".chart-progress");u&&u.forEach(function(e){const o=config.colors[e.dataset.color],t=e.dataset.series,i=e.dataset.icon,a=m(o,t,i);new ApexCharts(e,a).render()});const f=document.querySelector("#swiper-marketing-sales");f&&new Swiper(f,{loop:!0,autoplay:{delay:2500,disableOnInteraction:!1},pagination:{clickable:!0,el:".swiper-pagination"}});const p=document.querySelector("#liveVisitors"),y={chart:{height:153,parentHeightOffset:0,type:"bar",toolbar:{show:!1}},plotOptions:{bar:{borderRadius:8,columnWidth:"43%",endingShape:"rounded",startingShape:"rounded"}},colors:[config.colors.success],grid:{padding:{top:-4,left:-20,right:-2,bottom:-7},yaxis:{lines:{show:!1}}},dataLabels:{enabled:!1},series:[{data:[70,118,92,49,19,49,23,82,65,23,49,65,65]}],legend:{show:!1},xaxis:{labels:{show:!1},axisTicks:{show:!1},axisBorder:{show:!1}},yaxis:{labels:{show:!1}},responsive:[{breakpoint:1443,options:{plotOptions:{bar:{borderRadius:7}}}},{breakpoint:1372,options:{plotOptions:{bar:{borderRadius:6}}}},{breakpoint:1248,options:{plotOptions:{bar:{borderRadius:5}}}},{breakpoint:1200,options:{plotOptions:{bar:{borderRadius:6}}}},{breakpoint:992,options:{plotOptions:{bar:{borderRadius:8}},chart:{height:156}}},{breakpoint:838,options:{plotOptions:{bar:{borderRadius:6}}}},{breakpoint:644,options:{plotOptions:{bar:{borderRadius:4}}}},{breakpoint:474,options:{plotOptions:{bar:{borderRadius:7}}}},{breakpoint:383,options:{plotOptions:{bar:{borderRadius:6}}}}]};typeof p!==void 0&&p!==null&&new ApexCharts(p,y).render();var c=$(".datatables-ecommerce");c.length&&(c=c.DataTable({ajax:assetsPath+"json/table-dashboards.json",dom:"t",columns:[{data:"id"},{data:"name"},{data:"email"},{data:"role"},{data:"status"}],columnDefs:[{targets:0,searchable:!1,visible:!1},{targets:1,render:function(e,o,t,i){var a=t.image,s=t.name,v=t.username,h;if(a)var h='<img src="'+assetsPath+"img/avatars/"+a+'" alt="Avatar" class="rounded-circle">';else{var k=Math.floor(Math.random()*6),x=["success","danger","warning","info","dark","primary","secondary"],O=x[k],s=t.name,r=s.match(/\b\w/g)||[];r=((r.shift()||"")+(r.pop()||"")).toUpperCase(),h='<span class="avatar-initial rounded-circle bg-label-'+O+'">'+r+"</span>"}var C='<div class="d-flex justify-content-start align-items-center user-name"><div class="avatar-wrapper"><div class="avatar avatar-sm me-3">'+h+'</div></div><div class="d-flex flex-column"><span class="name text-truncate h6 mb-0">'+s+'</span><small class="user_name text-truncate">@'+v+"</small></div></div>";return C}},{targets:-2,render:function(e,o,t,i){var a=t.role,s={Admin:{icon:"ri-vip-crown-line",class:"primary"},Editor:{icon:"ri-edit-box-line",class:"warning"},Author:{icon:"ri-computer-line",class:"danger"},Maintainer:{icon:"ri-pie-chart-2-line",class:"info"},Subscriber:{icon:"ri-user-line",class:"success"}};return typeof s[a]>"u"?e:'<span class="d-flex align-items-center gap-2 text-heading"><i class="'+s[a].icon+" ri-22px text-"+s[a].class+'"></i>'+a+"</span>"}},{targets:-1,render:function(e,o,t,i){var a=t.status,s={1:{title:"Pending",class:"bg-label-warning"},2:{title:"Active",class:" bg-label-success"},3:{title:"Inactive",class:" bg-label-secondary"}};return typeof s[a]>"u"?e:'<span class="badge rounded-pill '+s[a].class+'">'+s[a].title+"</span>"}}],order:[[0,"asc"]]}));const d=document.querySelector("#visitsByDayChart"),w={chart:{height:314,type:"bar",parentHeightOffset:0,toolbar:{show:!1}},plotOptions:{bar:{borderRadius:8,distributed:!0,columnWidth:"55%",endingShape:"rounded",startingShape:"rounded"}},series:[{data:[38,55,48,65,80,38,48]}],tooltip:{enabled:!1},legend:{show:!1},dataLabels:{enabled:!1},colors:[config.colors_label.primary,config.colors.primary,config.colors_label.primary,config.colors.primary,config.colors.primary,config.colors_label.primary,config.colors_label.primary],grid:{show:!1,padding:{top:-15,left:-7,right:-4}},states:{hover:{filter:{type:"none"}},active:{filter:{type:"none"}}},xaxis:{axisTicks:{show:!1},axisBorder:{show:!1},categories:["S","M","T","W","T","F","S"],labels:{style:{colors:n,fontSize:"13px",fontFamily:"Inter"}}},yaxis:{show:!1},responsive:[{breakpoint:1240,options:{chart:{height:307}}},{breakpoint:1200,options:{chart:{height:327}}},{breakpoint:992,options:{chart:{height:250},plotOptions:{bar:{columnWidth:"35%"}}}},{breakpoint:577,options:{plotOptions:{bar:{columnWidth:"45%"}}}},{breakpoint:477,options:{plotOptions:{bar:{columnWidth:"50%"}}}}]};typeof d!==void 0&&d!==null&&new ApexCharts(d,w).render()})();
