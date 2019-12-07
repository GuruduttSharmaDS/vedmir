
function openpoploader(){
    $('body').append('<div class="overlaypage"></div>');
}
function removepoploader(){
    $('body').find('.overlaypage').remove();
}
function CloseLoader(){$('#divBlock').fadeOut(500)};
function openloader(){
	 $("#divBlock").animate({ width: ["toggle", "swing"], height: ["toggle", "swing"], opacity: 0.7 }, 500);
}
function OpenDiv1() { $('#sb-container').fadeIn(700, function () { $('.add_task').animate({ 'marginTop': '95px' }, "slow"); }); }
function RemoveDynamicMsgDiv() {
	$('.add_task').animate({ 'marginTop': '-530px' }, 1200, function () {
        $('#sb-container').fadeOut(700);
        $('#sb-container').remove();
    });
	
	CloseLoader();
}
function RemovePopupDiv(cat) {
	$('body').css('overflow','auto');
    $('.add_task').animate({ 'marginTop': '-530px' }, 1200, function () {
        $('#sb-container').fadeOut(700);
        $('#sb-container').remove();
    });
	if(cat=='1')
		window.location.href=BASEURL;
	CloseLoader();

}
function RemoveDelConfirmDiv() {
    $('#sb-containerDel').fadeOut(700);
    $('#sb-containerDel').remove();
}
function ResetTextBox(obj) {
	$(obj).find('input[type=text]').val('');
	$(obj).find('input[type=password]').val('');
	$(obj).find('input[type=email]').val('');
	$(obj).find('input[type=checkbox]').prop('checked',false);
	$(obj).find('input[type=radio]').prop('checked',false);
	$(obj).find('textarea').val('');
	$(obj).find('select').prop('selected',false);    
}
function getQueryString(sParam){
     var sPageURL = window.location.search.substring(1);
     var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
            return sParameterName[1];
    }
}
/*----------------------------Email Validation-------------------------------------------------*/
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\.+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}


