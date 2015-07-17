<div id="pix-icon-finder">
    <label>
        Icon set:
        <select>
            <option value="budicons" data-url="<?php echo esc_url( get_stylesheet_directory_uri() . '/inc/budicon-font.php' ); ?>">
                Budicons
            </option>
            <option value="zocial" data-url="<?php echo esc_url( get_stylesheet_directory_uri() . '/inc/zocial-font.php' ); ?>">
                Zocial
            </option>
            <option value="fontawesome" data-url="<?php echo esc_url( get_stylesheet_directory_uri() . '/inc/fontawesome-font.php' ); ?>">
                FontAwesome
            </option>
        </select>
    </label>

    <label>
        Find:
        <input type="text" placeholder="<?php _e('Enter keywords','twentyfifteen'); ?>">
    </label>
</div>
<div id="pix-icon-render" class="the-list"></div>
