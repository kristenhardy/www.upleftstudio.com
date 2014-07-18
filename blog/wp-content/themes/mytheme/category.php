<?php get_header(); ?>
<div style="background-color: #f2f2f2;" class="full">		
		<div class="container">
<div class="sixteen columns first bg">
<h1><?php single_cat_title(); ?></h1>
<?php if (is_category('reading-list')) : ?>
<p class="caption">You're reading about all the things I've read</p>
<?php elseif (is_category('app-for-kids')) : ?>
<p class="caption">A timeline of six months. Let's build a story app for kids!</p>
<?php elseif (is_category('animation')) : ?>
<p class="caption">We love animation. It's magical and takes far too much time to do well.<br/>We mostly write about animations by other folk.</p>
<?php elseif (is_category('i-dont-know')) : ?>
<p class="caption">We're as confused as you are</p>
<?php elseif (is_category('inspiration')) : ?>
<p class="caption">Other people's work. Inspiring and jealousy-provoking.</p>
<?php elseif (is_category('process')) : ?>
<p class="caption">How we do what we do</p>
<?php else : ?>
<p class="caption">This is some generic text to describe all other category pages, 
I could be left blank</p>
<?php endif; ?>
</div><!--end sixteen columns div-->
<div class="ten columns alpha">				
<?php 
$args = array ( 'category' => ID, 'posts_per_page' => 1);
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
<?php if (is_category('reading-list')) : ?>
<p>OK. So I haven't actually written anything yet about what I'm reading. Or I wrote it, then <stroke>the dog ate it</stroke> <stroke>the cat ate it</stroke> <stroke>I ate it</stroke>.</p>
<p>I'll write something about what I'm reading really soon.</p> 
<?php elseif (is_category('app-for-kids')) : ?>
That's all I've written so far about the iPhone app. Would you like to know more? Great. Please <a href="#">get in touch</a>.</p> 
<?php elseif (is_category('animation')) : ?>
<p class="small">That's all I've written so far about animation.</p> 
<?php elseif (is_category('i-dont-know')) : ?>
<p class="small">That's all I've written so far about things that I couldn't categorize very clearly.</p> 
<?php elseif (is_category('inspiration')) : ?>
<p class="small">That's all I've written so far about inspiring stuff.</p> 
<?php elseif (is_category('process')) : ?>
<p class="small">That's all I've written so far about my process.</p> 
<?php else : ?>
<p class="small"></p>
<?php endif; ?>
</div><!--end ten columns div-->
				



<?php get_sidebar(); ?>

<?php get_footer(); ?>								
							
