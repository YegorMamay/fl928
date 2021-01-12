<?php get_header(); ?>

<?php $column_class = is_active_sidebar('sidebar-widget-area3')
    ? 'col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8'
    : 'col-12';
?>

<div class="container">
<?php if (function_exists('kama_breadcrumbs')) kama_breadcrumbs(' » '); ?>
<div class="row">
    <div class="<?php echo $column_class; ?>">
        <h1 class="text-center"><?php post_type_archive_title(); ?></h1>

        <div class="vh-xs-2 vh-md-3"></div>

        <?php if (have_posts()) { ?>
            <div class="review-list">
                <?php while (have_posts()) {
                    the_post();
                    $id = get_the_ID();
                    $social = array();
                    $socials = array(
                        'vk' => array(
                            'url' => get_post_meta($id, 'review-vk', true),
                            'icon' => 'fa-vk',
                        ),
                        'youtube' => array(
                            'url' => get_post_meta($id, 'review-youtube', true),
                            'icon' => 'fa-youtube',
                        ),
                        'twitter' => array(
                            'url' => get_post_meta($id, 'review-twitter', true),
                            'icon' => 'fa-twitter',
                        ),
                        'facebook' => array(
                            'url' => get_post_meta($id, 'review-facebook', true),
                            'icon' => 'fa-facebook-square',
                        ),
                        'linkedin' => array(
                            'url' => get_post_meta($id, 'review-linkedin', true),
                            'icon' => 'fa-linkedin-in',
                        ),
                        'instagram' => array(
                            'url' => get_post_meta($id, 'review-instagram', true),
                            'icon' => 'fa-instagram',
                        ),
                        'google-plus' => array(
                            'url' => get_post_meta($id, 'review-google-plus', true),
                            'icon' => 'fa-google-plus-g',
                        ),
                        'odnoklassniki' => array(
                            'url' => get_post_meta($id, 'review-odnoklassniki', true),
                            'icon' => 'fa-odnoklassniki',
                        ),
                    );

                    foreach ($socials as $item) {
                        if (!empty($item['url'])) {
                            $social['url'] = $item['url'];
                            $social['icon'] = $item['icon'];
                        }
                    }
                    ?>
                    <div id="post-<?php the_ID() ?>" <?php post_class('review-item'); ?>>
                        <div class="row">
                            <div class="col-12 col-lg-2 text-center">
                                <div class="review-client">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'review-avatar')); ?>
                                    <?php if (count($social)) { ?>
                                        <a class="review-social" href="<?php echo esc_url($social['url']); ?>"
                                           target="_blank" rel="noopener noreferrer">
                                            <i class="fab <?php echo esc_attr($social['icon']); ?>"
                                               aria-hidden="true"></i>
                                        </a>
                                    <?php } ?>
                                </div>
                                <div class="review-author"><?php the_title() ?></div>
                            </div>
                            <div class="col-12 col-lg-10">
                                <div class="review-content"><?php the_content(); ?></div>
                                <div class="review-date text-right"><?php echo get_the_date('d.m.Y'); ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php bw_pagination(); ?>
            </div>
        <?php } else {
            get_template_part('loops/content', 'none');
        } ?>
    </div>
    
    <?php if (is_active_sidebar('sidebar-widget-area3')) { ?>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <?php dynamic_sidebar('sidebar-widget-area3'); ?>
        </div>
    <?php } ?>
</div>
</div><!-- /.container -->

<?php get_footer(); ?>