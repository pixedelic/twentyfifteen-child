<?php
/**
 * Theme Customizer support
 *
 */
// Early exit if Kirki is not installed
if ( ! class_exists( 'Kirki' ) ) {
    return;
}

require_once( get_stylesheet_directory() . '/inc/customizer/sanitize.php' );
require_once( get_stylesheet_directory() . '/inc/customizer/class.dynamic.sortable.php' );
require_once( get_stylesheet_directory() . '/inc/customizer/class.select.fonts.php' );

/**
 * Create panels using the WordPress Customizer API.
 */
add_action( 'customize_register', 'pix_customizer_panels' );
function pix_customizer_panels( $wp_customize ) {
    $wp_customize->add_panel( 'pix_topbar_panel', array(
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          => __('Top bar', 'twentyfifteen'),
    ));

    $wp_customize->add_panel( 'pix_header_panel', array(
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          => __('Header', 'twentyfifteen'),
    ));

    $wp_customize->add_panel( 'pix_typography_panel', array(
        'priority'       => 10,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          => __('Typography', 'twentyfifteen'),
    ));
}

add_action( 'customize_register', 'pix_topbar_sections' );
function pix_topbar_sections( $wp_customize ) {
    $wp_customize->add_section( 'pix_topbar_behaviour', array(
        'title' => __( 'Behaviour', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_topbar_panel',
    ) );

    $wp_customize->add_section( 'pix_topbar_colors', array(
        'title' => __( 'Colors', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_topbar_panel',
    ) );

    $wp_customize->add_section( 'pix_topbar_elements_l', array(
        'title' => __( 'Left elements', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_topbar_panel',
    ) );

    $wp_customize->add_section( 'pix_topbar_elements_r', array(
        'title' => __( 'Right elements', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_topbar_panel',
    ) );
}

add_action( 'customize_register', 'pix_header_sections' );
function pix_header_sections( $wp_customize ) {
    $wp_customize->add_section( 'pix_logo_section', array(
        'title' => __( 'Logo', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_header_panel',
    ) );

    $wp_customize->add_section( 'pix_header_color_section', array(
        'title' => __( 'Colors', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_header_panel',
    ) );

    $wp_customize->add_section( 'pix_header_layout_section', array(
        'title' => __( 'Layout', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_header_panel',
    ) );

    $wp_customize->add_section( 'pix_header_adv_section', array(
        'title' => __( 'Adv. banner', 'twentyfifteen' ),
        'description' => __( 'Visibile on Classic Header only', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_header_panel',
    ) );
}

add_action( 'customize_register', 'pix_typography_sections' );
function pix_typography_sections( $wp_customize ) {
    $wp_customize->add_section( 'pix_typography_general_section', array(
        'title' => __( 'General', 'twentyfifteen' ),
        'priority' => 10,
        'panel' => 'pix_typography_panel',
    ) );
}

/************************************************
*
*   pix_topbar_panel
*
************************************************/
add_filter( 'kirki/fields', 'pix_options_topbar_behaviour' );
function pix_options_topbar_behaviour( $fields ) {
   $fields[] = array(
     'type'        => 'checkbox',
     'settings'    => 'pix_display_topbar',
     'label'       => __( 'Display the topbar', 'twentyfifteen' ),
     'section'     => 'pix_topbar_behaviour',
     'default'     => '0',
     'priority'    => 10
   );
    return $fields;
}

add_filter( 'kirki/fields', 'pix_options_topbar_colors' );
function pix_options_topbar_colors( $fields ) {
    $fields[] = array(
       'type'        => 'color',
       'settings'    => 'pix_topbar_background',
       'label'       => __( 'Background color', 'twentyfifteen' ),
       'section'     => 'pix_topbar_colors',
       'default'     => '#464d4b',
       'priority'    => 10
    );

    $fields[] = array(
       'type'        => 'color',
       'settings'    => 'pix_topbar_color',
       'label'       => __( 'Text color', 'twentyfifteen' ),
       'section'     => 'pix_topbar_colors',
       'default'     => '#94a6a6',
       'priority'    => 10
    );

    return $fields;
}

add_filter( 'kirki/fields', 'pix_options_topbar_elements_l' );
function pix_options_topbar_elements_l( $fields ) {
    $fields[] = array(
        'type'        => 'dynamic',
        'settings'    => 'pix_topbar_elements_l',
        'label'       => __( 'Sortable', 'twentyfifteen' ),
        'section'     => 'pix_topbar_elements_l',
        'sanitize_callback' => 'pix_sanitize_editor',
        'default'     => '',
        'priority'    => 10
    );

    return $fields;
}

add_filter( 'kirki/fields', 'pix_options_topbar_elements_r' );
function pix_options_topbar_elements_r( $fields ) {
    $fields[] = array(
        'type'        => 'dynamic',
        'settings'    => 'pix_topbar_elements_r',
        'label'       => __( 'Sortable', 'twentyfifteen' ),
        'section'     => 'pix_topbar_elements_r',
        'default'     => '',
        'priority'    => 10
    );

    return $fields;
}

/************************************************
*
*   pix_header_panel
*
************************************************/
add_filter( 'kirki/fields', 'pix_options_logo' );
function pix_options_logo( $fields ) {
   $fields[] = array(
     'type'        => 'image',
     'settings'    => 'pix_logo',
     'label'       => __( 'Regular logo', 'twentyfifteen' ),
     'section'     => 'pix_logo_section',
     'default'     => '',
     'priority'    => 10,
     'output'      => array(
         array(
             'element'  => 'p',
             'property' => 'background-image',
         ),
     ),
   );

   $fields[] = array(
      'type'        => 'image',
      'settings'    => 'pix_logo_transparent',
      'label'       => __( 'Logo for the second header color', 'twentyfifteen' ),
      'help'        => __( 'This logo will be used as default one when you set the header as transparent.', 'twentyfifteen' ),
      'section'     => 'pix_logo_section',
      'default'     => '',
      'priority'    => 10,
      'output'      => array(
         array(
             'element'  => 'p',
             'property' => 'background-image',
         ),
      ),
   );

   $fields[] = array(
     'type'        => 'number',
     'settings'    => 'pix_logo_height',
     'label'       => __( 'Set the max height for the logo', 'twentyfifteen' ),
     'description' => __( 'The logo won\'t be taller than the header height, but here you can decide if you want to make it smaller', 'twentyfifteen' ),
     'section'     => 'pix_logo_section',
     'default'     => '',
     'priority'    => 10,
   );

    return $fields;
}

add_filter( 'kirki/fields', 'pix_options_header_colors' );
function pix_options_header_colors( $fields ) {
   $fields[] = array(
      'type'        => 'color',
      'settings'    => 'pix_header_background',
      'label'       => __( 'Header background', 'twentyfifteen' ),
      'section'     => 'pix_header_color_section',
      'default'     => '#ffffff',
      'priority'    => 10
   );

   $fields[] = array(
      'type'        => 'color',
      'settings'    => 'pix_header_color',
      'label'       => __( 'Header text color', 'twentyfifteen' ),
      'description' => __( 'Used for site title, nav links etc.', 'twentyfifteen' ),
      'section'     => 'pix_header_color_section',
      'default'     => '#222324',
      'priority'    => 10
   );

   $fields[] = array(
     'type'        => 'checkbox',
     'settings'    => 'pix_double_header',
     'label'       => __( 'Change colors on scroll down', 'twentyfifteen' ),
     'description' => __( 'Check this field if you want to use different colors (for instance white text on a transparent header) before start scrolling.', 'twentyfifteen' ),
     'section'     => 'pix_header_color_section',
     'default'     => '0',
     'priority'    => 10
   );

   $fields[] = array(
     'type'        => 'color-alpha',
     'settings'    => 'pix_transparent_header_background',
     'label'       => __( 'Transparent header background', 'twentyfifteen' ),
     'section'     => 'pix_header_color_section',
     'default'     => 'rgba(33,34,35,0)',
     'priority'    => 10
   );

   $fields[] = array(
     'type'        => 'color',
     'settings'    => 'pix_transparent_header_color',
     'label'       => __( 'Transparent header text color', 'twentyfifteen' ),
     'section'     => 'pix_header_color_section',
     'default'     => '#ffffff',
     'priority'    => 10
   );

    return $fields;
}

add_filter( 'kirki/fields', 'pix_options_header_layout' );
function pix_options_header_layout( $fields ) {
   $fields[] = array(
      'type'        => 'radio-image',
      'settings'    => 'pix_header_layout',
      'label'       => __( 'Layout', 'twentyfifteen' ),
      'section'     => 'pix_header_layout_section',
      'default'     => 'default',
      'priority'    => 10,
      'choices'     => array(
         'default' =>  get_stylesheet_directory_uri() . '/images/headers/default-header.png',
         'centered' =>  get_stylesheet_directory_uri() . '/images/headers/centered-header.png',
         'classic-centered' =>  get_stylesheet_directory_uri() . '/images/headers/classic-centered-header.png',
         'classic' =>  get_stylesheet_directory_uri() . '/images/headers/classic-header.png',
         'side' =>  get_stylesheet_directory_uri() . '/images/headers/side-header.png',
      ),
   );

   return $fields;
}

add_filter( 'kirki/fields', 'pix_options_header_adv' );
function pix_options_header_adv( $fields ) {
   $fields[] = array(
       'type'        => 'upload',
       'settings'    => 'pix_adv_banner_img',
       'label'       => __( 'Upload your image', 'twentyfifteen' ),
       'section'     => 'pix_header_adv_section',
       'default'     => '',
       'priority'    => 10,
   );

   $fields[] = array(
     'type'        => 'number',
     'settings'    => 'pix_banner_height',
     'label'       => __( 'Set the max height for the logo', 'twentyfifteen' ),
     'description' => __( 'The banner won\'t be taller than the header height, but here you can decide if you want to make it smaller', 'twentyfifteen' ),
     'section'     => 'pix_header_adv_section',
     'default'     => '60',
     'priority'    => 10,
   );

   $fields[] = array(
       'type'        => 'text',
       'settings'    => 'pix_adv_banner_url',
       'label'       => __( 'Link URL', 'twentyfifteen' ),
       'section'     => 'pix_header_adv_section',
       'default'     => 'http://themeforest.net/user/pixedelic/portfolio?ref=pixedelic',
       'priority'    => 10,
   );

   $fields[] = array(
       'type'        => 'text',
       'settings'    => 'pix_adv_banner_alt',
       'label'       => __( 'Alternative text', 'twentyfifteen' ),
       'section'     => 'pix_header_adv_section',
       'default'     => 'Pixedelic Portfolio',
       'priority'    => 10,
   );

    return $fields;
}


/************************************************
*
*   pix_typography_panel
*
************************************************/
add_filter( 'kirki/fields', 'pix_options_typography_general' );
function pix_options_typography_general( $fields ) {
    $fields[] = array(
       'type'        => 'color',
       'settings'    => 'pix_typography_color',
       'label'       => __( 'Text color', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '#222324',
       'priority'    => 10
    );

    $fields[] = array(
       'type'        => 'selectfont',
       'settings'    => 'pix_typography_font',
       'label'       => __( 'Font family', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '',
       'priority'    => 10,
       'field'      => 'family'
    );

    $fields[] = array(
       'type'        => 'text',
       'settings'    => 'pix_typography_font_custom',
       'label'       => __( 'Font family', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '',
       'priority'    => 10,
       'field'      => 'family'
    );

    $fields[] = array(
       'type'        => 'selectfont',
       'settings'    => 'pix_typography_font_weight',
       'label'       => __( 'Font weight', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '',
       'priority'    => 10,
       'field'      => 'weight'
    );

    $fields[] = array(
       'type'        => 'selectfont',
       'settings'    => 'pix_typography_font_subsets',
       'label'       => __( 'Font weight', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '',
       'priority'    => 10,
       'field'      => 'subsets'
    );

    $fields[] = array(
       'type'        => 'slider',
       'settings'    => 'pix_typography_font_size',
       'label'       => __( 'Font size', 'twentyfifteen' ),
       'description' => __( 'Size is intended in pixels', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '16',
       'priority'    => 10,
       'choices'     => array(
           'min'  => 0,
           'max'  => 100,
           'step' => 1
       ),
    );

    $fields[] = array(
       'type'        => 'slider',
       'settings'    => 'pix_typography_line_height',
       'label'       => __( 'Line height', 'twentyfifteen' ),
       'description' => __( 'Size is intended in ems', 'twentyfifteen' ),
       'section'     => 'pix_typography_general_section',
       'default'     => '1.7',
       'priority'    => 10,
       'choices'     => array(
           'min'  => 0,
           'max'  => 20,
           'step' => 0.01
       ),
    );

    return $fields;
}

add_action( 'customize_controls_enqueue_scripts', 'pix_customize_controls_scripts' );
function pix_customize_controls_scripts() {
    global $pix_theme_version;

    $array_dep = array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-dialog', 'jquery-ui-accordion' );
    wp_enqueue_script( 'pix_customizer_controls', get_stylesheet_directory_uri() . '/js/customizer-controls.js', $array_dep, true );

    wp_enqueue_style( 'pix_customizer_controls_css', get_stylesheet_directory_uri() . '/css/customizer-controls.css', array(), $pix_theme_version );
    wp_enqueue_style( 'fontawesome-font', get_stylesheet_directory_uri() . '/font/font-awesome.css', array(), '4.3.0' );
    wp_enqueue_style( 'budicon-font', get_stylesheet_directory_uri() . '/font/budicon-font.css', array(), '1.2' );
    wp_enqueue_style( 'jquery-style', get_stylesheet_directory_uri().'/css/jquery-ui.css', array(), $pix_theme_version );
}

add_action( 'customize_controls_init', 'pix_list_icon' );
function pix_list_icon(){ ?>
    <div id="list-icon" class="hidden" style="background-image:url(<?php echo esc_url( admin_url() . '/images/spinner-2x.gif' ); ?>);">
        <?php require_once get_stylesheet_directory() . '/inc/icon-list.php'; ?>
    </div><!-- #list-icon -->
<?php }
