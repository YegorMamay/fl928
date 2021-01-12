<?php get_header(); ?>

<div class="container">
<div class="row">
    <div class="col-12">
        <?php if (!is_front_page() && function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>
    </div>
</div><!-- /.row -->

<div class="row">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="col-lg-5">
			<?php the_post_thumbnail('large'); ?>
			<div class="vh-xs-2 vh-sm-0"></div>
		</div>
		<div class="col-lg-7">
			<h1 class="single-title"><?php the_title() ?></h1>
			<div class="prices"><?php the_excerpt(); ?></div>
			<div class="vh-xs-2"></div>
			<button class="btn btn-secondary one-click"><?php _e('Quick order', 'brainworks'); ?></button>
		</div>
        <div class="col-12">
            <div class="vh-xs-2"></div>
            <div><?php the_content(); ?></div>
            <div class="vh-xs-2"></div>
        </div>
	<?php endwhile; ?>
<?php endif; ?>
</div>
</div><!-- /.container -->

<?php get_footer(); ?>
