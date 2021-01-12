<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">

  <?php /* If there is plugin LayerSlider
   <link data-minify="0" rel="preload" href="/wp-content/plugins/LayerSlider/assets/static/layerslider/skins/fullwidth/skin.css" as="style">
   */
    ?>
       
    <title>
        <?php
        if (is_front_page()) {
            echo get_bloginfo('name');
        } elseif (is_post_type_archive()) {
            echo post_type_archive_title();
        } elseif (!is_front_page() || !is_page()) {
            echo single_post_title();
        } elseif (!is_front_page() || !is_single()) {
            echo the_title();
        } elseif (is_front_page() && is_category()) {
            echo single_cat_title();
        }
        if (is_archive()) {
            echo single_cat_title();
        }
        ?>
    </title>

    <meta name="description" content="<?php bloginfo('description'); ?>">

    <!-- OpenGraph -->
    <meta property="og:locale" content="ru_RU">
    <meta property="og:locale:alternate" content="ru_RU">
    <meta property="og:type" content="website">
    <meta property="og:title" content="
    <?php
    if (is_front_page()) {
        echo get_bloginfo('name');
    } elseif (is_post_type_archive()) {
        echo post_type_archive_title();
    } elseif (!is_front_page() || !is_page()) {
        echo single_post_title();
    } elseif (!is_front_page() || !is_single()) {
        echo the_title();
    } elseif (is_front_page() && is_category()) {
        echo single_cat_title();
    }
    if (is_archive()) {
        echo single_cat_title();
    }
    ?>
    ">
    <meta property="og:description" content="<?php bloginfo('description'); ?>">
    <meta property="og:url" content="<?php echo esc_url(site_url()); ?>">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
    <meta property="og:image:secure_url" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="
        <?php
    if (is_front_page()) {
        echo get_bloginfo('name');
    } elseif (is_post_type_archive()) {
        echo post_type_archive_title();
    } elseif (!is_front_page() || !is_page()) {
        echo single_post_title();
    } elseif (!is_front_page() || !is_single()) {
        echo the_title();
    } elseif (is_front_page() && is_category()) {
        echo single_cat_title();
    }
    if (is_archive()) {
        echo single_cat_title();
    }
    ?>
    ">
    <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
    <!-- OpenGraph end-->
    <link rel="shortcut icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon.ico'); ?>"
          type="image/x-icon">
    <link rel="icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/img/favicon.ico'); ?>"
          type="image/x-icon">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> id="top">

<?php wp_body_open(); ?>
<div class="wrapper js-container"><!--Do not delete!-->

    <header class="header <?php sticky_header(); ?>">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                    <div class="logo">
                        <?php get_default_logo_link([
                            'name' => 'logo.svg',
                            'options' => [
                                'class' => 'logo-img',
                                'width' => 100,
                                'height' => 50,
                            ],
                        ])
                        ?>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"></div>
                <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <div class="nav-wrapper">
                        <?php if (has_nav_menu('language-switcher')) { ?>
                            <nav class="nav js-menu">
                                <button type="button" tabindex="0"
                                        class="menu-item-close menu-close js-menu-close"></button>
                                <?php wp_nav_menu(array(
                                    'theme_location' => 'language-switcher',
                                    'container' => false,
                                    'menu_class' => 'menu-container',
                                    'menu_id' => '',
                                    'fallback_cb' => 'wp_page_menu',
                                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                    'depth' => 3
                                )); ?>
                            </nav>
                        <?php } ?>
                        <div class="woo-cart woo-cart-popup-wrapper">
                            <?php if ( class_exists( 'WooCommerce' ) ) { ?>
                                <?php echo woocommerce_cart(); ?>
                                <?php echo woocommerce_cart_popup(); ?>
                                <span id="modal-cart" class="cart-caption">
                                    <?php echo woocommerce_get_total_price(); ?>
                                </span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
<!-- Dropdown phones -->
<?php if (has_phones()) { ?>
    <ul class="phone-dropdown">
        <li class="phone-dropdown__item">
            <?php foreach(get_phones() as $key => $phone) { ?>
            <?php if ($key === key(get_phones())) { ?>
            <a href="tel:<?php echo esc_attr(get_phone_number($phone)); ?>" class="phone-dropdown__link phone-dropdown--main">
                <?php echo esc_html($phone); ?>
            </a>
            <button type="button" class="phone-dropdown__button js-dropdown"></button>
            <ul class="phone-dropdown__list js-phone-list">
                <?php  } else { ?>
                    <li class="phone-dropdown__item">
                        <a href="tel:<?php echo esc_attr(get_phone_number($phone)); ?>" class="phone-dropdown__link">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </li>
                <?php } ?>
                <?php } ?>
            </ul>
        </li>
    </ul>
<?php } ?>
<!-- Dropdown phones -->
                </div>
            </div>

            <?php if (has_nav_menu('main-nav')) { ?>
                <nav class="nav js-menu">
                    <button type="button" tabindex="0"
                            class="menu-item-close menu-close js-menu-close"></button>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'main-nav',
                        'container' => false,
                        'menu_class' => 'menu-container',
                        'menu_id' => '',
                        'fallback_cb' => 'wp_page_menu',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 3
                    )); ?>
                </nav>
            <?php } ?>
        </div>
    </header>

    <!-- Mobile menu start-->
    <div class="nav-mobile-header">
        <div class="social-mob"><?php echo do_shortcode('[bw-social]'); ?></div>
        <div class="logo"><?php get_default_logo_link(); ?></div>
        <button class="hamburger js-hamburger" type="button" tabindex="0">
        <span class="hamburger-box">
            <span class="hamburger-inner"></span>
        </span>
        </button>
    </div>
    <?php if (has_nav_menu('main-nav')) { ?>
        <nav class="nav js-menu hide-on-desktop">
            <button type="button" tabindex="0" class="menu-item-close menu-close js-menu-close"></button>
            <?php wp_nav_menu(array(
                'theme_location' => 'main-nav',
                'container' => false,
                'menu_class' => 'menu-container',
                'menu_id' => '',
                'fallback_cb' => 'wp_page_menu',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 3
            )); ?>
            <?php if (has_nav_menu('language-switcher')) { ?>
                <div class="mobile-language">
                    <?php wp_nav_menu(array(
                        'theme_location' => 'language-switcher',
                        'container' => false,
                        'menu_class' => 'menu-container',
                        'menu_id' => '',
                        'fallback_cb' => 'wp_page_menu',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 3
                    )); ?>
                </div>
            <?php } ?>
            <div class="mobile-phones">
                <?php echo do_shortcode('[bw-phone]'); ?>
            </div>
        </nav>
    <?php } ?>
    <!-- Mobile menu end-->
    <?php if ( class_exists( 'WooCommerce' ) ) { ?>
    <input id="cyr-value" type="hidden" value='<?php echo get_woocommerce_currency_symbol(); ?>'/>
    <?php } ?>
