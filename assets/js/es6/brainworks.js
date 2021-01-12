'use strict';

((w, d, $, ajax) => {

    $(() => {
        console.log('%cThe website developed by BRAIN WORKS — https://brainworks.pro/', 'color: blue');
        console.log('%cСайт разработан в BRAIN WORKS — https://brainworks.pro/', 'color: blue');

        const $w = $(w);
        const $d = $(d);
        const html = $('html');
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        if (isMobile) {
            html.addClass('is-mobile');
        }

        html.removeClass('no-js').addClass('js');

        dropdownPhone();
        scrollToElement();
        sidebarAccordion();
        updateCartTotalValue('#modal-cart');
        reviews('.js-reviews');
        scrollTop('.js-scroll-top');
        wrapHighlightedElements('.highlighted');
        if (ajax) {
            ajaxLoadMorePosts('.js-load-more', '.js-ajax-posts');
        }
        stickHeader('.js-header');
        stickFooter('.js-footer', '.js-container');
        // hamburgerMenu('.js-menu', '.js-hamburger', '.js-menu-close');
        anotherHamburgerMenu('.js-menu', '.js-hamburger', '.js-menu-close');
        buyOneClick('.one-click', '[data-field-id="field11"]', 'h1');
        // On Copy
        $d.on('copy', addLink);

        $w.on('resize', () => {
            if ($w.innerWidth() >= 630) {
                removeAllStyles($('.js-menu'));
            }
        });

        addLightBoxHandlerForImage('.wpgis-slider-for');
    });

    /**
     * Dropdown Phone
     *
     * @example
     * dropdownPhone();
     *
     * @returns {void}
     */
    const dropdownPhone = () => {
        const dropDownBtn = $('.js-dropdown');
        const dropDownList = $('.js-phone-list');

        dropDownBtn.on('click', function () {
            $(this).toggleClass('active').siblings('.js-phone-list').fadeToggle(300);
        });

        $(document).on('click', (event) => {
            if ($(event.target).closest('.js-dropdown, .js-phone-list').length) return;

            dropDownList.fadeOut(300);
            dropDownBtn.removeClass('active');
        });
    };

    /**
     * Stick Header
     *
     * @example
     * stickHeader('.js-header');
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @param {(string|Object)} element - element
     * @returns {void}
     */
    const stickHeader = (element) => {
        const w = $(window);
        const d = $(document);
        const windowHeight = w.height();
        const documentHeight = d.height();

        let resizeArr = [];
        let resizeTimeout = 0;

        /**
         * Debounce Resized
         *
         * @private
         * @returns {void}
         */
        const _debounceResized = () => {
            clearTimeout(resizeTimeout);

            resizeTimeout = setTimeout(() => {
                if (resizeArr.length) {
                    for (let i = 0; i < resizeArr.length; i++) {
                        resizeArr[i]();
                    }
                }
            }, 50);
        };

        _debounceResized();
        w.on('ready load resize orientationchange', _debounceResized);

        /**
         * Debounce Resize
         *
         * @param {Object} callback - function
         * @private
         * @returns {void}
         */
        const _debounceResize = (callback) => {
            if (typeof callback === 'function') {
                resizeArr.push(callback);
            } else {
                window.dispatchEvent(new Event('resize'));
            }
        };

        let didScroll = 0;
        let lastScrollTop = 0;
        let hideOnScrollList = [];

        w.on('scroll load resize orientationchange', () => {
            if (hideOnScrollList.length) didScroll = true;
        });

        /**
         * Has Scrolled
         *
         * @private
         * @returns {void}
         */
        const _hasScrolled = () => {
            let type = '';
            const scrollTop = w.scrollTop();

            if (scrollTop > lastScrollTop) {
                type = 'down';
            } else if (scrollTop < lastScrollTop) {
                type = 'up';
            } else {
                type = 'none';
            }

            if (scrollTop === 0) {
                type = 'start';
            } else if (scrollTop >= documentHeight - windowHeight) {
                type = 'end';
            }

            hideOnScrollList.forEach((callback) => {
                if (typeof callback === 'function') {
                    callback(type, scrollTop, lastScrollTop, w);
                }
            });

            lastScrollTop = scrollTop;
        };

        setInterval(() => {
            if (didScroll) {
                didScroll = false;

                window.requestAnimationFrame(_hasScrolled);
            }
        }, 250);

        /**
         * Throttle Scroll
         *
         * @param {Object} callback - function
         * @private
         * @returns {void}
         */
        const _throttleScroll = (callback) => {
            hideOnScrollList.push(callback);
        };

        const start = 400;
        const hideClass = 'onscroll-hide';
        const showClass = 'onscroll-show';
        const header = $(element);
        const headerFake = $('<div>').hide();
        const autoHideHeader = header.filter('.is-autohide');
        const headerOffsetTop = header.length ? header.offset().top : 0;
        //const headerOffsetTop = windowHeight;

        let stickyOn;

        /**
         * Handler on scroll
         *
         * @private
         * @returns {void}
         */
        const _onScroll = () => {
            stickyOn = w.scrollTop() >= headerOffsetTop;

            if (stickyOn) {
                header.addClass('is-fixed');
                headerFake.show();
            } else {
                header.removeClass('is-fixed');
                headerFake.hide();
            }
        };

        if (header.hasClass('is-sticky')) {
            header.after(headerFake);
            headerFake.height(header.innerHeight());

            _debounceResize(() => {
                headerFake.height(header.innerHeight());
            });

            _onScroll();
            w.on('scroll resize', _onScroll);
        }

        _throttleScroll((type, scroll) => {
            if (type === 'down' && scroll > start) {
                autoHideHeader.removeClass(showClass).addClass(hideClass);
            } else if (type === 'up' /*|| type === 'end'*/ || type === 'start') {
                autoHideHeader.removeClass(hideClass).addClass(showClass);
            }

            if (header.hasClass('is-transparent') && header.hasClass('is-sticky')) {
                header[(scroll > 70 ? 'add' : 'remove') + 'Class']('is-solid');
            }
        });
    };

    /**
     * Stick Footer
     *
     * @example
     * stickFooter('.js-footer', '.js-wrapper');
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @param {(string|Object)} element - footer element
     * @param {(string|Object)} container - container element
     * @returns {void}
     */
    const stickFooter = (element, container) => {
        let previousHeight, currentHeight;

        const offset = 0;
        const $footer = $(element);
        const $container = $(container);

        currentHeight = ($footer.outerHeight() + offset) + 'px';
        previousHeight = currentHeight;

        $container.css('paddingBottom', currentHeight);

        $(window).on('resize', () => {
            currentHeight = ($footer.outerHeight() + offset) + 'px';

            if (previousHeight !== currentHeight) {
                $container.css('paddingBottom', currentHeight);
            }
        });
    };

    /**
     * Reviews - Slick Slider
     *
     * @example
     * reviews('.js-reviews');
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @param {(string|Object)} container - reviews container
     * @returns {void}
     */
    const reviews = (container) => {
        const element = $(container);

        if (element.children().length > 1 && typeof $.fn.slick === 'function') {
            element.slick({
                adaptiveHeight: false,
                autoplay: false,
                autoplaySpeed: 3000,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev">&lsaquo;</button>',
                nextArrow: '<button type="button" class="slick-next">&rsaquo;</button>',
                dots: false,
                dotsClass: 'slick-dots',
                draggable: true,
                fade: false,
                infinite: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ],
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 300,
                swipe: true,
                zIndex: 10,
            });

            /*element.on('swipe', (slick, direction) => {
                console.log(slick, direction);
            });

            element.on('afterChange', (slick, currentSlide) => {
                console.log(slick, currentSlide);
            });

            element.on('beforeChange', (slick, currentSlide, nextSlide) => {
                console.log(slick, currentSlide, nextSlide);
            });*/
        }
    };

    /**
     * Hamburger Menu
     *
     * @example
     * hamburgerMenu('.js-menu', '.js-hamburger', '.js-menu-close');
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @param {(string|Object)} menuElement - Selected menu
     * @param {(string|Object)} hamburgerElement - Trigger element for open/close menu
     * @param {(string|Object)} closeTrigger - Trigger element for close opened menu
     * @returns {void}
     */
    /*const hamburgerMenu = (menuElement, hamburgerElement, closeTrigger) => {
        const menu = $(menuElement),
            close = $(closeTrigger),
            hamburger = $(hamburgerElement),
            menuAll = hamburger.add(menu);

        hamburger.add(close).on('click', () => {
            menu.toggleClass('is-active');
        });

        $(window).on('load', (event) => {
            if (document.location.hash !== '') {
                scrollToElement(document.location.hash);
            }
        });

        $(window).on('click', (e) => {
            if (!$(e.target).closest(menuAll).length) {
                menu.removeClass('is-active');
            }
        });
    };*/

    /**
     * Scroll to element
     *
     * @param {(string|Object)} elements Elements to add to handler
     * @returns {void}
     */
    /*const scrollHandlerForButton = (elements) => {
        elements = $(elements);

        let i, el;

        for (i = 0; i < elements.length; i++) {

            el = elements.eq(i);

            el.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                scrollToElement($(e.target.hash), () => {
                    document.location.hash = e.target.hash;
                });
            });

        }
    };*/


    /**
     * Another Hamburger Menu
     *
     * @param {string} menuElement Selector or element
     * @param {string} hamburgerElement Selector or element
     * @param {string} closeTrigger Also selector or element
     * @returns {void}
     */
    const anotherHamburgerMenu = (menuElement, hamburgerElement, closeTrigger) => {
        const Elements = {
            menu: $(menuElement),
            button: $(hamburgerElement),
            close: $(closeTrigger),
        };

        Elements.button.add(Elements.close).on('click', () => {
            Elements.menu.toggleClass('is-active');
        });

        Elements.menu.find('a').on('click', () => {
            Elements.menu.removeClass('is-active');
        });

        /**
         * Arrow Opener
         *
         * @param {Object} parent Selector or element
         * @returns {(Object)} jQuery element
         */
        const arrowOpener = function (parent) {
            const activeArrowClass = 'menu-item-has-children-arrow-active';

            return $('<button />')
                .addClass('menu-item-has-children-arrow')
                .on('click', function () {
                    parent.children('.sub-menu').eq(0).slideToggle(300);
                    if ($(this).hasClass(activeArrowClass)) {
                        $(this).removeClass(activeArrowClass);
                    } else {
                        $(this).addClass(activeArrowClass);
                    }

                });
        };

        const items = Elements.menu.find('.menu-item-has-children, .sub-menu-item-has-children');

        for (let i = 0; i < items.length; i++) {
            items.eq(i).append(arrowOpener(items.eq(i)));
        }
    };

    /**
     * Remove All Styles from sub menu element
     *
     * @param {Object} elementParent selector or element
     * @returns {void}
     */
    const removeAllStyles = (elementParent) => {
        elementParent.find('.sub-menu').removeAttr('style');
    };

    /**
     * Wrap all highlighted elements in container
     *
     * @param {(string|Object)} elements selector or elements
     * @returns {void}
     */
    const wrapHighlightedElements = (elements) => {
        elements = $(elements);

        let i, highlightedHeader;

        for (i = 0; i < elements.length; i++) {
            highlightedHeader = elements.eq(i);

            highlightedHeader.wrap('<div style="display: block;"></div>');
        }
    };

    /**
     * Buy in one click
     *
     * @example
     * buyOneClick('.one-click', '[data-field-id="field7"]', 'h1.page-name');
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @param {(string|Object)} button - The selected button when clicking on which the form of purchase pops up
     * @param {(string|Object)} field - The selected field for writing the value (disabled field)
     * @param {(string|Object)} headline - The element from which we get the value to write to the field
     * @returns {void}
     */
    const buyOneClick = (button, field, headline) => {
        const btn = $(button);

        if (btn.length) {
            btn.on('click', () => {
                $(field).prop('disabled', true).val($(headline).text());
            });
        }
    };

    /**
     * Scroll Top
     *
     * @example
     * scrollTop('.js-scroll-top');
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @param {(string|Object)} element - Selected element
     * @returns {void}
     */
    const scrollTop = (element) => {
        const el = $(element);

        el.on('click touchend', () => {
            $('html, body').animate({scrollTop: 0}, 'slow');
            return false;
        });

        let scrollPosition;

        $(window).on('scroll', function () {
            scrollPosition = $(this).scrollTop();

            if (scrollPosition > 200) {
                if (!el.hasClass('is-visible')) {
                    el.addClass('is-visible');
                }
            } else {
                el.removeClass('is-visible');
            }
        });
    };

    /**
     * Adding link to the site resource at copying
     *
     * @example
     * document.oncopy = addLink; or $(document).on('copy', addLink);
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @returns {void}
     */
    const addLink = () => {
        const body = document.body || document.getElementsByTagName('body')[0];
        const selection = window.getSelection();
        const page_link = '\n Источник: ' + document.location.href;
        const copy_text = selection + page_link;
        const div = document.createElement('div');

        div.style.position = 'absolute';
        div.style.left = '-9999px';

        body.appendChild(div);
        div.innerText = copy_text;

        selection.selectAllChildren(div);

        window.setTimeout(() => {
            body.removeChild(div);
        }, 0);
    };

    /**
     * Function to add scroll handler for all links with hash as first symbol of href
     *
     * @param {number} [animationSpeed=400] speed of animation
     * @returns {void}
     */
    const scrollToElement = (animationSpeed = 400) => {
        const links = $('a');

        links.each((index, element) => {
            const $element = $(element), href = $element.attr('href');
            if (href) {
                if (href[0] === '#' || href.slice(0, 2) === '/#' && !(href.slice(1, 3) === '__')) {
                    $element.on('click', (e) => {
                        e.preventDefault();
                        const target = $(href[0] === '#' ? href : href.slice(1));
                        const fixedHeader = $('.js-header');
                        const fixOffset = 20; // смещение блока его можно регулировать или стилями или тут, если не нужен ставим 0
                        let scrollBlockOffset;
                        if (target.length && href.slice(0, 3) !== '#__') { // проверка урла в аккордеоне

                            if (fixedHeader.length > 0 && $(window).width() > 1024) { // проверям наличие js-header в DOM дереве и ширина обьекта window больше 1024px (по желанию можно заменить на другое значение)
                                scrollBlockOffset = fixedHeader.outerHeight() + fixOffset; // если ширина больше 1024px делаем скролл с учетом высоты хедера и добавляем небольшой offset 'const fixOffset = 20' (по желанию конечно)
                            } else if (fixedHeader.length >= 0 && $(window).width() < 1024) { // если ширина меньше 1024px делаем скролл с учетом высоты моб. шапки nav-mobile-header
                                scrollBlockOffset = $('.nav-mobile-header').outerHeight() + fixOffset;
                            } else {
                                scrollBlockOffset = fixOffset; // если шапка не фиксированая просто используем наш offset
                            }

                            $('html, body').animate({
                                scrollTop: target.offset().top - scrollBlockOffset
                            }, animationSpeed);
                        } else if (href[0] === '/') {
                            location.href = href;
                        }
                    });
                }
            }
        });
    };
    /**
     * Sidebar Accordion
     *
     * @example
     * sidebarAccordion();
     *
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     *
     * @returns {void}
     */
    const sidebarAccordion = () => {
        const sidebarMenu = $('.left-sidebar .widget_nav_menu');
        const items = sidebarMenu.find('li');
        const subMenu = items.find('.sub-menu');

        if (subMenu.length) {
            subMenu.each(function (index, value) {
                $(value).parent().first().append('<i class="trigger"></i>');
            });
        }

        const classAction = 'is-opened';
        const trigger = items.find('.trigger');

        trigger.on('click', function () {
            const el = $(this), parent = el.parent();

            if (parent.hasClass(classAction)) {
                parent.removeClass(classAction);
            } else {
                parent.addClass(classAction);
            }
        });
    };

    /**
     * Ajax Load More Posts Handler
     *
     * @example
     * ajaxLoadMorePosts('.js-load-more', '.js-ajax-posts');
     * @author Fedor Kudinov <brothersrabbits@mail.ru>
     * @param {string} selector - Element for event handler (send ajax)
     * @param {string} container - The container to which the html markup will be added
     * @returns {void}
     */
    const ajaxLoadMorePosts = (selector, container) => {
        const btn = $(selector);
        const storage = $(container);
        let lastChildren;

        lastChildren = $(container).children().last();

        if (!btn.length && !storage.length) return;

        let data, ajaxStart;

        data = {
            'action': ajax.action,
            'nonce': ajax.nonce,
            'paged': 1,
        };

        btn.on('click', () => {
            if (ajaxStart) return;

            lastChildren = $(container).children().last();

            ajaxStart = true;

            btn.addClass('is-loading');

            $.ajax({
                'url': ajax.url,
                'method': 'POST',
                'dataType': 'json',
                'data': data,
            })
                .done((response) => {
                    const posts = response.data;
                    storage.append(response.data);

                    data.paged += 1;

                    if (posts !== '') {

                        $('html, body').animate({
                            scrollTop: $(lastChildren).offset().top
                        }, 1000);

                    }

                    ajaxStart = false;

                    btn.removeClass('is-loading');

                    if (posts === '') {
                        btn.remove();
                    }
                })
                .fail((jqXHR, textStatus, errorThrown) => {
                    ajaxStart = false;
                    throw new Error(`Handling Ajax request loading posts has caused an ${textStatus} - ${errorThrown}`);
                });

        });
    };

    /**
     * Add Light Box Handler For Image
     *
     * @description для плагина Advanced Woocommerce Product Gallery Slider
     * https://ru.wordpress.org/plugins/advanced-woocommerce-product-gallery-slider/
     *
     * @example
     * addLightBoxHandlerForImage('.wpgis-slider-for');
     *
     * @param {string} sliderContainer - Selected slider container
     *
     * @returns {void}
     */
    const addLightBoxHandlerForImage = (sliderContainer) => {

        const slider = $(sliderContainer);
        const verticalSlider = $('#wpgis-gallery');

        if (verticalSlider.find('.slick-slide').length < 5 && typeof $.fn.slick === 'function') {
            $(this).hide();
            $(sliderContainer).css({'width': '100%'});
        }

        $(window).on('load', () => {
            if (slider.length) {
                slider.find('img').each((index, element) => {
                    const el = $(element);

                    el.on('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                        el.parents('.slick-slide').find('.wpgis-popup').click();
                    });
                });
            }
        });
    };

    // Disabled page scroll
    $('.js-hamburger').on('click', function () {
        $('body').addClass('body-overflow');
    });

    $('.js-menu-close, .menu-link').on('click', function () {
        $('body').removeClass('body-overflow');
    });

    // Исправляет конфликт модального окна и галереи в карточке товара
    $('.form-cover input[type="text"]').on('focus', function () {
        $.fancybox.destroy();
    });

    /**
     *  Update totalCart value
     *
     *  Work with default WooCommerce Handler ver. 4.0.1
     *  Using default WooCommerce selector ver. 4.0.1
     *  Checked change total cart value for catalog items with button add to cart
     *
     *  Page checkout:
     *  -   cart popup disabled,
     *  -   update correct total value,
     *  -   fixed correct value when using shipping method
     *
     *  @example
     *  updateCartTotalValue('#modal-cart');
     *
     *  @param {string} elemId - wrapper id for modal cart widget
     *
     *  @returns {void}
     *
     */
    const updateCartTotalValue = (elemId) => {
        /*global wc_cart_fragments_params*/

        localStorage.setItem('currency', $('#cyr-value').val());
        const totalId = $(elemId);

        if ($(elemId).length > 0) {

            $(document).bind('ajaxStop.mine', function () {

                if ($('.shop_table').length > 0) {
                    totalId.css('pointerEvents', 'none');
                    let checkoutTotalValue = $('.shop_table .amount').text();
                    totalId.find('.amount').first().text(checkoutTotalValue);
                }

                if (sessionStorage.getItem(wc_cart_fragments_params.fragment_name) !== null) {

                    let sessionHash = sessionStorage.getItem(wc_cart_fragments_params.fragment_name);
                    let parseValue = JSON.parse(sessionHash);
                    let totalValueCart;
                    let totalValueCyr;

                    $.each(parseValue, (key, value) => {

                        if (key == 'div.widget_shopping_cart_content') {

                            let cartModalContent = $(value).text();
                            let cartContentString = cartModalContent.split(':').pop();
                            totalValueCart = Array.from(cartContentString.split('.'))[0];
                            totalValueCyr = localStorage.getItem('currency');

                        } else if ($('.cart-contents-count').text() < 1) {

                            totalId.find('.amount').first().text('0 ' + totalValueCyr);

                        } else {
                            totalId.find('.amount').first().text(totalValueCart + '.');

                        }
                    });
                }
            });
        }
    };

    $(window).load(function () {
        $(document.body).trigger('wc_fragment_refresh');
    });

})(window, document, jQuery, window.jpAjax);
