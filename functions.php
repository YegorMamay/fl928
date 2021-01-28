<?php
/**
 * All the functions are in the PHP pages in the `inc/` folder.
 */

//show_admin_bar(false);

require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/auth.php';
require get_template_directory() . '/inc/admin.php';
require get_template_directory() . '/inc/login.php';
require get_template_directory() . '/inc/customizer.php';

require get_template_directory() . '/inc/breadcrumbs.php';
require get_template_directory() . '/inc/cleanup.php';
require get_template_directory() . '/inc/custom-logo.php';
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/enqueues.php';
require get_template_directory() . '/inc/navbar.php';
require get_template_directory() . '/inc/widgets.php';
require get_template_directory() . '/inc/index-pagination.php';
require get_template_directory() . '/inc/split-post-pagination.php';
require get_template_directory() . '/inc/feedback.php';
require get_template_directory() . '/inc/shortcodes.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/custom-post-types.php';

require get_template_directory() . '/inc/LoadMorePosts.php';

add_action( 'after_setup_theme', function () {
	if ( function_exists( 'pll_register_string' ) ) {
		pll_register_string( 'email', 'Email', 'Brainworks' );
		pll_register_string( 'address', 'Address', 'Brainworks' );
		pll_register_string( 'work-schedule', 'Work Schedule', 'Brainworks' );

		pll_register_string( 'social-vk', 'Vk', 'Brainworks' );
		pll_register_string( 'social-twitter', 'Twitter', 'Brainworks' );
		pll_register_string( 'social-youtube', 'YouTube', 'Brainworks' );
		pll_register_string( 'social-facebook', 'Facebook', 'Brainworks' );
		pll_register_string( 'social-linkedin', 'Linkedin', 'Brainworks' );
		pll_register_string( 'social-instagram', 'Instagram', 'Brainworks' );
		pll_register_string( 'social-google-plus', 'Google Plus', 'Brainworks' );
		pll_register_string( 'social-odnoklassniki', 'Odnoklassniki', 'Brainworks' );
	}
} );

remove_action( 'wp_head', '_wp_render_title_tag', 1 ); //удаляет тег title из header.php

//WOOCOMMERCE
// Изменяет порядок артикула по отношению к другим элементам на странице товара
//remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 15 );

/**
 * Возможность загружать изображения для терминов (элементов таксономий: категории, метки).
 *
 * Пример получения ID и URL картинки термина:
 *     $image_id = get_term_meta( $term_id, '_thumbnail_id', 1 );
 *     $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
 *
 * @author: Pavel
 *
 * @version 1.0
 */
