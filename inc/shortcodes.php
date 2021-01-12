<?php

if (!function_exists('bw_polylang_shortcode')) {
    /**
     * Add Shortcode Polylang
     *
     * @param $atts
     *
     * @return array|null|string
     */
    function polylang_shortcode($atts)
    {
        // Attributes
        $atts = shortcode_atts(
            array(
                'dropdown' => 0, // display as list and not as dropdown
                'echo' => 0, // echoes the list
                'hide_if_empty' => 1, // hides languages with no posts ( or pages )
                'menu' => 0, // not for nav menu ( this argument is deprecated since v1.1.1 )
                'show_flags' => 0, // don't show flags
                'show_names' => 1, // show language names
                'display_names_as' => 'name', // valid options are slug and name
                'force_home' => 0, // tries to find a translation
                'hide_if_no_translation' => 0, // don't hide the link if there is no translation
                'hide_current' => 0, // don't hide current language
                'post_id' => null, // if not null, link to translations of post defined by post_id
                'raw' => 0, // set this to true to build your own custom language switcher
                'item_spacing' => 'preserve', // 'preserve' or 'discard' whitespace between list items
            ),
            $atts
        );

        if (function_exists('pll_the_languages')) {
            $flags = pll_the_languages($atts);

            if (0 === (int) $atts['dropdown']) {
                $flags = sprintf('<ul class="lang">%s</ul>', $flags);
            }

            return $flags;
        }

        return '';
    }

    add_shortcode('polylang', 'bw_polylang_shortcode');
}

if (!function_exists('bw_social_shortcode')) {
    /**
     * Add Shortcode Socials
     *
     * @param $atts
     *
     * @return string
     */
    function bw_social_shortcode($atts)
    {

        // Attributes
        $atts = shortcode_atts(
            array(),
            $atts
        );

        $output = '';

        if (has_social()) {
            $items = '';

            foreach (get_social() as $name => $social) {
                $icon_fallback = sprintf('<i class="%s" aria-hidden="true"></i>', esc_attr($social['icon']));

                $icon = !empty($social['icon-html']) ? strip_tags($social['icon-html'], '<i>') : $icon_fallback;

                $items .= sprintf(
                    '<li class="social-item">%s</li>',
                    sprintf(
                        '<a class="social-link social-%s" href="%s" target="_blank" rel="nofollow noopener" aria-label="%s">%s</a>',
                        esc_attr($name),
                        esc_attr(esc_url($social['url'])),
                        esc_attr($social['text']),
                        $icon
                    )
                );
            }

            $output = sprintf('<ul class="social">%s</ul>', $items);
        }

        return $output;
    }

    add_shortcode('bw-social', 'bw_social_shortcode');
}

if (!function_exists('bw_phone_shortcode')) {
    /**
     * Add Shortcode Phones
     *
     * @param $atts
     *
     * @return string
     */
    function bw_phone_shortcode($atts)
    {

        // Attributes
        $atts = shortcode_atts(
            array(),
            $atts
        );

        $output = '';

        if (has_phones()) {
            $items = '';

            foreach (get_phones() as $phone) {
                $items .= sprintf(
                    '<li class="phone-item">%s</li>',
                    sprintf(
                        '<a class="phone-number" href="tel:%s">%s</a>',
                        esc_attr(get_phone_number($phone)),
                        esc_html($phone)
                    )
                );
            }

            $output = sprintf('<ul class="phone">%s</ul>', $items);
        }

        return $output;
    }

    add_shortcode('bw-phone', 'bw_phone_shortcode');
}

