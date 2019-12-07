var _location = document.location.toString();

var _location = $.trim(_location);
var lastChar = _location.slice(-1);
if (lastChar == '/') {
    _location = _location.slice(0, -1);
}

/*var BASEARR = _location.split('/');
var BASEURL = 'http://www.itsonme.ch';

 if($.inArray( "devv.website", BASEARR ) > -1)
  BASEURL = 'http://devv.website/vedmir';

 if($.inArray( "vedmir.com", BASEARR ) > -1)
  BASEURL = 'https://vedmir.com';
  
 if($.inArray( "itsonme.ch", BASEARR ) > -1 || $.inArray( "http://www.itsonme.ch/", BASEARR ) > -1 )
  BASEURL = 'http://www.itsonme.ch';*/

var FRONTSTATIC =  BASEURL+'/system/static/frontend';
var DASHSTATIC =  BASEURL+'/system/static/dashboard';

$(document).ready(function(){

  GetDataOfThisPage();

  
  $('.validate-signup').on('click', function(){
      if($(this).val()==GLOBALERRORS.processing)
         return false;
      var btn = $(this).val();
      $(this).val(GLOBALERRORS.processing);
      var chk=0;
       var obj=$(this).closest('form');
   if( TextBoxValidation(obj) === false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }
    
    if(validateEmail(obj) == false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }


  $(obj).find('input[type=password]').each(function () {
      
      if ($(this).val().length < 6) {
            $(this).addClass("error");
          ($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error" style="color:#DC3C1E;">'+GLOBALERRORS.password6to20+'</label>');
           
            chk = 1;
        }else{
            if(!$(this).hasClass('more_error')){            
                $(this).removeClass("error");
                $(this).closest("div").find('label.error').remove();
            }
        }
  });

    if($('#password').val().length > 5){
      if($('#password').val() != $('#confirmPassword').val()){
            var pasObj = ('#confirmPassword');
            $(pasObj).addClass("error");
            ($(pasObj).closest("div").find('label.error').length>0)?"":$(pasObj).closest("div").append('<label class="error" style="color:#DC3C1E;">'+GLOBALERRORS.passwordNotMatchConfirmPassword+'</label>');
             
              chk = 1;
      }else{
          if(!$(pasObj).hasClass('more_error')){            
              $(pasObj).removeClass("error");
              $(pasObj).closest("div").find('label.error').remove();
          }
      }
    }

     if(chk===1){
          $(this).val(btn);
          return false;
      }
      
  }); 
  
  $('.validate-form').on('click', function(){
      if($(this).val()==GLOBALERRORS.processing)
         return false;
      var btn = $(this).val();
      $(this).val(GLOBALERRORS.processing);
      var chk=0;
       var obj=$(this).closest('form');
   if( TextBoxValidation(obj) === false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }
    
    if(validateEmail(obj) == false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }

     if(chk===1){
          $(this).val(btn);
          return false;
      }
      
  }); 

  $('#btnEnquiry').on('click', function(){
      if($(this).val()==GLOBALERRORS.processing)
         return false;
      $(this).val(GLOBALERRORS.processing);
      var chk=0;
       var obj=$(this).closest('form');
   if( TextBoxValidation(obj) === false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }
    
    if(validateEmail(obj) == false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }

     if(chk===1){
          $(this).val(GLOBALERRORS.submitMessage);
          return false;
      }
      
  });
  
  $('#btnBlogComment').on('click', function(){
      if($(this).val()==GLOBALERRORS.processing)
         return false;
      $(this).val(GLOBALERRORS.processing);
      var chk=0;
       var obj=$(this).closest('form');
   if( TextBoxValidation(obj) === false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }
    
    if(validateEmail(obj) == false){
      $(obj).find('.error:eq(0)').focus();
      chk=1;
      
    }

     if(chk===1){
          $(this).val(GLOBALERRORS.commentNow);
          return false;
      }
      
  });


});


/************Calling function according to current page**********/
function GetDataOfThisPage() {

  var uriArray=returnUriArray();
  // debugger;
    if(_location == BASEURL+'/blog')
    GetBlogList('',1);
  else if($.inArray( "blog", uriArray ) > -1 && $.inArray( "category", uriArray ) > -1)
    GetBlogList('',1);
  else if($.inArray( "blog", uriArray ) > -1 && $.inArray( "search", uriArray ) > -1)
    GetBlogList('',1);
  
// debugger;
  
}

/*-------- get blog list -----------*/
function GetBlogList(obj,cat){

  $('.tablebody').html('<p class="text-center"><img src="'+ DASHSTATIC+'/restaurant/assets/img/loading.gif" alt="loading"></p>'); 
  var formData = createSearchCondition('BlogList');
  var baseurl=BASEURL+'/commonajax';
  $.ajax({
    type: "POST",
    url: baseurl,
    data: formData,
    success: function (data) {
      currentAjax =0 ;
      $('#hidTotalRecord').val(data.count);
      $('.tablebody').html(data.id); 
      CreatePaging();
      ShowPageNumberMsg();
    },error: function(data){  }
  });
}

function createSearchCondition(cat){
        var tarray='';
        var pagesize=parseInt(getPagignationNumber());
        var pageNo=0;
        var daterange='';
        var filterType='';
        var filterBy='';
        // debugger;
        if($('.dataTables_paginate').find('#pagingNumber').length>0)
          pageNo=parseInt($('.dataTables_paginate li.active').find('a').html())-1;
        if($('.dataTables_paginate').find('#pagingNumber').length>0)
          pageNo=parseInt($('.dataTables_paginate li.active').find('a').html())-1;
        else if($('.dataTables_paginate').find('#selectpagingNumber').length>0)
           pageNo=$('.dataTables_paginate').find('#selectpagingNumber').val();


        if($('.filterbody').attr('id') != '' && $('.filterbody').html()!=''){
          filterType= $('.filterbody').attr('id');
          filterBy = $('.filterbody').html();
        }
// debugger;
        if(cat=='BlogList'){
            tarray= { action: "Get_Blog_List",pagesize:pagesize,pageno:pageNo,filterType:filterType,filterBy:filterBy};
          }        
        
          return tarray;
  }

function SearchRecordByPageNumber(cat){
currentAjax = 1; 
 $('.tablebody').html('<p class="text-center"><img src="'+ DASHSTATIC+'/restaurant/assets/img/loading.gif" alt="loading"></p>'); 
 var formData = createSearchCondition(cat);
  $.ajax({
    type: "POST",
    url: BASEURL+'/commonajax',
    data: formData,
    success: function (data) {
      currentAjax = 0;
        $('#hidTotalRecord').val(data.count);
        $('.tablebody').html(data.id);
        ShowPageNumberMsg();   
    },error: function(){alert(GLOBALERRORS.internalError);}
  });
}

/*-------- add blog comment  -----------*/
function addBlogComment(obj,e) {
  $('.submitResponce').html('');
    event.preventDefault();
    $.ajax({
      url:BASEURL+'/home/add-blog-comment',
      method:'POST',
      data: new FormData(obj),
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if (data == 1)
          var alert = '<span class="label label-success">'+GLOBALERRORS.commentAddedSuccessfully+'</span>';
        else if (data == 'email')
          var alert = '<span class="label label-danger">'+GLOBALERRORS.emailRequired+'</span>';
        else
          var alert = '<span class="label label-danger">'+GLOBALERRORS.somethingIsWrong+'</span>';
        
        $('.submitResponce').html(alert);
        $('#btnBlogComment').val(GLOBALERRORS.commentNow);

      },
      failed:function(data){

        $('#btnBlogComment').val(GLOBALERRORS.commentNow);
        $('.submitResponce').html('<span class="label label-danger">'+GLOBALERRORS.somethingIsWrong+'</span>');
      }
    });
}

