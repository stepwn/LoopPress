<?php
function looppress_nft_custom_post_type() {
    register_post_type('looppress_nft', array(
        'labels' => array(
            'name' => 'LoopPress NFTs',
            'singular_name' => 'LoopPress NFT',
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-images-alt2', // Set a custom menu icon
        'rewrite' => array('slug' => 'looppress-nfts'), // Custom permalink structure
        'taxonomies' => array('category'), // You can add more taxonomies here
        'has_archive' => true, // Enable archive page
        'show_in_menu' => false,
    ));
}
add_action('init', 'looppress_nft_custom_post_type');

function looppress_custom_post_template($template) {
    if (is_singular('looppress_nft')) {
        ob_start();
        include(plugin_dir_path(__FILE__) . 'single-looppress_nft.php');
        $content = ob_get_clean();
        wp_head();
        echo $content;
        wp_footer();
        return '';
    }
    return $template;
}
add_filter('template_include', 'looppress_custom_post_template');

// Add custom meta box to select layout style
function looppress_add_layout_style_meta_box() {
    add_meta_box(
        'looppress_layout_style_meta_box',
        'Layout Style',
        'looppress_render_layout_style_meta_box',
        'looppress_nft', // You can change this to other post types if needed
        'normal', // Position of the meta box
        'default'
    );
}
add_action('add_meta_boxes', 'looppress_add_layout_style_meta_box');

// Render the content of the custom meta box
function looppress_render_layout_style_meta_box($post) {
    $layout_style = get_post_meta($post->ID, 'looppress_layout_style', true);

    // Define layout options
    $layout_options = array(
        'two-column' => 'Two-Column',
        'stacked' => 'Stacked',
    );

    // Output the radio buttons
    foreach ($layout_options as $value => $label) {
        echo '<label>';
        echo '<input type="radio" name="looppress_layout_style" value="' . esc_attr($value) . '" ' . checked($layout_style, $value, false) . '>';
        echo ' ' . esc_html($label); // Add a space before the label
        echo '</label><br>';
    }
}

// Save the selected layout style when the post is saved or updated
function looppress_save_layout_style_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['looppress_layout_style'])) {
        $layout_style = sanitize_key($_POST['looppress_layout_style']);
        update_post_meta($post_id, 'looppress_layout_style', $layout_style);
    }
}
add_action('save_post', 'looppress_save_layout_style_meta_box');

// Add custom meta fields to LoopPress NFTs
function looppress_nft_meta_fields() {
    add_meta_box(
        'looppress_nft_meta',
        'LoopPress NFT Details',
        'looppress_nft_meta_callback',
        'looppress_nft',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'looppress_nft_meta_fields');

// Callback function for rendering meta box content
function looppress_nft_meta_callback($post) {
    // Add nonce for security and authentication
    wp_nonce_field('looppress_nft_nonce', 'looppress_nft_nonce');

    // Get existing values from the database
    $nft_id = get_post_meta($post->ID, 'looppress_nft_id', true);
    $minter = get_post_meta($post->ID, 'looppress_minter', true);
    $token = get_post_meta($post->ID, 'looppress_token', true);
    $contract = get_post_meta($post->ID, 'looppress_contract', true);
    $desc = get_post_meta($post->ID, 'looppress_short_description', true);

    // Render the fields
    echo '<label for="looppress_nft_id">LoopPress NFT ID:</label>';
    echo '<input type="text" id="looppress_nft_id" name="looppress_nft_id" value="' . esc_attr($nft_id) . '"><br>';

    echo '<label for="looppress_minter">LoopPress Minter:</label>';
    echo '<input type="text" id="looppress_minter" name="looppress_minter" value="' . esc_attr($minter) . '"><br>';

    echo '<label for="looppress_contract">LoopPress Contract:</label>';
    echo '<input type="text" id="looppress_contract" name="looppress_contract" value="' . esc_attr($token) . '"><br>';

    echo '<label for="looppress_short_description">LoopPress Short Description:</label>';
    echo '<input type="text" id="looppress_short_description" name="looppress_short_description" value="' . esc_attr($desc) . '"><br>';
}

// Save custom meta fields
function save_looppress_nft_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['looppress_nft_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['looppress_nft_nonce'], 'looppress_nft_nonce')) {
        return;
    }

    // Check if the current user has permission to save
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sanitize and save the data
    if (isset($_POST['looppress_nft_id'])) {
        update_post_meta($post_id, 'looppress_nft_id', sanitize_text_field($_POST['looppress_nft_id']));
    }
    if (isset($_POST['looppress_minter'])) {
        update_post_meta($post_id, 'looppress_minter', sanitize_text_field($_POST['looppress_minter']));
    }
    if (isset($_POST['looppress_token'])) {
        update_post_meta($post_id, 'looppress_token', sanitize_text_field($_POST['looppress_token']));
    }
    if (isset($_POST['looppress_contract'])) {
        update_post_meta($post_id, 'looppress_contract', sanitize_text_field($_POST['looppress_contract']));
    }
    if (isset($_POST['looppress_short_description'])) {
        update_post_meta($post_id, 'looppress_short_description', sanitize_text_field($_POST['looppress_short_description']));
    }
}
add_action('save_post_looppress_nft', 'save_looppress_nft_meta');

function add_looppress_shortcode_to_posts( $content ) {
    // Get the current post ID
    $post_id = get_the_ID();
    
    // Check if the post has looppress_nft_id, looppress_minter, and looppress_token metadata
    $nft_id_metadata = get_post_meta( $post_id, 'looppress_nft_id', true );
    $minter_metadata = get_post_meta( $post_id, 'looppress_minter', true );
    $token_metadata = get_post_meta( $post_id, 'looppress_token', true );
    $contract_metadata = get_post_meta( $post_id, 'looppress_contract', true );
    // Check if the post content already contains [looppress] shortcode
    $has_looppress_shortcode = strpos( $content, '[looppress ' ) !== false;
    
    if ( $nft_id_metadata || $minter_metadata || $token_metadata || $contract_metadata ) {
        if(!$has_looppress_shortcode){
        // Add [looppress] shortcode with attributes to the post content
        $shortcode_attributes = 'nft_id="' . $nft_id_metadata . '" minter="' . $minter_metadata . '" token="' . $token_metadata . '" contract="' . $contract_metadata . '"';
        $content = '[looppress ' . $shortcode_attributes . ']' . $content . '[/looppress]';
        }
    }
    return $content;
}
add_filter( 'the_content', 'add_looppress_shortcode_to_posts' );