<?php
/*
 * Plugin Name: Umnico Live Chat
 * Plugin URI: https://umnico.com/
 * Description: Free online chat center widget to communicate with the audience on your website and continue conversations on social media or instant messengers.
 * Version: 1.0.1
 * Requires at least: 5.2
 * Requires PHP: 5.2
 * Author: Umnico
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: umnico-live-chat
 * Domain Path: /languages
 */

if (!defined("ABSPATH")) exit;

require_once plugin_dir_path(__FILE__) . "config.php";
require_once plugin_dir_path(__FILE__) . "install.php";

add_action(
    "plugins_loaded",
    function () {
        register_deactivation_hook(__FILE__, function () {
            umnico_clear_wp_options();
        });

        add_action("admin_menu", "umnico_create_menu");
        add_action("admin_enqueue_scripts", "umnico_enqueue_settings_files");
        add_action("wp_enqueue_scripts", "umnico_enqueue_script");
        add_action("wp_ajax_toggle_widget", "umnico_ajax_handler");

        // i18n
        add_filter("load_textdomain_mofile", "umnico_load_textdomain", 10, 2);
        load_plugin_textdomain(UMNICO_WP_TEXT_DOMAIN);

        add_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED, false);
    }
);

function umnico_create_menu()
{
    add_menu_page(
        __("Add Umnico Live Chat to your website", "umnico-live-chat"),
        __("Umnico Live Chat", "umnico-live-chat"),
        "manage_options",
        __FILE__,
        "umnico_plugin_settings_page",
        plugins_url("/assets/images/logo-circle.svg", __FILE__),
    );
}

function umnico_enqueue_settings_files()
{
    wp_enqueue_script(
        "toggle_widget",
        plugins_url("/assets/scripts/settings.js", __FILE__),
        array("jquery"),
        UMNICO_PLUGIN_VERSION,
        true
    );
    wp_localize_script(
        "toggle_widget",
        "umnico_ajax",
        array(
            "ajax_url" => admin_url("admin-ajax.php"),
            "nonce" => wp_create_nonce(UMNICO_WP_AJAX_REFERER)
        )
    );

    wp_enqueue_style("admin-fonts", plugins_url("/assets/styles/fonts.css", __FILE__));
    wp_enqueue_style("admin-style", plugins_url("/assets/styles/settings.css", __FILE__));
}

function umnico_enqueue_script()
{
    $widget_hash = get_option(UMNICO_WP_VARIABLE_WIDGET);
    $widget_disabled = get_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED);

    if (!isset($widget_hash) || empty($widget_hash) || (isset($widget_disabled) && $widget_disabled)) {
        return;
    }

    $output = "document.umnicoWidgetHash = '$widget_hash';";

    wp_enqueue_script("umnico-live-chat", UMNICO_WIDGET_LOADER_URL, array(), UMNICO_PLUGIN_VERSION, array("in_footer" => true, "strategy" => "async"));
    wp_add_inline_script("umnico-live-chat", $output, "before");
}

function umnico_ajax_handler()
{
    check_ajax_referer(UMNICO_WP_AJAX_REFERER);

    $widget_disabled = get_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED);
    if ($widget_disabled) {
        esc_html_e("Switch off", "umnico-live-chat");
        update_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED, false);
    } else {
        esc_html_e("Switch on", "umnico-live-chat");
        update_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED, true);
    }

    wp_die();
}

function umnico_load_textdomain($mofile, $domain)
{
    if (UMNICO_WP_TEXT_DOMAIN === $domain && false !== strpos($mofile, WP_LANG_DIR . "/plugins/")) {
        $locale = apply_filters("plugin_locale", determine_locale(), $domain);
        $mofile = WP_PLUGIN_DIR . "/" . dirname(plugin_basename(__FILE__)) . "/languages/" . $domain . "-" . $locale . ".mo";
    }
    return $mofile;
}

function umnico_clear_wp_options()
{
    delete_option(UMNICO_WP_VARIABLE_WIDGET);
    delete_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED);
}