/*-------- Sending mail of equiry  -----------*/
function sendEnquiry(obj,e) {
  $('.submitResponce').html('');
    event.preventDefault();
    $.ajax({
      url:BASEURL+'/home/send-enquiry',
      method:'POST',
      data: new FormData(obj),
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if (data == 1)
          var alert = '<span class="label label-success">'+GLOBALERRORS.thankyouForContact+'</span>';
        else
          var alert = '<span class="label label-danger">'+GLOBALERRORS.somethingIsWrong+'</span>';
        
        $('.submitResponce').html(alert);
        $('#btnEnquiry').val(GLOBALERRORS.submitMessage);

      },
      failed:function(data){

        $('#btnEnquiry').val(GLOBALERRORS.submitMessage);
        $('.submitResponce').html('<span class="label label-danger">'+GLOBALERRORS.somethingIsWrong+'</span>');
      }
    });
}




function TextBoxValidation(obj) {
    var check = true;
    $(obj).find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {
    var c = $(this).attr('required');
    var v = $(this).val().trim();
    // $(this).css("border", "solid 1px #c9cfd4 !important");
    // if (c == 'required' && v == '') {
    //         $(this).css("border", "solid 1px #DC3C1E");
    //         check = false;
    //     }     
    if (c == 'required' && v == '') {
            $(this).addClass("error");
          ($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error" style="color:#DC3C1E;">'+GLOBALERRORS.thisFieldIsRequired+'</label>');
           
            check = false;
        }
        else{
            if(!$(this).hasClass('more_error')){            
                $(this).removeClass("error");
                $(this).closest("div").find('label.error').remove();
            }
        }
    });
    return check;
}


