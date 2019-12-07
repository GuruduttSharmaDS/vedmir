var _location = document.location.toString();

var _location = $.trim(_location);
var lastChar = _location.slice(-1);
if (lastChar == '/') {
    _location = _location.slice(0, -1);
}

/*var BASEARR = _location.split('/');
var BASEURL = 'http://localhost/vedmir';

if($.inArray( "dsdev.website", BASEARR ) > -1)
  BASEURL = 'http://dsdev.website/vedmir';*/



	function SearchRecordWhenClickOnPaging(){		
		
		var uriArray=returnUriArray();
		if(_location == BASEURL+'/blog' || ($.inArray( "blog", uriArray ) > -1 && $.inArray( "category", uriArray ) > -1) || ($.inArray( "blog", uriArray ) > -1 && $.inArray( "search", uriArray ) > -1))
			SearchRecordByPageNumber('BlogList');
		
	}

	function ChangePageNumber(obj){
		var uriArray=returnUriArray();
		if(_location == BASEURL+'/blog' || ($.inArray( "blog", uriArray ) > -1 && $.inArray( "category", uriArray ) > -1) || ($.inArray( "blog", uriArray ) > -1 && $.inArray( "search", uriArray ) > -1))
			GetBlogList('',1);
	}


	function CreatePaging(){
		$('.dataTables_paginate').html('');
		var pageCount=parseInt(getPagignationNumber());
		var totalRow = parseInt($('#hidTotalRecord').val());
		if(totalRow>pageCount){
			var start=Math.ceil(parseFloat(totalRow/pageCount));
			if(start>10){
				$('.dataTables_paginate').html('<label>Go To Page:- </label><select id="selectpagingNumber" ></select>');
				var $sel=$('.dataTables_paginate').find('#selectpagingNumber');
				var res='';
				for(var i=0;i<start;i++)
					res+='<option value="'+(i)+'">'+(i+1)+'</option>';
				$sel.html(res);
			}
			else{
				$('.dataTables_paginate').append('<ul id="pagingNumber"></ul>');
				var $ul=$('.dataTables_paginate').find('#pagingNumber');
				
				$ul.html('<li class="prev"><a href="javascript:"><span>&lt;&lt;</span></a></li>');
				for(var i=0;i<start;i++){
					
					$ul.append('<li><a href="javascript:">'+$ul.find('li').length+'</a></li>');
				}
					
				$ul.append('<li class="next"><a href="javascript:"><span>&gt;&gt;</span></a></li>');
				$ul.find('li:eq(0)').addClass('disabled');
				$ul.find('li:eq(1)').addClass('active');
				if($ul.find('li').length==3)
					$ul.find('li:eq(2)').addClass('disabled');
			}
		}
		$('#sample_1_info').removeClass('hide').addClass('show');		
	}
	
	$(document).on('change', '#selectpagingNumber', function (e) {
	   	SearchRecordWhenClickOnPaging();
	  
	});
	$(document).on('click', '#pagingNumber li a', function (e) {
		var c=$(this).closest('li').attr('class');
		if(c!=undefined){
			if(c.indexOf('disabled')>-1){
				e.preventDefault();
				return;
			}
		}
		$('#pagingNumber li:first,#pagingNumber li:last').removeClass('disabled');
		var currentIndex=$(this).closest('li').index();
		var n1=parseInt($('#pagingNumber').find('li').length-2);
		var $act=$('.dataTables_paginate li.active');
		var activeIndex=$act.find('a').html();
		if(currentIndex==0)
			activeIndex=parseInt(activeIndex)-1;
		else if(currentIndex==$('#pagingNumber').find('li').length-1)
			activeIndex=parseInt(activeIndex)+1;
		else
			activeIndex=parseInt(currentIndex);
	
		$('#pagingNumber li').removeClass('active');	
		$('#pagingNumber').find('li:eq('+activeIndex+')').addClass('active');
		if(activeIndex==1)
			$('#pagingNumber').find('li:first').addClass('disabled');
		else if(activeIndex==n1)
				$('#pagingNumber').find('li:last').addClass('disabled');
		
		SearchRecordWhenClickOnPaging();
		e.preventDefault();
	});
	function ShowPageNumberMsg(){
		$('#sample_1_info div.dataTables_info').html('');
		var pageCount=parseInt(getPagignationNumber());
		var total = parseInt($('#hidTotalRecord').val());
		var pageNo=0;
		if($('.dataTables_paginate').find('#pagingNumber').length>0)
			pageNo=$('.dataTables_paginate li.active').find('a').html();
		else if($('.dataTables_paginate').find('#selectpagingNumber').length>0)
			pageNo=$('.dataTables_paginate').find('#selectpagingNumber').find('option:selected').val();
		if(pageNo==0)
			return;
		var start=((parseInt(pageNo)-1)*pageCount)+1;
		var end=(pageNo*pageCount);	
		if(end>total)
			end=total;		
		var msg='Showing '+start+' to '+end+' of '+total+' entries';
		$('#sample_1_info div.dataTables_info').html(msg);
	}
	function getPagignationNumber()
	{
		var pageperno=$('#no_results option:selected').val();
		
		return pageperno;
	}
	