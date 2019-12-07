
function ResetTextBox(obj) {

	$(obj).find('input[type=text]').val('');

	$(obj).find('input[type=password]').val('');

	$(obj).find('input[type=email]').val('');

	$(obj).find('input[type=checkbox]').prop('checked',false);

	$(obj).find('input[type=radio]').prop('checked',false);

	$(obj).find('textarea').val('');

	$(obj).find('select').prop('selected',false);    

}


/*----------------------------Email Validation-------------------------------------------------*/

function validateEmail(email) {

    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\.+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return re.test(email);

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

function textBoxValidation(obj) {

    var check = true;

    $(obj).find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {

		var c = $(this).attr('required');

		
        var e = $(this).serializeArray();
        var v = (e.length)?e[0].value.trim():'';

        if ($(this).attr('type') == 'file')
            v =$(this).val();
        

		// $(this).removeClass("error border border-dange");

		if (c == 'required' && v == '') {
            $(this).addClass("error border border-dange");
        	($(this).closest("div").find('label.error').length>0)?"":$(this).closest("div").append('<label class="error text-danger">This field is required.</label>');
            check = false;

        }else if ($(this).attr('type')=='email' && validateEmail($(this).val().trim()) == false) {
          $(this).addClass("error border border-dange");
          ($(this).closest("div").find('label.error').length>0)?$(this).closest("div").find('label.error').text('Invalid email address.'):$(this).closest("div").append('<label class="error text-danger">Invalid email address.</label>');
            check = false;
        }else{
            if(!$(this).hasClass('more_error')){            
                $(this).removeClass("error border border-dange");
                $(this).closest("div").find('label.error').remove();
            }
        }

        

        if($('body .you_requested').find('.tab-pane').length > 0) {

            $('body .you_requested').find('.tab-pane').removeClass('active');

            $('body .you_requested').find('#'+GLOBALLANG.language).addClass('active');

            $('body .you_requested').find('.nav-tabs li').removeClass('active');

            $('body .you_requested').find('.nav-tabs li').find('a[href=#'+GLOBALLANG.language+']').closest('li').addClass('active');



            if($('body .you_requested').find('#variable-'+GLOBALLANG.language).length > 0) {

                $('body .you_requested').find('#variable-'+GLOBALLANG.language).addClass('active');

                $('body .you_requested').find('.nav-tabs li').find('a[href=#variable-'+GLOBALLANG.language+']').closest('li').addClass('active');

            }
            if($('body .you_requested').find('#addons-'+GLOBALLANG.language).length > 0) {

                $('body .you_requested').find('#addons-'+GLOBALLANG.language).addClass('active');

                $('body .you_requested').find('.nav-tabs li').find('a[href=#addons-'+GLOBALLANG.language+']').closest('li').addClass('active');

            }

        }



    });

    return check;

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

 
function checkCardCVV() {
    if (String.fromCharCode(event.keyCode).match(/[^0-9]/g)) return false;
    if($(event.target).val().length == 4) return false;
}

function checkCardMonthYear(exp_month, exp_year) {

    var month = $(exp_month).val();
    var year = $(exp_year).val();
    var d = new Date(),
    n = d.getMonth()+1,
    y = d.getFullYear().toString().substr(-2);
    if (y == year) {
        $.each($(exp_month+" option"), function(){
            if (parseInt($(this).val().trim()) < n) {
                $(this).addClass('hide');
            }else
                $(this).removeClass('hide');
        });
       
    }else
        $(exp_month).children().removeClass('hide');
    if (parseInt(month.trim()) < n){
        $(exp_month+" option[value=" + ((n < 10)?'0'+n:n) +"]").attr('selected', true);
    }

}
 
 
function OnlyAlphabet() {
     var charCode = (event.which) ? event.which : event.keyCode;
     if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
     return true;
     else
    return false
}
 
function OnlyFloat() {
    var value = $(event.target).val()
    var charCode = (event.which) ? event.which : event.keyCode;
    if((value.indexOf('.')!=-1) && (charCode < 48 || charCode > 57))
        return false;
    else if((charCode != 46 || $(event.target).val().indexOf('.') != -1) && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
function OnlyInteger(e) {
    if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
}



function OnlyNumericKey(e) {

    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||

             // Allow: Ctrl+A, Command+A

            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 

             // Allow: home, end, left, right, down, up

            (e.keyCode >= 35 && e.keyCode <= 40)) {

                 // let it happen, don't do anything

                 return;

        }

        // Ensure that it is a number and stop the keypress

        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {

            e.preventDefault();

        }

}



/*************************************************** File Upload Preview ************************************************************************/

function filepreviewnew(input) {   

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {

          $(input).closest('div.image-upload').find('img').attr('src',e.target.result);            

        }

        reader.readAsDataURL(input.files[0]);       

    } else 

        $(input).closest('div.image-upload').find('img').attr('src',DASHSTATIC+'/restaurant/assets/img/uplod.png'); 

}
function userimgpreview(input) {   

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {

          $(document).find('img.image-upload-img').attr('src',e.target.result);            

        }

        reader.readAsDataURL(input.files[0]);       

    }else
        $(document).find('img.image-upload-img').attr('src',DASHSTATIC+'/img/user-icon.jpg'); 

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

/******************************* COPY TEXT One Tab To another **********************/
// $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//   var target = $(e.target).attr("href"); // activated tab
//   target = target.replace("#", "");
//   var langSuffixItem = GLOBALLANG.langSuffix;
//   var currentLanguage = GLOBALLANG.language;
//   var formType = target.split('-');
//   $('#'+target).find('input[type=text], textarea').each(function(){

//       if($(this).val() == '') {
//           if(formType[0] == 'variable') {
//               var inputName = $(this).attr('name').split('_');
//               inputName[0] = inputName[0].replace('[]','');
//               var diveCurrentIndex = $(this).closest('.variableitemdetails').attr('data-counter');
//               if(this.tagName.toLowerCase() == 'input')
//                   $(this).val($('#variable-'+currentLanguage).find('.box-'+diveCurrentIndex).find('input[name="'+inputName[0]+langSuffixItem+'[]"]').val());
//               else
//                   $(this).val($('#variable-'+currentLanguage).find('.box-'+diveCurrentIndex).find('textarea[name="'+inputName[0]+langSuffixItem+'[]]"').val());
//           }
//           else if(formType[0] == 'addons'){
//               var inputName = $(this).attr('name').split('_');
//               var lastInputName = '';
              
//               if( inputName.length > 1 ) 
//                   lastInputName = inputName[1].substr(inputName[1].indexOf("[") , inputName[1].length -1);
//                   //inputName[0] = inputName[0].substr(0,inputName[0].indexOf("["));
              
//               else {
                  
//                   lastInputName = inputName[0].substr(inputName[0].indexOf("[") , inputName[0].length -1);
//                   inputName[0] = inputName[0].substr(0,inputName[0].indexOf("["));

//               }

//               if(this.tagName.toLowerCase() == 'input')
//                   $(this).val($('#addons-'+currentLanguage).find('input[name="'+inputName[0]+langSuffixItem+lastInputName+'"]').val());
//               else
//                   $(this).val($('#addons-'+currentLanguage).find('textarea[name="'+inputName[0]+langSuffixItem+lastInputName+'"]').val());
//           }
//           else {
//               var inputName = $(this).attr('name').split('_');
//               if(this.tagName.toLowerCase() == 'input')
//                   $(this).val($('#'+currentLanguage).find('input[name='+inputName[0]+langSuffixItem+']').val());
//               else
//                   $(this).val($('#'+currentLanguage).find('textarea[name='+inputName[0]+langSuffixItem+']').val());
//           }
         
//       }

//   });
// });

function getMsg(message="", msgtype=1){
    if(msgtype == 1){ //success message
        msg = '<div onclick="javascript:$(this).fadeOut(500)" style="list-style: none;overflow: hidden; margin: 4px 0px; border-radius: 2px; border-width: 2px; border-style: solid; border-color: rgb(124, 221, 119); box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px; background-color: rgb(188, 245, 188); color: darkgreen; cursor: pointer;" class="animated flipInX"><div class="noty_bar noty_type_success" id="noty_1432600013676628200"><div class="noty_message" style="font-size: 14px; line-height: 16px; text-align: center; padding: 10px; width: auto; position: relative;"><div class="noty_text" style="font-family: Nunito Sans, sans-serif;">'+message+'</div></div></div></div>';
    } else if(msgtype == 2){ //Error message
        msg ='<div onclick="javascript:$(this).fadeOut(500)" style="list-style: none;overflow: hidden; margin: 4px 0px; border-radius: 2px; border-width: 2px; border-style: solid; border-color: rgb(226, 83, 83); box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px; background-color: rgb(255, 129, 129); color: rgb(255, 255, 255); cursor: pointer;" class="animated flipInX"><div class="noty_bar noty_type_error" id="noty_505214828237683140"><div class="noty_message" style="font-size: 14px; line-height: 16px; text-align: center; padding: 10px; width: auto; position: relative; font-weight: bold;"><div class="noty_text" style="font-family: Nunito Sans, sans-serif;">'+message+'</div></div></div></div>';
    } else if(msgtype == 3){ //Warning message
        msg ='<div onclick="javascript:$(this).fadeOut(500)" style="list-style: none;overflow: hidden; margin: 4px 0px; border-radius: 2px; border-width: 2px; border-style: solid; border-color: rgb(255, 194, 55); box-shadow: rgba(0, 0, 0, 0.1) 0px 2px 4px; background-color: rgb(255, 234, 168); color: rgb(130, 98, 0); cursor: pointer;" class="animated flipInX"><div class="noty_bar noty_type_warning" id="noty_140323524152335250"><div class="noty_message" style="font-size: 14px; line-height: 16px; text-align: center; padding: 10px; width: auto; position: relative;"><div class="noty_text" style="font-family: Nunito Sans, sans-serif;"><strong>Warning!</strong> <br> '+message+'</div></div></div></div>';
    }
    return msg;      
}