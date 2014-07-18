<?php get_header(); ?>
<div class="full bg-iphone">		
		<div class="container">
<div class="sixteen columns first">
<h1 style="color:#fff;">App for kids</h1>
<p class="caption">A scary adventure. Some silly games. And a hairy princess or two.</p>
</div>
<div class="container first">
<div class="ten columns alpha">
<img style="margin-top:60px;" class="pin scale-with-grid" src="http://www.placehold.it/550x140">
<?php 
$args = array ( 'category' => 'app-for-kids', 'posts_per_page' => 1);
$myposts = get_posts( $args );
foreach( $myposts as $post ) :	setup_postdata($post);
 ?>
<?php if ( have_posts() ) : while ( have_posts() ) :
the_post(); ?>
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a><br />
</h2>
<p class="date"><?php the_date(); ?></p>
<?php the_content(); ?>
<hr>
<?php endwhile; else: ?>
<p>There are no posts or pages here.</p>
<?php endif; ?>
<?php endforeach; ?>
<p class="small">That's all I've written so far about the iPhone app. Would you like to know more? Great. Please <a href="#hello">get in touch</a>.</p> 
</div>
				
								
							
		
			

<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>