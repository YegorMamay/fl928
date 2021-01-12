<?php get_header(); ?>

<?php $column_class = is_active_sidebar('sidebar-widget-area')
    ? 'col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9'
    : 'col-12';
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if (function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>
            <h1 class="page-name"><?php post_type_archive_title(); ?></h1>
        </div>
    </div><!-- /.row -->
    
    <div class="row">
        <?php if (is_active_sidebar('sidebar-widget-area')) { ?>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 left-sidebar">
                <?php dynamic_sidebar('sidebar-widget-area'); ?>
            </div>
        <?php } ?>
        
        <div class="<?php echo $column_class; ?>">
            <div class="row">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="col-12 col-sm-12 col-md-4">
                   <div class="catalog-item">
                    <div><a href="<?php the_permalink(); ?>" class="image-catalogs"><?php the_post_thumbnail('large'); ?></a></div>
                    <h6 class="text-center"><a href="<?php the_permalink(); ?>" class="title-catalogs"><?php the_title(); ?></a></h6>
                    </div>
                    <div class="vh-xs-3"></div>
                </div>  
                <?php endwhile;
                    else : ?>
                <?php get_template_part('loops/content', 'none'); ?>
                <?php endif; ?>
            </div>
            
            
    <?php if (function_exists('bw_pagination')) {
            bw_pagination();
        } else {
            if (is_paged()) { ?>
                <ul class="pagination">
                    <li class="older"><?php next_posts_link('<i class="fa fa-arrow-left"></i> ' . __('Previous', 'brainworks')) ?></li>
                    <li class="newer"><?php previous_posts_link(__('Next', 'brainworks') . ' <i class="fa fa-arrow-right"></i>') ?></li>
                </ul>
            <?php }
        }
 ?>
            
        </div>

    </div><!-- /.row -->
</div><!-- /.container -->

<?php get_footer(); ?>