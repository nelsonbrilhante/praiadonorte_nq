<?php
/**
 * Kadence - Praia do Norte Child Theme
 *
 * Surf premium child theme for Praia do Norte WooCommerce store.
 */

// Enqueue parent + child styles, Google Fonts, and navigation JS
add_action('wp_enqueue_scripts', function () {
    // Google Fonts: Montserrat (headings) + Inter (body) — matches Laravel frontend
    wp_enqueue_style(
        'pn-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700;800&display=swap',
        [],
        null
    );

    // Parent theme style (Kadence handles its own enqueue, but ensure dependency)
    wp_enqueue_style('kadence-global', get_template_directory_uri() . '/assets/css/global.css', [], null);

    // Child theme custom CSS
    wp_enqueue_style(
        'pn-custom',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        ['kadence-global'],
        '2.0.0'
    );

    // Navigation JS (mobile menu + dropdowns)
    wp_enqueue_script(
        'pn-navigation',
        get_stylesheet_directory_uri() . '/assets/js/navigation.js',
        [],
        '1.0.0',
        true
    );
}, 20);

// Dynamic locale based on ?lang= param or cookie
add_filter('locale', function ($locale) {
    if (is_admin()) return $locale;
    return pn_get_wp_locale();
});

// Ensure cookie is set early (before output)
add_action('init', function () {
    if (!is_admin()) pn_get_locale();
}, 1);

// --- WooCommerce Portuguese text overrides ---

// "Add to cart" → "Adicionar ao Carrinho"
add_filter('woocommerce_product_single_add_to_cart_text', function ($text) {
    if (pn_get_locale() === 'en') return $text;
    return 'Adicionar ao Carrinho';
});

add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) {
    if (pn_get_locale() === 'en') return $text;
    if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
        return 'Adicionar ao Carrinho';
    }
    if ($product->is_type('variable')) {
        return 'Selecionar Opções';
    }
    if (!$product->is_in_stock()) {
        return 'Esgotado';
    }
    return $text;
}, 10, 2);

// "Sale!" badge → "Promoção!"
add_filter('woocommerce_sale_flash', function ($html) {
    if (pn_get_locale() === 'en') return $html;
    return '<span class="onsale">Promoção!</span>';
});

// Cart page strings
add_filter('woocommerce_cart_item_remove_link', function ($link) {
    if (pn_get_locale() === 'en') return $link;
    return str_replace('Remove this item', 'Remover este item', $link);
});

// "View cart" → "Ver Carrinho"
add_filter('wc_add_to_cart_message_html', function ($message) {
    if (pn_get_locale() === 'en') return $message;
    $message = str_replace('View cart', 'Ver Carrinho', $message);
    return $message;
});

// Checkout button text
add_filter('woocommerce_order_button_text', function ($text) {
    if (pn_get_locale() === 'en') return $text;
    return 'Finalizar Encomenda';
});

// "Return to shop" → "Voltar à Loja"
add_filter('woocommerce_return_to_shop_text', function ($text) {
    if (pn_get_locale() === 'en') return $text;
    return 'Voltar à Loja';
});

// Product tabs: rename to Portuguese
add_filter('woocommerce_product_tabs', function ($tabs) {
    if (pn_get_locale() === 'en') return $tabs;
    if (isset($tabs['description'])) {
        $tabs['description']['title'] = 'Descrição';
    }
    if (isset($tabs['additional_information'])) {
        $tabs['additional_information']['title'] = 'Informação Adicional';
    }
    if (isset($tabs['reviews'])) {
        $tabs['reviews']['title'] = 'Avaliações';
    }
    return $tabs;
}, 98);

// "Related products" heading
add_filter('woocommerce_product_related_products_heading', function ($heading) {
    if (pn_get_locale() === 'en') return $heading;
    return 'Produtos Relacionados';
});

// Category count text
add_filter('woocommerce_subcategory_count_html', function ($html) {
    if (pn_get_locale() === 'en') return $html;
    return str_replace(['product', 'products'], ['produto', 'produtos'], $html);
});

// --- Custom Header & Footer (replaces Kadence defaults) ---

/**
 * Get Laravel frontend URL from WP option (set during setup).
 */
function pn_get_laravel_url() {
    return rtrim(get_option('pn_laravel_url', 'http://localhost:8000'), '/');
}

/**
 * Get current frontend locale: (1) ?lang= param, (2) pn_locale cookie, (3) 'pt' default.
 */
