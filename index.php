<?php 
/* Short and sweet */
define('WP_USE_THEMES', false);
require('blog/wp-blog-header.php');
?>


<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>Up Left Studio | Illustration and other things</title>
	<meta name="description" content="A very tiny studio run by Kristen Hardy in Melbourne, Australia. We make digital stuff, illustrations and tiny animations. A little bit of creative stuff and a little bit of commercial stuff.">
	<meta name="author" content="">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">
	<link rel='stylesheet' href='stylesheets/styles.css' /> 
	<link rel="stylesheet" href="stylesheets/font-awesome.min.css">
	


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/assets/favicon.ico">
	<link rel="apple-touch-icon" href="images/assets/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/assets/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/assets/apple-touch-icon-114x114.png">



	<!-- javascript stuff
	=================================================== -->
	
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
	<script type='text/javascript' src='js/menu_jquery.js'></script>
	<script type="text/javascript" src="js/jquery.jcarousel.min.js"></script>
	<script type="text/javascript" src="js/jquery.pikachoose.min.js"></script>
	<script type="text/javascript" src="js/jquery.touchwipe.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	

	<script>
	$(document).ready(function(){
    $('a[href*="#"]').click(function(){
        $($(this).attr("href")).effect("highlight", {color: '#ec0778'}, 2000);
    });
});
</script> 


			
							
	


</head>

<body class="background">



	<!-- Primary Page Layout
	================================================== -->


<a href="index.php"><div id="logo" class="pin">

</div></a><!--end logo div -->

<div id='cssmenu'>
<ul>
<li><a class="current" href='index.php'><span>Home</span></a></li>
   <li><a href='work.php'><span>Work</span></a></li>
      
   <li><a href='about.php'><span>About</span></a></li>


   <li><a href='blog'><span>Blog</span></a></li>
</ul>
</div>

<div style="background-color: #f2f2f2;" class="full bg">		
		<div class="container">
		
		
		
		
				<div class="eight columns first alpha">
				<h1>Digital stuff, illustration</h1>
				<p class="caption">A little bit of this & that</p>
								
								
								</div><!--end eight columns-->
					<div class="eight columns omega">			
								<img src="images/assets/girl.gif">
								</div><!--end eight columns omega-->
		
		</div><!--end container div-->
	</div><!-- end full div -->
	
<div class="full" style="background-color:#387dc5;">		
		<div class="container">
		<div class="eight columns alpha">
		<h2>What we're working on</h2>

		
		<div class="imagecontainer">
			<div class="image">
			<a href="blog/app-for-kids/">
			<img class="pin-link scale-with-grid" src="http://placehold.it/550x300"></a>
			</div><!--end image div -->
			<a href="blog/app-for-kids/"><div class="play2"><i class="fa fa-arrow-circle-right"></i> Find out more </div></a>
			</div><!--end imagecontainer div -->
				<p style="color: #fff;" class="caption">Right now, we're in the studio working on a kids' app. </p>
<hr>
<h3>Recent illustrations</h3>
<a href="work.php#p1"><img class="pin-link" src="images/thumb-illustration-joe.png"></a>
<a href="work.php#p2"><img class="pin-link" src="images/thumb-dragon-sketch.gif"></a>
<a href="work.php#p3"><img class="pin-link" src="http://www.placehold.it/100x100"></a>
<hr>

		<p>Have a project in mind? Let's <a style="color:#fff;" href="#hello">have a chat</a>.</p>	

		</div><!--end eight columns alpha -->
		
				<div id="move" class="five columns pin  omega">
								<h3>The latest from the blog</h3>
		
		
		
				
				<?php
				global $post;
				$args = array( 'posts_per_page' => 1 );
				$myposts = get_posts( $args );
				foreach( $myposts as $post ) :	setup_postdata($post); ?>
				<h4 style="font-weight:bold;"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4><br />
				<p class="date"><?php the_date(); echo "<br />"; ?></p>
				<p><?php the_excerpt(); ?></p>
				<p class="small"><a href="<?php the_permalink() ?>">Read more</a></p>
				
				
				<?php endforeach; ?>
		</div><!--five columns div-->
		
		</div>
	
	
	
	
	
		



		
		
		
			

	


<footer>
		<div class="container">
			<div class="row">
			<div id="hello" class="five columns alpha" style="padding:20px;">
				<h4>Contact me</h4>
				<p>If you have an idea, a project, a question or something else, I'd love to hear from you</p>
					<ul>
						<li><i class="fa fa-phone"></i>   0422 238 578</li>
						<li><i class="fa fa-envelope"></i>    <a href="mailto:kristen@upleftstudio.com">kristen@upleftstudio.com</a></li>
						<li><i class="fa fa-twitter"></i>  <a href="http://www.twitter.com/kristen_hardy">@kristen_hardy</a></li>
						
					</ul>
			</div>


		</div>

	</div>
</div>
</footer>



<!-- End Document
================================================== -->
</body>
</html>