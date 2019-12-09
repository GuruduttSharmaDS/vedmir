var DASHURL =  BASEURL+'/dashboard';
var DASHSTATIC =  BASEURL+'/system/static/dashboard';
var COMMONAJAX =DASHURL+'/admin/commonajax';
var currentAjax = 0 ;
var audioElement;
var btnText = 'Submit';

$(document).ready(function(){
  if(!audioElement) {
    audioElement = document.createElement('audio');
    audioElement.innerHTML = '<source src="' + DASHSTATIC+'/admin/media/notification.mp3'+ '" type="audio/mpeg" />'
  }
  GetDataOfThisPage();

  // setInterval(function () { if(currentAjax == 0) GetAdminNotification() } , 3000);
    $('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').focus(function() {
      $(this).removeClass("error border border-dange");
      $(this).closest("div").find('label.error').remove();
    });
    //validate Form with including inpute type email
    $('.validate-form').unbind("click").click(function(e){
      e.stopPropagation();
      btnText = $(this).text();
      if($(this).html()=='<i class="fa fa-spinner fa-spin"></i> Processing')
        return false;
      $(this).html('<i class="fa fa-spinner fa-spin"></i> Processing');
      var chk=0;
      var obj=$(this).closest('form');
      if(textBoxValidation(obj)==false)
        chk=1;
    
      if(chk===1){
        $(this).html(btnText);
        return false;
      }
      obj.submit();
    });

  /**************** Product Section *****************/
  $(document).on('click', '.editCategory', function() {
    openpoploader();
    var categoryId = $(this).attr("data-id");
    if( categoryId < 1 ) {
      removepoploader();
      return false;
    }
    currentAjax = 1 ;
    $.ajax({
      url: COMMONAJAX,
      type: "POST",
      data: {"action" : "gettabRecords", "tab" : "category", "key" : "categoryId", "value" : categoryId},
      success:function(response){
        currentAjax = 0 ;
        removepoploader();  
          if( response.valid ) {
            
            $('.formarea').find('#categoryName').val(response.data.categoryName);
            $('.formarea').find('input[name=hiddenval]').val(response.data.categoryId);
            $('.formarea').find('#slugName').val(response.data.slug);
            $('.formarea').find('#metaTitle').val(response.data.metaTitle);
            $('.formarea').find('#metaDescription').val(response.data.metaDescription);
            $('.formarea').find('#description').html(response.data.description);
            $('.formarea').find('#aboutus').html(response.data.extraDescription);
            $('.formarea').find('div.note-editable.card-block').html(response.data.extraDescription);
            $('.formarea').find('#metaKeywords').val(response.data.keywords);
            $('.formarea').find('#categoryName').val(response.data.categoryName);
            $('.formarea').find('#uploadIcons').next('div.previewimg').html((response.data.img)?'<img src="'+response.data.img+'" width="70px" height="50px">':'');

            $('.formarea').find('#bannerDescription').val(response.data.bannerDescription);
            $('.formarea').find('#bannerTitle').val(response.data.bannerTitle);
            $('.formarea').find('#bannerImg').next('div.previewimg').html((response.data.bannerImg)?'<img src="'+UPLOADPATH+'/category_images/'+response.data.bannerImg+'" width="70px" height="50px">':'');

            if( response.data.isNew == 1 )
              $('.formarea').find('#isNew').prop("checked", true);
            $('.formarea').find('.firstinput').focus();

            $('#newFormModal').modal('show');
            $('#newFormModal').find('.newFormModalTitle').text('Update');
          }
      },
      error:function(response){
        currentAjax = 0 ;
          removepoploader();
      }
    });
  });

  $('.validate-change-password').on('click', function(){

    if($(this).find('span').html()=='<i class="fa fa-spinner fa-spin"></i>')
      return false;
    var btnObj = $(this);
    var btn = $(this).find('span.btntext').text();
    $(this).find('span').html('<i class="fa fa-spinner fa-spin"></i>');
    var chk=0;
    var obj=$(this).closest('form');
    if( textBoxValidation(obj) === false){
      obj.find('.error:eq(0)').focus();
      chk=1;      
    }
    obj.find('input[type=password]').not("input[name='form_current_password']").each(function () {

      if (!validatePassword($(this).val())) {     
        $(this).addClass("error");
        ($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error text-danger">Password must contain at least 8 characters including one lowercase letter, one uppercase letter, one numeric digit, and one special character.</label>');
        chk = 1;
      }else{
        if(!$(this).hasClass('more_error')){            
          $(this).removeClass("error");
          $(this).closest("div").find('label.error').remove();
        }
      }
    });

    if(chk == 0){
      if(obj.find('#form_password_1').val() != obj.find('#form_password_2').val()){
        var pasObj = ('#form_password_2');
        $(pasObj).addClass("error");
        ($(pasObj).closest("div").find('label.error').length>0)?"":$(pasObj).closest("div").append('<label class="error text-danger">Password and confirm password does not match.</label>');

        chk = 1;
      }else{
        if(!$(pasObj).hasClass('more_error')){            
          $(pasObj).removeClass("error");
          $(pasObj).closest("div").find('label.error').remove();
        }
      }
    }

    if(chk===1){
      btnObj.find('span.btntext').text(btn);
      return false;
    }else
    obj.submit();

  });

});

function GetDataOfThisPage() {

  var uriArray=returnUriArray();
  
  if($.inArray( "category", uriArray ) > -1 && $.inArray( "product", uriArray ) > -1)
    getCategoryList();
  else if($.inArray("productlist",uriArray) > -1 && $.inArray("product",uriArray) > -1)
    getProductList();
  else if($.inArray( "order", uriArray ) > -1 && $.inArray( "completed", uriArray ) > -1 )
    completedOrderList();
  else if($.inArray( "order", uriArray ) > -1 && $.inArray( 'visitors', uriArray ) > -1)
    visitorHistory();
  else if($.inArray("user-list",uriArray) > -1 && $.inArray("user",uriArray) > -1)
    getUserList();
  else if($.inArray("reviewlist",uriArray) > -1 && $.inArray("review", uriArray ) > -1) 
    getReviewList(); 
  else if($.inArray("newreviewlist",uriArray) > -1 && $.inArray("review", uriArray ) > -1) 
    getNewReviewList(); 
  else if($.inArray("contact_enquiry",uriArray) > -1) 
    getContactEnquiryList();
  else if($.inArray("notification",uriArray) > -1 && $.inArray("notificationlist",uriArray) > -1) 
    getNotificationList();
}

function GetAdminNotification() {
  currentAjax = 1;
  $.ajax({
    type: "POST",
    url: COMMONAJAX,
    data: {action : "getAdminNotification"},    
    success: function (response) {
        if( parseInt(response.totalRows) > 0 ){
            var notificationhtml='<a class="dropdown-item" href="'+DASHURL+'/admin/notification/notificationlist"> <p class="mb-0 font-weight-normal float-left">You have '+response.totalRows+' new notifications </p> <span class="badge badge-pill badge-warning float-right">View all</span> </a><div class="dropdown-divider"></div>';

      $('.notification-bell').find('span.count').text(response.totalRows);
      $('.notification-bell').find('.dropdown-menu-right').html(notificationhtml+response.notificationHtml);
      if(response.new != ''){
        if(response.newOrder != '')
          neworderAudio.play();
        else
          audioElement.play();
      }
        }else
          $('.notification-bell').find('span.count').text('0');

    },complete: function(data){
    currentAjax = 0 ;

    }

  });

}


function GetCategoryList(){

  $('#tableDataList').DataTable({

      "processing": true,

      "serverSide": true,

      "pageLength": 10,
        "preDrawCallback": function (settings) {                
      currentAjax = 1 ;
        },
        "fnDrawCallback": function( oSettings ) {
      currentAjax = 0 ;
    },

      "ajax":{

          "url": DASHURL+'/admin/commonajax',

          "dataType": "json",

          "type": "POST",

          "data":{'action' : 'getCategoryList' }

      },

      "columns": [

            {"data": "icons"},

            {"data": "categoryName"},

            {"data": "isNew"},            

            {"data": "status"},

            {"data": "action"},

         ],

         "order": [[0, 'desc']]



  });

}

function GetProductList(){
    
  $('#tableDataList').DataTable({

      "processing": true,

      "serverSide": true,

      "pageLength": 10,
        "preDrawCallback": function (settings) {                
      currentAjax = 1 ;
        },
        "fnDrawCallback": function( oSettings ) {
      currentAjax = 0 ;
    },

      "ajax":{
             
          "url": DASHURL+'/admin/commonajax',

          "dataType": "json",

          "type": "POST",

          "data":{"action" : "getProductList" }

      },
          
      "columns": [
            
            {"data": "sku"},
            
            {"data": "icons"},
            
            {"data": "productName"},

            {"data": "category", "className": "width200"},

            {"data": "price"},

            {"data": "action"},

         ],

         "order": [[1, 'asc']]



  });

}


function completedOrderList(){
  $('#tableDataList').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
        "preDrawCallback": function (settings) {                
      currentAjax = 1 ;
        },
        "fnDrawCallback": function( oSettings ) {
      currentAjax = 0 ;
    },
    "ajax":{
      "url": DASHURL+'/admin/commonajax',
      "dataType": "json",
      "type": "POST",
      "data":{"action" : "completedOrderList" }
    },
    "columns": [
    {"data": "generatedId"},
    {"data": "user"},
    {"data": "vendor"},
    {"data": "paidTotal"},
    {"data": "itemCount"},
    {"data": "addedOn"},
    {"data": "status"},
    {"data": "action"},
    ],
    "order": [[7, 'desc']]
  });
}

