<?php get_header(); ?>
<p>This is the front-­‐page.php</p>
<?php if ( have_posts() ) : while ( have_posts() ) :
the_post(); ?>
<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a><br />
</h3>
<?php the_content(); ?>
<hr>
<?php endwhile; else: ?>
<p>There are no posts or pages here.</p>
<?php endif; ?>
<!-- Add the pagination functions here. -->

<div class="nav-previous alignleft"><?php next_posts_link( 'Older posts' ); ?></div>
<div class="nav-next alignright"><?php previous_posts_link( 'Newer posts' ); ?></div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>