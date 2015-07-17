<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style');
function enqueue_parent_theme_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_style( 'child-theme', get_stylesheet_uri() );
}

/**
 * Customizer.
 */
require get_stylesheet_directory() . '/inc/kirki/kirki.php';
require get_stylesheet_directory() . '/inc/customizer.php';

add_filter( 'kirki/config', 'advanced_customizer_configuration' );
if ( !function_exists('advanced_customizer_configuration') ) :
function advanced_customizer_configuration( $config ) {

    $config['url_path'] = get_stylesheet_directory_uri() . '/inc/kirki/';
    return $config;

}
endif;

if ( ! function_exists( 'pix_sanitize_hex_color' ) ) :
/**
 * Copy of sanitize_color()
 * @since Logan 1.0
 */
function pix_sanitize_hex_color( $color ) {
    if ( '' === $color )
        return '';

    // 3 or 6 hex digits, or the empty string.
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
        return $color;

    if ( preg_match('|^([A-Fa-f0-9]{3}){1,2}$|', $color ) )
        return '#'.$color;

    return null;
}
endif;

if ( ! function_exists( 'pix_hex2rgbcompiled' ) ) :
/**
 * Retrieve rgb versione of hex colors.
 * @since Logan 1.0
 */
function pix_hex2rgbcompiled($hex) {
    $hex = pix_sanitize_hex_color($hex);
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return esc_attr($rgb[0].','.$rgb[1].','.$rgb[2]);
}
endif;

if ( ! function_exists( 'pix_get_google_font_list' ) ) :
/**
 * Get the google font list.
 */
function pix_get_google_font_list() {
    $request_url = get_stylesheet_directory_uri().'/font/webfonts.google.txt';

    $raw_response = wp_remote_get($request_url);

    if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
        $body = $raw_response['body'];
        return $body;
    }

}
endif;

if ( ! function_exists( 'pix_font_set' ) ) :
/**
* Print the font sets to load them from Google
*/
function pix_font_set(){
    $fonts = array();

    $json = pix_get_google_font_list();
    $decoded = json_decode($json);

    $google_array = array();
    $google_families = array();

    foreach ( $decoded->items as $item ) {

        $family = $item->family;
        array_push($google_families, $family);
        $google_array[$family]['variants'] = $item->variants;
        $google_array[$family]['subsets'] = $item->subsets;

    }

    /*general font*/
    $body_font = get_theme_mod('pix_typography_font_custom') != '' ? get_theme_mod('pix_typography_font_custom') : get_theme_mod('pix_typography_font');
    if ( $body_font!='' && in_array($body_font, $google_families) ) {
        $fonts[$body_font]['variants'] = $google_array[$body_font]['variants'];
        $body_font_subsets = get_theme_mod('pix_typography_font_subsets');
        $body_font_subsets = is_array($body_font_subsets) ? $body_font_subsets : array($body_font_subsets);
        $fonts[$body_font]['subsets'] = $body_font_subsets;
    }

    $options = array(
        'sitetitle',
        'tagline',
        'nav',
        'floating_side',
        'footer',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'meta',
        'widget_titles',
        'widget_li',
        'buttons',
        'code'
    );

    foreach ( $options as $option ) {

        $font = get_theme_mod('pix_typography_' . $option . '_font_custom') != '' ? get_theme_mod('pix_typography_' . $option . '_font_custom') : get_theme_mod('pix_typography_' . $option . '_font');
        if ( $font!='' && in_array($font, $google_families) ) {
            $variant = get_theme_mod('pix_typography_' . $option . '_font_weight');

            if ( !isset($fonts[$font]['variants']) )
                $fonts[$font]['variants'] = array();
            if ( !in_array($variant, $fonts[$font]['variants']) )
                array_push($fonts[$font]['variants'], $variant);

            $subsets = get_theme_mod('pix_typography_' . $option . '_font_subsets');
            $subsets = is_array($subsets) ? $subsets : array($subsets);

            if ( !isset($fonts[$font]['subsets']) )
                $fonts[$font]['subsets'] = array();
            if ( !in_array($subsets, $fonts[$font]['subsets']) ) {
                foreach($subsets as $subset) {
                    array_push($fonts[$font]['subsets'], $subset);
                }
            }
        }

    }

    $site_font = '';
    foreach($fonts as $font => $vars) {
        $site_font .= '"'.(str_replace(' ','+',esc_js($font)));
        $site_font .= ':';
        $site_font .= str_replace('regular','400', esc_js( implode( array_unique($vars['variants']),',') ) );
        $site_font .= ':';
        $site_font .= esc_js( implode(array_unique($vars['subsets']),',') ).'",';
    }

    return $site_font;
}
endif;//pix_font_set

add_action('pix_custom_font_loader', 'pix_custom_font_loader');
if ( ! function_exists( 'pix_custom_font_loader' ) ) :
/**
 * Custom font loader
 */