if (!function_exists('bw_messengers_shortcode')) {
    /**
     * Add Shortcode Messengers
     *
     * @param $atts
     *
     * @return string
     */
    function bw_messengers_shortcode($atts)
    {

        // Attributes
        $atts = shortcode_atts(
            array(),
            $atts
        );

        $output = '';

        if (has_messengers()) {
            $items = '';

            foreach (get_messengers() as $name => $messenger) {
                $icon = sprintf('<i class="%s" aria-hidden="true"></i>', esc_attr($messenger['icon']));

                $link = sprintf(
                    '<a class="messenger-link messenger-%s" href="%s" target="_blank" aria-label="%s" rel="nofollow">%s</a>',
                    esc_attr($name),
                    esc_attr(get_phone_number($messenger['tel'])),
                    esc_attr($messenger['text']),
                    $icon
                );

                $item = sprintf('<li class="messenger-item">%s</li>', $link);

                $items .= $item . PHP_EOL;
            }

            $output = sprintf('<ul class="messenger">%s</ul>', $items);
        }

        return $output;
    }

    add_shortcode('bw-messengers', 'bw_messengers_shortcode');
}

if (!function_exists('bw_html_sitemap')) {
    /**
     * Add Shortcode HTML Sitemap
     *
     * @param $atts
     *
     * @return string
     */
    function bw_html_sitemap($atts)
    {
        $output = '';
        $args = array(
            'public' => 1,
        );

        // If you would like to ignore some post types just add them to the array below
        $ignoreposttypes = array(
            'attachment',
            'popup',
            'reviews'
        );

        $post_types = get_post_types($args, 'objects');

        foreach ($post_types as $post_type) {
            if (!in_array($post_type->name, $ignoreposttypes)) {
                $output .= '<h4 class="sitemap-headline">' . $post_type->labels->name . '</h4><div class="vh-xs-1"></div>';
                $args = array(
                    'posts_per_page' => -1,
                    'post_type' => $post_type->name,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                );
                $posts_array = get_posts($args);
                $output .= '<ul class="sitemap-list">';
                foreach ($posts_array as $pst) {
                    $output .= '<li class="sitemap-item"><a class="sitemap-link" href="' . get_permalink($pst->ID) . '">' . $pst->post_title . '</a></li>';
                }
                $output .= '</ul><div class="vh-xs-2"></div>';
            }
        }

        return $output;
    }

    add_shortcode('bw-html-sitemap', 'bw_html_sitemap');
}

if (!function_exists('bw_last_posts')) {
    /**
     *
     * Shortcode для отображения трёх последних новостей в блоге.
     * Новости должны быть:
     * - Опубликованы
     * - Желательно с картинкой
     * ТАКЖЕ! Шорткод может принимать такие аттрибуты, как:
     * - count - число новостей для вывода (по-стандарту: 3)
     * - button_title - текст в кнопке (по-стандарту: 'Читать полностью')
     *
     * @param  array $atts Аттрибуты шорткода
     *
     * @return string       Разметка (на Bootstrap)
     */
    function bw_last_posts($atts = array())
    {
        $atts = shortcode_atts(
            array(
                'count' => 3, // Кол-во новостей для отображения
                'button_title' => __('Continue reading', 'brainworks') // Текст в ссылке
            ),
            $atts
        );

        $posts = wp_get_recent_posts(array(
            'numberposts' => $atts['count'],
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish'
        ), ARRAY_A);

        $output = '<div class="container"><div class="row">';

        foreach ($posts as $key => $post) {
            $thumbnail_id = get_post_thumbnail_id($post['ID']);
            $thumbnail = get_the_post_thumbnail_url($post['ID'], 'medium');
            $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            $permalink = get_permalink($post['ID']);
            $output .= '<div class="col-md-4 col-lg-4 col-12 col-sm-12"><div class="custom-card custom-card-with-image">';
            if ($thumbnail !== false) {
                $output .= '<div class="custom-card-image">
                                    <img src="' . $thumbnail . '" title="' . $post['post_title'] . '" alt="' . $thumbnail_alt . '" width="100%" height="auto"  />
                                </div>';
            }
            $output .= '<div class="custom-card-body">
                                <h3>
                                    <a href="' . $permalink . '">' . $post['post_title'] . '</a>
                                </h3>
                                <p>
                                    ' . $post['post_excerpt'] . '
                                </p>
                                <br />
                                <a href="' . $permalink . '" class="btn btn-secondary btn-sm">
                                    ' . $atts['button_title'] . '
                                </a>
                            </div>';
            $output .= '</div></div>';
        }

        $output .= '</div></div>';

        return $output;
    }

    add_shortcode('bw-last-posts', 'bw_last_posts');
}

