<?php
declare( strict_types=1 );

$dt_post_type = get_post_type();
if ( ! current_user_can( 'access_' . $dt_post_type ) ) {
    wp_safe_redirect( '/settings' );
}

( function () {
    $post_type = get_post_type();
    $post_id = get_the_ID();
    if ( !DT_Posts::can_view( $post_type, $post_id )){
        get_template_part( "403" );
        die();
    }
    $post_settings = apply_filters( "dt_get_post_type_settings", [], $post_type );
    $dt_post = DT_Posts::get_post( $post_type, $post_id );
    $tiles = DT_Posts::get_post_tiles( $post_type );
    get_header();
    dt_print_details_bar(
        true,
        false,
        false,
        $post_type === "contacts" && ( $dt_post["type"] ?? "" ) === "access",
        false,
        false,
        [],
        true
    );
    ?>
    <div id="content" class="single-template">
        <div id="inner-content" class="grid-x grid-margin-x grid-margin-y">

            <?php do_action( 'dt_record_top_full_with', $post_type, $dt_post ) ?>

            <main id="main" class="large-7 medium-12 small-12 cell" role="main" style="padding:0">
                <div class="cell grid-y grid-margin-y">

                    <?php do_action( 'dt_record_top_above_details', $post_type, $dt_post ); ?>

                    <!--
                        Status section
                    -->
                    <section id="contact-status" class="small-12 cell bordered-box">
                        <h3 class="section-header">
                            <?php echo esc_html__( "Status", "disciple_tools" )?>
                        </h3>
                        <?php do_action( "dt_details_additional_section", 'status', $post_type ); ?>
                        <?php
                        //setup the order of the tile fields
                        $order = $custom_tiles[$post_type]['status']["order"] ?? [];
                        foreach ( $post_settings["fields"] as $key => $option ){
                            if ( isset( $option["tile"] ) && $option["tile"] === 'status' ){
                                if ( !in_array( $key, $order )){
                                    $order[] = $key;
                                }
                            }
                        }
                        foreach ( $order as $field_key ) {
                            if ( !isset( $post_settings["fields"][$field_key] ) ){
                                continue;
                            }

                            $field = $post_settings["fields"][$field_key];
                            if ( isset( $field["tile"] ) && $field["tile"] === 'status'){
                                render_field_for_display( $field_key, $post_settings["fields"], $dt_post );
                            }
                        }
                        ?>
                    </section>

                    <!--
                        Main details section
                    -->
                    <section id="details-tile" class="small-12 cell bordered-box" >
                        <!--
                            Name row
                        -->
                        <div id="name-row" style="display: flex;">
                            <div class="item-details-header" style="flex-grow:1;">
                                <?php $picture = apply_filters( 'dt_record_picture', null, $post_type, $post_id );
                                if ( !empty( $picture ) ) : ?>
                                    <img src="<?php echo esc_html( $picture )?>" style="height:50px; vertical-align:middle">
                                <?php else : ?>
                                    <i class="fi-torso large" style="padding-bottom: 1.2rem"></i>
                                <?php endif; ?>
                                <span id="title" contenteditable="true" class="title dt_contenteditable"><?php the_title_attribute(); ?></span>
                            </div>
                            <div>
                                <button class="section-chevron chevron_down show-details-section">
                                    <img src="<?php echo esc_html( get_template_directory_uri() . '/dt-assets/images/chevron_down.svg' ) ?>"/>
                                </button>
                                <button class="section-chevron chevron_up show-details-section">
                                    <img src="<?php echo esc_html( get_template_directory_uri() . '/dt-assets/images/chevron_up.svg' ) ?>"/>
                                </button>
                            </div>
                        </div>

                        <!--
                            Details section
                        -->
                        <div class="collapsed-details-section" style="">
                            <div style="display:flex">
                            <?php
                            //setup the order of the tile fields
                            $order = $custom_tiles[$post_type]['details']["order"] ?? [];
                            foreach ( $post_settings["fields"] as $key => $option ){
                                if ( isset( $option["tile"] ) && $option["tile"] === 'details' && $option['type'] === "communication_channel" ){
                                    if ( !in_array( $key, $order )){
                                        $order[] = $key;
                                    }
                                }
                            }
                            foreach ( $order as $field_key ) {
                                if ( !isset( $post_settings["fields"][$field_key] ) ){
                                    continue;
                                }

                                $field = $post_settings["fields"][$field_key];
                                if ( isset( $field["tile"] ) && $field["tile"] === 'details'){
                                    $basis = ( isset( $field["in_create_form"] ) && $field["in_create_form"] === true ) ? '20%' : 'auto';
                                    ?>
                                    <div style="display:none; flex-basis:<?php echo esc_html( $basis ); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding-right:15px; margin-bottom:5px" id="collapsed-detail-<?php echo esc_html( $field_key ); ?>">
                                        <?php if ( isset( $field["icon"] ) ) : ?>
                                            <img src="<?php echo esc_html( $field["icon"] ); ?>" style="margin-right:3px; vertical-align:middle; height:15px;width:15px">
                                        <?php else : ?>
                                            <strong><?php echo esc_html( $field['name'] ); ?></strong>
                                        <?php endif ?>
                                        <span class="collapsed-items"></span>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                            </div>
                            <div style="display:flex">
                            <?php
                            $order = $custom_tiles[$post_type]['details']["order"] ?? [];
                            foreach ( $post_settings["fields"] as $key => $option ){
                                if ( isset( $option["tile"] ) && $option["tile"] === 'details' && $option['type'] !== "communication_channel" ){
                                    if ( !in_array( $key, $order ) && !in_array( $key, [ 'name' ] ) ) {
                                        $order[] = $key;
                                    }
                                }
                            }
                            foreach ( $order as $field_key ) {
                                if ( !isset( $post_settings["fields"][$field_key] ) ){
                                    continue;
                                }

                                $field = $post_settings["fields"][$field_key];
                                if ( isset( $field["tile"] ) && $field["tile"] === 'details'){
                                    $basis = ( isset( $field["in_create_form"] ) && $field["in_create_form"] === true ) ? '20%' : 'auto';
                                    ?>
                                        <div style="display:none; flex-basis:<?php echo esc_html( $basis ); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding-right:15px; margin-bottom:5px" id="collapsed-detail-<?php echo esc_html( $field_key ); ?>">
                                            <?php if ( isset( $field["icon"] ) ) : ?>
                                                <img src="<?php echo esc_html( $field["icon"] ); ?>" style="margin-right:3px; vertical-align:middle; height:15px;width:15px">
                                            <?php else : ?>
                                                <strong><?php echo esc_html( $field['name'] ); ?></strong>
                                            <?php endif ?>
                                                <span class="collapsed-items"></span>
                                        </div>
                                    <?php
                                }
                            }
                            ?>
                            </div>
                        </div>
                        <div id="show-details-edit-button" class="show-details-section" style="text-align: center; background-color:rgb(236, 245, 252);margin: 3px -15px -15px -15px; border-radius:5px;">
                            <a class="button clear " style="margin:0;padding:3px 0; width:100%">
<!--                                <img class="dt-icon" style="margin-left:0px; height:10px; width:10px; filter:invert(22%) sepia(0%) saturate(0%) hue-rotate(223deg) brightness(101%) contrast(84%)" src="--><?php //echo esc_url( get_template_directory_uri() ) . '/dt-assets/images/facebook.svg' ?><!--" alt="facebook">-->
                                <?php esc_html_e( 'Edit all details fields', 'disciple_tools' ); ?>
<!--                                <img class="dt-icon" style="margin-left:0px; height:10px; width:10px; filter:invert(22%) sepia(0%) saturate(0%) hue-rotate(223deg) brightness(101%) contrast(84%)" src="--><?php //echo esc_url( get_template_directory_uri() ) . '/dt-assets/images/gender.svg' ?><!--" alt="gender">-->
<!--                                <img class="dt-icon" style="margin-left:0px; height:10px; width:10px; filter:invert(22%) sepia(0%) saturate(0%) hue-rotate(223deg) brightness(101%) contrast(84%)" src="--><?php //echo esc_url( get_template_directory_uri() ) . '/dt-assets/images/location.svg' ?><!--" alt="location">-->
                            </a></div>

                        <div id="details-section" class="display-fields" style="display: none; margin-top:20px">
                            <h3 class="section-header">
                                <?php echo esc_html__( "All Details Fields", "disciple_tools" )?>
                            </h3>
                            <div class="">
                                <?php
                                // let the plugin add section content
                                do_action( "dt_details_additional_section", 'details', $post_type );
                                //setup the order of the tile fields
                                $order = $custom_tiles[$post_type]['details']["order"] ?? [];
                                foreach ( $post_settings["fields"] as $key => $option ){
                                    if ( isset( $option["tile"] ) && $option["tile"] === 'details' ){
                                        if ( !in_array( $key, $order )){
                                            $order[] = $key;
                                        }
                                    }
                                }
                                foreach ( $order as $field_key ) {
                                    if ( !isset( $post_settings["fields"][$field_key] ) ){
                                        continue;
                                    }

                                    $field = $post_settings["fields"][$field_key];
                                    if ( isset( $field["tile"] ) && $field["tile"] === 'details'){
                                        render_field_for_display( $field_key, $post_settings["fields"], $dt_post );
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </section>


                    <!--
                        Tiles Section
                    -->
                    <div class="cell small-12">
                        <div class="grid-x grid-margin-x grid-margin-y grid">
                            <?php
                            foreach ( $tiles as $tile_key => $tile_options ){
                                if ( ( isset( $tile_options["hidden"] ) && $tile_options["hidden"] == true ) || in_array( $tile_key, [ 'details' ] ) ) {
                                    continue;
                                }
                                ?>
                                <section id="<?php echo esc_html( $tile_key ) ?>" class="xlarge-6 large-12 medium-6 cell grid-item">
                                    <div class="bordered-box" id="<?php echo esc_html( $tile_key ) ?>-tile">
                                        <?php
                                        //setup tile label if see by customizations
                                        if ( isset( $tile_options["label"] ) ){ ?>
                                            <h3 class="section-header">
                                                <?php echo esc_html( $tile_options["label"] )?>
                                                <button class="section-chevron chevron_down">
                                                    <img src="<?php echo esc_html( get_template_directory_uri() . '/dt-assets/images/chevron_down.svg' ) ?>"/>
                                                </button>
                                                <button class="section-chevron chevron_up">
                                                    <img src="<?php echo esc_html( get_template_directory_uri() . '/dt-assets/images/chevron_up.svg' ) ?>"/>
                                                </button>

                                            </h3>
                                        <?php } ?>

                                        <div class="section-body">
                                            <?php
                                            // let the plugin add section content
                                            do_action( "dt_details_additional_section", $tile_key, $post_type );

                                            //setup the order of the tile fields
                                            $order = $tile_options["order"] ?? [];
                                            foreach ( $post_settings["fields"] as $key => $option ){
                                                if ( isset( $option["tile"] ) && $option["tile"] === $tile_key ){
                                                    if ( !in_array( $key, $order )){
                                                        $order[] = $key;
                                                    }
                                                }
                                            }
                                            foreach ( $order as $field_key ) {
                                                if ( !isset( $post_settings["fields"][$field_key] ) ){
                                                    continue;
                                                }

                                                $field = $post_settings["fields"][$field_key];
                                                if ( isset( $field["tile"] ) && $field["tile"] === $tile_key){
                                                    render_field_for_display( $field_key, $post_settings["fields"], $dt_post, true );
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </section>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </main>

            <aside class="auto cell grid-x">
                <section class="comment-activity-section cell"
                         id="comment-activity-section">
                    <?php get_template_part( 'dt-assets/parts/loop', 'activity-comment' ); ?>
                </section>
            </aside>

        </div>
    </div>

    <?php get_template_part( 'dt-assets/parts/modals/modal', 'share' ); ?>
    <?php get_template_part( 'dt-assets/parts/modals/modal', 'tasks' ); ?>
    <?php get_template_part( 'dt-assets/parts/modals/modal', 'new-contact' ); ?>

    <div class="reveal" id="create-tag-modal" data-reveal data-reset-on-close>
        <h3><?php esc_html_e( 'Create Tag', 'disciple_tools' )?></h3>
        <p><?php esc_html_e( 'Create a tag and apply it to this record.', 'disciple_tools' )?></p>

        <form class="js-create-tag">
            <label for="title">
                <?php esc_html_e( "Tag", "disciple_tools" ); ?>
            </label>
            <input name="title" id="new-tag" type="text" placeholder="<?php esc_html_e( "Tag", 'disciple_tools' ); ?>" required aria-describedby="name-help-text">
            <p class="help-text" id="name-help-text"><?php esc_html_e( "This is required", "disciple_tools" ); ?></p>
        </form>

        <div class="grid-x">
            <button class="button button-cancel clear" data-close aria-label="Close reveal" type="button">
                <?php echo esc_html__( 'Cancel', 'disciple_tools' )?>
            </button>
            <button class="button" data-close type="button" id="create-tag-return">
                <?php esc_html_e( 'Create and apply tag', 'disciple_tools' ); ?>
            </button>
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <?php get_footer();
} )();