function newOrderList(){
  if($('#tableDataList').length){
    $('#tableDataList').DataTable({
      "processing": true,
      "serverSide": true,
      "pageLength": 10,
        "preDrawCallback": function (settings) {                
      currentAjax = 1 ;
        },
        "fnDrawCallback": function( oSettings ) {
      currentAjax = 0 ;
      $('.template-demo .btn:eq(0)').text('New ('+oSettings.jqXHR.responseJSON.tabStatistics.new+')');
      $('.template-demo .btn:eq(1)').text('Ongoing ('+oSettings.jqXHR.responseJSON.tabStatistics.ongoing+')');
      $('.template-demo .btn:eq(2)').text('Delivered ('+oSettings.jqXHR.responseJSON.tabStatistics.completed+')');
      $('.template-demo .btn:eq(3)').text('Cancelled ('+oSettings.jqXHR.responseJSON.tabStatistics.Cancelled+')');
      $('.template-demo .btn:eq(4)').text('All ('+oSettings.jqXHR.responseJSON.tabStatistics.total+')');
      
    },
          scrollX:        true,
          scrollCollapse: true,
      "ajax":{
        "url": DASHURL+'/admin/commonajax',
        "dataType": "json",
        "type": "POST",
        "data":{"action" : "newOrderList", "filterOrder": $('.template-demo .btn.btn-primary').data('filter'), "filterDateRange": $('#reportrange').val(), "vendorId": $('#vendorId').val()}
      },
      "columns": [
      {"data": "generatedId"},
      {"data": "user"},
      {"data": "city"},
      {"data": "vendor"},
      {"data": "grandTotal"},
      {"data": "status"},
      {"data": "paymentStatus"},
      {"data": "addedOn"},
      {"data": "deliveryDate"},
      {"data": "action"},
      ],
        "scrollX": false,
        "autoWidth": false,
        "fixedHeader": {
            "header": false,
            "footer": false
        },
        "columnDefs": [
          { "width": "70px", "targets": 0 },
          { "width": "50px", "targets": 1 },
          { "width": "50px", "targets": 2 },
          { "width": "50px", "targets": 3 },
          { "width": "20px", "targets": 4 },
          { "width": "70px", "targets": 5 },
          { "width": "80px", "targets": 6 },
          { "width": "40px", "targets": 7 },
          { "width": "40px", "targets": 8 },
          { "width": "10px", "targets": 9 }
        ],
      "order": [[9, 'desc']]
    });
  }
}


