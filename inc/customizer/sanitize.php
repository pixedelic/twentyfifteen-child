<?php
function pix_sanitize_editor( $content ){
    if('' === $content){
        return '';
    }
    if ( current_user_can('unfiltered_html') )
        return wp_kses($content, wp_kses_allowed_html('post'));
    else
        return stripslashes( wp_filter_post_kses( addslashes($content) ) );
}
