<div class="reveal" id="create-contact-modal" data-reveal data-reset-on-close>

    <p class="lead"><?php esc_html_e( 'Create Contact', 'disciple_tools' )?></p>

    <form class="js-create-contact hide-after-contact-create">
        <label for="title">
            <?php esc_html_e( "Name of Contact", "disciple_tools" ); ?>
        </label>
        <input name="title" type="text" placeholder="<?php esc_html_e( "Name", "disciple_tools" ); ?>" required aria-describedby="name-help-text">

        <div>
            <button class="button loader js-create-contact-button" type="submit"><?php esc_html_e( "Create Contact", "disciple_tools" ); ?></button>
            <button class="button button-cancel clear hide-after-contact-create" data-close aria-label="Close reveal" type="button">
                <?php esc_html_e( 'Cancel', 'disciple_tools' )?>
            </button>
        </div>
        <p style="color: red" class="error-text"></p>
    </form>

    <p class="reveal-after-contact-create" style="display: none"><?php esc_html_e( "Contact Created", 'disciple_tools' ) ?>: <span id="new-contact-link"></span></p>


    <hr class="reveal-after-group-create" style="display: none">
    <div class="grid-x">
        <a class="button reveal-after-contact-create" id="go-to-contact" style="display: none">
            <?php esc_html_e( 'Edit New Contact', 'disciple_tools' )?>
        </a>
        <button class="button reveal-after-contact-create button-cancel clear" data-close type="button" id="create-contact-return" style="display: none">
            <?php
            if ( is_singular( "contacts" )){
                esc_html_e( 'Return to Contact', 'disciple_tools' );
            } elseif ( is_singular( "groups" )){
                esc_html_e( 'Return to Group', 'disciple_tools' );
            } else {
                esc_html_e( 'Return', 'disciple_tools' );
            }
            ?>
        </button>
        <button class="close-button" data-close aria-label="Close modal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