if( is_admin() && ! class_exists('Term_Meta_Image') ){

    // init
    //add_action('current_screen', 'Term_Meta_Image_init');
    add_action( 'admin_init', 'Term_Meta_Image_init' );
    function Term_Meta_Image_init(){
        $GLOBALS['Term_Meta_Image'] = new Term_Meta_Image();
    }

    class Term_Meta_Image {

        // для каких таксономий включить код. По умолчанию для всех публичных
        static $taxes = []; // пример: array('category', 'post_tag');

        // название мета ключа
        static $meta_key = '_thumbnail_id';
        static $attach_term_meta_key = 'img_term';

        // URL пустой картинки
        static $add_img_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=';

        public function __construct(){
            // once
            if( isset($GLOBALS['Term_Meta_Image']) )
                return $GLOBALS['Term_Meta_Image'];

            $taxes = self::$taxes ? self::$taxes : get_taxonomies( [ 'public' =>true ], 'names' );

            foreach( $taxes as $taxname ){
                add_action( "{$taxname}_add_form_fields",   [ $this, 'add_term_image' ],     10, 2 );
                add_action( "{$taxname}_edit_form_fields",  [ $this, 'update_term_image' ],  10, 2 );
                add_action( "created_{$taxname}",           [ $this, 'save_term_image' ],    10, 2 );
                add_action( "edited_{$taxname}",            [ $this, 'updated_term_image' ], 10, 2 );

                add_filter( "manage_edit-{$taxname}_columns",  [ $this, 'add_image_column' ] );
                add_filter( "manage_{$taxname}_custom_column", [ $this, 'fill_image_column' ], 10, 3 );
            }
        }

        ## поля при создании термина
        public function add_term_image( $taxonomy ){
            wp_enqueue_media(); // подключим стили медиа, если их нет

            add_action('admin_print_footer_scripts', [ $this, 'add_script' ], 99 );
            $this->css();
            ?>
            <div class="form-field term-group">
                <label><?php _e('Image', 'default'); ?></label>
                <div class="term__image__wrapper">
                    <a class="termeta_img_button" href="#">
                        <img src="<?php echo self::$add_img_url ?>" alt="">
                    </a>
                    <input type="button" class="button button-secondary termeta_img_remove" value="<?php _e( 'Remove', 'default' ); ?>" />
                </div>

                <input type="hidden" id="term_imgid" name="term_imgid" value="">
            </div>
            <?php
        }

        ## поля при редактировании термина
        public function update_term_image( $term, $taxonomy ){
            wp_enqueue_media(); // подключим стили медиа, если их нет

            add_action('admin_print_footer_scripts', [ $this, 'add_script' ], 99 );

            $image_id = get_term_meta( $term->term_id, self::$meta_key, true );
            $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : self::$add_img_url;
            $this->css();
            ?>
            <tr class="form-field term-group-wrap">
                <th scope="row"><?php _e( 'Image', 'default' ); ?></th>
                <td>
                    <div class="term__image__wrapper">
                        <a class="termeta_img_button" href="#">
                            <?php echo '<img src="'. $image_url .'" alt="">'; ?>
                        </a>
                        <input type="button" class="button button-secondary termeta_img_remove" value="<?php _e( 'Remove', 'default' ); ?>" />
                    </div>

                    <input type="hidden" id="term_imgid" name="term_imgid" value="<?php echo $image_id; ?>">
                </td>
            </tr>
            <?php
        }

        public function css(){
            ?>
            <style>
                .termeta_img_button{ display:inline-block; margin-right:1em; }
                .termeta_img_button img{ display:block; float:left; margin:0; padding:0; min-width:100px; max-width:150px; height:auto; background:rgba(0,0,0,.07); }
                .termeta_img_button:hover img{ opacity:.8; }
                .termeta_img_button:after{ content:''; display:table; clear:both; }
            </style>
            <?php
        }

        ## Add script
        public function add_script(){
            // выходим если не на нужной странице таксономии
            //$cs = get_current_screen();
            //if( ! in_array($cs->base, array('edit-tags','term')) || ! in_array($cs->taxonomy, (array) $this->for_taxes) )
            //  return;

            $title = __('Featured Image', 'default');
            $button_txt = __('Set featured image', 'default');
            ?>
            <script>
                jQuery(document).ready(function($){
                    var frame,
                        $imgwrap = $('.term__image__wrapper'),
                        $imgid   = $('#term_imgid');

                    // добавление
                    $('.termeta_img_button').click( function(ev){
                        ev.preventDefault();

                        if( frame ){ frame.open(); return; }

                        // задаем media frame
                        frame = wp.media.frames.questImgAdd = wp.media({
                            states: [
                                new wp.media.controller.Library({
                                    title:    '<?php echo $title ?>',
                                    library:   wp.media.query({ type: 'image' }),
                                    multiple: false,
                                    //date:   false
                                })
                            ],
                            button: {
                                text: '<?php echo $button_txt ?>', // Set the text of the button.
                            }
                        });

                        // выбор
                        frame.on('select', function(){
                            var selected = frame.state().get('selection').first().toJSON();
                            if( selected ){
                                $imgid.val( selected.id );
                                $imgwrap.find('img').attr('src', selected.sizes.thumbnail.url );
                            }
                        } );

                        // открываем
                        frame.on('open', function(){
                            if( $imgid.val() ) frame.state().get('selection').add( wp.media.attachment( $imgid.val() ) );
                        });

                        frame.open();
                    });

                    // удаление
                    $('.termeta_img_remove').click(function(){
                        $imgid.val('');
                        $imgwrap.find('img').attr('src','<?php echo self::$add_img_url ?>');
                    });
                });
            </script>

            <?php
        }

        ## Добавляет колонку картинки в таблицу терминов
        public function add_image_column( $columns ){
            // fix column width
            add_action( 'admin_notices', function(){
                echo '<style>.column-image{ width:50px; text-align:center; }</style>';
            });

            // column without name
            return array_slice( $columns, 0, 1 ) + [ 'image' =>'' ] + $columns;
        }

        public function fill_image_column( $string, $column_name, $term_id ){

            if( 'image' === $column_name && $image_id = get_term_meta( $term_id, self::$meta_key, 1 ) ){
                $string = '<img src="'. wp_get_attachment_image_url( $image_id, 'thumbnail' ) .'" width="50" height="50" alt="" style="border-radius:4px;" />';
            }

            return $string;
        }

        ## Save the form field
        public function save_term_image( $term_id, $tt_id ){
            if( isset($_POST['term_imgid']) && $attach_id = (int) $_POST['term_imgid'] ){
                update_term_meta( $term_id,   self::$meta_key,             $attach_id );
                update_post_meta( $attach_id, self::$attach_term_meta_key, $term_id );
            }
        }

        ## Update the form field value
        public function updated_term_image( $term_id, $tt_id ){
            if( ! isset($_POST['term_imgid']) )
                return;

            $cur_term_attach_id = (int) get_term_meta( $term_id, self::$meta_key, 1 );

            if( $attach_id = (int) $_POST['term_imgid'] ){
                update_term_meta( $term_id,   self::$meta_key,             $attach_id );
                update_post_meta( $attach_id, self::$attach_term_meta_key, $term_id );

                if( $cur_term_attach_id != $attach_id )
                    wp_delete_attachment( $cur_term_attach_id );
            }
            else {
                if( $cur_term_attach_id )
                    wp_delete_attachment( $cur_term_attach_id );

                delete_term_meta( $term_id, self::$meta_key );
            }
        }

    }

}
/**
 *  Добавил метаполе для вложений (img_term), где хранится ID термина к которому прикреплено вложение.
 *  Добавил физическое удаление картинки (файла вложения) при удалении его у термина.
 */

