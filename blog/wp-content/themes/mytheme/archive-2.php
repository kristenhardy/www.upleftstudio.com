<?php get_header(); ?>
<div style="background-color: #f2f2f2;" class="full">		
		<div class="container">
<div class="sixteen columns bg">
<h2>ARCHIVES</h2>
<p class="caption">All here, in chronological order</p>
</div><!--end sixteen columns div-->
<div class="ten columns alpha">				
<?php 
$args = array ( 'category' => ID, 'posts_per_page' => 1);
$myposts = get_posts( $args );
foreach( $myposts as $post ) :	setup_postdata($post);
 ?>

<?php if ( have_posts() ) : while ( have_posts() ) :
the_post(); ?>
<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a><br />
</h3>
<p class="date"><?php the_date(); ?></p>
<?php the_content(); ?>
<hr>
<?php endwhile; else: ?>
<p>There are no posts or pages here.</p>
<?php endif; ?>
<?php endforeach; ?>

</div><!--end ten columns div-->
				


<?php get_sidebar(); ?>

<?php get_footer(); ?>								
							
