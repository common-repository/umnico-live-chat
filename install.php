<?php

if (!defined("ABSPATH")) exit;

function umnico_plugin_settings_page()
{
    umnico_update_widget_token();

    $widget_hash = get_option(UMNICO_WP_VARIABLE_WIDGET);
    $is_widget_installed = isset($widget_hash) && !empty($widget_hash);

    $widget_payload = $is_widget_installed ? file_get_contents(UMNICO_URL . "/api/widgets-noauth/" . $widget_hash) : false;
    $is_widget_hash_active = boolval($widget_payload);
    if ($is_widget_installed && !$is_widget_hash_active) {
        delete_option(UMNICO_WP_VARIABLE_WIDGET);
    }

    $install_link = umnico_generate_install_link();

    if ($is_widget_installed && $is_widget_hash_active) {
        $widget_disabled = get_option(UMNICO_WP_VARIABLE_WIDGET_DISABLED);
        $is_widget_disabled = isset($widget_disabled) && $widget_disabled;

        include_once(plugin_dir_path(__FILE__) . "templates/plugin-installed.php");
    } else {
        include_once(plugin_dir_path(__FILE__) . "templates/plugin-install.php");
    }
}

function umnico_update_widget_token()
{
    if (current_user_can("administrator")) {
        $nonce = sanitize_text_field(wp_unslash($_GET["nonce"]));
        $has_wpnonce = isset($nonce) && wp_verify_nonce($nonce);
        if (!$has_wpnonce) {
            return;
        }

        $has_widget_token = isset($_GET["widget_token"]) && !empty($_GET["widget_token"]);
        if (!$has_widget_token) {
            return;
        }

        update_option(UMNICO_WP_VARIABLE_WIDGET, sanitize_text_field($_GET["widget_token"]));
    }
}

function umnico_generate_install_link()
{
    $http_host = '';
    $request_uri = '';

    if (isset($_SERVER["HTTP_HOST"])) {
        $http_host = sanitize_text_field(wp_unslash($_SERVER["HTTP_HOST"]));
    }

    if (isset($_SERVER["REQUEST_URI"])) {
        $request_uri = sanitize_text_field(wp_unslash($_SERVER["REQUEST_URI"]));
    }

    $protocol = "http" . (($_SERVER["SERVER_PORT"] == 443) ? "s://" : "://");

    $callback_url = strtolower(urlencode($protocol . $http_host . $request_uri));

    $site_name = get_bloginfo("name");

    $site_domain = get_site_url();
    $site_domain = str_replace("http://", "", $site_domain);
    $site_domain = str_replace("https://", "", $site_domain);
    $site_domain = preg_replace("(:[0-9]{1,6})", "", $site_domain);

    $user_email = wp_get_current_user()->user_email;
    $user_name = wp_get_current_user()->display_name;

    $query_params = [
        "umnico_plugin_source" => "wordpress",
        "callback_url" => $callback_url,
        "site_name" => $site_name,
        "site_domain" => $site_domain,
        "user_email" => $user_email,
        "user_name" => $user_name,
        "utm_source" => "wordpress.org",
        "utm_medium" => "referral"
    ];

    $install_link = esc_url(UMNICO_URL . umnico_route_with_slash(umnico_get_prefix_lang(), "/register/"));
    $install_link = add_query_arg($query_params, $install_link);
    $install_link = wp_nonce_url($install_link, -1, "nonce");

    return $install_link;
}

function umnico_get_prefix_lang()
{
    $locale = str_replace("_", "-", strtolower(get_user_locale()));
    $locale = preg_replace("/[-_][a-z]+$/i", "", $locale);

    $umnico_prefix_locales = ["ru", "de", "es", "pt"];
    if (in_array($locale, $umnico_prefix_locales)) {
        return $locale;
    }

    return "";
}

function umnico_route_with_slash($prefix, $route)
{
    if ($prefix && !empty($prefix)) {
        return "/" . $prefix . $route;
    }

    return $route;
}
