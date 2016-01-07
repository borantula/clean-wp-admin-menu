<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://borayalcin.me
 * @since      1.0.0
 *
 * @package    Clean_Wp_Admin_Menu
 * @subpackage Clean_Wp_Admin_Menu/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Clean_Wp_Admin_Menu
 * @subpackage Clean_Wp_Admin_Menu/admin
 * @author     Bora Yalcin <email@borayalcin.me>
 */
class Clean_Wp_Admin_Menu_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    public $toggleItemSlug = 'toggle_extra';
    public $toggleItemOrder = '98.1';
    public $hiddenItemsOptionName = 'toggle_extra_items';
    public $nonceName = 'toggle_extra_options';


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Clean_Wp_Admin_Menu_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Clean_Wp_Admin_Menu_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/clean-wp-admin-menu-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Clean_Wp_Admin_Menu_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Clean_Wp_Admin_Menu_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/clean-wp-admin-menu-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Add menu pages in admin
     */
    public function addMenuPages()
    {
        $toggleName = __('Toggle Extra', $this->plugin_name);

        //placeholder
        add_menu_page($toggleName, $toggleName, 'manage_options', $this->toggleItemSlug, function () {
            return false;
        }, "dashicons-hidden", $this->toggleItemOrder);

        add_options_page(
            __('Clean Admin Menu', $this->plugin_name),
            __('Clean Admin Menu', $this->plugin_name),
            'manage_options',
            $this->plugin_name . '_options',
            array(
                $this,
                'settingsPage'
            )
        );

    }

    /**
     * Add necessary items
     */
    public function adminMenuAction()
    {

        global $_registered_pages, $_parent_pages, $menu, $admin_page_hooks, $submenu;
        global $self, $parent_file, $submenu_file, $plugin_page, $typenow, $_wp_real_parent_file;

        //list of items selected from settings page
        $selectedItems = $this->selectedItems();
        $menuItems     = wp_list_pluck($menu, 2);


        foreach ($menu as $k => $item) {
            // Reminder for parent menu items array
            // 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes, 5 = hookname, 6 = icon_url

            $isSelected      = in_array($item[2], $selectedItems);
            $isCurrentItem   = false;
            $isCurrentParent = false;


            //check if item is parent of current item
            //if not both of them, it deserves to be hidden if it is selected
            if ($parent_file) {
                $isCurrentItem = ($item[2] == $parent_file);

                if (isset($_parent_pages[$parent_file])) {
                    $isCurrentParent = ($_parent_pages[$parent_file] === $item[2]);
                }


            }


            $isHidden = ($isSelected && false === ($isCurrentParent OR $isCurrentItem));


            if ($isHidden) {
                $menu[$k][4] = $item[4] . ' hidden clean-wp-menu__valid-item';
            }
        }
    }


    public function settingsPage()
    {
        global $_registered_pages, $_parent_pages, $menu, $admin_page_hooks, $submenu;

        $this->saveSettings();

        $pluginName = $this->plugin_name;

        $selectedItems = $this->selectedItems();

        ob_start();
        include plugin_dir_path(__FILE__) . 'partials/clean-wp-admin-menu-admin-display.php';
        $output = ob_get_clean();
        echo $output;
    }

    public function selectedItems()
    {
        $items = get_option($this->hiddenItemsOptionName);
        if (!$items) {
            $items = array();
            return $items;
        }
        return $items;
    }

    private function saveSettings()
    {
        global $menu;
        if (!isset($_POST[$this->nonceName])) {
            return false;
        }

        $verify = check_admin_referer($this->nonceName, $this->nonceName);


        //TODO if empty but has post delete items

        if (!isset($_POST['toggle_extra_items'])) {
            $itemsToSave = [];
        } else {

            $menuItems = wp_list_pluck($menu, 2);

            $items = $_POST['toggle_extra_items'];

            //save them after a check if they really exists on menu
            $itemsToSave = [];

            if ($items) {
                foreach ($items as $item) {
                    if (in_array($item, $menuItems)) {
                        $itemsToSave[] = $item;
                    }
                }
            }
        }

            //update the option and set as autoloading option
            update_option($this->hiddenItemsOptionName, $itemsToSave, true);

            // we'll redirect to same page when saved to see results.
            // redirection will be done with js, due to headers error when done with wp_redirect
            $adminPageUrl = admin_url('options-general.php?page=clean-wp-admin-menu_options');
        ?>
        <script>
            window.location.href = "<?=$adminPageUrl ;?>";
        </script>
        <?php
        exit;
    }
}