function pix_custom_font_loader(){

	if(pix_font_set()!='') { ?>

<script type="text/javascript">
	WebFontConfig = {
		google: {
			families: [ <?php echo pix_font_set(); ?> ]
		}
	};
(function() {
var wf = document.createElement('script');
wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
wf.type = 'text/javascript';
wf.async = 'true';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(wf, s);
})();
</script>

<?php }
}
endif;//pix_custom_font_loader

add_action('admin_head', 'pix_google_font_editor');
if ( ! function_exists( 'pix_google_font_editor' ) ) :
/**
 * Google font loader
 */
function pix_google_font_editor(){
    if(pix_font_set()!='') {

?> <script type="text/javascript">
    WebFontConfig = {
        google: {
            families: [ <?php echo pix_font_set(); ?> ]
        }
    };
(function() {
var wf = document.createElement('script');
wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
  '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
wf.type = 'text/javascript';
wf.async = 'true';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(wf, s);
})();
</script>
<?php }
}
endif;

if ( ! function_exists( 'pix_style_weight_font' ) ) :
function pix_style_weight_font($option, $att) {
	if ( $att == 'style' ) {
	    if (strpos($option,'italic') !== false)
	    	return 'italic';
	    else
	    	return 'normal';
	} elseif ( $att == 'weight' ) {
    	return str_replace('italic', '', $option);;
	} else {
		return $option;
	}
}
endif;

if ( ! function_exists( 'pix_esc_font_family' ) ) :
/**
 * Escape font family with qhotes
 */
function pix_esc_font_family( $font ) {
    if ( '' === $font )
        return '';

    $font = esc_attr($font);
    $font = html_entity_decode($font, ENT_COMPAT);
    return $font;

    return null;
}
endif;

add_action( 'wp_head', 'pix_typography_css' );
if ( ! function_exists( 'pix_typography_css' ) ) :
/**
 * Prints custom CSS generated by Live Customizer
 */
function pix_typography_css() {
?>
<style type="text/css">
	body {
		color: <?php echo pix_sanitize_hex_color(get_theme_mod('pix_typography_color')); ?>;
		font-family: "<?php echo get_theme_mod('pix_typography_font_custom') != '' ? pix_esc_font_family(get_theme_mod('pix_typography_font_custom')): pix_esc_font_family(get_theme_mod('pix_typography_font')); ?>";
		font-weight: <?php echo pix_style_weight_font( esc_attr(get_theme_mod('pix_typography_font_weight')), 'weight'); ?>;
		line-height: <?php echo floatval(get_theme_mod('pix_typography_line_height')); ?>em;
	}
</style>
<?php
}
endif; // pix_typography_css


add_action( 'after_setup_theme', 'child_setup' );
if ( ! function_exists( 'child_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function child_setup() {

    $options = array (

        array( "id" => "pix_display_topbar",
            "std" => false),

        array( "id" => "pix_topbar_background",
            "std" => '#ffffff'),

        array( "id" => "pix_topbar_color",
            "std" => '#222324'),

        array( "id" => "pix_topbar_elements_l",
            "std" => ''),

        array( "id" => "pix_topbar_elements_r",
            "std" => ''),

        array( "id" => "pix_logo",
            "std" => ''),

        array( "id" => "pix_logo_transparent",
            "std" => ''),

        array( "id" => "pix_logo_height",
            "std" => '38'),

        array( "id" => "pix_header_background",
            "std" => '#ffffff'),

        array( "id" => "pix_header_color",
            "std" => '#222324'),

        array( "id" => "pix_double_header",
            "std" => false),

        array( "id" => "pix_transparent_header_background",
            "std" => 'rgba(0,0,0,0)'),

        array( "id" => "pix_transparent_header_color",
            "std" => '#ffffff'),

        array( "id" => "pix_header_layout",
            "std" => ''),

        array( "id" => "pix_adv_banner_img",
            "std" => ''),

        array( "id" => "pix_banner_height",
            "std" => ''),

        array( "id" => "pix_adv_banner_url",
            "std" => ''),

        array( "id" => "pix_adv_banner_alt",
            "std" => ''),

        array( "id" => "pix_typography_color",
            "std" => '#222324'),

        array( "id" => "pix_typography_font",
            "std" => 'Lato'),

        array( "id" => "pix_typography_font_custom",
            "std" => ''),

        array( "id" => "pix_typography_font_weight",
            "std" => '400'),

        array( "id" => "pix_typography_font_subsets",
            "std" => 'latin'),

        array( "id" => "pix_typography_font_size",
            "std" => '16'),

        array( "id" => "pix_typography_line_height",
            "std" => '1.7'),

    );

    foreach ($options as $value) :
        $mods = get_theme_mods();
        $name = $value['id'];
        if(!isset($mods[$name])){
            set_theme_mod($value['id'], $value['std']);
        }
    endforeach;
}
endif;

add_filter('kirki/config', 'disable_inline_css');

function disable_inline_css(){
    $args = array(
        'disable_google_fonts'   => true,
        'disable_output'   => true,
    );

    return $args;

}
