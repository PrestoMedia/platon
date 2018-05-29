 
/**== loader js ==*/

$(window).load(function() {
    $(".bg_load").fadeOut("slow");
})

var Filters = [] ; 
var type , page , query  ;

$(document).ready(function() {
	type = $('body').attr('PageType') ;
	page = parseFloat($('body').attr('page')) ;
	query = parseFloat($('body').attr('query')) ;
	   if ( type == 'result' ){
			GetPage(page);
		}else if ( type == 'filter' ){
			GetFilter(page);
		}
	
		$('.search-input').keyup(function(e) {
			if(e.which == 13) {
				Search(); 
			}else{
				Find(); 
			}
		});
	
	$(document).click(function (e) {
		if ($(e.target).parents("#hero-search-results ").length === 0) {
			$("#hero-search-results").hide();
 		}
		if ($(e.target).parents(".search-filter").length === 0) {
			$("#hero-search-filter").hide();
 		}
	});
	
	$('.close-box , #dark-box ').click(function(e){
		if ($(e.target).hasClass('clo')){ 
			$('#dark-box').fadeOut();
			$('body').css(    'overflow', 'auto') ;
		}
	})
	
	$('.close-edit').click(function(){
		$('#dark-edit').fadeOut();
		$('body').css(    'overflow', 'auto') ;
		$('.edit-box iframe').remove();
	})
	
	$('.close-filter').click(function(){
		$('#dark-filter').fadeOut();
 	})
	
	$('.show-results-filter').click(function(){
		$('#dark-filter').fadeIn();
	})
	
	$('.select-provider').click(function(){
		if ( Filters.indexOf( $(this).attr('provider') ) == -1 ){
			$(this).addClass('selected') ;
			Filters.push( $(this).attr('provider') )
		}else{
			$(this).removeClass('selected') ;
			Filters.splice( Filters.indexOf( $(this).attr('provider') ) , 1  ) ; 
		}
	})
	
	$('.search-filter').click(function(){
		$('#hero-search-filter').show() ;
	})
	
	
		  
	
	
})

$(window).on("scroll", function() {
	if ( type == 'result' || type == 'filter' ){
		var scrollHeight = $(document).height();
		var scrollPosition = $(window).height() + $(window).scrollTop();
		
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
			NextPage();
			//$('#Show-More').show();
		} 
	}
});

 


function SelectSearch(provider){
	$('*#search-provider').text(provider) ;
}
var FindTemplate ; 

function Find(){
	if (  $('.search-input').val( ).length <= 2  ){
		return false ; 
	}
	 $.ajax({
       type: "POST",
       url: "Search/find",
       dataType: "json",
	   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	   data : { query : $('.search-input').val( ) , provider : $('.selected-provider').text() },
       cache: false,
	   async: true,
       success: function(data) {
		    if (!FindTemplate  ){
				FindTemplate = Handlebars.compile($("#FindTemplate").html());
			}
			$('#hero-search-results').html(FindTemplate(data));
			$('#hero-search-results').show();
       },
       error: function(xhr, status, error) {
       }
    });
}

var mode = 'page' // ajax

function Search(){

  
	if ( mode == 'ajax' ){
  
 
	$('#hero-search-results').hide();
	$('span#search-keyword').text(  $('.search-input').val( ) ) ; 
	$('#search-in').text(  'search start' ) ; 
	$('#search-percent').text( '1') ; 
	$('#hero-search-repository').show();
	$('#search-bar').width('1%');
  
	$.ajax({
       type: "POST",
       url: "Search/query",
       dataType: "json",
	   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	   data : { mode : mode ,  query : $('.search-input').val( ) , provider : $('.selected-provider').text() },
       cache: false,
	   async: true ,
	   success: function(data) {  
		 if ( data.id  ){
				 window.location = 'Search/result/' + data.id + '/1' ;
		 }
       } ,
    });
	setTimeout(SearchState, 1000) ;
	}else{
		
		$('.bg_load').show();
		var url = 'Search/query' ; 
		var form = $('<form action="' + url + '" method="post">' +
		  '<input type="text" name="mode" value="' + mode + '" />' +
		  '<input type="text" name="query" value="' + $('.search-input').val( ) + '" />' +
		  '<input type="text" name="provider" value="' + $('.selected-provider').text() + '" />' +
		  '<input type="text" name="_token" value="' +$('meta[name="csrf-token"]').attr('content') + '" />' +
		  '</form>');
		$('body').append(form);
		form.submit();
	}
}


