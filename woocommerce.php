<?php get_header(); ?>

<?php $column_class = is_active_sidebar('sidebar-widget-area')
    ? 'col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9'
    : 'col-12';
?>

<div class="container">
  
   <?php if (function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>
   
    <div class="row">
    <?php if ( !is_single() && is_active_sidebar('sidebar-widget-area') ) : ?>
        <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 left-sidebar">
            <?php dynamic_sidebar('sidebar-widget-area'); ?>
        </div>
    <?php endif; ?>
    
    <div class="col-12 <?php echo !is_single() ? $column_class : ''; ?>">
        <?php woocommerce_content(); ?>
    </div>
    
    </div><!-- /.row -->
</div><!-- /.container -->

<?php get_footer(); ?>