//Изменение перевода
add_filter('gettext', 'translate_text');
add_filter('ngettext', 'translate_text');

function translate_text($translated) {
$translated = str_ireplace('Подытог', 'Итого', $translated);
return $translated;
}
//WOOCOMMERCE END

// Отменяет удаление из корзины через определенный срок
function devise_remove_schedule_delete() {
    remove_action( 'wp_scheduled_delete', 'wp_scheduled_delete' );
}
add_action( 'init', 'devise_remove_schedule_delete' );

// Плагин Yoast: отменяет создание автоматических редиректов
add_filter('wpseo_premium_post_redirect_slug_change', '__return_true' );
// Плагин Yoast END


// Добавляет кнопки + и - для выбора количества товаров на странице товара
add_action( 'woocommerce_after_add_to_cart_quantity', 'ts_quantity_plus_sign' );

function ts_quantity_plus_sign() {
    echo '<button type="button" class="plus" >+</button></div>';
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'ts_quantity_minus_sign' );

function ts_quantity_minus_sign() {
    echo '<div class="qua-custom"><button type="button" class="minus" >-</button>';
}

add_action( 'wp_footer', 'ts_quantity_plus_minus' );

// To run this on the single product page (buttons plus/minus);
function ts_quantity_plus_minus() {

    if ( function_exists( 'is_product' ) && ! is_product() ) return;
    ?>
    <script type="text/javascript">
        window.onload = function () {
            jQuery(document).ready(function($){

                $('.cart .qty.text').attr('type', 'text');
                $('form.cart').on( 'click', 'button.plus, button.minus', function() {

                    var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
                    var val = parseFloat(qty.val());
                    var max = parseFloat(qty.attr( 'max' ));
                    var min = parseFloat(qty.attr( 'min' ));
                    var step = parseFloat(qty.attr( 'step' ));

                    if ( $( this ).is( '.plus' ) ) {
                        if ( max && ( max <= val ) ) {
                            qty.val( max );
                        }
                        else {
                            qty.val( val + step );
                        }
                    }
                    else {
                        if ( min && ( min >= val ) ) {
                            qty.val( min );
                        }
                        else if ( val > 1 ) {
                            qty.val( val - step );
                        }
                    }
                });
            });
        }
    </script>
    <?php
}
// Добавляет кнопки + и - END


//show hide content text

### Function: Enqueue JavaScripts
add_action( 'wp_enqueue_scripts', 'showhide_scripts' );
function showhide_scripts() {
    wp_enqueue_script( 'jquery' );
}

