<?php
/**
 * The Single Posts Loop
 * =====================
 */
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article id="post_<?php the_ID() ?>" <?php post_class() ?>>
            <h1 class="single-title"><?php the_title() ?></h1>
            <div class="vh-xs-2"></div>
            <?php /*
            <h5>
                <span class="text-muted author"><?php _e('By', 'brainworks'); echo " "; the_author() ?></span>
                <time class="text-muted" datetime="<?php the_time('d-m-Y')?>"><?php the_date(get_option('date_format')); ?></time>
            </h5>
            <p class="text-muted" style="margin-bottom: 30px;">
                <i class="fa fa-folder-open-o"></i>&nbsp; <?php _e('Filed under', 'brainworks'); ?>: <?php the_category(', ') ?><br/>
                <i class="fa fa-comment-o"></i>&nbsp; <?php _e('Comments', 'brainworks'); ?>: <?php comments_popup_link(__('None', 'brainworks'), '1', '%'); ?>
            </p>
            */ ?>
        <section>
            <!--<?php the_post_thumbnail('full'); ?>-->
            <?php the_content() ?>
            <?php wp_link_pages(); ?>
        </section>
    </article>
    
    <div class="vh-xs-2"></div>
    
    <hr>
    
    <span class="text-muted text-italic bold">
        <?php _e('By', 'brainworks'); echo " "; the_author_meta(first_name); echo " "; the_author_meta(last_name); ?>, 
        <?php _e('Category', 'brainworks'); ?>: <?php the_category(', '); ?>, 
        <?php the_time('j F Y') ?>
    </span>
    
    <div class="vh-xs-2"></div>
    
    <?php comments_template('/loops/comments.php'); ?>
<?php endwhile; ?>

<?php else : ?>
    <?php get_template_part('loops/content', 'none'); ?>
<?php endif; ?>
