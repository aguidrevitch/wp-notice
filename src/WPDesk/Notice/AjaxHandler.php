<?php

namespace WPDesk\Notice;

use WPDesk\PluginBuilder\Plugin\HookablePluginDependant;
use WPDesk\PluginBuilder\Plugin\PluginAccess;

/**
 * Class Notice.
 *
 * @package WPDesk\Notice
 */
class AjaxHandler implements HookablePluginDependant
{

    use PluginAccess;

    const SCRIPTS_VERSION = '1';
    const POST_FIELD_NOTICE_NAME = 'notice-name';

    /**
     * @var string
     */
    private $assetsURL;

    /**
     * AjaxHandler constructor.
     *
     * @param string $assetsURL Assets URL.
     */
    public function __construct($assetsURL)
    {
        $this->assetsURL = $assetsURL;
    }

    /**
     * Hooks.
     */
    public function hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
        add_action('wp_ajax_wpdesk_notice_dismiss', [$this, 'processAjaxNoticeDismiss']);
    }

    /**
     * Enqueue admin scripts.
     */
    public function enqueueAdminScripts()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_register_script(
            'wpdesk_notice',
            trailingslashit($this->assetsURL) . 'js/' . $suffix . '.js',
            array( 'jquery' ),
            self::SCRIPTS_VERSION
        );
    }

    /**
     * Process AJAX notice dismiss.
     */
    public function processAjaxNoticeDismiss()
    {
        if (isset($_POST[self::POST_FIELD_NOTICE_NAME])) {
            $noticeName = $_POST[self::POST_FIELD_NOTICE_NAME];
            delete_option(PermanentDismissibleNotice::OPTION_NAME_PREFIX . $noticeName);
        }
        die();
    }

}

