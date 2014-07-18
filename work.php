<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>Our work | Up Left Studios</title>
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
	  <script src="js/jquery.smooth-scroll.js"></script>


	  <script>
    $(document).ready(function() {

      $('ul.mainnav a').smoothScroll();

      $('p.subnav a').click(function(event) {
        event.preventDefault();
        var link = this;
        $.smoothScroll({
          scrollTarget: link.hash
        });
      });

      $('#change-speed').on('click', function() {
        var $p1 = $('ul.mainnav a').first(),
            p1Opts = $p1.smoothScroll('options') || {};

        p1Opts.speed = p1Opts.speed === 1400 ? 400 : 1400;
        $p1.smoothScroll('options', p1Opts);
      });

      $('button.scrollsomething').click(function() {
        $.smoothScroll({
          scrollElement: $('div.scrollme'),
          scrollTarget: '#findme'
        });
        return false;
      });
      $('button.scrollhorz').click(function() {
        $.smoothScroll({
          direction: 'left',
          scrollElement: $('div.scrollme'),
          scrollTarget: '.horiz'
        });
        return false;
      });

    });

  </script>


</head>
<body class="background">

<a href="index.php"><div id="logo" class="pin">

</div></a><!--end logo div -->

	<!-- Primary Page Layout
	================================================== -->

<div id='cssmenu'>
<ul>
   <li><a href='index.php'><span>Home</span></a></li>

   <li><a class="current" href='work.php'><span>Work</span></a></li>
      
   <li><a href='about.php'><span>About</span></a></li>


   <li><a href='blog'><span>Blog</span></a></li>
</ul>
</div>


<div style="background-color: #f2f2f2;" class="full bg">



	<div class="container">
		
		<div class="fourteen columns main-section first">
		<h1>Work		<img src="images/process.png">
</h1>
			<p style="padding-bottom:40px;" class="caption">Illustration and digital stuff. We're working on it.</p>
			
		</div><!--end fourteen columns-->
		</div><!--end container-->
	<div class="container">
		<div id="nav" class="sixteen columns alpha">
		

<ul class="mainnav">
      <li><a href="#p1"><img class="left pin-link" src="images/thumb-illustration-joe.png"></a></li>
      <li><a href="#p2"><img class="pin-link" src="images/thumb-dragon-sketch.gif"></a></li>
      <li><a href="#p3"><img class="pin-link" src="http://www.placehold.it/100x100"></a></li>
     <li><a href="#p4"><img class="pin-link" src="http://www.placehold.it/100x100"></a></li>

      
    </ul>
<hr>
<section id="destination1">
<h3 id="p1">Joe in danger</h3>
<img class="pin scale-with-grid" src="images/process-5-joe.png">
<p class="date">June, 2014</p>
<p>Produced for a recent illustration challenge.</p>
</section> 
<hr>
<section id="destination2">
<h3 id="p2">Dragons</h3> 
<img class="pin scale-with-grid" src="images/dragon-sketch.jpg">
<p class="date">March, 2014</p>
<p >Who doesn't love a monster/dragon/creepy crawly? Only very sad people, I think.</p>
</section>
<hr>
<section id="destination3">
<h3 id="p3">Placeholder</h3> 
<img class="pin scale-with-grid" src="http://www.placehold.it/800x400">
<p class="date">July, 2014</p>
<p>Some placeholder text</p>
</section>
<hr>
		</div><!--end ten columns div -->
		
		</div><!--end container div -->
	
</div>
	<footer>
			<div class="container">
				<div class="row">
				<div class="five columns alpha">
					<h4>Contact me</h4>
					<p>If you have an idea, a project, a question or something else, I'd love to hear from you</p>
						<ul>
							<li><i class="fa fa-phone"></i>   0422 238 578</li>
							<li><i class="fa fa-envelope"></i>    <a href="mailto:kristen@upleftstudio.com">hello@kristenhardy.com</a></li>
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