function SearchState(){
	 $.ajax({
       type: "POST",
       url: "Search/state",
       dataType: "json",
	   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
       cache: false,
       success: function(data) {
		   console.log(data);
		 if (! data){  
			setTimeout(SearchState, 500) ;
		 }else{
			 
		 if ( data.percent == 100 ){
				  window.location = 'Search/result/' + data[0]['query'] + '/1' ;
			 }else{
				 setTimeout(SearchState, 500) ;
			 }
			 $('#hero-search-repository').show();
			 $('#search-percent').text(data.percent);
			 $('#search-in').text( data.in);
			 $('#search-bar').width(  data.percent +  '%');
		 }
       } ,
        error: function(xhr, status, error) {
            if ( xhr.status == 200 ){
				 setTimeout(SearchState, 500) ;
			}
        }
    });
}
 

function Results(id){
	window.location = 'Search/result/' + id + '/1'  ;
}

var MainTemplate ; 

function NextPage(){

	page = page + 1 ; 
	if ( type == 'result'  ){
	    GetPage(page)
		//window.location = 'Search/result/' + query + '/' + page ;
	}else if ( type == 'filter'  ){
		GetFilter(page) ;
		//window.location = 'Search/filter/' + query +'/' + $('body').attr('filter') + '/' + page ;
	}
	$('#Show-More').hide();
}

function GetPage(id){
	 $.ajax({
       type: "POST",
       url: "json/" + query + '.repository.json' ,
       dataType: "json",
       cache: false,
       contentType: "application/json",
       success: function(data) {
		    var Page = [] ; 
			start = ( ( id - 1 ) * 40 ) ;
			 
			end = (( id - 1 )* 40 ) + 40  ;
			if ( end >= data.length ){
				end = data.length ;
				$('#Show-More').remove();
			}
		   
		    for(i= start; i <= end ; i++){
				Page.push( data[i]) ;
		    }
			 
			$('#search-count').text( data.length ) ; 
			if (!MainTemplate) {
                MainTemplate = Handlebars.compile($("#MainTemplate").html());
            }
		   $('#photos').append(MainTemplate(Page));
		   
			$grid = $('#photos').masonry({
			  itemSelector: '.grid-item',
			  columnWidth: 0
			});
			
			$grid.masonry('reloadItems')  ;
			
			$grid.imagesLoaded().progress( function() {
			  $grid.masonry('layout');
			});
		   
		   
		 
       },
        error: function(xhr, status, error) {
            alert(xhr.status);
        }
    });
}



function GetFilter(id){
	 $.ajax({
       type: "POST",
       url: "json/" + query + '.repository.json' ,
       dataType: "json",
       cache: false,
       contentType: "application/json",
       success: function(data) {
		   
		    FilterData = [] ; 
		    temp =  $('body').attr('filter').split(",")
			for(i= 0 ; i < data.length ; i++){
				if ( temp.indexOf(data[i]['provider'] ) >= 0 ){
					FilterData.push( data[i]) ;
				}
		    }
		   
	 
		    var Page = [] ; 
			start = ( ( id - 1 ) * 40 ) ;
			console.log(start);
			end = (( id - 1 )* 40 ) + 40  ;
			if ( end >= FilterData.length ){
				end = FilterData.length ;
				$('#Show-More').remove();
			}
			 
		    for(i= start; i <= end ; i++){
				Page.push( FilterData[i]) ;
		    }
			console.log(FilterData.length);
			$('#search-count').text( FilterData.length ) ; 
			if (!MainTemplate) {
                MainTemplate = Handlebars.compile($("#MainTemplate").html());
            }
		   $('#photos').append(MainTemplate(Page));
		 
			$grid = $('#photos').masonry({
			  itemSelector: '.grid-item',
			  columnWidth: 0
			});
			
			$grid.masonry('reloadItems')  ;
			
			$grid.imagesLoaded().progress( function() {
			  $grid.masonry('layout');
			});
		 
		 
       },
        error: function(xhr, status, error) {
            alert(xhr.status);
        }
    });
}