function pn_get_locale() {
    static $locale = null;
    if ($locale !== null) return $locale;

    $valid = ['pt', 'en'];

    if (isset($_GET['lang']) && in_array($_GET['lang'], $valid, true)) {
        $locale = $_GET['lang'];
        if (!headers_sent()) {
            setcookie('pn_locale', $locale, [
                'expires' => time() + 30 * 86400, 'path' => '/',
                'secure' => is_ssl(), 'httponly' => false, 'samesite' => 'Lax',
            ]);
        }
        return $locale;
    }

    if (isset($_COOKIE['pn_locale']) && in_array($_COOKIE['pn_locale'], $valid, true)) {
        $locale = $_COOKIE['pn_locale'];
        return $locale;
    }

    $locale = 'pt';
    return $locale;
}

function pn_get_wp_locale() {
    return pn_get_locale() === 'en' ? 'en_US' : 'pt_PT';
}

/** Build a Laravel URL with the correct locale prefix. */
function pn_laravel_url(string $path = ''): string {
    return pn_get_laravel_url() . '/' . pn_get_locale() . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Translation helper for header/footer navigation strings.
 */
function pn_t(string $key): string {
    static $strings = null;
    if ($strings === null) {
        $strings = [
            'pt' => [
                'nav.about' => 'Sobre', 'nav.team' => 'Equipa', 'nav.services' => 'Serviços',
                'nav.violations' => 'Contraordenações', 'nav.documents' => 'Documentos',
                'nav.parking' => 'Estacionamento', 'nav.fort' => 'Forte', 'nav.ale' => 'ALE',
                'nav.surferWall' => 'Surfer Wall', 'nav.forecast' => 'Previsões',
                'nav.programs' => 'Programas', 'nav.events' => 'Eventos', 'nav.news' => 'Notícias',
                'nav.shop' => 'Loja', 'nav.cart' => 'Carrinho',
                'nav.mainNav' => 'Navegação principal', 'nav.mobileNav' => 'Navegação móvel',
                'footer.contact' => 'Contacto', 'footer.complaintsBook' => 'Livro de Reclamações',
                'footer.whistleblower' => 'Portal de Denúncias', 'footer.partners' => 'Parceiros',
                'footer.copyright' => 'Todos os direitos reservados.',
                'footer.privacy' => 'Política de Privacidade', 'footer.terms' => 'Termos de Uso',
                'footer.cookies' => 'Política de Cookies',
                'footer.address' => 'Rua da Praia do Norte, Centro de Alto Rendimento de Surf, 2450-504 Nazaré',
                'footer.landline' => '+351 262 550 010', 'footer.phone' => '934 000 126',
                'footer.cmNazare' => 'Câmara Municipal da Nazaré',
                'footer.jfNazare' => 'Junta de Freguesia da Nazaré',
                'footer.legalNav' => 'Links legais',
                'footer.externalLink' => 'abre numa nova janela',
                'footer.nq.team' => 'Corpos Sociais',
                'footer.nq.documents' => 'Ética e Transparência',
                'footer.nq.violations' => 'Contraordenações',
                'footer.nq.fort' => 'Forte de S. Miguel Arcanjo',
                'breadcrumb.home' => 'Início', 'breadcrumb.shop' => 'Loja',
                'lang.switchTo' => 'EN', 'lang.label' => 'Idioma',
            ],
            'en' => [
                'nav.about' => 'About', 'nav.team' => 'Team', 'nav.services' => 'Services',
                'nav.violations' => 'Violations', 'nav.documents' => 'Documents',
                'nav.parking' => 'Parking', 'nav.fort' => 'Fort', 'nav.ale' => 'ALE',
                'nav.surferWall' => 'Surfer Wall', 'nav.forecast' => 'Forecast',
                'nav.programs' => 'Programs', 'nav.events' => 'Events', 'nav.news' => 'News',
                'nav.shop' => 'Shop', 'nav.cart' => 'Cart',
                'nav.mainNav' => 'Main navigation', 'nav.mobileNav' => 'Mobile navigation',
                'footer.contact' => 'Contact', 'footer.complaintsBook' => 'Complaints Book',
                'footer.whistleblower' => 'Whistleblower Portal', 'footer.partners' => 'Partners',
                'footer.copyright' => 'All rights reserved.',
                'footer.privacy' => 'Privacy Policy', 'footer.terms' => 'Terms of Use',
                'footer.cookies' => 'Cookie Policy',
                'footer.address' => 'Rua da Praia do Norte, Centro de Alto Rendimento de Surf, 2450-504 Nazaré',
                'footer.landline' => '+351 262 550 010', 'footer.phone' => '934 000 126',
                'footer.cmNazare' => 'Nazaré City Council',
                'footer.jfNazare' => 'Nazaré Parish Council',
                'footer.legalNav' => 'Legal links',
                'footer.externalLink' => 'opens in a new window',
                'footer.nq.team' => 'Corporate Bodies',
                'footer.nq.documents' => 'Ethics & Transparency',
                'footer.nq.violations' => 'Traffic Violations',
                'footer.nq.fort' => 'Fort of São Miguel Arcanjo',
                'breadcrumb.home' => 'Home', 'breadcrumb.shop' => 'Shop',
                'lang.switchTo' => 'PT', 'lang.label' => 'Language',
            ],
        ];
    }
    $locale = pn_get_locale();
    return $strings[$locale][$key] ?? $strings['pt'][$key] ?? $key;
}

/**
 * Remove Kadence header/footer and inject custom ones matching Laravel site.
 */
add_action('after_setup_theme', function () {
    // Remove Kadence default widgets that may contain demo content
    remove_action('kadence_top_header', 'Kadence\top_header_row', 10);
}, 20);

add_action('wp', function () {
    // Remove all Kadence header actions
    remove_all_actions('kadence_header');
    // Remove all Kadence footer actions
    remove_all_actions('kadence_footer');

    // Add custom header and footer
    add_action('kadence_header', 'pn_render_custom_header');
    add_action('kadence_footer', 'pn_render_custom_footer');
});

/**
 * Render custom header matching Laravel site.
 */
function pn_render_custom_header() {
    $theme_uri = get_stylesheet_directory_uri();
    $cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/carrinho/');
    $lang_toggle_url = esc_url(add_query_arg('lang', pn_get_locale() === 'pt' ? 'en' : 'pt'));
    ?>
    <header class="pn-header">
        <div class="pn-header-inner">
            <!-- Left: Logo -->
            <a href="<?php echo esc_url(pn_laravel_url()); ?>" class="pn-logo">
                <img src="<?php echo esc_url($theme_uri . '/assets/images/imagem-grafica-nq-original-name.svg'); ?>" alt="Nazaré Qualifica" class="pn-logo-img">
            </a>

            <!-- Center: Nav Pill (desktop) -->
            <nav class="pn-nav-desktop" aria-label="<?php echo esc_attr(pn_t('nav.mainNav')); ?>">
                <div class="pn-nav-pill">
                    <!-- NQ Dropdown -->
                    <div class="pn-dropdown">
                        <button class="pn-nav-link pn-dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true">
                            Nazaré Qualifica
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="pn-dropdown-menu">
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/sobre')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.about')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/equipa')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.team')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/servicos')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.services')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/contraordenacoes')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.violations')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/documentos')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.documents')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/estacionamento')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.parking')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/forte')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.fort')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/ale')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.ale')); ?></a>
                        </div>
                    </div>

                    <!-- Praia do Norte Dropdown -->
                    <div class="pn-dropdown">
                        <button class="pn-nav-link pn-dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true">
                            Praia do Norte
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="pn-dropdown-menu">
                            <a href="<?php echo esc_url(pn_laravel_url('sobre')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.about')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('surfer-wall')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.surferWall')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('previsoes')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.forecast')); ?></a>
                        </div>
                    </div>

                    <!-- Carsurf Dropdown -->
                    <div class="pn-dropdown">
                        <button class="pn-nav-link pn-dropdown-trigger" type="button" aria-expanded="false" aria-haspopup="true">
                            Carsurf
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-chevron"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="pn-dropdown-menu">
                            <a href="<?php echo esc_url(pn_laravel_url('carsurf')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.about')); ?></a>
                            <a href="<?php echo esc_url(pn_laravel_url('carsurf/programas')); ?>" class="pn-dropdown-item"><?php echo esc_html(pn_t('nav.programs')); ?></a>
                        </div>
                    </div>

                    <!-- Eventos -->
                    <a href="<?php echo esc_url(pn_laravel_url('eventos')); ?>" class="pn-nav-link"><?php echo esc_html(pn_t('nav.events')); ?></a>

                    <!-- Notícias -->
                    <a href="<?php echo esc_url(pn_laravel_url('noticias')); ?>" class="pn-nav-link"><?php echo esc_html(pn_t('nav.news')); ?></a>

                    <!-- Loja (active, internal) -->
                    <a href="<?php echo esc_url(home_url('/loja/')); ?>" class="pn-nav-link pn-nav-active">
                        <?php echo esc_html(pn_t('nav.shop')); ?>
                        <span class="pn-nav-underline"></span>
                    </a>
                </div>
            </nav>

            <!-- Right: Lang Toggle + Cart + Mobile Toggle -->
            <div class="pn-header-right">
                <a href="<?php echo $lang_toggle_url; ?>"
                   class="pn-lang-toggle"
                   title="<?php echo esc_attr(pn_t('lang.label')); ?>">
                    <?php echo esc_html(pn_t('lang.switchTo')); ?>
                </a>
                <a href="<?php echo esc_url($cart_url); ?>" class="pn-cart-icon" title="<?php echo esc_attr(pn_t('nav.cart')); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <span class="pn-cart-badge" <?php echo $cart_count > 0 ? '' : 'style="display:none"'; ?>><?php echo esc_html($cart_count); ?></span>
                </a>
                <button type="button" class="pn-mobile-toggle" aria-label="Menu" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-hamburger-icon"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-close-icon" style="display:none"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="pn-mobile-menu" aria-hidden="true">
            <nav aria-label="<?php echo esc_attr(pn_t('nav.mobileNav')); ?>">
                <!-- Language toggle (mobile) -->
                <div class="pn-mobile-direct-links" style="border-bottom: 1px solid var(--pn-gray-light); padding-bottom: 0.5rem; margin-bottom: 0.5rem;">
                    <a href="<?php echo $lang_toggle_url; ?>" style="font-weight: 600; color: var(--pn-ocean-primary);">
                        <?php echo esc_html(pn_t('lang.switchTo')); ?> — <?php echo esc_html(pn_t('lang.label')); ?>
                    </a>
                </div>

                <!-- NQ Section -->
                <div class="pn-mobile-section">
                    <button class="pn-mobile-section-toggle" type="button" aria-expanded="false">
                        Nazaré Qualifica
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-chevron"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="pn-mobile-section-items">
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/sobre')); ?>"><?php echo esc_html(pn_t('nav.about')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/equipa')); ?>"><?php echo esc_html(pn_t('nav.team')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/servicos')); ?>"><?php echo esc_html(pn_t('nav.services')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/contraordenacoes')); ?>"><?php echo esc_html(pn_t('nav.violations')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/documentos')); ?>"><?php echo esc_html(pn_t('nav.documents')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/estacionamento')); ?>"><?php echo esc_html(pn_t('nav.parking')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/forte')); ?>"><?php echo esc_html(pn_t('nav.fort')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/ale')); ?>"><?php echo esc_html(pn_t('nav.ale')); ?></a>
                    </div>
                </div>

                <!-- PN Section -->
                <div class="pn-mobile-section">
                    <button class="pn-mobile-section-toggle" type="button" aria-expanded="false">
                        Praia do Norte
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-chevron"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="pn-mobile-section-items">
                        <a href="<?php echo esc_url(pn_laravel_url('sobre')); ?>"><?php echo esc_html(pn_t('nav.about')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('surfer-wall')); ?>"><?php echo esc_html(pn_t('nav.surferWall')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('previsoes')); ?>"><?php echo esc_html(pn_t('nav.forecast')); ?></a>
                    </div>
                </div>

                <!-- Carsurf Section -->
                <div class="pn-mobile-section">
                    <button class="pn-mobile-section-toggle" type="button" aria-expanded="false">
                        Carsurf
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pn-chevron"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="pn-mobile-section-items">
                        <a href="<?php echo esc_url(pn_laravel_url('carsurf')); ?>"><?php echo esc_html(pn_t('nav.about')); ?></a>
                        <a href="<?php echo esc_url(pn_laravel_url('carsurf/programas')); ?>"><?php echo esc_html(pn_t('nav.programs')); ?></a>
                    </div>
                </div>

                <!-- Direct links -->
                <div class="pn-mobile-direct-links">
                    <a href="<?php echo esc_url(pn_laravel_url('eventos')); ?>"><?php echo esc_html(pn_t('nav.events')); ?></a>
                    <a href="<?php echo esc_url(pn_laravel_url('noticias')); ?>"><?php echo esc_html(pn_t('nav.news')); ?></a>
                    <a href="<?php echo esc_url(home_url('/loja/')); ?>" class="pn-mobile-active"><?php echo esc_html(pn_t('nav.shop')); ?></a>
                </div>
            </nav>
        </div>
    </header>
    <?php
}

