<?php
include_once(DEX_PLUGIN_DIR . 'inc/form-builder.php');
//SETTING UP THE PAGE 
function set_page()
{
        add_menu_page('Inventory', 'Inventory', 'manage_options', 'inventory', 'inventory_page', ' dashicons-database-add', 40);
        add_submenu_page('inventory', 'Add Inventory', 'Add Inventory', 'manage_options', 'add-inventory', 'add_inventory');
        add_submenu_page('inventory', 'Edit Inventory', 'Edit Inventory', 'manage_options', 'edit-inventory', 'edit_inventory');

}
//CALLING THE FUNCTION
add_action('admin_menu', 'set_page');

include_once(DEX_PLUGIN_DIR . 'inventory/all.php');
include_once(DEX_PLUGIN_DIR . 'inventory/add.php');
include_once(DEX_PLUGIN_DIR . 'inventory/edit.php');