if (!function_exists('bw_advert_shortcode')) {
    /**
     * Add Shortcode Advert Post List
     *
     * @param array $atts
     *
     * @return string
     */
    function bw_advert_shortcode($atts = [])
    {
        // Attributes
        $atts = shortcode_atts([
            'count' => 3,
            'class' => 'front-news',
            'category' => null,
            'display_category' => false,
            'display_datetime' => false,
            'array_index' => false,
        ], $atts);

        $posts = [];
        $output = '';

        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $atts['count'],
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'on-front',
                    'value' => 'yes',
                ],
            ]
        ];

        if (!is_null($atts['category'])) {
            $args['category__in'] = explode(',', $atts['category']);
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $items = '';
            $basic_class = trim(strip_tags($atts['class']));

            while ($query->have_posts()) {
                $query->the_post();

                $thumbnail = has_post_thumbnail() ? sprintf(
                    '<figure class="%s-preview"><a href="%s">%s</a></figure>',
                    $basic_class,
                    get_the_permalink(),
                    get_the_post_thumbnail(null, 'medium', ['class' => $basic_class . '-thumbnail'])
                ) : '';

                $category = '';
                $datetime = '';

                if ($atts['display_datetime']) {
                    $datetime = sprintf(
                        '<time class="%s-datetime" datetime="%s">%s</time>',
                        $basic_class,
                        get_the_date('c'),
                        get_the_date()
                    );
                }

                if ($atts['display_category']) {
                    $category = sprintf(
                        '<div class="%s-category">#%s</div>',
                        $basic_class,
                        get_the_category_list(',')
                    );
                }

                $meta = sprintf(
                    '<div class="%s-meta">%s %s</div>',
                    $basic_class,
                    $category,
                    $datetime
                );

                $headline = sprintf(
                    '<h3 class="%s-headline"><a href="%s">%s</a></h3>',
                    $basic_class,
                    get_the_permalink(),
                    get_the_title()
                );

                $excerpt = sprintf('<div class="%s-excerpt">%s</div>', $basic_class, get_the_excerpt());

                $btn = sprintf(
                    '<div class="front-news-btn">
                        <a class="btn btn-secondary btn-sm %s-link" href="%s">%s</a>
                    </div>',
                    $basic_class,
                    get_the_permalink(),
                    __('Continue reading', 'brainworks')
                );

                $box = sprintf(
                    '<div class="%s-box">%s <div class="%s-inner">%s %s %s %s</div></div>',
                    $basic_class,
                    $thumbnail,
                    $basic_class,
                    $meta,
                    $headline,
                    $excerpt,
                    $btn
                );

                $item = sprintf(
                    '<section id="post-%s" class="%s">%s</section>',
                    get_the_ID(),
                    join(' ', get_post_class(['col-sm-6 col-md-4', $basic_class . '-item'])),
                    $box
                );

                $posts[] = $item;

                $items .= $item;
            }

            wp_reset_postdata();

            $output = sprintf('<div class="row %s-list">%s</div>', $basic_class, $items);
        }

        if ($atts['array_index'] && array_key_exists($atts['array_index'], $posts)) {
            return $posts[$atts['array_index']];
        }

        return $output;
    }

    add_shortcode('bw-advert', 'bw_advert_shortcode');
}

if (!function_exists('bw_custom_login_shortcode')) {
    function bw_custom_login_shortcode($atts)
    {
        if (!bw_user_logged_in()) {
            $output = '
                <form class="login-form form-" action="/wp-json/api/auth/login" method="POST" autocomplete="off">
                <fieldset>
                    <input type="hidden" name="_redirect_url" value="' . strtok($_SERVER['REQUEST_URI'], '?') . '" />
                    <div class="login-form-row form-group">
                        <label for="login">' . __('Login', 'brainworks') . '</label>
                        <input type="text" id="login" name="login" class="form-field" required />
                    </div>
                    <div class="login-form-row form-group">
                        <label for="password">' . __('Password', 'brainworks') . '</label>
                        <input type="password" id="password" name="password" class="form-field" required />
                    </div>
                    <div class="login-form-row form-group">
                        <button type="submit" name="submit" class="btn btn-primary login-form-submit">
                            ' . __('Login', 'brainworks') . '
                        </button>
                    </div>   
                </fieldset>   
                </form>
            ';
            return $output;
        }
        return '';
    }

    add_shortcode('bw-custom-login', 'bw_custom_login_shortcode');
}

