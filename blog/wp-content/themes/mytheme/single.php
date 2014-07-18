<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
<div style="background-color: #f2f2f2;" class="full">		
		<div class="container">
	<div class="sixteen columns first">


			


<!-- WordPress Loop -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="entry <?php if(is_home() && $post==$posts[0] && !is_paged()) echo ' firstpost';?>">
<h1 class="entrytitle" id="post-<?php the_ID(); ?>"> <?php the_title(); ?></h1>
</div><!--end entrytitle div -->
</div><!--end sixteen columns div -->
</div><!--end container div -->
<div class="container">
<div class="sixteen columns">
<div class="ten columns alpha">
<p class="date"> <?php the_time('F jS') ?>, <?php the_time('Y') ?></p>
<div class="caption">
<?php the_excerpt(); ?>
</div><!--end caption div-->
<div class="entrybody">
<?php the_content(__('Read more'));?>

<p class="small">Published in <span class="tag"><?php the_category(' &bull; '); ?></span></p>
<p class="blogtags"><?php the_tags(); ?> </p> 
<hr class="nav-hr">
</div><!--end entrybody div -->
<?php edit_post_link(__('<strong>Edit</strong>'));?>



<?php endwhile; else: ?>
<p> <?php _e('Sorry, no posts matched your criteria.'); ?> </p>
<?php endif; ?>
<div class="goback"><a href="http://localhost/www.upleftstudio.com/blog"><p><i class="fa fa-chevron-left"></i> See all of my blog posts</p></a></div>



<!-- End WordPress Loop -->
</div>


<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>