/**
 * Render custom footer matching Laravel site.
 */
function pn_render_custom_footer() {
    $theme_uri = get_stylesheet_directory_uri();
    $year = date('Y');
    ?>
    <footer class="pn-footer">
        <div class="pn-footer-container">
            <!-- 4-column grid -->
            <div class="pn-footer-grid">
                <!-- Column 1: Praia do Norte -->
                <nav aria-label="Praia do Norte">
                    <h3>Praia do Norte</h3>
                    <ul>
                        <li><a href="<?php echo esc_url(pn_laravel_url('sobre')); ?>"><?php echo esc_html(pn_t('nav.about')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('noticias')); ?>"><?php echo esc_html(pn_t('nav.news')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('eventos')); ?>"><?php echo esc_html(pn_t('nav.events')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('surfer-wall')); ?>"><?php echo esc_html(pn_t('nav.surferWall')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('previsoes')); ?>"><?php echo esc_html(pn_t('nav.forecast')); ?></a></li>
                    </ul>
                </nav>

                <!-- Column 2: Carsurf -->
                <nav aria-label="Carsurf">
                    <h3>Carsurf</h3>
                    <ul>
                        <li><a href="<?php echo esc_url(pn_laravel_url('carsurf')); ?>"><?php echo esc_html(pn_t('nav.about')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('carsurf/programas')); ?>"><?php echo esc_html(pn_t('nav.programs')); ?></a></li>
                    </ul>
                </nav>

                <!-- Column 3: Nazaré Qualifica -->
                <nav aria-label="Nazaré Qualifica">
                    <h3>Nazaré Qualifica</h3>
                    <ul>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/sobre')); ?>"><?php echo esc_html(pn_t('nav.about')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/equipa')); ?>"><?php echo esc_html(pn_t('footer.nq.team')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/servicos')); ?>"><?php echo esc_html(pn_t('nav.services')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/documentos')); ?>"><?php echo esc_html(pn_t('footer.nq.documents')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/contraordenacoes')); ?>"><?php echo esc_html(pn_t('footer.nq.violations')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/estacionamento')); ?>"><?php echo esc_html(pn_t('nav.parking')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/forte')); ?>"><?php echo esc_html(pn_t('footer.nq.fort')); ?></a></li>
                        <li><a href="<?php echo esc_url(pn_laravel_url('nazare-qualifica/ale')); ?>"><?php echo esc_html(pn_t('nav.ale')); ?></a></li>
                    </ul>
                </nav>

                <!-- Column 4: Contacto -->
                <div>
                    <h3><?php echo esc_html(pn_t('footer.contact')); ?></h3>
                    <p class="pn-footer-address"><?php echo esc_html(pn_t('footer.address')); ?></p>
                    <p class="pn-footer-phone"><a href="tel:+351262550010"><?php echo esc_html(pn_t('footer.landline')); ?></a></p>
                    <p class="pn-footer-phone"><a href="tel:+351934000126"><?php echo esc_html(pn_t('footer.phone')); ?></a></p>
                    <p class="pn-footer-contact-link"><a href="<?php echo esc_url(pn_laravel_url('contacto')); ?>"><?php echo esc_html(pn_t('footer.contact')); ?></a></p>

                    <ul class="pn-footer-institutional">
                        <li><a href="https://www.livroreclamacoes.pt/Inicio/" target="_blank" rel="noopener noreferrer"><?php echo esc_html(pn_t('footer.complaintsBook')); ?> <span class="pn-ext" aria-hidden="true">&#8599;</span><span class="sr-only">(<?php echo esc_html(pn_t('footer.externalLink')); ?>)</span></a></li>
                        <li><a href="https://nazarequalifica.portaldedenuncias.pt/" target="_blank" rel="noopener noreferrer"><?php echo esc_html(pn_t('footer.whistleblower')); ?> <span class="pn-ext" aria-hidden="true">&#8599;</span><span class="sr-only">(<?php echo esc_html(pn_t('footer.externalLink')); ?>)</span></a></li>
                    </ul>

                    <h4><?php echo esc_html(pn_t('footer.partners')); ?></h4>
                    <ul>
                        <li><a href="https://www.cm-nazare.pt/" target="_blank" rel="noopener noreferrer"><?php echo esc_html(pn_t('footer.cmNazare')); ?> <span class="pn-ext" aria-hidden="true">&#8599;</span><span class="sr-only">(<?php echo esc_html(pn_t('footer.externalLink')); ?>)</span></a></li>
                        <li><a href="http://www.jf-nazare.pt/" target="_blank" rel="noopener noreferrer"><?php echo esc_html(pn_t('footer.jfNazare')); ?> <span class="pn-ext" aria-hidden="true">&#8599;</span><span class="sr-only">(<?php echo esc_html(pn_t('footer.externalLink')); ?>)</span></a></li>
                    </ul>
                </div>
            </div>

            <!-- NQ Logo -->
            <div class="pn-footer-logo">
                <a href="<?php echo esc_url(pn_laravel_url()); ?>">
                    <img src="<?php echo esc_url($theme_uri . '/assets/images/nq-horizontal-white.svg'); ?>" alt="Nazaré Qualifica" class="pn-footer-logo-img">
                </a>
            </div>

            <!-- Copyright bar -->
            <div class="pn-footer-copyright">
                <p>&copy; <?php echo esc_html($year); ?> Nazaré Qualifica, EM. <?php echo esc_html(pn_t('footer.copyright')); ?></p>
                <nav class="pn-footer-legal" aria-label="<?php echo esc_attr(pn_t('footer.legalNav')); ?>">
                    <a href="<?php echo esc_url(pn_laravel_url('privacidade')); ?>"><?php echo esc_html(pn_t('footer.privacy')); ?></a>
                    <a href="<?php echo esc_url(pn_laravel_url('termos')); ?>"><?php echo esc_html(pn_t('footer.terms')); ?></a>
                    <a href="<?php echo esc_url(pn_laravel_url('cookies')); ?>"><?php echo esc_html(pn_t('footer.cookies')); ?></a>
                </nav>
            </div>
        </div>
    </footer>
    <?php
}

