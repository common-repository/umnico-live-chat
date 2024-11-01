<?php

if (!defined("ABSPATH")) exit;

umnico_load_env();

define("UMNICO_PLUGIN_VERSION", "1.0.0");
define("UMNICO_URL", getenv("UMNICO_URL") ?: "https://umnico.com");
define("UMNICO_WIDGET_LOADER_URL", getenv("UMNICO_WIDGET_LOADER_URL") ?: UMNICO_URL . "/assets/widget-loader.js");
define("UMNICO_WP_AJAX_REFERER", "umnico_secret_key");
define("UMNICO_WP_VARIABLE_WIDGET", "umnico_widget_token");
define("UMNICO_WP_VARIABLE_WIDGET_DISABLED", "umnico_widget_disabled");
define("UMNICO_WP_PLUGIN_URL", plugin_dir_url(__FILE__));
define("UMNICO_WP_TEXT_DOMAIN", "umnico-live-chat");

function umnico_load_env()
{
    $file = sprintf('%s/%s', __DIR__, '.env');
    if (!file_exists($file)) {
        return;
    }

    if (!is_file($file) || !is_readable($file)) {
        throw new \RuntimeException(sprintf('%s file is not readable', '.env'));
    }

    umnico_set_env($file);
}

function umnico_set_env($file)
{
    $envs = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $envs[trim($name)] = trim($value);
    }

    foreach ($envs as $envName => $envValue) {
        putenv(sprintf('%s=%s', $envName, $envValue));
    }
}
