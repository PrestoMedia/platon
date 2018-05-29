<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Mobile Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- Site Metas -->
      <title>Presto Media | Universal Media Search</title>

	  <meta name="csrf-token" content="{{ csrf_token() }}">
	  <base href="{{url('/')}}/" target="_self">
	  
	  <link rel="icon" href="img/favicon.png"  type="image/png">
	  <link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
 	  
      <!-- Site Icons -->
       <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="css/bootstrap.min.css?v=4">
      <!-- Site CSS -->
      <link rel="stylesheet" href="css/style.css?v=4">
      <!-- theme_preview -->
      <link rel="stylesheet" href="css/landing.css?v=4">
      <!-- Responsive CSS -->
      <link rel="stylesheet" href="css/responsive.css?v=4">
      <!-- Colors CSS -->
      <link rel="stylesheet" href="css/colors.css?v=4">
      <!-- Custom CSS -->
      <link rel="stylesheet" href="css/custom.css?v=4">
      <!-- Wow Animation CSS -->
      <link rel="stylesheet" href="css/animate.css?v=4">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <body PageType="<?= $type ?>"  <?php if (  $type == 'filter' ){ print "filter='$filter'" ; } ?>    query="<?= $query ?>" page="<?= $page ?>"  class="results_page">
      <div class="bg_load" style="z-index:9999;">
         <div class="verticle-center"><img class="loader_animation" src="img/loader_1.png" alt="#" /></div>
      </div>
	  
	  
	  
  <div id="dark-edit" >
		<div class="close-edit" ></div>
		<div class="edit-box" >
		 
		</div>
	  </div> 
	  
	  
	  
	  
	  
	  
	  <div id="dark-box" class="clo" >
		<div class="close-box clo" ></div>
		<div class="image-box" >
			<div class="image-box-header" >
				<div class="provider" >
					<img  src="img/flickr.jpg" >  
					<div>		
						
						<span id="image-box-provider" >provider</span>
						<span id="image-box-size">width : 0px ; height : 0px</span>
					</div>
				</div>
				<div>
					<a id="download" href="" download="image.png"  target="_blank" class="NewSearch" > Download </a>
					<a id="edit" href="javascript:Edit()" class="NewSearch"> Edit </a>
				</div>
				<div id="image-box-name" >
					Image name 
				</div>
			</div>
			<div class="image-box-image" >
				<img src="images/blank.png" ></img>
			</div>
		</div>
	</div> 
	  
	  
	  
	  <div id="dark-filter" >
		<div class="close-filter" ></div>
		<div class="filter-box" >
			<div class="image-box-header" >
				<div id="image-box-name" >
					Results filter .
				</div>
			</div>
			<div class="filter-body" >
				Select provider
				<div provider="flickr" class="select-provider" >
					<img src="img/flickr.jpg" /> flickr
				</div>
				<div provider="wikimedia" class="select-provider" >
					<img src="img/wikimedia.jpg" /> wikimedia
				</div>
				<div provider="pixabay" class="select-provider" >
					<img src="img/pixabay.jpg" /> pixabay
				</div>
				<div provider="unsplash" class="select-provider" >
					<img src="img/unsplash.jpg" /> unsplash
				</div>
				<div provider="giphy" class="select-provider" >
					<img src="img/giphy.jpg" /> giphy
				</div>
				

			</div>
			<div class="filter-footer" >
				<a href="javascript:Filter()" class="NewFilter">Filter</a>
			</div>
		</div>
	  </div> 
	  
	  
	  
	  
	  
	  
	  <header>
			<a href="{{url('/')}}/" ><img src="img/logo_purple.png" class="logo" ></img></a>
			<div class="user" >
				Anonymous
				<img src="avatar/0.jpg" />
			</div>	
				<div class="editor" >
					<a href="Image/Edit/0" > Use Image Editor</a> 
				</div>
				<div class="hero-search">
					   <!-- Html Elements for Search -->
					   <div class="position-relative">
						  <form autocomplete="off" class="search search-default width-1-1" name="search-hero" onsubmit="return false;">
							 <span class="search-icon icon"><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" ratio="1"><circle fill="none" stroke="#000" stroke-width="1.1" cx="9" cy="9" r="7"></circle><path fill="none" stroke="#000" stroke-width="1.1" d="M14,14 L18,18 L14,14 Z"></path></svg></span>
							 <input value="<?=$text ?>" autocomplete="off" id="hero-search" class="search-input" type="search" placeholder="Search media...">
							 <span class="search-filter icon"> <span class="selected-provider" id="search-provider" > <?= $providers ?> </span><img src="img/filter.png" />  </span>
						  </form>
						  <div id="hero-search-completed"  >
							<a href="#">
								<img src="avatar/0.jpg" >
								<user>
									User name  <br>
								<time>00/00/0000 00:00:000</time>
								<div class="keyword" >Search completed open results [ <span id="search-keyword" >keyword</span> ]  </div> 
								</user>
								</a>
						  </div>
						  <div id="hero-search-repository"  >
							New search [ <span id="search-keyword" >keyword</span> ]   [ <span id="search-provider" >keyword</span> ] 
							<div class="progress_container">
								<div class="progress progress-danger progress-striped active">
								  <div class="bar" id="search-bar" style="width: 1%;"></div>
								</div>
							</div>
							<div>
								Searching <span id="search-in" >start</span> <span id="search-percent" >1</span>%
							</div>
						  </div>
						  <ul id="hero-search-results" class="position-absolute width-1-1 list">
						   <script id="FindTemplate" type="text/x-handlebars-template">
                           @{{#each this}}
						    
							<li>
								@{{#xif  " id == '0' " }}
								<a href="javascript:Search()">
								@{{/xif}}
								@{{#xif  " id != '0' " }}
								<a href="javascript:Results(@{{id}})">
								@{{/xif}}
									<user>
  										<div class="keyword" >@{{query}}</div> 
										<div class="provider" > [ @{{provider}} ] @{{#xif  " count != '0' " }} @{{count}} Results @{{/xif}}</div> 
									</user>
								</a>
							</li>
							
						  @{{/each}}
                          </script>
						  </ul>
						  
						<ul id="hero-search-filter" class="position-absolute  list">
							<li provider="flickr" class="search-provider" >
								 <a href="javascript:SelectSearch('flickr')" > flickr </a>
							</li>
							<li provider="wikimedia" class="search-provider" >
								 <a href="javascript:SelectSearch('wikimedia')"> wikimedia </a>
							</li>
							<li provider="pixabay" class="search-provider" >
								<a  href="javascript:SelectSearch('pixabay')">  pixabay </a>
							</li> 
							<li provider="unsplash" class="search-provider" >
								<a href="javascript:SelectSearch('unsplash')"> unsplash</a>
							</li>
							<li provider="unsplash" class="search-provider" >
								<a href="javascript:SelectSearch('giphy')"> giphy</a>
							</li>
							<li provider="All providers" class="search-provider" >
								<a href="javascript:SelectSearch('All providers')">All providers</a>
							</li>

							
                          </script>
						  </ul>
						  
					   </div>
					</div>
		
		
		
	  </header>

	  
	  
      <div id="wrapper">  
		  <div id="photo-results" >
			<div><?= $text  ?></div>
			<div class="photo-results-details" > <span id="search-count" >0</span> Photo  |   <?php if ( $providers != 'All providers' && $filter != '' ){ print "providers [ $filter ]"; } else { print $providers ;  } ?>    </div>
			<?php if ( $providers == 'All providers' ){ ?>
			<div class="show-results-filter" ></div>
			<? } ?>
		  </div>
          <div id="photos" class="grid">
	 
				<script id="MainTemplate" type="text/x-handlebars-template">
				@{{#each this}}	
					<div id="@{{id}}" class="grid-item one-four"  onclick="ShowDialog(@{{id}})"  >
 						
						<div class="title" >
							@{{#xif  " name != '' " }}
								@{{name}}
						    @{{/xif}}
							@{{#xif  " width != '0' " }}
							<div class="data" >
								Width : @{{width}} -  Height : @{{height}}
							</div>
							 @{{/xif}}
						</div>
						

						<div class="provider" >
							<img  src="img/@{{provider}}.jpg" /> @{{provider}}
						</div>
						<img class="photo" url="@{{url}}" user="@{{user}}"  id="@{{id}}"   source="@{{src}}"   height="@{{height}}" width="@{{width}}" provider="@{{provider}}" name="@{{name}}" src="@{{thumbnail}}" />
					</div>
				@{{/each}}
				</script>
			
			</div>
       </div>
 
	  <a id="Show-More" href="javascript:NextPage();" class="btn btn-lg clear-gray ml-2" target="_blank">Load more</a>
 
 
	  <footer>
			&copy;	 2018 Presto Media. All rights reserved.
	  </footer>
	  
 
	  
      <script src="js/jquery.min.js?v=4"></script>
      <script src="js/bootstrap.min.js?v=4"></script>
	  <script src="js/handlebars.min.js?v=4"></script>
	  <script src="js/handlebars-helper.js?v=4"></script>
	  <script src="js/jquery.fileDownload.js?v=4"></script>
 
 
	  <script src="js/imagesloaded.pkgd.min.js"></script>
	  <script src="js/masonry.pkgd.min.js"></script>
      <script src="js/custom.js?v=4"></script>

   </body>
</html>