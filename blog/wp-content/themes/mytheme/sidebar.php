<div style="padding-top:50px;" class="four columns offset-by-one list omega">
<!--the following code creates a list of all post categories, wrapped in a ul > li -->
<h4>View by topic</h4>
<?php
$args = array(
  'orderby' => 'name',
  'order' => 'ASC'
  );
$categories = get_categories($args);
  foreach($categories as $category) { 
    echo '<a class="tag" href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "%s" ), $category->name ) . '" ' . '>' . $category->name.' </a>';  } 
?>
<hr>
<div class="four columns">
<?php if (is_category('reading-list')) : ?>
<p class="caption"></p>
<?php elseif (is_category('app-for-kids')) : ?>
<p class="small"><i class="fa fa-calendar"></i> This project started in July 2014.</p>
<?php elseif (is_category('animation')) : ?>
<p class="caption"></p>
<?php elseif (is_category('i-dont-know')) : ?>
<p class="caption"></p>
<?php elseif (is_category('inspiration')) : ?>
<p class="caption"></p>
<?php elseif (is_category('process')) : ?>
<p class="caption"></p>
<?php else : ?>
<p class="caption"></p>
<?php endif; ?>
</div>
</div>
</div>
