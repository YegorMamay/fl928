<?php

function bw_register_cpts_reviews()
{
    /**
     * Post Type: Reviews.
     */
    $labels = array(
        'name' => __('Reviews', 'brainworks'),
        'singular_name' => __('Review', 'brainworks'),
    );

    $args = array(
        'label' => __('Reviews', 'brainworks'),
        'labels' => $labels,
        'description' => '',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'delete_with_user' => false,
        'show_in_rest' => false,
        'rest_base' => 'reviews',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'has_archive' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'exclude_from_search' => false,
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'rewrite' => array('slug' => 'reviews', 'with_front' => true),
        'query_var' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    );

    register_post_type('reviews', $args);
}

add_action('init', 'bw_register_cpts_reviews');


function bw_create_catalog()
{
	flush_rewrite_rules();

    register_post_type('catalog', array(
        'label'  => null,
        'labels' => array(
            'name'               => 'Каталог', // основное название для типа записи
            'singular_name'      => 'Каталог', // название для одной записи этого типа
            'add_new'            => 'Добавить позицию', // для добавления новой записи
            'add_new_item'       => 'Добавлена позиция', // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => 'Редактировать позицию', // для редактирования типа записи
            'new_item'           => 'Новая позиция', // текст новой записи
            'view_item'          => 'Посмотреть', // для просмотра записи этого типа.
            'search_items'       => 'Поиск', // для поиска по этим типам записи
            'not_found'          => 'Не найден', // если в результате поиска ничего не было найдено
            'not_found_in_trash' => 'Не найден в корзине', // если не было найдено в корзине
            'parent_item_colon'  => '', // для родителей (у древовидных типов)
            'menu_name'          => 'Каталог', // название меню
        ),
        'description'         => '',
        'public'              => true,
        'show_in_menu'        => null, // показывать ли в меню адмнки
        'show_in_rest'        => null, // добавить в REST API. C WP 4.7
        'rest_base'           => null, // $post_type. C WP 4.7
        'menu_position'       => 30,
        'menu_icon'           => 'dashicons-list-view',
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields', 'excerpt'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'has_archive'         => true, // пример archive-slug.php
        'rewrite'             => true,
        'query_var'           => true,
	    'taxonomies'          => ['sn_cat'],
    ));

    register_taxonomy(
        'sn_cat',
        'catalog', // привязываеться к произвольному типу записи
        array(
            'label' => __('Категории'),
            'rewrite' => array('slug' => 'categories'), // slug по которому будет выводиться категории
            'hierarchical' => true,
        )
    );
}

add_action('init', 'bw_create_catalog');