/***************** End Product Section ***************************/


/************Calling function according to current page**********/


/************ End Calling function according to current page**********/

/************ active deactive records **********/
function ActivateDeActivateThisRecord(obj, tableName,id) {

    var $tr = $(obj).closest('tr');

    var index = $tr.index();

    $newindex=$('.table').find('tbody tr:eq('+parseInt(index)+')').find('td:last').index();

    $status=$(obj).attr('data-status');

    $status=($status.trim()=='Active')?1:0;

    $msg=($status==1) ? "Do You Want To Make This As DeActive" : "Do You Want To Make This As Active";

    var action = "CallHandlerForActivatedRecord(" + id + "," + index + ",'" + tableName + "',"+$status+");";

    removePopover();
    $(obj).popover({
        placement : 'top',
        html : true,
        title : 'Active/DeActive <a href="javascript:" onclick="removePopover();" class="close" data-dismiss="alert">&times;</a>',
        content : '<div class="row" id="popover-section"><div class="col-sm-12"><p>' + $msg + '</p><p class=msg></p></div><div class="col-sm-12"><a href="javascript:void(0)" id="deleteyes" onclick="' + action + '" class="btn btn-primary mr-1 btn-popover pull-right">Yes</a><a href="javascript:void(0)" onclick="removePopover();" class="btn btn-danger mr-1 pull-right">No</a></div></div>'
    }).popover('show');

}