// --- Custom Product Page Header (matches Laravel layout) ---
add_action('woocommerce_before_main_content', function () {
    if (!is_product()) {
        return;
    }

    $product = wc_get_product(get_the_ID());
    if (!$product) {
        return;
    }

    $categories = wc_get_product_category_list($product->get_id(), '||');
    $price = $product->get_price();
    $regular_price = $product->get_regular_price();
    $on_sale = $product->is_on_sale();
    ?>
    <nav class="pn-breadcrumbs" aria-label="Breadcrumb">
        <div class="pn-breadcrumbs-inner">
            <ol>
                <li>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span class="sr-only"><?php echo esc_html(pn_t('breadcrumb.home')); ?></span>
                    </a>
                    <svg class="pn-bc-sep" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/loja/')); ?>"><?php echo esc_html(pn_t('breadcrumb.shop')); ?></a>
                    <svg class="pn-bc-sep" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </li>
                <li>
                    <span aria-current="page"><?php echo esc_html(wp_trim_words($product->get_name(), 7, '...')); ?></span>
                </li>
            </ol>
        </div>
    </nav>
    <section class="pn-product-header">
        <div class="pn-product-header-inner">
            <?php if ($categories) : ?>
                <div class="pn-product-categories">
                    <?php
                    // Split category links and render with separators
                    $cat_links = explode('||', $categories);
                    foreach ($cat_links as $i => $cat_link) :
                        // Strip HTML tags to get plain text for display
                        $cat_name = strip_tags($cat_link);
                        if ($i > 0) : ?>
                            <span class="pn-product-category-sep">&middot;</span>
                        <?php endif; ?>
                        <span class="pn-product-category"><?php echo esc_html(trim($cat_name)); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1><?php echo esc_html($product->get_name()); ?></h1>

            <?php if ($price) : ?>
                <div class="pn-product-header-price">
                    <span class="pn-current-price"><?php echo number_format((float) $price, 2, ',', '.'); ?> &euro;</span>
                    <?php if ($on_sale && $regular_price) : ?>
                        <span class="pn-original-price"><?php echo number_format((float) $regular_price, 2, ',', '.'); ?> &euro;</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}, 5);

