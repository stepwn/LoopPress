<?php
function looppress_dashboard_shortcode($atts) {
    $html = isset($atts['html']) ? $atts['html'] : "<button class='wp-block-button__link wp-element-button looppressButton' onclick='looppress_unlock(this)'>Connect Wallet</button>";
    $redir = isset($atts['redir']) ? $atts['redir'] : false;
    return $html;
}
add_shortcode('looppress_dashboard', 'looppress_dashboard_shortcode');

function looppress_register_shortcode() {
  add_shortcode('looppress_dashboard', 'looppress_dashboard_shortcode');
  add_shortcode('looppress_button', 'looppress_dashboard_shortcode');
}
add_action('init', 'looppress_register_shortcode');

add_shortcode('looppress', 'looppress_membership_shortcode');
add_shortcode('looppress_required', 'looppress_membership_shortcode'); // backwards compatible but depreciated
// shortcodes for nested gates
add_shortcode('looppress_inner', 'looppress_membership_shortcode');
add_shortcode('looppress_inner2', 'looppress_membership_shortcode');
add_shortcode('looppress_inner3', 'looppress_membership_shortcode');
add_shortcode('looppress_inner4', 'looppress_membership_shortcode');
add_shortcode('looppress_inner5', 'looppress_membership_shortcode');
// ... continue as needed

function looppress_shortcode($content = "",$fail_message = "") {
	$redir = false;
	if($content != ""){
		$redir = true;
	}
    if (!get_user_meta(get_current_user_id(), 'lrc_account') && !get_user_meta(get_current_user_id(), 'ethereum_address')) {
    // Only display contents if user is logged in
        return do_shortcode("[looppress_button]");
    }
    else{
        $html = '<p class="looppress_fail_message">'.$fail_message;
        if(get_option("looppress_add_accountButton_to_fail") == 1){
            $dashboard_shortcode = do_shortcode('[looppress_dashboard]'); // Adding the shortcode here
            $html .= $dashboard_shortcode;
        }
        return $html.'</p>';
    }
}

function looppress_membership_shortcode( $atts, $content = null ) {
    $redir = false;
    if ( $content != "" ) {
        $redir = true;
    }

    // Check if no attributes are passed, then set default post metadata
    if ( empty( $atts ) ) {
        $post_id = get_the_ID(); // Get the current post ID
        $nft_id_from_metadata = get_post_meta( $post_id, 'looppress_nft_id', true );
        if($nft_id_from_metadata){
            $atts = array( 'nft_id' => $nft_id_from_metadata );
        }
        $nft_minter_from_metadata = get_post_meta( $post_id, 'looppress_minter', true );
        if($nft_minter_from_metadata){
            $atts = array( 'minter' => $nft_minter_from_metadata );
        }
        $nft_token_from_metadata = get_post_meta( $post_id, 'looppress_token', true );
        if($nft_token_from_metadata){
            $atts = array( 'token' => $nft_token_from_metadata );
        }
        $nft_contract_from_metadata = get_post_meta( $post_id, 'looppress_contract', true );
        if($nft_contract_from_metadata){
            $atts = array( 'contract' => $nft_contract_from_metadata );
        }
    }

    if (!get_user_meta(get_current_user_id(), 'lrc_account') && !get_user_meta(get_current_user_id(), 'ethereum_address')) {
        // Only display contents if user is logged in
        return do_shortcode('[looppress_button redir="'.$redir.'" text="Unlock Content"]');
    }

    // Extract attributes
    $attributes = shortcode_atts( [ 'contract' => '', 'token' => '', 'minter' => '', 'nft_id' => '', 'schedule' => '' ], $atts );

    // Check for schedule attribute and parse it if present
    if ( isset( $attributes['schedule'] ) && !empty( $attributes['schedule'] ) ) {
        $schedule_logic = json_decode( str_replace( "'", '"', $attributes['schedule'] ), true );
        if ( json_last_error() == JSON_ERROR_NONE && is_array( $schedule_logic ) ) {
            // Get the current date
            $current_date = new DateTime();
            $applicable_logic = null;

            // Iterate through the schedule logic and find the most recent applicable logic
            foreach ( $schedule_logic as $date => $logic ) {
                $schedule_date = DateTime::createFromFormat('Y-m-d H:i', $date);
                if ( $schedule_date <= $current_date ) {
                    $applicable_logic = $logic;
                } else {
                    break; // Since the logic is ordered, we can break early
                }
            }

            // If applicable logic is found, override the respective attributes
            if ( $applicable_logic !== null ) {
                foreach (['contract', 'token', 'minter', 'nft_id'] as $attr) {
                    if (isset($applicable_logic[$attr])) {
                        $attributes[$attr] = $applicable_logic[$attr];
                    }
                }
            }
        }
    }

    $fail_message = isset( $atts['fail-message'] ) ? $atts['fail-message'] : get_option("looppress_default_fail_message","<span class='dashicons dashicons-lock' style='font-size: 2.5em; width: 2.5em; display: block; margin: auto; box-sizing: border-box;'></span><br><b>You do not own the required NFT to view this content.</b><br><small>If you recently acquired the NFT, it may take up to 30 minutes for the transaction to post and be available.</small>");

    $selected_account = get_user_meta(get_current_user_id(), 'ethereum_address', true) ?? '';

    return has_membership( $selected_account, $attributes ) ? do_shortcode( $content ) : looppress_shortcode( $content, $fail_message );
}
