<?php get_header(); ?>

<?php $column_class = is_active_sidebar('sidebar-widget-area2')
    ? 'col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9'
    : 'col-12';
?>

<div class="container">
    <div class="row">
        <div class="<?php echo $column_class; ?>">
            <?php if (!is_front_page() && function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>
            <?php get_template_part('loops/content', 'single'); ?>
        </div>
        <?php if (is_active_sidebar('sidebar-widget-area2')) { ?>
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <?php dynamic_sidebar('sidebar-widget-area2'); ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php get_footer(); ?>