if (!function_exists('bw_custom_register_shortcode')) {
    function bw_custom_register_shortcode($atts)
    {
        if (!bw_user_logged_in()) {
            $output = '
            <form class="login-form form-" action="/wp-json/api/auth/register" method="POST" autocomplete="off">
            <fieldset>
                <input type="hidden" name="_redirect_url" value="' . strtok($_SERVER['REQUEST_URI'], '?') . '" />
                <div class="login-form-row form-group">
                    <label for="login">' . __('Login', 'brainworks') . '</label>
                    <input type="text" id="login" name="login" class="form-field" required />
                </div>
                <div class="login-form-row form-group">
                    <label for="email">' . __('Email', 'brainworks') . '</label>
                    <input type="email" id="email" name="email" class="form-field" required />
                </div>
                <div class="login-form-row form-group">
                    <label for="password">' . __('Password', 'brainworks') . '</label>
                    <input type="password" id="password" name="password" class="form-field" required />
                </div>
                <div class="login-form-row form-group">
                    <label for="retry_password">' . __('Retry password', 'brainworks') . '</label>
                    <input type="password" id="retry_password" name="retry_password" class="form-field" required />
                </div>
                <div class="login-form-row form-group">
                    <button type="submit" name="submit" class="btn btn-primary login-form-submit">
                        ' . __('Register', 'brainworks') . '
                    </button>
                </div>    
            </fieldset>  
            </form>
            ';
            return $output;
        }
        return '';
    }

    add_shortcode('bw-custom-register', 'bw_custom_register_shortcode');
}


if (!function_exists('bw_custom_auth_shortcode')) {
    function bw_custom_auth_shortcode($atts)
    {

        if (!bw_user_logged_in()) {
            $login_form = do_shortcode('[bw-custom-login]');
            $register_form = do_shortcode('[bw-custom-register]');
            $output = '<div class="login-block row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                ' . $login_form . ' 
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                ' . $register_form . '
                </div>
            </div>';
            return $output;
        }
        return '';
    }

    add_shortcode('bw-custom-auth', 'bw_custom_auth_shortcode');
}

if (!function_exists('bw_reviews_shortcode')) {
    /**
     * Add Shortcode Reviews List
     *
     * @param $atts
     *
     * @return string
     */
    function bw_reviews_shortcode($atts)
    {
        // Attributes
        $atts = shortcode_atts(
            array(),
            $atts
        );

        $output = '';

        $args = array(
            'post_type' => 'reviews',
            'publish_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'review-display',
                    'value' => '1',
                )
            ),
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {

            $output .= '<div class="review-slider text-center js-reviews">';

            while ($query->have_posts()) {
                $query->the_post();

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

                $post_class = 'class="' . join(' ', get_post_class('review-item', null)) . '"';

                $output .= '<div id="post-' . get_the_ID() . '" ' . $post_class . '>';

                $output .= '<div class="review-client">';
                $output .= get_the_post_thumbnail(null, 'thumbnail', array('class' => 'review-avatar'));
                if (count($social)) {
                    $output .= '<a class="review-social" href="' . esc_url($social['url']) . '" target="_blank" rel="noopener noreferrer">';
                    $output .= '<i class="fab ' . esc_attr($social['icon']) . '" aria-hidden="true"></i>';
                    $output .= '</a>';
                }
                $output .= '</div>';
                $output .= '<div class="review-title">' . get_the_title() . '</div>';
                $output .= '<div class="review-content">' . get_the_content() . '</div>';

                $output .= '</div>';
            }

            wp_reset_postdata();

            $output .= '</div>';
        }

        return $output;
    }

    add_shortcode('bw-reviews', 'bw_reviews_shortcode');
}