var url , provider , name , width , height , source ,  Image  ;

function ShowDialog(id){
 
	Image = id  ;
	url = $('#photos div[id="'+ id +'"] img.photo').attr('url') ;
    provider = $('#photos div[id="'+ id +'"] img.photo').attr('provider') ;
	name = $('#photos div[id="'+ id +'"] img.photo').attr('name') ;
	width = $('#photos div[id="'+ id +'"] img.photo').attr('width') ;
	height = $('#photos div[id="'+ id +'"] img.photo').attr('height') ;
	user = $('#photos div[id="'+ id +'"] img.photo').attr('user') ;
	source = $('#photos div[id="'+ id +'"] img.photo').attr('source') ;
	
 
 

 
 
	
	
	
	$('.image-box-image img').remove();
	var img = $('<img >');  
	img.attr('src', url);
	
	
	
	if ( provider == 'giphy' ){
		$('#edit').hide();
	}else{
		$('#edit').show();
	}
	
	img.appendTo('.image-box-image');
	if ( user != '' ){
		$('#image-box-provider').html(provider + '<span> / '+ user +'</span>' );
	}else{
		$('#image-box-provider').text(provider);
	}
	
	$('#image-box-provider').attr('onclick', 'openSource("'+ source +'")');
	
	$('#image-box-name').text(name);
	if ( width == '0' || height == '0' ){
		$('#image-box-size').text('');
	}else{
		$('#image-box-size').text('width : '+  width+'px ; height : '+ height +'px');
	}
	$('#dark-box .provider img').attr('src' , 'img/'+ provider +'.jpg' );
	
	$('body').css(  'overflow', 'hidden') ;
	
	

	
	$('#dark-box').fadeIn();
	
	$("<img/>",{
     load : function(){
			$('.image-box-image img').css( 'margin-left' , this.width/2*-1 + 'px' ) ;
			$('.image-box-image img').css( 'margin-top' , this.height/2*-1 + 'px' ) ;
			$('.image-box-image img').css( 'left' , '50%' ) ;
			$('.image-box-image img').css( 'top' ,  '50%' ) ;
        },
        src  : url
    });
	
 
	
}

 

function openSource(url){
	window.open(url) ; 
}

function Filter(){
	if ( Filters.length != 0 ){
	 window.location = 'Search/filter/' + query + '/'+ Filters  +'/' + 1 ;
	}else{
	window.location = 'Search/result/' + query + '/'+   1 ;	
	}
}

function Edit(){
	$(".bg_load").fadeIn("slow");
		$.ajax({
		   type: "POST",
		   url: "Image/Save",
		   dataType: "json",
		   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		   data : { 
			   action : 'edit',
			   url : url ,
			   name : name ,
			   width : width,
			   height : height,
			   user : user ,
			   source : source,
			   provider : provider
		   },
		   cache: false,
		   async: true,
		   success: function(data) {
			   $('#dark-box').fadeOut();
				$(".bg_load").fadeOut("slow");
				$('.edit-box').height( ($(window).height() - 60 ) + 'px' ) ;
				$('.edit-box').append('<iframe src="'+ 'Image/Edit/' + data.id +'" id="modalIFrame" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto" title="Dialog Title"></iframe>');
				$('body').css(    'overflow', 'hidden') ;
				$('#dark-edit').show();
				 
		   },
		   error: function(xhr, status, error) {
		   }
		});
 } 
 
 function Back(){
	 $('#dark-edit').fadeOut();
	 $('.edit-box iframe').remove();
	 ShowDialog(Image) ;
 }
function Download(){
		$.ajax({
		   type: "POST",
		   url: "Image/Save",
		   dataType: "json",
		   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		   data : { action : 'download', name : name },
		   cache: false,
		   async: true,
		   success: function(data) {
				 console.log(data);
		   },
		   error: function(xhr, status, error) {
		   }
		});
        } 
 
 
 
  
  