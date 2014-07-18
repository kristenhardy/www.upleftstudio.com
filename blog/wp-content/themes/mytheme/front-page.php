<?php get_header(); ?>
<div style="background-color: #f2f2f2;" class="full">		
		<div class="container">
<div class="ten columns alpha">
<?php
  // set up or arguments for our custom query
  $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
  $query_args = array(
    'post_type' => 'post',
    'posts_per_page' => 5,
    'paged' => $paged
  );
  // create a new instance of WP_Query
  $the_query = new WP_Query( $query_args );
?>
<div class="sixteen columns first">
<h1>Blog</h1>
<div class="ten columns">
		<p class="caption">Projects, people, places, nice things. We'll write about that sort of stuff here.</p>
		</div>
		</div>
		</div><!--close container -->
		<div class="container">
<div class="ten columns alpha">
<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); // run the loop ?>


  <article>
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
   <p class="date"><?php the_time(get_option('date_format'));?></p>
    <p class="caption">
      <?php the_excerpt(); ?>
    </p>
    <p class="small"><a href="<?php the_permalink() ?>" rel="bookmark" title="Read more">Read the article</a></p>
    <hr>

  </article>
<?php endwhile; ?>

<?php if ($the_query->max_num_pages > 1) { // check if the max number of pages is greater than 1  ?>
  <nav class="prev-next-posts">
    <div class="prev-posts-link">
      <?php echo get_next_posts_link( 'Older posts', $the_query->max_num_pages ); // display older posts link ?>
    </div>
    <div class="next-posts-link">
      <?php echo get_previous_posts_link( 'Newer posts' ); // display newer posts link ?>
    </div>
  </nav>
<?php } ?>

<?php else: ?>
  <article>
    <h1>Sorry...</h1>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
  </article>
<?php endif; ?>
<!-- Add the pagination functions here. -->
				</div><!--end ten columns div-->
				

							
		






			

<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>