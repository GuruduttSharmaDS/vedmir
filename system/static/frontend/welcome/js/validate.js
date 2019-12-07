function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\.+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
/*----Validation----*/
function TextBoxValidation(obj) {
    var check = true;
    $(obj).find('input[type=text],input[type=password],input[type=email],input[type=file],textarea,select').each(function () {
		var c = $(this).attr('required');
		var v = $(this).val().trim();  
		if (c == 'required' && v == '') {
            $(this).addClass("error");
            check = false;
        }
        else{
            if(!$(this).hasClass('more_error'))           
                $(this).removeClass("error");
        }
    });
    return check;
}
function OnlyNumeric(evt) {
    var chCode = evt.keyCode ? evt.keyCode : evt.charCode ? evt.charCode : evt.which;
	return ((chCode >= 48 && chCode <= 57) || chCode == 46 || (chCode >= 37 && chCode <= 40) || (chCode >= 8 && chCode <= 9) || (chCode==3))?true:false;
}
function ResetTextBoxForRegister(obj) {
	$(obj).find('input[type=text],[type=password],[type=hidden]').val('');
	$(obj).find('input[type=text],[type=password]').next('span').html('');
	$(obj).find('textarea').val('');
	$(obj).find('input[type=password]').val('');
	$(obj).find('input[type=email]').val('');
	$(obj).find('input[type=hidden]').val(''); 
	$(obj).find('input[type=text],input[type=password],select,textarea').css("border", "solid 1px #c9cfd4");
}
function sendMail(obj,type){
     $('.msg').html('');
     if($(obj).text()=='...Processing')
		return;
	 var formObj=$(obj).closest('form');
	 var btxt=(type=='0')?'I’m curious!':'SUBMIT';
     if(TextBoxValidation(formObj)==false){
		   $(obj).text(btxt);
		   return false;
	 }
	 var email=$('#email').val().trim();
	 if(email!=''){
		 if(!validateEmail(email)){
			$('.msg').html('Email is not in valid format !');
			$('.msg').css('display','block');
			setTimeout(function(){ $('.msg').css('display','none'); }, 3000);
			return false;
		}
	 }
	 else{
		$(obj).text(btxt);
		$('#email').focus();
		return false;
	 }
	 
	$(obj).text('...Processing');
	var name=$('#name').val();
	var subject=$('#subject').val();
	var message=$('#message').val();
	var formData=(type=='0')?{action:"sendmail",email:email,dtype:type}:{action:"sendmail",name:name,email:email,subject:subject,message:message,dtype:type};
	  $.ajax({
		url: BASEURL+'/welcome/mailing',
		type: "POST",
		data: formData,
		success: function (data) {
			if(data.valid){
				$('.msg').html((type=='0')?'Thank you for subscription.':'Thanks for your inquiry. Our team will contact you soon.');
				
				ResetTextBoxForRegister('.form-enq')
			}
			else
				$('.msg').html('An unexpected error occurred.Try again');
			
			$('.msg').css('display','block');
			setTimeout(function(){ $('.msg').css('display','none'); }, 3000);
			$(obj).text(btxt);
		    
		},error(data){
			$('.msg').html('An unexpected error occurred');
			$('.msg').css('display','block');
			$(obj).text(btxt);
			setTimeout(function(){ $('.msg').css('display','none'); }, 3000);
		}
	});
  }