// Add custom body class for CSS targeting
add_filter('body_class', function ($classes) {
    $classes[] = 'pn-surf-theme';
    $classes[] = 'pn-locale-' . pn_get_locale();
    return $classes;
});

// Set HTML lang attribute based on detected locale
add_filter('language_attributes', function ($output) {
    $lang = pn_get_locale() === 'en' ? 'en-US' : 'pt-PT';
    return preg_replace('/lang="[^"]*"/', 'lang="' . $lang . '"', $output);
});

// WooCommerce: Override "no products found" message
add_filter('woocommerce_no_products_found', function () {
    if (pn_get_locale() === 'en') return;
    echo '<p class="woocommerce-info">Nenhum produto encontrado.</p>';
});

// WooCommerce: Override breadcrumb defaults
add_filter('woocommerce_breadcrumb_defaults', function ($defaults) {
    $defaults['home'] = pn_get_locale() === 'en' ? 'Home' : 'Início';
    return $defaults;
});

// Remove "SKU" label or translate it
add_filter('woocommerce_product_sku', function ($sku) {
    return $sku;
});

// Translate "in stock" / "out of stock"
add_filter('woocommerce_get_availability_text', function ($text, $product) {
    if (pn_get_locale() === 'en') return $text;
    if ($product->is_in_stock()) {
        return 'Em stock';
    }
    return 'Esgotado';
}, 10, 2);