function CallHandlerForActivatedRecord(id,index, tab,status) {

  $('#deleteyes').html("Processing..");
  $('#popover-section').find('.msg').html('').css('display','none');

  
    currentAjax = 1 ;
    $.ajax({

    url: DASHURL+'/admin/commonajax', 

    type: "POST",

    data: {action:'changeStatus', id: id, tab: tab, status: status },

    dataType: "text",

    success: function (response) {
      response = JSON.parse(response);
      if(response.valid){

        $newindex=$('.table').find('tbody tr:eq('+parseInt(index)+')').find('td:last').index();

        /*Change Status Text*/

        $objstatustxt=$('.table').find('tbody tr:eq('+parseInt(index)+')').find('td:eq('+parseInt($newindex-1)+')');

        var statustxt=( status == 0 ) ? 'Active' : 'DeActive';
        if(tab != 'product' && tab != 'addons')
          $objstatustxt.html(( status == 0 ) ? "Active" : "DeActive");

        /*Change Class*/

        $('.table').find('tbody tr:eq('+parseInt(index)+')').find('td:last').find('button').attr('data-status',statustxt);
        if( status == 0 )
          $('.table').find('tbody tr:eq('+parseInt(index)+')').find('td:last').find('button.text-danger').addClass('text-success').removeClass('text-danger');
        else        
          $('.table').find('tbody tr:eq('+parseInt(index)+')').find('td:last').find('button.text-success').addClass('text-danger').removeClass('text-success');
        removePopover();
      }else
        $('#popover-section').find('.msg').html(getMsg(((response.msg)?response.msg:"Something went wrong!"),2)).css('display','block');
        
        $('#deleteyes').html('Yes');
        
      setTimeout(function(){$('#popover-section').find('.msg').html('').css('display','none');} , 2000);  
            
      
    },
    error:function(response){
    currentAjax = 0 ;     
        $('#deleteyes').html('Yes');
        $('#popover-section').find('.msg').html(getMsg("Something went wrong!",2)).css({'display':'block'});
      setTimeout(function(){$('#popover-section').find('.msg').html('').css('display','none');} , 2000);
    }

  });

}


function removePopover(){
  $('.popover').popover('dispose');
}

/***************** End Active DeActive records ****************/
/*********************** Delete Records ***********************/

// function delete_row(obj,tab,id){  

//   var  $tr=$(obj).closest('tr');

//   var index=$tr.index();

//   var action = "CallHandlerForDeleteRecord(" + id + "," + index + ",\'" + tab + "\');";
//   console.log (action);
//   removePopover();
//   $(obj).popover({
//       placement : 'top',
//       html : true,
//       title : 'Active/DeActive <a href="javascript:" onclick="removePopover();" class="close" data-dismiss="alert">&times;</a>',
//       content : '<div class="row" id="popover-section"><div class="col-sm-12"><p>Are You Sure Want To Delete This Record ?</p><p class=msg></p></div><div class="col-sm-12"><a href="javascript:void(0)" id="deleteyes" onclick="' + action + '" class="btn btn-primary mr-1 btn-popover pull-right">Yes</a><a href="javascript:void(0)" onclick="removePopover();" class="btn btn-danger mr-1 pull-right">No</a></div></div>'
//   }).popover('show');

// }

function CallHandlerForDeleteRecord(obj,tab, id, event) {
  // $('#deleteyes').html("Processing");
  var  $tr  = $(obj).closest('tr');
  var index = $tr.index();

  var formData = { action:"deleteRecord", index:index,id:id, tab: tab, event: event};

  currentAjax = 1 ;    
  $.ajax({
    url: DASHURL+'/admin/commonajax',
    type: "POST",
    data: formData,
    cache: false,
    success: function (d) {
      // var $ntr = $('.table').find('tbody').find('tr:eq(' + index + ')');
      // $ntr.remove();
      // removePopover();
      location.reload();

    },
    error : function(d) {}
  });

}

function approveReview(obj,tab,id){  

  var  $tr=$(obj).closest('tr');

  var index=$tr.index();

  var action = "CallHandlerForApproveReview(" + id + "," + index + ",'" + tab + "');";

  removePopover();
  $(obj).popover({
      placement : 'top',
      html : true,
      title : 'Active/DeActive <a href="javascript:" onclick="removePopover();" class="close" data-dismiss="alert">&times;</a>',
      content : '<div class="row" id="popover-section"><div class="col-sm-12"><p>Are You Sure Want To make public this review ?</p><p class=msg></p></div><div class="col-sm-12"><a href="javascript:void(0)" id="deleteyes" onclick="' + action + '" class="btn btn-primary mr-1 btn-popover pull-right">Yes</a><a href="javascript:void(0)" onclick="removePopover();" class="btn btn-danger mr-1 pull-right">No</a></div></div>'
  }).popover('show');

}

