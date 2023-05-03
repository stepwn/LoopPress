<?php
/*
Plugin Name: LoopPress
Plugin URI: https://swantech.us/looppress
Description: Token gate content with Loopring NFTs
Version: 1.0.4
Author: Stephen Swanson
Author URI: https://swantech.us
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Create the LoopPress web3 dashboard
function create_looppress_page() {
    $post_id = wp_insert_post(
        array(
            'post_title' => 'LoopPress',
            'post_type' => 'page',
            'post_content' => '[looppress]',
			'post_status' => 'publish'
        )
    );
}
register_activation_hook( __FILE__, 'create_looppress_page' );
// destroy the LoopPress page upon deactivation
function looppress_deactivation() {
    $query = new WP_Query( array( 'title' => 'LoopPress' ) );
    if ( $query->have_posts() ) {
        $post_id = $query->posts[0]->ID;
        wp_delete_post( $post_id, true );
    }
}
register_deactivation_hook( __FILE__, 'looppress_deactivation' );

function walletconnect_enqueue_scripts() {
  // Enqueue Web3.js library
  wp_enqueue_script('web3', 'https://unpkg.com/web3@1.2.11/dist/web3.min.js', array(), null, true);

  // Enqueue Web3Modal library
  wp_enqueue_script('web3modal', 'https://unpkg.com/web3modal@1.9.0/dist/index.js', array(), null, true);

  // Enqueue evm-chains library
  wp_enqueue_script('evm-chains', 'https://unpkg.com/evm-chains@0.2.0/dist/umd/index.min.js', array(), null, true);

  // Enqueue WalletConnect library
  wp_enqueue_script('walletconnect', 'https://unpkg.com/@walletconnect/web3-provider@1.2.1/dist/umd/index.min.js', array(), null, true);

  // Enqueue your plugin script that contains the code to initiate WalletConnect
  wp_enqueue_script('walletconnect-wordpress', plugin_dir_url(__FILE__) . 'walletconnect.js?v=' . time(), array('jquery'), null, true,'defer');

  // Enqueue your style
  wp_enqueue_style('looppress-style', plugin_dir_url(__FILE__) . 'style.css', array(), filemtime(plugin_dir_path(__FILE__) . 'style.css'), 'all');

  // Dashicons
  wp_enqueue_style('dashicons');
  // Add the key to the inline JS code
  $key = uniqid(); // Generate a unique key
	$_SESSION['proxy_key'] = $key; // Save the key to session storage
	$eth_address = isset($_SESSION['selectedAccount']) ? $_SESSION['selectedAccount'] : '';
	$lrc_account = isset($_SESSION['selectedAccountId']) ? $_SESSION['selectedAccountId'] : '';
	$inline_script = "const looppressKey = '$key';const eth_address = '$eth_address';const lrc_account = '$lrc_account';";
  // Add the inline JS code to the looppress-script file
  wp_add_inline_script('walletconnect-wordpress', $inline_script);
}
add_action('wp_enqueue_scripts', 'walletconnect_enqueue_scripts');

add_action('admin_menu', 'looppress_plugin_menu');
function looppress_plugin_menu() {
    $icon_url = plugins_url( 'lrc.svg', __FILE__ );
    add_menu_page('loopPress Plugin Settings', 'LoopPress Settings', 'manage_options', 'looppress-settings', 'looppress_settings_page', $icon_url);
    echo '<style>.wp-menu-image img {width: 24px;}</style>';
}

add_action('admin_init', 'looppress_settings_init');
function looppress_settings_init() {
    register_setting('looppress_settings', 'loopring_api_key');
}

function looppress_settings_page() {
    ?>
    <div class="wrap">
        <h1>LoopPress Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('looppress_settings'); ?>
            <?php do_settings_sections('looppress_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="loopring_api_key">Loopring API Key</label></th>
                    <td><input type="password" id="loopring_api_key" name="loopring_api_key" value="<?php echo esc_attr(get_option('loopring_api_key')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
		<div>
			<p>Thank you for using LoopPress!</p>
			<p>Please visit <a href="https://github.com/stepwn/LoopPress">GitHub</a> for more information and updates.</p>
			<p>If you find LoopPress helpful, please consider donating to support development. NFTs Welcome!</p>
			<img src="<?php echo plugin_dir_url( __FILE__ ) . 'donate-qr-code.png'; ?>" alt="Donate QR code" />
			<p>0x8886d71DCd602fF1b3e001aA30e080D24E6407A7</p>
		</div>
    </div>
    <?php
}
function looppress_dashboard_shortcode() {
if(!isset($_SESSION['selectedAccount'])&&!isset($_SESSION['selectedAccountId'])){
    return '<div style="text-align:center;display:none;" id="connect-wallet-section">
	<img src="' . plugin_dir_url( __FILE__ ) . 'eth.png" alt="ETH logo" style="width:24px">
	<img src="' . plugin_dir_url( __FILE__ ) . 'lrc.png" alt="LRC logo" style="width:24px">
	<img src="' . plugin_dir_url( __FILE__ ) . 'wp.ico" alt="WordPress logo" style="width:24px">
	</div>
<div id="prepare"><button id="btn-connect" style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button" >Log in Web3</button><p>Click the button above to connect to your wallet.</p></div><div id="connected" style="display:none;overflow-wrap:normal;"><button style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button" id="btn-disconnect">Disconnect</button><p>Connected to <span id="network-name"></span> network with account:</p><p id="selected-account"></p><p id="account-balance"></p>Loopring ID: <p id="loopring-account-ID"></p></div>';
  }
else{
	return '<div style="text-align:center;display:none;" id="connect-wallet-section">
	<img src="' . plugin_dir_url( __FILE__ ) . 'eth.png" alt="ETH logo" style="width:24px">
	<img src="' . plugin_dir_url( __FILE__ ) . 'lrc.png" alt="LRC logo" style="width:24px">
	<img src="' . plugin_dir_url( __FILE__ ) . 'wp.ico" alt="WordPress logo" style="width:24px">
	</div>
	<div id="prepare"><button id="btn-connect" isLoggedIn="True" style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button" >Log in Web3</button><p>Click the button above to connect to your wallet.</p></div><div id="connected" style="display:none;overflow-wrap:normal;"><button style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button" id="btn-disconnect">Disconnect</button><p>Connected to <span id="network-name"></span> network with account:</p><p id="selected-account"></p><p id="account-balance"></p>Loopring ID: <p id="loopring-account-ID"></p></div>';
}
}
function looppress_shortcode($content = "",$fail_message = "") {
	$redir = false;
	if($content != ""){
		$redir = true;
	}
if(!isset($_SESSION['selectedAccount'])&&!isset($_SESSION['selectedAccountId'])){
  // Only display contents if user is logged in
    return '
	<div style="text-align:center;display:none;" id="connect-wallet-section">
	<img src="' . plugin_dir_url( __FILE__ ) . 'eth.png" alt="ETH logo" style="width:24px">
	<img src="' . plugin_dir_url( __FILE__ ) . 'lrc.png" alt="LRC logo" style="width:24px">
	<img src="' . plugin_dir_url( __FILE__ ) . 'wp.ico" alt="WordPress logo" style="width:24px">
	<br>
	<div id="prepare"><button id="btn-connect" redirect="'.$redir.'" style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button" >Log in Web3</button><p>Click the button above to connect to your wallet.</p></div><div id="connected" style="display:none;overflow-wrap:normal;"><button style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button" id="btn-disconnect">Disconnect</button><p>Connected to <span id="network-name"></span> network with account:</p><p id="selected-account"></p><p id="account-balance"></p>Loopring ID: <p id="loopring-account-ID"></p></div>
	</div>
	';
  }
else{
	$html = '<p class="looppress_fail_message">'.$fail_message;
	$page_title = 'LoopPress'; // Replace with your page title
	$page_query = new WP_Query( array( 'pagename' => $page_title ) );
	if ( $page_query->have_posts() ) {
		$page_query->the_post();
		$page_link = get_permalink();
		$html .='<a href="' . $page_link . '" style="display:block;margin:auto;width:fit-content;"class="wp-block-button__link wp-element-button">Web3 Dashboard</a>';
		wp_reset_postdata();
	}
	return $html.'</p>';
}
}

function looppress_register_shortcode() {
  add_shortcode('looppress', 'looppress_dashboard_shortcode');
}
add_action('init', 'looppress_register_shortcode');


function looppress_membership_shortcode( $atts, $content = null ) {
	// Get the token address or minter address from the shortcode attributes
	$token_addresses = isset( $atts['token'] ) ? explode( ',', $atts['token'] ) : array();
	$minter_addresses = isset( $atts['minter'] ) ? explode( ',', $atts['minter'] ) : array();
	$NFT_IDs = isset( $atts['nft_id'] ) ? explode( ',', $atts['nft_id'] ) : array();
	
	// Get other attributes
	$fail_message = isset( $atts['fail_message'] ) ? $atts['fail_message'] : "<span class='dashicons dashicons-lock' style='font-size: 2.5em; width: 2.5em; display: block; margin: auto; box-sizing: border-box;'></span><br><b>You do not own the required NFT to view this content.</b><br><small>If you recently acquired the NFT, it may take up to 30 minutes for the transaction to post and be available.</small>";
	// Get the selected account from the Web3Modal provider
	$selected_account = '';
	if ( isset( $_SESSION['selectedAccount'] ) ) {
		$selected_account = $_SESSION['selectedAccount'];
	}
	// Check if the user has the required NFT ownership
	if ( has_membership( $selected_account, $token_addresses, $minter_addresses,$NFT_IDs ) ) {
		// Return the contents of the shortcode if the user has the required NFT ownership
		return do_shortcode( $content );
	} else {
		// Return a message if the user doesn't have the required NFT ownership
		return looppress_shortcode($content,$fail_message);
	}
}

function has_membership( $selected_account, $token_addresses, $minter_addresses,$NFT_IDs ) {
	// Check if the selected account has the required NFT ownership
	if ( empty( $selected_account ) ) {
		return false;
	}
	$loopringApiKey = get_option('loopring_api_key');
	$loopring_account_id = $_SESSION['selectedAccountId'];
	if( ! empty($token_addresses) && count($token_addresses) == 1){
		$url = "https://api3.loopring.io/api/v3/user/nft/balances?accountId=$loopring_account_id&tokenAddrs=$token_addresses[0]";	
	}
	else{
		$url = "https://api3.loopring.io/api/v3/user/nft/balances?accountId=$loopring_account_id";
	}
	$headers = array('Content-Type: application/json', "X-API-KEY: $loopringApiKey");
	$nfts = array();
    $offset = 0;
    do {
        $url_with_offset = $url . "&offset=$offset";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // set timeout to 60 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url_with_offset);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode != 200) {
            die('Error retrieving NFTs from Loopring API');
        }
        $json = json_decode($response, true);
        $nfts = array_merge($nfts, $json['data']);
		foreach ( $nfts as $nft ) {
			if ( ! empty( $token_addresses ) ) {
				foreach($token_addresses as $token_address){
					if(strtolower( trim( $nft['tokenAddress'] ) ) === strtolower( trim( $token_address ) )){
						return true;
					}
				}
			}
			if ( ! empty( $minter_addresses ) ) {
				foreach($minter_addresses as $minter_address){
					if(strtolower( trim( $nft['minter'] ) ) === strtolower( trim( $minter_address ) )){
						return true;
					}
				}
			}
			if ( ! empty( $NFT_IDs ) ) {
				foreach($NFT_IDs as $NFT_ID){
					if(strtolower( trim( $nft['nftId'] ) ) === strtolower( trim( $NFT_ID ) )){
						return true;
					}
				}
			}
		}
        $offset += count($json['data']);
    } while ($offset < $json['totalNum']);
	// Double Check if the user has the required NFT ownership
	foreach ( $nfts as $nft ) {
		if ( ! empty( $token_addresses ) ) {
			foreach($token_addresses as $token_address){
				if(strtolower( trim( $nft['tokenAddress'] ) ) === strtolower( trim( $token_address ) )){
					return true;
				}
			}
		}
		else if ( ! empty( $minter_addresses ) ) {
			foreach($minter_addresses as $minter_address){
				if(strtolower( trim( $nft['minter'] ) ) === strtolower( trim( $minter_address ) )){
					return true;
				}
			}
		}
		if ( ! empty( $NFT_IDs ) ) {
			foreach($NFT_IDs as $NFT_ID){
				if(strtolower( trim( $nft['nftId'] ) ) === strtolower( trim( $NFT_ID ) )){
					return true;
				}
			}
		}
	}
	return false;
}


add_shortcode( 'looppress_required', 'looppress_membership_shortcode' );

// Register AJAX action to clear session variables
add_action('wp_ajax_clear_session', 'clear_session');

// Function to clear session variables
function clear_session() {
  // Clear session variables
session_start();
session_unset();
session_destroy();
  exit;
  die();
}
// Add JavaScript code to clear session variables on page unload
function clear_session_on_unload() {
  ?>
  <script>
// Function to clear session variables
function clear_session() {
  jQuery.ajax({
    url: '<?php echo admin_url("admin-ajax.php"); ?>',
    type: 'POST',
    data: { action: 'clear_session' },
  });
}
</script>

  <?php
}
add_action('wp_footer', 'clear_session_on_unload');

