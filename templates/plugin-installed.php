<?php if (!defined("ABSPATH")) exit; ?>

<div class="umnico-plugin umnico-plugin--installed umnico-plugin__wrapper">
    <img class="umnico-plugin__logo" src="<?php echo esc_html(UMNICO_WP_PLUGIN_URL); ?>assets/images/umnico-logo.svg" width="128" height="40" alt="Umnico Live Chat Logo" />

    <h2 class="umnico-plugin__title">
        <?php esc_html_e('Umnico Live Chat has been successfully installed', 'umnico-live-chat'); ?>
    </h2>
    <p class="umnico-plugin__text">
        <?php esc_html_e('Live chat widget is installed on your website. Now you can set up and test it in your Umnico account.', 'umnico-live-chat'); ?>
    </p>
    <a class="umnico-brand-button umnico-brand-button--long" href="<?php echo esc_html(UMNICO_URL); ?>/app/account/widgets/" target="_blank">
        <?php esc_html_e('Set up', 'umnico-live-chat'); ?>
    </a>

    <p class="umnico-plugin__text">
        <?php esc_html_e('You can control the chat appearance on you website (disconnect and connect) using the following button.', 'umnico-live-chat'); ?>
    </p>
    <button type="button" class="js-toggle-button umnico-brand-button umnico-brand-button--long">
        <?php $is_widget_disabled ? esc_html_e('Switch on', 'umnico-live-chat') : esc_html_e('Switch off', 'umnico-live-chat'); ?>
    </button>

    <p class="umnico-plugin__text">
        <?php esc_html_e('Any questions? We are always here to help you!', 'umnico-live-chat'); ?>
    </p>
    <div class="umnico-plugin__buttons">
        <a class="umnico-brand-button" href="<?php echo esc_html(UMNICO_URL); ?>/app/support/" target="_blank">
            <?php esc_html_e('Chat Support', 'umnico-live-chat'); ?>
        </a>
        <a class="umnico-brand-button" href="<?php echo esc_url(UMNICO_URL . umnico_route_with_slash(umnico_get_prefix_lang(), '/help/')); ?>" target="_blank">
            <?php esc_html_e('Knowledge Base', 'umnico-live-chat'); ?>
        </a>
    </div>
</div>
