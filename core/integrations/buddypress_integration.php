<?php

// Add custom metadata to BuddyBoss groups upon creation or update.
function add_custom_nft_metadata_to_group( $group_id, $member, $group ) {
    // Check if the custom attributes are set in the POST request.
    if ( isset( $_POST['contract'] ) ) {
        groups_update_groupmeta( $group_id, 'contract', sanitize_text_field( $_POST['contract'] ) );
    }
    if ( isset( $_POST['minter'] ) ) {
        groups_update_groupmeta( $group_id, 'minter', sanitize_text_field( $_POST['minter'] ) );
    }
    if ( isset( $_POST['nft'] ) ) {
        groups_update_groupmeta( $group_id, 'nft', sanitize_text_field( $_POST['nft'] ) );
    }
    if ( isset( $_POST['schedule'] ) ) {
        groups_update_groupmeta( $group_id, 'schedule', sanitize_text_field( $_POST['schedule'] ) );
    }
}

// Hook the function to the group creation and update actions.
add_action( 'groups_update_group', 'add_custom_nft_metadata_to_group', 10, 3 );

function looppress_group_tab_screen() {
    add_action( 'bp_template_content', 'looppress_group_tab_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'groups/single/plugins' ) );
}


function looppress_group_tab_content() {
    // Get the current group ID.
    $group_id = bp_get_current_group_id();

    // Check if the form is submitted.
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        // Check if the custom attributes are set in the POST request.
        if ( isset( $_POST['contract'] ) ) {
            groups_update_groupmeta( $group_id, 'contract', sanitize_text_field( $_POST['contract'] ) );
        }
        if ( isset( $_POST['minter'] ) ) {
            groups_update_groupmeta( $group_id, 'minter', sanitize_text_field( $_POST['minter'] ) );
        }
        if ( isset( $_POST['nft'] ) ) {
            groups_update_groupmeta( $group_id, 'nft', sanitize_text_field( $_POST['nft'] ) );
        }
        if ( isset( $_POST['schedule'] ) ) {
            groups_update_groupmeta( $group_id, 'schedule', sanitize_text_field( $_POST['schedule'] ) );
        }
        // Add a success message.
        bp_core_add_message( 'Settings saved successfully.', 'success' );
    }

    // Fetch the saved values (if any) from the group metadata.
    $contract = $group_id ? groups_get_groupmeta( $group_id, 'contract', true ) : '';
    $minter   = $group_id ? groups_get_groupmeta( $group_id, 'minter', true ) : '';
    $nft      = $group_id ? groups_get_groupmeta( $group_id, 'nft', true ) : '';
    $schedule      = $group_id ? groups_get_groupmeta( $group_id, 'schedule', true ) : '';
    // Output the custom fields.
    ?>
    <form action="" method="post" class="standard-form">
        <h4>LoopPress Settings</h4>
        <br>
        <label for="contract">Contract</label>
        <input type="text" name="contract" id="contract" value="<?php echo esc_attr( $contract ); ?>">
        <br>
        <label for="minter">Minter</label>
        <input type="text" name="minter" id="minter" value="<?php echo esc_attr( $minter ); ?>">
        <br>
        <label for="nft">NFT</label>
        <input type="text" name="nft" id="nft" value="<?php echo esc_attr( $nft ); ?>">
        <br>
        <label for="nft">Schedule</label>
        <input type="text" name="schedule" id="schedule" value="<?php echo esc_attr( $schedule ); ?>">
        <br>
        <input type="submit" value="Save Settings">
    </form>
    <?php
}

function looppress_register_group_tab() {
    if ( bp_is_group() ) {
        bp_core_new_subnav_item( array(
            'name'            => 'LoopPress',
            'slug'            => 'looppress',
            'parent_url'      => bp_get_group_permalink( groups_get_current_group() ),
            'parent_slug'     => bp_get_current_group_slug(),
            'screen_function' => 'looppress_group_tab_screen',
            'position'        => 75,
            'user_has_access' => bp_current_user_can( 'bp_moderate' ),
            'item_css_id'     => 'group-looppress'
        ) );
    }
}
add_action( 'bp_setup_nav', 'looppress_register_group_tab', 100 );


function looppress_gate_groups_access() {
    // Check if we're viewing a single group.
    if ( bp_is_group() ) {
        // Fetch the group's minter, nft, and contract attributes from group meta.
        $group_id = bp_get_current_group_id();
        $group_minter = groups_get_groupmeta( $group_id, 'minter', true );
        $group_nft = groups_get_groupmeta( $group_id, 'nft', true );
        $group_schedule = groups_get_groupmeta( $group_id, 'schedule', true );
        $group_contract = groups_get_groupmeta( $group_id, 'contract', true );
        $has_access = true;
        if($group_minter || $group_nft || $group_contract || $group_schedule){
            $has_access = false;
            // Get the current user.
            $current_user = wp_get_current_user();

            // Fetch the user's NFTs from user meta (assuming it's stored as an array).
            $user_nfts = get_user_meta( $current_user->ID, 'nfts', true );

            // Check each user's NFT against the group's attributes.
            foreach ( $user_nfts as $nft ) {
                if ( check_conditions( $nft, array( 'minter' => $group_minter, 'nft' => $group_nft, 'contract' => $group_contract, 'schedule' => $group_schedule ) ) ) {
                    $has_access = true;
                    break;
                }
            }
        }
        if(bp_current_user_can( 'bp_moderate' )){
            $has_access = true;
        }

        // If the user doesn't have access, redirect them with a message.
        if ( ! $has_access ) {
            bp_core_add_message( 'Sorry, you do not have access to this group based on your NFTs.', 'error' );
            bp_core_redirect( bp_get_groups_directory_permalink() );
        }
    }
}

