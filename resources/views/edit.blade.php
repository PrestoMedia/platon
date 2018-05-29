<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	
	  <meta name="csrf-token" content="{{ csrf_token() }}">
	  <base href="{{url('/')}}/" target="_self">
	
    <link rel="stylesheet" href="pixie/styles.min.css?v7">
	<link rel="stylesheet" href="js/jquery.nstSlider.css?v7">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>

   <link rel="icon" href="img/favicon.png"  type="image/png">
	  <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	
	
	<title>Presto Media | Universal Media Search</title>
	
</head>
<body>
<div class="global-spinner">
	<div class="verticle-center"><img class="loader_animation" src="img/loader_1.png" alt="#" /></div>
</div>
<pixie-editor></pixie-editor>
<script src="js/jquery.min.js?v7"></script>
<script src="js/jquery.nstSlider.js?v7"></script>
<script src="pixie/scripts.min.js?v7"></script>
<script src="js/PxLoader.js"></script>
<script src="js/PxLoaderImage.js?v7"></script>

<div class="editor-overlay-container cdk-overlay-container" id="overlay-container"  style="display: none" >
<div class="cdk-overlay-backdrop cdk-overlay-dark-backdrop cdk-overlay-backdrop-showing"></div>
<div class="cdk-overlay-connected-position-bounding-box" style="top: 0px; left: 0px; height: 100%; width: 100%;">
      
<div id="Save-Image" class="cdk-overlay-pane floating-panel" dir="ltr" style="display : none ; pointer-events: auto;top: 25px;width: 450px;padding: 15px;position: absolute;/* left: 524px; */margin-left: calc( 50% - 450px /2 );/* margin-right: auto; */">
         <Embed-image-panel>
            <h2 trans="">Download Image</h2>
			<div>
				<div style="height: 37px;" >
				   <div style="float: left;     font-size: 14px; line-height: 35px;"  >Select image quality :</div>
						
					<select class="form-control" id="Quality" style="float: left;width: 183px;margin-left: 20px;">
						<option value="100">Highest Quality</option>
						<option value="75" selected >High Quality</option>
						<option value="50" >Medium Quality</option>
						<option  value="25" >Low Quality</option>
					</select>
					 
				</div>
			</div>
		 
            <!---->
            <div class="buttons ng-star-inserted">
               <button onclick="Save()" color="primary" mat-flat-button="" trans="" class="mat-flat-button mat-primary">
                  <span class="mat-button-wrapper">Download</span>
                  <div class="mat-button-ripple mat-ripple" matripple=""></div>
                  <div class="mat-button-focus-overlay"></div>
               </button>

               <button color="primary" onclick=" $('#overlay-container , #Save-Image').fadeOut();" mat-stroked-button="" trans="" class="mat-stroked-button mat-primary">
                  <span class="mat-button-wrapper">Close</span>
                  <div class="mat-button-ripple mat-ripple" matripple="">
                  </div>
                  <div class="mat-button-focus-overlay"></div>
               </button>
            </div>
            <!---->
         </Embed-image-panel>
      </div>
	  
	  
	  
	  <div id="Embed-Image" class="cdk-overlay-pane floating-panel" dir="ltr" style=" display : none ; pointer-events: auto;top: 25px;width: 450px;padding: 15px;position: absolute;/* left: 524px; */margin-left: calc( 50% - 450px /2 );/* margin-right: auto; */">
         <Embed-image-panel>
            <h2 trans="">Embed This Image</h2>
			<div>
				<div>
				   <div class="dialogCaption">Copy this code to embed this image on your site:</div>
				   <div class="separator"><hr><span class="text" trans="">Full Image:</span></div>
				   <textarea id="FullImage" onclick="this.select()" readonly="readonly" class="embedPhoto500 form-control"></textarea>
		 
				   <div class="separator"><hr><span class="text" trans="">WordPress Shortcode : </span></div>
				   <textarea id="WordPress" onclick="this.select()" readonly="readonly" class="embedShortcode form-control"></textarea>
				   
				   <div class="separator"><hr><span class="text" trans="">API query link : </span></div>
				   <textarea id="API" onclick="this.select()" readonly="readonly" class="embedShortcode form-control"></textarea>
 				</div>
			</div>
            <!---->
            <div class="buttons ng-star-inserted">
               <button id="Download" onclick="window.open('files/<?= $file ?>.jpg')" color="primary" mat-flat-button="" trans="" class="mat-flat-button mat-primary">
                  <span class="mat-button-wrapper">Download image</span>
                  <div class="mat-button-ripple mat-ripple" matripple=""></div>
                  <div class="mat-button-focus-overlay"></div>
               </button>

               <button color="primary" onclick=" $('#overlay-container , #Embed-Image').fadeOut();" mat-stroked-button="" trans="" class="mat-stroked-button mat-primary">
                  <span class="mat-button-wrapper">Close</span>
                  <div class="mat-button-ripple mat-ripple" matripple="">
                  </div>
                  <div class="mat-button-focus-overlay"></div>
               </button>
            </div>
            <!---->
         </Embed-image-panel>
      </div>
   </div>