function validateEmail(obj) {
  var check = true;
  $(obj).find('input[type=email]').each(function () {
    var email = $(this).val().trim();
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\.+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!re.test(email)){
      $(this).addClass("error");
      ($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error" style="color:#DC3C1E;">'+GLOBALERRORS.invalidEmail+'</label>');
      check = false;
    }else{
      if(!$(this).hasClass('more_error')){            
        $(this).removeClass("error");
        $(this).closest("div").find('label.error').remove();
      }
    }    
  });
  return check;
}


function changeLanguage(lang) {
  lang = (lang == '') ? 'english' : lang;
  var formData={lang:lang};
  $.ajax({
    type: "POST",
    url: BASEURL+'/dashboard/auth/change-lang/'+lang,
    data: formData,
    success: function (d) {
     window.location.reload(true);
    },error(d){}
  });
  debugger;
}


/*-------- Restaurant Signup -----------*/
function restaurantSignup(obj,e) {
  $('.msg').html('');
  $('.sigin').val(GLOBALERRORS.processing);
    e.preventDefault();
    $.ajax({
      url:BASEURL+'/home/restaurant_signup',
      method:'POST',
      data: new FormData(obj),
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        $("html, body").animate({ scrollTop: 0 }, "slow");
        if (data.trim() == 'added'){
          var alert = '<span class="alert alert-success" class="close" data-dismiss="alert" aria-label="close">'+GLOBALERRORS.venueSignupSuccess+'</span>';
          ResetTextBox(obj);
        }
        else if (data.trim() == 'nameAlreadyExist')
          var alert = '<span class="alert alert-danger" class="close" data-dismiss="alert" aria-label="close">'+GLOBALERRORS.venueNameAlreadyExist+'</span>';
        else if (data.trim() == 'emailAlreadyExist')
          var alert = '<span class="alert alert-danger" class="close" data-dismiss="alert" aria-label="close">'+GLOBALERRORS.emailAlreadyExist+'</span>';
        else
          var alert = '<span class="alert alert-danger" class="close" data-dismiss="alert" aria-label="close">'+GLOBALERRORS.somethingIsWrong+'</span>';
        
        $('.msg').html(alert);
        $('.sigin').val(GLOBALERRORS.signUp);

      },
      failed:function(data){

        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.sigin').val(GLOBALERRORS.signUp);
        $('.msg').html('<span cclass="alert alert-danger" class="close" data-dismiss="alert" aria-label="close">'+GLOBALERRORS.somethingIsWrong+'</span>');
      }
    });
}