// Translate "Category:" / "Categories:" on product pages
add_filter('woocommerce_product_meta_start', function () {
    // This is handled by CSS and WooCommerce templates, but we override via gettext
});

// Gettext filter for remaining untranslated WooCommerce strings
add_filter('gettext', function ($translated, $original, $domain) {
    if ($domain !== 'woocommerce' || pn_get_locale() === 'en') {
        return $translated;
    }

    $translations = [
        'Add to cart'            => 'Adicionar ao Carrinho',
        'View cart'              => 'Ver Carrinho',
        'Cart'                   => 'Carrinho',
        'Checkout'               => 'Finalizar Compra',
        'Shop'                   => 'Loja',
        'Search'                 => 'Pesquisar',
        'Search products…'       => 'Pesquisar produtos…',
        'No products were found' => 'Nenhum produto encontrado',
        'Sale!'                  => 'Promoção!',
        'Read more'              => 'Ver Mais',
        'Select options'         => 'Selecionar Opções',
        'Out of stock'           => 'Esgotado',
        'In stock'               => 'Em stock',
        'Description'            => 'Descrição',
        'Additional information' => 'Informação Adicional',
        'Reviews'                => 'Avaliações',
        'Related products'       => 'Produtos Relacionados',
        'Update cart'            => 'Atualizar Carrinho',
        'Apply coupon'           => 'Aplicar Cupão',
        'Coupon code'            => 'Código do Cupão',
        'Cart totals'            => 'Totais do Carrinho',
        'Subtotal'               => 'Subtotal',
        'Total'                  => 'Total',
        'Proceed to checkout'    => 'Finalizar Compra',
        'Place order'            => 'Confirmar Encomenda',
        'Billing details'        => 'Dados de Faturação',
        'Ship to a different address?' => 'Enviar para morada diferente?',
        'Your order'             => 'A Sua Encomenda',
        'Product'                => 'Produto',
        'Price'                  => 'Preço',
        'Quantity'               => 'Quantidade',
        'Category:'              => 'Categoria:',
        'Categories:'            => 'Categorias:',
        'Tag:'                   => 'Etiqueta:',
        'Tags:'                  => 'Etiquetas:',
        'Home'                   => 'Início',
        'Results'                => 'Resultados',
        'Showing all'            => 'A mostrar todos',
        'Showing the single result' => 'A mostrar o resultado',
        'Default sorting'        => 'Ordenação predefinida',
        'Sort by popularity'     => 'Ordenar por popularidade',
        'Sort by average rating' => 'Ordenar por avaliação',
        'Sort by latest'         => 'Ordenar por mais recente',
        'Sort by price: low to high' => 'Ordenar por preço: menor para maior',
        'Sort by price: high to low' => 'Ordenar por preço: maior para menor',
        'Showing the single result'  => 'A mostrar o único resultado',
        'Showing all %d results'     => 'A mostrar todos os %d resultados',
        'Showing %1$d&ndash;%2$d of %3$d results' => 'A mostrar %1$d&ndash;%2$d de %3$d resultados',
        '%s quantity'                => 'Quantidade de %s',
        'Shop order'                 => 'Ordenar',
        'View full-screen image gallery' => 'Ver galeria de imagens',
        'Original price was: %s.'    => 'Preço original: %s.',
        'Current price is: %s.'      => 'Preço atual: %s.',
        'Shipping'                    => 'Envio',
        'Free shipping'               => 'Envio gratuito',
        'Local pickup'                => 'Recolha local',
        'Flat rate'                   => 'Taxa fixa',
        'Calculate shipping'          => 'Calcular envio',
        'Update totals'               => 'Atualizar totais',
        'Enter your address to view shipping options.' => 'Introduza a sua morada para ver opções de envio.',
        'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.' => 'Não existem opções de envio disponíveis. Verifique se a morada foi introduzida corretamente ou contacte-nos se precisar de ajuda.',
        'Change address'              => 'Alterar morada',
    ];

    return $translations[$original] ?? $translated;
}, 10, 3);