function EmailValidation(obj) {
  var check = true;
  $(obj).find('input[type=email]').each(function () {
    var email = $(this).val().trim();
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\.+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!re.test(email)){
      $(this).addClass("error");
      ($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error" style="color:#DC3C1E;">Invalid Email.</label>');
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
//for alphanumeric paasword
function checkalphanumeric(input){
	var reg = /^[^%\s]{8,}/;
    var reg2 = /[a-zA-Z]/;
    var reg3 = /[0-7]/;
    if((reg.test(input) && reg2.test(input) && reg3.test(input))==false){
    	alert('Password must be alphanumeric with minimum 8 characters.');
		return false;
	}
	else
	 return true;
}
/*----Validation----*/
function TextBoxRestaurantValidation(obj) {
    var check = true;
    $(obj).find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {
		var c = $(this).attr('required');
		var v = $(this).val().trim();
        
		$(this).css("border", "solid 1px #c9cfd4");
		if (c == 'required' && v == '') {
            $(this).css("border", "solid 1px #DC3C1E");
            check = false;
        }     
		if (c == 'required' && v == '') {
            $(this).addClass("error");
        	($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error" style="color:#DC3C1E;">This field is required.</label>');
           
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
function TextBoxAdminValidation(obj) {
    var check = true;
    $(obj).find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {
  var c = $(this).attr('required');
  var v = $(this).val().trim();
  /*$(this).css("border", "solid 1px #c9cfd4");
  if (c == 'required' && v == '') {
            $(this).css("border", "solid 1px #DC3C1E");
            check = false;
        }*/        
  if (c == 'required' && v == '') {
            $(this).addClass("error");
         ($(this).closest("div.item").next('div.error_report').length>0)?$(this).closest("div.item").next('div.error_report').html($(this).attr('data-error')):$('<div class="error_report" style="text-align: center;">'+$(this).attr('data-error')+'</div>').insertAfter($(this).closest("div.item"));
            check = false;
        }
        else{
            if(!$(this).hasClass('more_error')){            
                $(this).removeClass("error");
                $(this).closest("div.item").next('div.error_report').remove();
            }
        }
    });
    return check;
}
function checkerrorfield(obj){
	var check=true;
     $(obj).find('input[type=text],input[type=password],input[type=email],input[type=file],textarea').each(function () {
		  if($(this).hasClass('error') || $(this).hasClass('more_error')){
				$(this).focus();
				check=false;
				return false;
		   }
    });
    return check;
}
//For Text Box Empty
function ResetTextBoxForRegister(obj) {
	var pagename=returnPageName();//for necessary item reset according to pages.
	$(obj).find('input[type=text],[type=password],[type=hidden]').val('');
	$(obj).find('input[type=text],[type=password]').next('span').html('');
	$(obj).find('textarea').val('');
	$(obj).find('input[type=password]').val('');
	$(obj).find('input[type=email]').val('');
	$(obj).find('input[type=hidden]').val(''); 
	$(obj).find('input[type=text],input[type=password],select,textarea').css("border", "solid 1px #c9cfd4");
	if(pagename!='registration.php'){
		if($(obj).find('input[type=radio]').length>0)
		   $(obj).find('input[type=radio]').prop("checked",false);
	}
    if($(obj).find('input[type=checkbox]').length>0)
	   $(obj).find('input[type=checkbox]').prop("checked",false);
}
/*------------------------------------------- Validate Site Url ------------------------------------------------------------------------------*/
function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}
function returnUriArray(){
	var _location=document.location.toString();
	return _location.split('/');
}

function returnPageName(){
    var _location=document.location.toString();
    var applicationNameIndex=_location.lastIndexOf('/')+1;
    var applicationName=_location.substring(0,applicationNameIndex);
    var pageName=_location.replace(applicationName,'').trim().toLowerCase();
    return pageName;
}
function OnlyNumeric(evt) {
    var chCode = evt.keyCode ? evt.keyCode : evt.charCode ? evt.charCode : evt.which;
    if ((chCode >= 48 && chCode <= 57) || chCode == 46 || (chCode >= 37 && chCode <= 40) || (chCode >= 8 && chCode <= 9) || (chCode==3))
        return true;
    else
        return false;
}
/****************************************************** Count Rows **********************************************/
function countrows(obj){
	var rows=0;
	$(obj).find('tr').each(function(){
	    rows++;
	});
	return rows;
}
/************************************************** Check Username/Slug url ********************************************************************/
function checkusername(username){
	var usernameRegex = /^[a-zA-Z0-9._-]+$/; 
	return usernameRegex.test(username);
}
/*************************************************** File Upload Preview ************************************************************************/
function fileuploadpreview(input) {		
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$(input).next('div.previewimg').html('<img src="'+e.target.result+'" width="70px" height="50px">');            
        }
        reader.readAsDataURL(input.files[0]);       
    } else 
        $(input).next('div.previewimg').html('');
}
function fileuploadwithdimenssionpreview(input) { 
    if (input.files && input.files[0]) {
        var _URL = window.URL || window.webkitURL;
       var  img = new Image();
        img.onload = function (e) {
            if(this.width==800 && this.height==600){
                $(input).removeClass("error");
                $(input).removeClass("more_error");
                $(input).closest("div").find('label.error').remove();
                $(input).next('div.previewimg').html('<img src="'+img.src+'" width="70px" height="50px">');
            }
            else{
                $(input).addClass("error");
                $(input).addClass('more_error');
                ($(input).closest("div").find('label.error').length>0)?$(input).closest("div").find('label.error').html("Image should be (800x600px) size"):$(input).closest("div").append('<label class="error">Image should be (800x600px) size</label>');
				$(input).val('');
				return false;
            }                       
        }
        img.src = _URL.createObjectURL(input.files[0]);      
    } else 
        $(input).next('div.previewimg').html('');
}
/************************************************** File Upload Video ***************************************************************************/
function getfilesize(input,filesize){  
    var getfilesize=input.files[0].size;
    var finalsize=getfilesize/1048576;  
    if(finalsize>filesize){      
        $(input).addClass("error");
        $(input).addClass("more_error");
        ($(input).closest("div").find('label.error').length>0)?"":$(input).closest("div").append('<label class="error">File size less than '+filesize+'MB.</label>');
        return false;
    }
    else{
        $(input).removeClass("error");
        $(input).removeClass("more_error");
        $(input).closest("div").find('label.error').remove();
        return true;
    }
}
/************************************************** Check FIle Image Extension ************************************************************************/
function validateFileExtension(obj){
	var allowedFiles = [".jpeg", ".jpg", ".png", ".JPGE", ".JPG", ".PNG"];
    var fileUpload = $(obj);    
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    if (!regex.test(fileUpload.val().toLowerCase()))        
        return false;
    return true;
}
/************************************************** Check FIle Video Extension ************************************************************************/
function validateVideoFileExtension(obj){
    var allowedFiles = [".mp4", ".webm", ".ogg"];
    var fileUpload = $(obj);    
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
    if (!regex.test(fileUpload.val().toLowerCase()))        
        return false;
    return true;
}
function getWords(str,len) {
    return str.split(/\s+/).slice(0,len).join(" ");
}