<?php
/**
 * sortable Customizer Control with more options
 *
 * @package     Kirki
 * @subpackage  Controls
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pix_add_dynamic_sortable_controls( $controls ) {
    $controls['dynamic'] = 'Pix_Controls_Sortable_Control';

    return $controls;
}
add_action( 'kirki/control_types', 'pix_add_dynamic_sortable_controls' );

add_action('customize_register', 'pix_customizer_dynamic_sortable');
function pix_customizer_dynamic_sortable($wp_customize){
    // Early exit if the class already exists
    if ( class_exists( 'Pix_Controls_Sortable_Control' ) ) {
    	return;
    }

    class Pix_Controls_Sortable_Control extends WP_Customize_Control {

    	public $type = 'dynamic';

        public function render_content() {
              ?>
              <div class="pix-customize-builder">

                  <div class="pix-builder-area">

                  <?php
                      $str = urldecode(html_entity_decode($this->value()));
                      parse_str($str, $arr);

                      if ( isset($arr[ $this->id ]) ) {

                          $fields = $arr[ $this->id ];

                          foreach ($fields as $key => $value) { ?>
                              <div class="pix-panel-builder">
                                  <h3><input type="text" value="<?php echo esc_attr($value['name']); ?>" name="<?php echo $this->id; ?>[<?php echo $key; ?>][name]"></h3>
                                  <div>
                                      <p>
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'Type:', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <select name="<?php echo $this->id; ?>[<?php echo $key; ?>][type]">
                                              <option value="text" <?php selected( esc_attr($value['type']), "text" ); ?>><?php _e( 'text', 'twentyfifteen-child' ); ?></option>
                                              <option value="icon" <?php selected( esc_attr($value['type']), "icon" ); ?>><?php _e( 'icon only', 'twentyfifteen-child' ); ?></option>
                                              <?php if ( class_exists('YITH_WCWL') ) { ?>
                                                  <option value="wishlist" <?php selected( esc_attr($value['type']), "wishlist" ); ?>><?php _e( 'wishlist', 'twentyfifteen-child' ); ?></option>
                                              <?php } ?>
                                              <?php if ( function_exists('icl_object_id') ) { ?>
                                                  <option value="lang-switcher" <?php selected( esc_attr($value['type']), "lang-switcher" ); ?>><?php _e( 'language switcher', 'twentyfifteen-child' ); ?></option>
                                              <?php } ?>
                                          </select>
                                      </p>

                                      <p>
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'Text:', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <input type="text" value="<?php echo esc_attr($value['text']); ?>" name="<?php echo $this->id; ?>[<?php echo $key; ?>][text]">
                                      </p>

                                      <p class="add-icon">
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'Icon:', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <div class="div-icon-placeholder <?php echo esc_attr($value['icon']); ?>"></div>
                                          <input type="text" class="icon-placeholder" value="<?php echo esc_attr($value['icon']); ?>" name="<?php echo $this->id; ?>[<?php echo $key; ?>][icon]">
                                          <a href="#" class="add-icon-button add-new-h2"><?php _e( 'add icon', 'twentyfifteen-child' ); ?></a>
                                          <a href="#" class="remove-icon-button add-new-h2"><?php _e( 'remove icon', 'twentyfifteen-child' ); ?></a>
                                      </p>

                                      <p>
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'URL:', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <input type="text" value="<?php echo esc_url($value['url']); ?>" name="<?php echo $this->id; ?>[<?php echo $key; ?>][url]">
                                      </p>

                                      <p>
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'Target (if a link):', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <select name="<?php echo $this->id; ?>[<?php echo $key; ?>][target]">
                                              <option value="_blank" <?php selected( esc_attr($value['target']), "_blank" ); ?>><?php _e( 'open in a new window', 'twentyfifteen-child' ); ?></option>
                                              <option value="_self" <?php selected( esc_attr($value['target']), "_self" ); ?>><?php _e( 'open in the same window', 'twentyfifteen-child' ); ?></option>
                                          </select>
                                      </p>

                                      <p>
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'ID:', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <input type="text" value="<?php echo esc_attr($value['id']); ?>" name="<?php echo $this->id; ?>[<?php echo $key; ?>][id]">
                                      </p>

                                      <p>
                                          <label>
                                              <span class="customize-control-title"><?php _e( 'Class:', 'twentyfifteen-child' ); ?></span>
                                          </label>
                                          <input type="text" value="<?php echo esc_attr($value['class']); ?>" name="<?php echo $this->id; ?>[<?php echo $key; ?>][class]">
                                      </p>

                                      <p>
                                          <a href="#" class="notice-dismiss delete-panel"><?php _e( 'Delete', 'twentyfifteen-child' ); ?></a>
                                      </p>

                                  </div>
                              </div>
                          <?php }

                      }

                  ?>

                  </div>

                  <input type="hidden" <?php $this->link(); ?> data-builder="true" value="<?php echo esc_attr( $this->value() ); ?>">

                  <p>
                      <a href="#" class="button builder-add-element"><?php _e( 'Add an element', 'twentyfifteen-child' ); ?></a>
                  </p>

                  <div class="pix-panel-builder to-clone">
                      <h3><input type="text" value="<?php _e( 'Element', 'twentyfifteen-child'); ?>" name="<?php echo $this->id; ?>[clone][name]"></h3>
                      <div>
                          <p>
                              <label>
                                  <span class="customize-control-title"><?php _e( 'Type:', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <select name="<?php echo $this->id; ?>[clone][type]">
                                  <option value="text"><?php _e( 'text', 'twentyfifteen-child' ); ?></option>
                                  <option value="icon"><?php _e( 'icon only', 'twentyfifteen-child' ); ?></option>
                                  <?php if ( class_exists('YITH_WCWL') ) { ?>
                                      <option value="wishlist"><?php _e( 'wishlist', 'twentyfifteen-child' ); ?></option>
                                  <?php } ?>
                                  <?php if ( function_exists('icl_object_id') ) { ?>
                                      <option value="lang-switcher"><?php _e( 'language switcher', 'twentyfifteen-child' ); ?></option>
                                  <?php } ?>
                              </select>
                          </p>

                          <p>
                              <label>
                                  <span class="customize-control-title"><?php _e( 'Text:', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <input type="text" name="<?php echo $this->id; ?>[clone][text]">
                          </p>

                          <p class="add-icon">
                              <label>
                                  <span class="customize-control-title"><?php _e( 'Icon:', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <div class="div-icon-placeholder"></div>
                              <input type="text" class="icon-placeholder" name="<?php echo $this->id; ?>[clone][icon]">
                              <a href="#" class="add-icon-button add-new-h2"><?php _e( 'add icon', 'twentyfifteen-child' ); ?></a>
                              <a href="#" class="remove-icon-button add-new-h2"><?php _e( 'remove icon', 'twentyfifteen-child' ); ?></a>
                          </p>

                          <p>
                              <label>
                                  <span class="customize-control-title"><?php _e( 'URL:', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <input type="text" name="<?php echo $this->id; ?>[clone][url]">
                          </p>

                          <p>
                              <label>
                                  <span class="customize-control-title"><?php _e( 'Target (if a link):', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <select name="<?php echo $this->id; ?>[clone][target]">
                                  <option value="_blank"><?php _e( 'open in a new window', 'twentyfifteen-child' ); ?></option>
                                  <option value="_self"><?php _e( 'open in the same window', 'twentyfifteen-child' ); ?></option>
                              </select>
                          </p>

                          <p>
                              <label>
                                  <span class="customize-control-title"><?php _e( 'ID:', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <input type="text" name="<?php echo $this->id; ?>[clone][id]">
                          </p>

                          <p>
                              <label>
                                  <span class="customize-control-title"><?php _e( 'Class:', 'twentyfifteen-child' ); ?></span>
                              </label>
                              <input type="text" name="<?php echo $this->id; ?>[clone][class]">
                          </p>

                          <p>
                              <a href="#" class="notice-dismiss delete-panel"><?php _e( 'Delete', 'twentyfifteen-child' ); ?></a>
                          </p>

                      </div>
                  </div>

              </div>
              <?php
         }
    }
}