</div>
 <?php if ( $provider != '' ){ ?>
 <div class="source" >
 <a href="<?= $source ?>" target="_blank" ><?php print $provider ?> <?php if ( $user ){ print "/ $user" ; } ?><a>
 <button class="copy" onclick="Copy('<?php print $provider ?> <?php if ( $user ){ print "/ $user" ; } ?>');" >Copy</button>
 </div>
 <? } ?>
 <script>
	 var spinner = document.querySelector('.global-spinner');
	var ImageData ; 
    var pixie = new Pixie({
        image: 'files/<?= $file ?>.jpg',
        onSave: function(data, name) {
		 ImageData = data ; 	
		 $('#overlay-container , #Save-Image').fadeIn();		
		}
    });
	
	var id = <?= $file ?>  ;
	
	function Save(){
		spinner.style.display = 'flex';
		 $.ajax({
		   type: "POST",
		   url: "Image/Update",
		   dataType: "json",
		   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		   data : {  compress : $('#Quality').val() ,data : ImageData , id : id },
		   cache: false,
		   async: true,
		   success: function(data) {
			   
				id = data.id ; 
				
				DownloadImage('files/'+ id +'.jpg')
				 
			   
				$('#overlay-container , #Save-Image').fadeOut();	
				 $('.embed-button').remove();
				$( ".export-button" ).after( '<button onclick="ShowEmbed()" class="embed-button mat-button ng-star-inserted" mat-button=""><span class="mat-button-wrapper"><svg style=" width: 24px; fill: #fff;" enable-background="new 0 0 30 30" height="30px" id="Embed" version="1.1" viewBox="0 0 30 30" width="30px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon id="_x3E_" points="30,13.917 19,7 19,10 27,15 19,20 19,23 30,15.959 "/><polygon id="_x3C_" points="0,13.917 11,7 11,10 3,15 11,20 11,23 0,15.958 "/></svg> <span class="name" trans="">Embed</span></span><div class="mat-button-ripple mat-ripple" matripple=""></div><div class="mat-button-focus-overlay"></div></button>');
				
				$('#Download').attr('onclick' , "DownloadImage('files/"+ id +".jpg')") ;
				$('#FullImage').val("<div><a href='{{url('/')}}/files/"+ id +".jpg' target='_blank'><img src='{{url('/')}}/files/"+ id +".jpg' alt='' border=0 width='500' height='368' nopin='nopin' ondragstart='return false;' onselectstart='return false;' oncontextmenu='return false;'/></a></div><div style='color:#444;'><small>"+ id +"</small></div>") ;
				$('#WordPress').val("[img src='{{url('/')}}/files/"+ id +".jpg' link_to_img='yes']");
				$('#API').val("{{url('/')}}/api/" + id );
				history.pushState({}, 'Edit image',  "{{url('/')}}/Image/Edit/" + id );
				spinner.style.display = 'none';
		  },
		   error: function(xhr, status, error) {
			   spinner.style.display = 'none';
		   }
		});
        }
	$('.nstSlider').nstSlider({
		"left_grip_selector": ".leftGrip",
		"value_changed_callback": function(cause, leftValue, rightValue) {
			$(this).parent().find('.leftLabel').text(leftValue);
		}
	});
	
	
	function ShowEmbed(){
			$('#overlay-container').fadeIn();	
			$('#Embed-Image').fadeIn();
	}
	function DownloadImage(url){
		    var a = document.createElement('a');
				a.href = url ;
				a.download = 'image.jpg';
				document.body.appendChild(a);
				a.click();
				document.body.removeChild(a);
	}
	 
	 if ( id != 0 ){
		setTimeout(function(){
			$( ".open-button" ).before( '<button style="padding-left: 0px;" onclick="parent.Back();" class="embed-button mat-button ng-star-inserted" mat-button=""><span class="mat-button-wrapper"><svg style="fill : #fff" height="32" viewBox="0 0 48 48" width="32" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h48v48h-48z" fill="none"/><path d="M40 22h-24.34l11.17-11.17-2.83-2.83-16 16 16 16 2.83-2.83-11.17-11.17h24.34v-4z"/></svg> <span class="name" trans="">Back</span></span><div class="mat-button-ripple mat-ripple" matripple=""></div><div class="mat-button-focus-overlay"></div></button>');
		},500); 
	 }else{
		setTimeout(function(){
			$( ".open-button" ).before( '<button style="padding-left: 0px;"  onclick="Search();" class="embed-button mat-button ng-star-inserted" mat-button=""><span class="mat-button-wrapper"><svg style="fill : #fff" height="24px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g transform="translate(576 192)"><path d="M-544.88-165.121l-7.342-7.342c-1.095,1.701-2.541,3.148-4.242,4.242l7.343,7.342c1.172,1.172,3.071,1.172,4.241,0   C-543.707-162.048-543.707-163.947-544.88-165.121z"/><path d="M-552-180c0-6.627-5.373-12-12-12s-12,5.373-12,12s5.373,12,12,12S-552-173.373-552-180z M-564-171c-4.964,0-9-4.036-9-9   c0-4.963,4.036-9,9-9c4.963,0,9,4.037,9,9C-555-175.036-559.037-171-564-171z"/><path d="M-571-180h2c0-2.757,2.242-5,5-5v-2C-567.86-187-571-183.858-571-180z"/></g></svg><span padding-left: 7px; class="name" trans="">Search</span></span><div class="mat-button-ripple mat-ripple" matripple=""></div><div class="mat-button-focus-overlay"></div></button>');
		},500); 

	 }
	spinner.style.display = 'none';
	function Search(){
			window.location = "{{url('/')}}/" ;
	}
 
	function Copy(text) {
	  var $temp = $("<input>");
	  $("body").append($temp);
	  $temp.val(text).select();
	  document.execCommand("copy");
	  $temp.remove();
	}
	
</script>
</body>
</html>