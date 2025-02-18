<?php
/**
 * Handles plugin deactivation tasks.
 *
 * @since 1.0.0
 */

function servicehub_mvm_deactivate() {
    // Remove the "Vendor" user role
    remove_role('vendor');
}