// addes shortcode posts category
if (!function_exists('sn_catalog_shortcode')) {
    add_shortcode('catalog', 'show_categories_catalog');

    function show_categories_catalog($atts)
    {
        $atts = shortcode_atts(array(
            'id' => 1,
            'count' => 6
        ), $atts);

        $args = array(
            'posts_per_page'        => $atts['count'],
            'post_type'      => 'catalog',
            'tax_query' => array(
                array(
                    'taxonomy' => 'sn_cat',
                    'field'    => 'id',
                    'terms'    => $atts['id'],
                ),
            ),
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) : ?>
            <div class="row">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="col-12 col-sm-12 col-md-4">
                    <div class="catalog-shortcode-item">
                        <div><a href="<?php the_permalink(); ?>" class="image-catalogs"><?php the_post_thumbnail(); ?></a></div>
                        <h6 class="text-center"><a href="<?php the_permalink(); ?>" class="title-catalogs"><?php the_title(); ?></a></h6>
                    </div>
                        <div class="vh-xs-3"></div>
                </div>
                    <?php endwhile; ?>
            </div>
        <?php else :

        endif;

        wp_reset_postdata();        
    }
}



add_shortcode('sn_cat', 'cat_and_child_id');

/**
 * @param array $atts
 * @return string
 * Работает как с указанием таксономии так и без
 * при указании таксономии показывает все категории [sn_cat id="7, 78, 82" term="name_term"]
 */
function cat_and_child_id( $atts ) {
    $str = $atts['id'];
    $arr_id = explode( ', ', $str );
    $name_cpt = $atts['term'];

    if ( taxonomy_exists( $name_cpt ) ) {
        $categories = get_categories( [
            'type' => 'post',
            'taxonomy' => $name_cpt,
            'hide_empty' => false,
        ] );

        $html = '<div class="row list-cat">';

        foreach ( $arr_id as $cat ) {
            $cat_id = $cat;
            $cat_url = get_category_link( $cat_id );
            $cat_name = get_cat_name( $cat_id );
            $term_id = $cat_id;
            $image_id = get_term_meta( $term_id, '_thumbnail_id', true);
            $image_url =  wp_get_attachment_image_url( $image_id, 'large' );

            if ( $categories ) {
                foreach ( $categories as $term ) {
                    $term_cat_id = $term->term_id;
                    $term_cat_name = $term->name;

                    if ( $term_cat_id == $cat_id ) {
                        if(!empty($image_url)) {
                            $image_url =  wp_get_attachment_image_url( $image_id, 'large' );
                        } else {
                            $image_url = get_parent_theme_file_uri('assets/img/placeholder.jpg');
                        }
                        $html .= sprintf( '<div class="list-cat__item col-12 col-md-4 col-lg-4"><a href="%s" class="list-cat__link"><img src="%s" class="list-cat__img" alt="image"><div class="list-cat__title h4">%s</div></a></div>', $cat_url, $image_url, $term_cat_name );
                    }
                }
            }

            if ( $cat_name ) {

                $html .= sprintf( '<div class="list-cat__item col-12 col-md-4 col-lg-4"><a href="%s" class="list-cat__link"><div class="list-cat__title h4">%s</div></a></div>', $cat_url, $cat_name );

            }

        }

        $html .= '</div>';

        return $html;
    } else {
        $html = '<div class="row list-cat">';

        foreach ( $arr_id as $cat ) {
            $cat_id = $cat;
            $cat_url = get_category_link( $cat_id );
            $cat_name = get_cat_name( $cat_id );

            if ( $cat_name ) {
                $html .= sprintf( '<div class="list-cat__item col-12 col-md-4 col-lg-4"><a href="%s" class="list-cat__link"><div class="list-cat__title h4">%s</div></a></div>', $cat_url, $cat_name );
            }

        }

        $html .= '</div>';

        return $html;
    }
}
