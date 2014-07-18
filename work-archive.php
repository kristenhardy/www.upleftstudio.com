<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>Work | Up Front Studios </title>
	<meta name="description" content="">
	<meta name="author" content="">
	

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">
	<link rel='stylesheet' href='stylesheets/styles.css' /> <!--this styles the global navigation-->
	<link rel="stylesheet" href="stylesheets/font-awesome.min.css">


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">



	<!-- javascript stuff
	=================================================== -->
	
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<script type='text/javascript' src='js/menu_jquery.js'></script>


</head>


	<!-- javascript stuff
	=================================================== -->
	
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
		<script type="text/javascript" src="js/gallery.js"></script>
		<script src="../js/jquery.shuffle.min.js"></script>
			<script type='text/javascript' src='js/menu_jquery.js'></script>
		
	
	
	<!--this is for the gallery shuffle stuff -->
	<script>
			$(document).ready(function() {
	
				/* initialize shuffle plugin */
				var $grid = $('#grid');
	
				$grid.shuffle({
					itemSelector: '.item' // the selector for the items in the grid
				});
	
				/* reshuffle when user clicks a filter item */
				$('#filter a').click(function (e) {
					e.preventDefault();
	
					// set active class
					$('#filter a').removeClass('active');
					$(this).addClass('active');
	
					// get group name from clicked item
					var groupName = $(this).attr('data-group');
	
					// reshuffle grid
					$grid.shuffle('shuffle', groupName );
				});
	
			});
		</script>
		
		
	
		
<body>	

<a href="index.php"><div id="logo" class="pin">

</div></a><!--end logo div -->

	<!-- Global navigation
	================================================== -->
<div id='cssmenu'>
<ul>
   <li><a class="current" href='work.php'><span>Work</span></a></li>
      
   <li><a href='about.php'><span>About</span></a></li>


   <li><a href='blog/index.php'><span>Blog</span></a></li>
</ul>
</div>



<!-- Primary Page Layout
================================================== -->
<div class="bg-image">
	<div class="container">
		
		
				</div><!--end container-->
		</div><!-- end bg-image-->


	
<div class="container">

<h2>My recent work</h2>
<p class="caption">This is the caption about hte work I do, and the things I think.</p>
			
<div style="padding-top: 40px;" class="sixteen columns">
<!--start gallery-->
<div id="gallery">


<div class="sixteen columns">
<div class="three columns">	

<ul id="filter">
			<li><a class="active tag pink" href="#" data-group="all">All</a></li>
			<li><a class="tag purple" href="#" data-group="red">Kid stuff</a></li>
			<li><a class="tag orange" href="#" data-group="green">Digital artwork</a></li>
			<li><a class="tag lime" href="#" data-group="letters">UX</a></li>
			<li><a class="tag blue" href="#" data-group="blue">Mostly by hand</a></li>
			<li><a class="tag navy" href="#" data-group="numbers">Politics</a></li>
			<li><a class="tag lime" href="#" data-group="letters">Comics</a></li>
		</ul>


</div><!--end three columns -->

<div class="eleven columns offset-by-one">

		<div id="grid">
			<div class="item blue pin"  data-groups='["all", "numbers", "blue", "square"]'><a href="portfolio-kids-stuff.html"><img src="images/portfolio-thumb-1.png">blah blah blah pig's bottom</a></div>
			<div class="item green pin" data-groups='["all", "numbers", "green", "square"]'>blah blah blah blah blah blah blah blah blah blah</div>
			<div class="item blue pin"  data-groups='["all", "letters", "blue", "square"]'>blah blah blah blah blah blah blah blah blah blah</div>
			<div class="item red pin"   data-groups='["all", "numbers", "red", "square"]'>blah blah blah blah blah blah blah blah blah blah</div>
			<div class="item red circle pin"   data-groups='["all", "numbers", "red", "circle"]'>blah blah blah blah blah blah blah blah blah blah</div>
			<div class="item red pin"   data-groups='["all", "numbers", "red", "square"]'>blah blah blah blah blah blah blah blah blah blah</div>
			<div class="item green circle pin" data-groups='["all", "numbers", "green", "circle"]'>blah blah blah blah blah blah blah blah blah blah</div>
			
		</div>
</div><!--end ten columns -->
<hr>		
<h4>What I'm working on</h4>
<p>I'm working on a kids' story app. Follow along with my progress on <a href="#">my blog</a>.</p>
<hr>
<h4>My process</h4>
<img class="left" src="images/about-me.png">
<p>Process, mediums and some photos of my new Wacom tablet (I'm pretty excited about it).</p>
<hr>
	</div>
	<!-- /#container -->
	</div>



</div>
<!-- end gallery-->

</div>
		
</div><!--end sixteen div -->
</div><!--end container div-->


		
		</body>
	<footer>
			<div class="container">
				<div class="row">
				<div class="five columns alpha">
					<h4>Contact me</h4>
						<ul>
							<li>my phone number: 0422 238 578</li>
							<li>my email: hello@kristenhardy.com</li>
						</ul>
				</div>
			<div class="three columns alpha">
				<h4>About me</h4>
				<p>I like drawing ideas.</p>
			</div>
			<div class="three columns omega">
				<h4>Social media</h4>
					<ul>
						<li><img class="left" src="images/twitter.gif">Twitter</li>
	
					</ul>
			</div>
	
		</div>
	</div>
	</footer>
	
	
	
	<!-- End Document
	================================================== -->
	
	</html>