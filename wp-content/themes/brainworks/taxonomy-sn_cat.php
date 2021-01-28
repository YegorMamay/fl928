<?php get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if (function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>
            <h1 class="page-name"><?php single_cat_title() ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-3  left-sidebar">
            <?php get_sidebar(); ?>
        </div>

        <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
            <div class="row">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="col-12 col-sm-12 col-md-4">
                   <div class="catalog-item">
                    <div><a href="<?php the_permalink(); ?>" class="image-catalogs"><?php the_post_thumbnail('medium'); ?></a></div>
                    <h6 class="text-center"><a href="<?php the_permalink(); ?>" class="title-catalogs"><?php the_title(); ?></a></h6>
                    </div>
                    <div class="vh-xs-3"></div>
                </div>
                <?php endwhile;
                    else : ?>
                <?php get_template_part('loops/content', 'none'); ?>
                <?php endif; ?>
            </div>
        </div>

    <?php  the_archive_description( '<div class="term-description">', '</div>' );  ?>
    <div class="vh-xs-2"></div>
   
    </div><!-- /.row -->
</div><!-- /.container -->



<?php get_footer(); ?>
