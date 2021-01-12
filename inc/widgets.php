<?php

function bw_widgets_init()
{
    /**
     * Sidebar Left
     */
    register_sidebar(array(
        'name' => __('Sidebar Left', 'brainworks'),
        'id' => 'sidebar-widget-area',
        'description' => __('The sidebar widget area', 'brainworks'),
        'before_widget' => '<section class="widget-item %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ));

    /**
     * Sidebar Right
     */
    register_sidebar(array(
        'name' => __('Sidebar Right', 'brainworks'),
        'id' => 'sidebar-widget-area2',
        'description' => __('The sidebar widget area', 'brainworks'),
        'before_widget' => '<section class="widget-item %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ));
    
    /**
     * Review Page Sidebar
     */
    register_sidebar(array(
        'name' => __('Review Page Sidebar', 'brainworks'),
        'id' => 'sidebar-widget-area3',
        'description' => __('The sidebar widget area', 'brainworks'),
        'before_widget' => '<section class="widget-item %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ));
    
    
    /**
     * Woo cart
     */
    register_sidebar(array(
        'name' => __('Woo Cart', 'brainworks'),
        'id' => 'cart-widget-area',
        'description' => __('Woo cart widget area', 'brainworks'),
        'before_widget' => '<section class="widget-item %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ));

    /**
     * Footer
     */
    register_sidebar(array(
        'name' => __('Footer', 'brainworks'),
        'id' => 'footer-widget-area',
        'description' => __('The footer widget area', 'brainworks'),
        'before_widget' => '<div class="widget-item %1$s %2$s col-sm-6 col-md-3">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ));

}

add_action('widgets_init', 'bw_widgets_init');
