<?php

if ( wp_is_block_theme() ) {
    block_header_area();
}
?>

<style>
    .looppress-wrapper {
    display: flex;
    justify-content: center; /* Center the columns horizontally */
    width: 100%;
}

<?php if ( get_post_meta( get_the_ID(), 'looppress_layout_style', true ) === 'two-column' ) : ?>
.looppress-two-column-layout {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: stretch;
    max-width: 100%; /* Set a maximum width for the columns */
    margin: auto; /* Center the columns within the wrapper */
    padding: 20px; /* Add padding on either side */
}

.looppress-left-column,
.looppress-right-column {
    flex: 0 0 calc(50% - 10px); /* Each column takes up 50% width with spacing */
    text-align: center;
}

.looppress-spacing-element {
    flex: 0 0 20px; /* Adjust this value to control spacing width */
}

.looppress-right-column {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
<?php else : ?>
.looppress-stacked-layout {
    text-align: center;
    margin:auto;
    padding: 20px;
    width: 100%;
}
<?php endif; ?>

    /* Optional styling for better appearance */
    img {
        height: auto;
        max-width: 100%;
        border-radius: 16px;
    }

    @media (max-width: 768px) {
        .looppress-two-column-layout {
            flex-direction: column;
            align-items: center;
        }

        .looppress-left-column {
            flex: 1;
            margin-right: 0;
            margin-bottom: 20px;
        }
        .looppress-wrapper{
            width:100%;
        }
        .looppress-stacked-layout{
            width:100%;
        }
        img{
            max-width:100%;
            height:auto;
        }
    }
</style>

<?php
while ( have_posts() ) : the_post();
?>
<div class="looppress-wrapper">
    <?php if ( get_post_meta( get_the_ID(), 'looppress_layout_style', true ) === 'two-column' ) : ?>
    <div class="looppress-two-column-layout">
        <div class="looppress-left-column">
            <h1 class="looppress-nft-title"><?php the_title(); ?></h1>
            <?php the_post_thumbnail( 'large' ); ?>
            <p><?php echo get_post_meta( get_the_ID(), 'looppress_short_description', true ); ?></p>
        </div>
        <div class="looppress-right-column">
            <?php the_content(); ?>
        </div>
    </div>
    <?php else : ?>
    <div class="looppress-stacked-layout">
        <h1 class="looppress-nft-title"><?php the_title(); ?></h1>
        <?php the_post_thumbnail( 'large' ); ?>
        <p><?php echo get_post_meta( get_the_ID(), 'looppress_short_description', true ); ?></p>
        <?php the_content(); ?>
    </div>
    <?php endif; ?>
</div>
<?php
endwhile;

if ( wp_is_block_theme() ) {
    block_footer_area();
}
?>
