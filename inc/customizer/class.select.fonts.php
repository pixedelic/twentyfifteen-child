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

function pix_add_select_fonts_controls( $controls ) {
    $controls['selectfont'] = 'Pix_Controls_Select_Fonts';

    return $controls;
}
add_action( 'kirki/control_types', 'pix_add_select_fonts_controls' );

add_action('customize_register', 'pix_customizer_select_fonts');
function pix_customizer_select_fonts($wp_customize){
    // Early exit if the class already exists
    if ( class_exists( 'Pix_Controls_Select_Fonts' ) ) {
    	return;
    }

    class Pix_Controls_Select_Fonts extends WP_Customize_Control {

		public $type = 'selectfont';

		public $field = 'family';

        public function render_content() {

			  $json = pix_get_google_font_list();
              $decoded = json_decode($json);

              ?>

			  <?php if ( $this->field == 'family' ) : ?>
	              <label>
					<span class="customize-control-title">
  		  				<?php echo esc_attr( $this->label ); ?>
  		  				<?php if ( ! empty( $this->description ) ) : ?>
  		  					<?php // The description has already been sanitized in the Fields class, no need to re-sanitize it. ?>
  		  					<span class="description customize-control-description"><?php echo $this->description; ?></span>
  		  				<?php endif; ?>
  		  			</span>

	                  <select <?php $this->link(); ?> class="chosen-select select2 select-font-family">
	                      <option value="initial" data-variants="400,700," data-subsets="all," <?php selected( $this->value(), "initial" ); ?>><?php _e('None (for CSS customisations)', 'twentyfifteen-child'); ?></option>
	                      <optgroup label="<?php _e('Web-safe fonts', 'twentyfifteen-child'); ?>">
	                          <option value='Georgia, serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), 'Georgia, serif' ); ?>>Georgia, serif</option>
	                          <option value='"Palatino Linotype", "Book Antiqua", Palatino, serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Palatino Linotype", "Book Antiqua", Palatino, serif' ); ?>>"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
	                          <option value='"Times New Roman", Times, serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Times New Roman", Times, serif' ); ?>>"Times New Roman", Times, serif</option>
	                          <option value='Arial, Helvetica, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), 'Arial, Helvetica, sans-serif' ); ?>>Arial, Helvetica, sans-serif</option>
	                          <option value='"Arial Black", Gadget, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Arial Black", Gadget, sans-serif' ); ?>>"Arial Black", Gadget, sans-serif</option>
	                          <option value='"Comic Sans MS", cursive, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Comic Sans MS", cursive, sans-serif' ); ?>>"Comic Sans MS", cursive, sans-serif</option>
	                          <option value='Impact, Charcoal, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), 'Impact, Charcoal, sans-serif' ); ?>>Impact, Charcoal, sans-serif</option>
	                          <option value='"Lucida Sans Unicode", "Lucida Grande", sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Lucida Sans Unicode", "Lucida Grande", sans-serif' ); ?>>"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
	                          <option value='Tahoma, Geneva, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), 'Tahoma, Geneva, sans-serif' ); ?>>Tahoma, Geneva, sans-serif</option>
	                          <option value='"Trebuchet MS", Helvetica, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Trebuchet MS", Helvetica, sans-serif' ); ?>>"Trebuchet MS", Helvetica, sans-serif</option>
	                          <option value='Verdana, Geneva, sans-serif' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), 'Verdana, Geneva, sans-serif' ); ?>>Verdana, Geneva, sans-serif</option>
	                          <option value='"Courier New", Courier, monospace' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Courier New", Courier, monospace' ); ?>>"Courier New", Courier, monospace</option>
	                          <option value='"Courier 10 Pitch", Courier, monospace' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Courier 10 Pitch", Courier, monospace' ); ?>>"Courier New", Courier, monospace</option>
	                          <option value='"Lucida Console", Monaco, monospace' data-variants="400,700," data-subsets="all," <?php selected( $this->value(), '"Lucida Console", Monaco, monospace' ); ?>>"Lucida Console", Monaco, monospace</option>
	                      </optgroup>
	                      <optgroup label="<?php _e('Google fonts', 'twentyfifteen-child'); ?>">
	                      <?php
	                          foreach ( $decoded->items as $item ) {

	                              $family = $item->family;
	                              $variants = str_replace('regular','400',implode(',', $item->variants));
	                              $subsets = implode(',', $item->subsets);

	                              ?>

	                              <option value="<?php echo $family; ?>" data-variants="<?php echo $variants; ?>," data-subsets="<?php echo $subsets; ?>," <?php selected( $this->value(), $family ); ?>><?php echo $family; ?></option>

	                          <?php }
	                      ?>
	                      </optgroup>
	                      <?php if ( $this->description != '' ) { ?>
	                          <br><span class="description customize-control-description"><?php echo $this->description; ?></span>
	                      <?php } ?>
	                  </select>

	              </label>

			  <?php elseif ( $this->field == 'weight' ) : ?>
	              <label>
					<span class="customize-control-title">
						<?php echo esc_attr( $this->label ); ?>
						<?php if ( ! empty( $this->description ) ) : ?>
							<?php // The description has already been sanitized in the Fields class, no need to re-sanitize it. ?>
							<span class="description customize-control-description"><?php echo $this->description; ?></span>
						<?php endif; ?>
					</span>

	                <select <?php $this->link()->weight; ?> class="chosen-select select2 select-font-weight">
	                    <?php
	                        for ($i = 1; $i <= 10; $i++) {
	                            $n = $i*100;
	                            ?>
	                            <option value="<?php echo $n; ?>" <?php selected( $this->value()->weight, $n ); ?>><?php echo $n ; ?></option>
	                            <?php $n = $n == '400' ? '' : $n; ?>
	                            <option value="<?php echo $n . 'italic'; ?>" <?php selected( $this->value()->weight, $n . 'italic' ); ?>><?php echo $n . 'italic'; ?></option>
	                        <?php
	                        }
	                    ?>
	                </select>

				</label>

			<?php elseif ( $this->field == 'subsets' ) : ?>
	              <label>
					<span class="customize-control-title">
		  				<?php echo esc_attr( $this->label ); ?>
		  				<?php if ( ! empty( $this->description ) ) : ?>
		  					<?php // The description has already been sanitized in the Fields class, no need to re-sanitize it. ?>
		  					<span class="description customize-control-description"><?php echo $this->description; ?></span>
		  				<?php endif; ?>
		  			</span>

					<?php
						$value = !is_array($this->value()->subsets) ? array($this->value()->subsets) : $this->value()->subsets;
					?>

					<select <?php $this->link()->subsets; ?> class="chosen-select select2 select-font-subsets" multiple>
	                    <option value="latin" <?php selected( true, in_array( 'latin', $value )); ?>>latin</option>
	                    <option value="latin-ext" <?php selected( true, in_array( 'latin-ext', $value )); ?>>latin-ext</option>
	                    <option value="menu" <?php selected( true, in_array( 'menu', $value )); ?>>menu</option>
	                    <option value="greek" <?php selected( true, in_array( 'greek', $value )); ?>>greek</option>
	                    <option value="greek-ext" <?php selected( true, in_array( 'greek-ext', $value )); ?>>greek-ext</option>
	                    <option value="cyrillic" <?php selected( true, in_array( 'cyrillic', $value )); ?>>cyrillic</option>
	                    <option value="cyrillic-ext" <?php selected( true, in_array( 'cyrillic-ext', $value )); ?>>cyrillic-ext</option>
	                    <option value="vietnamese" <?php selected( true, in_array( 'vietnamese', $value )); ?>>vietnamese</option>
	                    <option value="arabic" <?php selected( true, in_array( 'arabic', $value )); ?>>arabic</option>
	                    <option value="khmer" <?php selected( true, in_array( 'khmer', $value )); ?>>khmer</option>
	                    <option value="lao" <?php selected( true, in_array( 'lao', $value )); ?>>lao</option>
	                    <option value="tamil" <?php selected( true, in_array( 'tamil', $value )); ?>>tamil</option>
	                    <option value="bengali" <?php selected( true, in_array( 'bengali', $value )); ?>>bengali</option>
	                    <option value="hindi" <?php selected( true, in_array( 'hindi', $value )); ?>>hindi</option>
	                    <option value="korean" <?php selected( true, in_array( 'korean', $value )); ?>>korean</option>
	                </select>

				</label>

			  <?php endif; ?>

			  <script>
				jQuery(document).ready(function($) {
					$('.select2').select2();
				});
	  		</script>
              <?php
         }
    }
}
