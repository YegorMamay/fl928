<?php get_header(); ?>
<div class="container">

<?php if (function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>

<h1 class="text-center"><?php /*_e('Category', 'brainworks');*/ ?>#<?php echo single_cat_title(); ?></h1>

<div class="vh-xs-2"></div>
<hr>
<div class="vh-xs-2"></div>

<?php get_template_part('loops/content', get_post_format()); ?>

<?php  the_archive_description( '<div class="term-description">', '</div>' );  ?>
<div class="vh-xs-2"></div>

</div><!-- /.container -->
<?php get_footer(); ?>
