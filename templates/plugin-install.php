<?php if (!defined("ABSPATH")) exit; ?>

<div class="umnico-plugin umnico-plugin__wrapper">
  <img class="umnico-plugin__logo" src="<?php echo esc_html(UMNICO_WP_PLUGIN_URL); ?>assets/images/umnico-logo.svg" width="128"
    height="40" alt="Umnico Live Chat Logo" />

  <h2 class="umnico-plugin__title">
    <?php esc_html_e('Add Umnico Live Chat to your website', 'umnico-live-chat'); ?>
  </h2>
  <p class="umnico-plugin__text">
    <?php esc_html_e('Free live chat software - Umnico Live Chat, will be automatically added to your WordPress site.', 'umnico-live-chat'); ?>
  </p>

  <a class="umnico-brand-button umnico-brand-button--long" href="<?php echo esc_url($install_link); ?>" target="_self">
    <?php esc_html_e('Install', 'umnico-live-chat'); ?>
  </a>
</div>
