<?php
declare(strict_types=1);
?>
<div class="bordered-box">
    <h5><?php esc_html_e( "Contacts", "disciple_tools" ); ?></h5>

    <div class="js-sort-dropdown" hidden>
        <ul class="dropdown menu" data-dropdown-menu>
            <li>
                <a class="button" href="<?php echo esc_url( home_url( '/' ) ) . "contacts/new" ?>"><?php esc_html_e( "Create new contact", "disciple_tools" ); ?></a>
            </li>
            <li>
                <a href="#"><?php esc_html_e( "Sort" ); ?></a>
                <ul class="menu">
                    <li><a href="#" class="js-sort-by" data-column-index="7" data-order="desc">
                        <?php esc_html_e( "Most recent", "disciple_tools" ); ?>
                    </a></li>
                    <li><a href="#" class="js-sort-by" data-column-index="7" data-order="asc">
                        <?php esc_html_e( "Least recent", "disciple_tools" ); ?>
                    </a></li>
                </ul>
            </li>
        </ul>
    </div>

    <table class="table-remove-top-border js-list">
        <thead><tr>
            <th data-priority="1"></th>
            <th data-priority="2"><?php esc_html_e( "Name" ); ?></th>
            <th><?php esc_html_e( "Status", "disciple_tools" ); ?></th>
            <th><?php esc_html_e( "Faith Milestones", "disciple_tools" ); ?></th>
            <th data-priority="4"><?php esc_html_e( "Assigned to", "disciple_tools" ); ?></th>
            <th><?php esc_html_e( "Location", "disciple_tools" ); ?></th>
            <th data-priority="3"><?php esc_html_e( "Group", "disciple_tools" ); ?></th>
            <!--<th><?php esc_html_e( "Last modified", "disciple_tools" ); ?></th>-->
        </tr></thead>
        <tbody>
            <tr class="js-list-loading"><td colspan=7><?php esc_html_e( "Loading...", "disciple_tools" ); ?></td></tr>
        </tbody>
    </table>

</div>