function CallHandlerForApproveReview(id,index, tab) {

  $('#deleteyes').html("Processing");
  
  currentAjax = 1 ;
  $.ajax({

    url: DASHURL+'/admin/commonajax',

    type: "POST",

    data: {action:'changeStatus', id: id, tab: tab, status: 0 },

    success: function (d) {

      var $ntr = $('.table').find('tbody').find('tr:eq(' + index + ')');

      $ntr.remove();

      removePopover();

    },
    error : function(d) {}

  });

}

function delete_image(obj, tab, id){  

  var  $tr=$(obj).closest('div.gallery-image');

  var index=$tr.index();

  var action = "CallHandlerForDeleteImage(" + id + ",'" + tab + "'," + index + ");";

      action = '"' + action + '"';

  var msg = "Are You Sure Want To Delete This Image?";

  var $div = "<div id='sb-containerDel'><div id='sb-wrapper' style='width:425px'><h1 style='font-size: 20.5px;'>"+msg+"</h1><hr/><table><tr><td><a href='javascript:void(0)' id='deleteyes' onclick=" + action + " class='button'>Yes</a></td><td><a href='javascript:void(0)' onclick='RemoveDelConfirmDiv();' class='button_deact fr'>No</a></td></tr></table></div></div>";

    $('body').append($div);

    $('#sb-containerDel').show('slow');

}

function CallHandlerForDeleteImage(id, tab, index) {

  $('#deleteyes').html("Processing..");

  var formData={action:"deleteRecord", tab:tab, id:id};

  
    currentAjax = 1 ;
    $.ajax({

    url: DASHURL+'/admin/commonajax',

    type: "POST",

    data: formData,

    success: function (d) {

      var $ntr = $('div.galleryImagesArea').find('div.gallery-image:eq(' + index + ')');

      $ntr.remove();

      RemoveDelConfirmDiv();

    },
    error : function(d) {}

  });

}
/********************** End Delete Records ************************/


/*********************Start User Section*************************/
function getUserList(){
  // $('#tableDataList').DataTable({
  //   "processing": true,
  //   "serverSide": true,
  //   "pageLength": 10,
  //   "preDrawCallback": function (settings) {                
  //     currentAjax = 1 ;
  //   },
  //   "fnDrawCallback": function( oSettings ) {
  //     currentAjax = 0 ;
  //   },
  //   "ajax":{
  //       "url": DASHURL+'/admin/commonajax',
  //       "dataType": "json",
  //       "type": "POST",
  //       "data":{"action" : "getUserList"}
  //   },
  //   "columns": [
  //         {"data": "img"},
  //         {"data": "userName"},
  //         {"data": "email"},
  //         {"data": "mobile"},
  //         {"data": "addedOn"},
  //         {"data": "action"},
  //      ],
  //   "order": [[5, 'desc']]
  // });
}

/************************End User Section************************/

/**************************GetNotificationList*****************/
function GetNotificationList(){

  $('#tableDataList').DataTable({

      "processing": true,

      "serverSide": true,

      "pageLength": 10,
        "preDrawCallback": function (settings) {                
      currentAjax = 1 ;
        },
        "fnDrawCallback": function( oSettings ) {
      currentAjax = 0 ;
    }, 

      "ajax":{

          "url": DASHURL+'/admin/commonajax',

          "dataType": "json",

          "type": "POST",

          "data":{'action' : 'GetNotificationList' }

      },

      "columns": [

            {"data": "notification"},
            
            {"data":"time"},

            {"data":"status"},

            {"data": "action"},

         ],

         "order": [[3, 'desc']]



  });

}


/***********************End GetNotificationList*****************/


function submitForm(obj, e){
  e.preventDefault();
  $(obj).find('.msg').html('');
  currentAjax = 1 ;
  $.ajax({
    url: COMMONAJAX,
    type: "POST",
    data: new FormData(obj),
    contentType: false,
    cache: false,
    processData:false,
    success:function(response){
      if(response.valid){
          $(obj).find('.msg').html(getMsg(response.msg,1));
          if(parseInt($(obj).find('#hiddenval').val()) < 1)
            window.location.reload();
      }else
          $(obj).find('.msg').html(getMsg(((response.msg)?response.msg:"Something went wrong!"),2));
    },
    error:function(response){
      $(obj).find('.msg').html(getMsg("Something went wrong!",2));
    },complete: function(response){
      currentAjax = 0 ;
      $(obj).find('.validate-form').text(btnText); 
      setTimeout(function(){$(obj).find('.msg').html('');}  , 3000);

    }
  });
}