// Also translate Kadence theme strings
add_filter('gettext', function ($translated, $original, $domain) {
    if ($domain !== 'kadence' || pn_get_locale() === 'en') {
        return $translated;
    }

    $translations = [
        'Search'        => 'Pesquisar',
        'Menu'          => 'Menu',
        'Close'         => 'Fechar',
        'Navigation'    => 'Navegação',
        'Skip to content' => 'Ir para o conteúdo',
    ];

    return $translations[$original] ?? $translated;
}, 10, 3);

// --- WooCommerce plural form translations (result count) ---
add_filter('ngettext', function ($translation, $single, $plural, $number, $domain) {
    if ($domain !== 'woocommerce' || pn_get_locale() === 'en') {
        return $translation;
    }

    if ($single === 'Showing the single result' && $plural === 'Showing all %d results') {
        if ($number === 1) {
            return 'A mostrar o único resultado';
        }
        return sprintf('A mostrar todos os %d resultados', $number);
    }

    return $translation;
}, 10, 5);

// Handle "Category:" / "Categories:" (uses _n(), not __())
add_filter('ngettext', function ($translation, $single, $plural, $number, $domain) {
    if ($domain !== 'woocommerce' || pn_get_locale() === 'en') {
        return $translation;
    }

    if ($single === 'Category:' && $plural === 'Categories:') {
        return $number === 1 ? 'Categoria:' : 'Categorias:';
    }

    if ($single === 'Tag:' && $plural === 'Tags:') {
        return $number === 1 ? 'Etiqueta:' : 'Etiquetas:';
    }

    return $translation;
}, 10, 5);