add_action( 'bp_template_redirect', 'looppress_gate_groups_access' );

function looppress_add_custom_activity_fields() {
    ?>
    <div id="looppress-settings-wrapper">
        <h4 id="looppress-settings-header"><span id="lock">&#x1F512;</span> <u>LoopPress Settings</u></h4>
        <div id="looppress-settings-content" style="display:none;">
            <label for="contract">Contract</label>
            <input type="text" name="contract" id="contract" />
            <br>
            <label for="minter">Minter</label>
            <input type="text" name="minter" id="minter" />
            <br>
            <label for="nft">NFT</label>
            <input type="text" name="nft" id="nft" />
            <br>
            <label for="schedule">Schedule</label>
            <input type="text" name="schedule" id="schedule" />
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById('looppress-settings-header').addEventListener('click', function() {
            var content = document.getElementById('looppress-settings-content');
            if (content.style.display === 'none') {
                content.style.display = 'block';
            } else {
                content.style.display = 'none';
            }
        });
    </script>
    <style type="text/css">
        #looppress-settings-header {
            cursor: pointer;
        }
        #looppress-settings-content {
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
    <?php
}

add_action( 'bp_activity_post_form_options', 'looppress_add_custom_activity_fields' );

function looppress_save_custom_activity_fields() {
    if ( isset( $_POST['contract'] ) && isset( $_POST['minter'] ) && isset( $_POST['nft'] ) ) {
        // Sanitize and save the data as activity meta.
        bp_activity_update_meta( bp_get_activity_id(), 'contract', sanitize_text_field( $_POST['contract'] ) );
        bp_activity_update_meta( bp_get_activity_id(), 'minter', sanitize_text_field( $_POST['minter'] ) );
        bp_activity_update_meta( bp_get_activity_id(), 'nft', sanitize_text_field( $_POST['nft'] ) );
        bp_activity_update_meta( bp_get_activity_id(), 'schedule', sanitize_text_field( $_POST['schedule'] ) );
    }
}
add_action( 'bp_activity_posted_update', 'looppress_save_custom_activity_fields', 10, 3 );

function looppress_display_custom_activity_fields() {
    $contract = bp_activity_get_meta( bp_get_activity_id(), 'contract', true );
    $minter = bp_activity_get_meta( bp_get_activity_id(), 'minter', true );
    $nft = bp_activity_get_meta( bp_get_activity_id(), 'nft', true );

    if ( $contract || $minter || $nft || $schedule) {
        echo '<div class="looppress-meta">';
        echo '<p>Contract: ' . esc_html( $contract ) . '</p>';
        echo '<p>Minter: ' . esc_html( $minter ) . '</p>';
        echo '<p>NFT: ' . esc_html( $nft ) . '</p>';
        echo '<p>Schedule: ' . esc_html( $schedule ) . '</p>';
        echo '</div>';
    }
}
add_action( 'bp_activity_entry_content', 'looppress_display_custom_activity_fields' );

function restrict_activity_access_based_on_nfts() {
    // Check if we're viewing a single activity.
    if ( bp_is_single_activity() ) {
        // Fetch the activity's minter, nft, and contract attributes from activity meta.
        $activity_id = bp_get_activity_id();
        $activity_minter = bp_activity_get_meta( $activity_id, 'minter', true );
        $activity_nft = bp_activity_get_meta( $activity_id, 'nft', true );
        $activity_schedule = bp_activity_get_meta( $activity_id, 'schedule', true );
        $activity_contract = bp_activity_get_meta( $activity_id, 'contract', true );

        $has_access = false;

        // Get the current user.
        $current_user = wp_get_current_user();

        // Get the activity details.
        $activity = new BP_Activity_Activity( $activity_id );

        // Check if the current user is the author of the activity.
        if ( $activity->user_id == $current_user->ID ) {
            $has_access = true;
        }
        // Only check access if the activity has NFT attributes set and the user is not the author.
        elseif ( $activity_minter || $activity_nft || $activity_contract || $activity_schedule ) {
            // Fetch the user's NFTs from user meta (assuming it's stored as an array).
            $user_nfts = get_user_meta( $current_user->ID, 'nfts', true );

            // Check each user's NFT against the activity's attributes.
            foreach ( $user_nfts as $nft ) {
                if ( check_conditions( $nft, array( 'minter' => $activity_minter, 'nft' => $activity_nft, 'contract' => $activity_contract, 'schedule' => $activity_schedule ) ) ) {
                    $has_access = true;
                    break;
                }
            }
        } else {
            // If the activity doesn't have NFT attributes set, allow access.
            $has_access = true;
        }

        // Allow access for moderators.
        if ( bp_current_user_can( 'bp_moderate' ) ) {
            $has_access = true;
        }

        // If the user doesn't have access, redirect them with a message.
        if ( ! $has_access ) {
            bp_core_add_message( 'Sorry, you do not have access to this activity based on your NFTs.', 'error' );
            bp_core_redirect( bp_get_activity_directory_permalink() );
        }
    }
}

add_action( 'bp_template_redirect', 'restrict_activity_access_based_on_nfts' );