### Function: Load Translation
add_action( 'plugins_loaded', 'brainworks' );
function showhide_textdomain() {
    load_plugin_textdomain( 'brainworks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

### Function: Short Code For Inserting Press Release Into Post
add_shortcode( 'showhide', 'showhide_shortcode' );
function showhide_shortcode( $atts, $content = null ) {
    // Variables
    $post_id = get_the_id();
    $word_count = number_format_i18n( sizeof( explode( ' ', strip_tags( $content ) ) ) );

    // Extract ShortCode Attributes
    $attributes = shortcode_atts( array(
        'type' => 'pressrelease',
        'more_text' => __( 'Show Press Release (%s More Words)', 'wp-showhide' ),
        'less_text' => __( 'Hide Press Release (%s Less Words)', 'wp-showhide' ),
        'hidden' => 'yes'
    ), $atts );

    // More/Less Text
    $more_text = sprintf( $attributes['more_text'], $word_count );
    $less_text = sprintf( $attributes['less_text'], $word_count );

    // Determine Whether To Show Or Hide Press Release
    $hidden_class = 'sh-hide';
    $hidden_css = 'display: none;';
    $hidden_aria_expanded = 'false';
    if( $attributes['hidden'] === 'no' ) {
        $hidden_class = 'sh-show';
        $hidden_css = 'display: block;';
        $hidden_aria_expanded = 'true';
        $tmp_text = $more_text;
        $more_text = $less_text;
        $less_text = $tmp_text;
    }

    // Format HTML Output
    $output = '<div id="' . $attributes['type'] . '-content-' . $post_id . '" class="sh-content ' . $attributes['type'] . '-content ' . $hidden_class . '" style="' . $hidden_css . '">' . do_shortcode( $content ) . '</div>';
    $output .= '<div id="' . $attributes['type'] . '-link-' . $post_id . '" class="sh-link ' . $attributes['type'] . '-link js-show-more ' . $hidden_class .'"><a href="#" class="btn-show-more" onclick="showhide_toggle(\'' . esc_js( $attributes['type'] ) . '\', ' . $post_id . ', \'' . esc_js( $more_text ) . '\', \'' . esc_js( $less_text ) . '\'); return false;" aria-expanded="' . $hidden_aria_expanded .'"><span id="' . $attributes['type'] . '-toggle-' . $post_id . '">' . $more_text . '</span></a></div>';

    return $output;
}

### Function: Add JavaScript To Footer
add_action( 'wp_footer', 'showhide_footer' );
function showhide_footer() {
    ?>
    <?php if( WP_DEBUG ): ?>
        <script type="text/javascript">
            function showhide_toggle(type, post_id, more_text, less_text) {
                var   $link = jQuery("#"+ type + "-link-" + post_id)
                    , $link_a = jQuery('a', $link)
                    , $content = jQuery("#"+ type + "-content-" + post_id)
                    , $toggle = jQuery("#"+ type + "-toggle-" + post_id)
                    , show_hide_class = 'sh-show sh-hide';
                $link.toggleClass(show_hide_class);
                $content.toggleClass(show_hide_class).toggle();
                if($link_a.attr('aria-expanded') === 'true') {
                    $link_a.attr('aria-expanded', 'false');
                } else {
                    $link_a.attr('aria-expanded', 'true');
                }
                if($toggle.text() === more_text) {
                    $toggle.text(less_text);
                    $link.trigger( "sh-link:more" );
                } else {
                    $toggle.text(more_text);
                    $link.trigger( "sh-link:less" );
                }
                $link.trigger( "sh-link:toggle" );
            }
        </script>
    <?php else : ?>
        <script type="text/javascript">function showhide_toggle(e,t,r,g){var a=jQuery("#"+e+"-link-"+t),s=jQuery("a",a),i=jQuery("#"+e+"-content-"+t),l=jQuery("#"+e+"-toggle-"+t);a.toggleClass("sh-show sh-hide"),i.toggleClass("sh-show sh-hide").toggle(),"true"===s.attr("aria-expanded")?s.attr("aria-expanded","false"):s.attr("aria-expanded","true"),l.text()===r?(l.text(g),a.trigger("sh-link:more")):(l.text(r),a.trigger("sh-link:less")),a.trigger("sh-link:toggle")}</script>
    <?php endif; ?>
    <?php
}