// Handle "Showing X–Y of Z results" (ngettext with context)
add_filter('ngettext_with_context', function ($translation, $single, $plural, $number, $context, $domain) {
    if ($domain !== 'woocommerce' || pn_get_locale() === 'en') {
        return $translation;
    }

    if ($context === 'with first and last result') {
        return 'A mostrar %1$d&ndash;%2$d de %3$d resultados';
    }

    return $translation;
}, 10, 6);

// AJAX cart fragment update for badge count in custom header
add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $count = WC()->cart->get_cart_contents_count();
    if ($count > 0) {
        $fragments['.pn-cart-badge'] = '<span class="pn-cart-badge">' . esc_html($count) . '</span>';
    } else {
        $fragments['.pn-cart-badge'] = '<span class="pn-cart-badge" style="display:none"></span>';
    }
    return $fragments;
});

// Show pickup address below Local Pickup shipping method
add_action('woocommerce_after_shipping_rate', function ($method) {
    if ($method->method_id === 'local_pickup') {
        echo '<p style="font-size:0.85em;color:#6c757d;margin:0.25rem 0 0 1.5rem;">';
        echo 'Forte de S. Miguel Arcanjo, Rua 25 de Abril, Nazaré, 2450-065';
        echo '</p>';
    }
});

// JS fallback for WooCommerce Block strings the language pack may not cover
add_action('wp_footer', function () {
    if (!is_cart() && !is_checkout()) {
        return;
    }
    if (pn_get_locale() === 'en') {
        return;
    }
    ?>
    <script>
    (function() {
        var translations = {
            'Products in cart': 'Produtos no carrinho',
            'Cart totals': 'Totais do Carrinho',
            'Add coupons': 'Adicionar cupões',
            'Estimated total': 'Total estimado',
            'Proceed to Checkout': 'Finalizar Compra',
            'Product': 'Produto',
            'Total': 'Total',
            'Subtotal': 'Subtotal',
            'Loading products in cart\u2026': 'A carregar produtos\u2026',
            'Remove item': 'Remover item',
            'Coupon code': 'Código do cupão',
            'Apply': 'Aplicar',
            'Shipping': 'Envio',
            'Local pickup': 'Recolha local',
            'Calculate shipping': 'Calcular envio',
            'Change address': 'Alterar morada'
        };
        function translateNode(node) {
            if (node.nodeType === 3) {
                var text = node.textContent.trim();
                if (translations[text]) {
                    node.textContent = node.textContent.replace(text, translations[text]);
                }
            }
        }
        function translateAll() {
            document.querySelectorAll('.wp-block-woocommerce-cart, .wp-block-woocommerce-checkout, .wc-block-cart, .wc-block-checkout').forEach(function(block) {
                var walker = document.createTreeWalker(block, NodeFilter.SHOW_TEXT);
                while (walker.nextNode()) { translateNode(walker.currentNode); }
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() { setTimeout(translateAll, 500); });
        } else {
            setTimeout(translateAll, 500);
        }
        var observer = new MutationObserver(function() { setTimeout(translateAll, 100); });
        var target = document.querySelector('.wp-block-woocommerce-cart, .wp-block-woocommerce-checkout, .wc-block-cart, .wc-block-checkout');
        if (target) { observer.observe(target, { childList: true, subtree: true }); }
    })();
    </script>
    <?php
});
