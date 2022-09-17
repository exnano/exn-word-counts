<?php

/**
 * EXN Word Counts.
 *
 * @author  Exnano Creative
 * @license MIT
 *
 * @see    https://github.com/exnano/exn-word-counts
 */

namespace ExnanoCreative\ExnWordCounts;

\defined('ABSPATH') || exit;

final class Plugin
{
    private $title;
    private $description;
    private $menu_parent;
    private $plugin_page;
    private $nonce_key;
    private $hook;
    private $path;
    private $screen;
    private $plugin_url;
    private $post_type;

    /**
     * constructor.
     */
    public function __construct()
    {
        // $plugin_data = get_plugin_data(EXNANO_WORDCOUNTS_FILE);

        $this->post_type = 'post';

        // $this->title = $plugin_data['Name'];
        // $this->description = $plugin_data['Description'];
        $this->plugin_page = 'exn-word-counts';

        $this->hook = plugin_basename(EXNANO_WORDCOUNTS_FILE);
        $this->path = realpath(plugin_dir_path(EXNANO_WORDCOUNTS_FILE));
        $this->plugin_url = plugin_dir_url(EXNANO_WORDCOUNTS_FILE);
        // $this->screen = 'settings_page_exn-wpmu-cf-dns-manager';
    }

    private function init_actions()
    {
        // unofficial constant: possible to disable nag notices
        !\defined('DISABLE_NAG_NOTICES') && \define('DISABLE_NAG_NOTICES', true);

        $post_type = $this->post_type;
        $prefix    = 'exn_';

        // add_action(
        //     'admin_enqueue_scripts',
        //     function ($hook) {
        //         $plugin_url = $this->plugin_url;
        //         $version = \defined('EXNANO_WORDCOUNTS_VERSION') ? EXNANO_WORDCOUNTS_VERSION : date('ymdh');

        //         if ($hook === $this->screen) {
        //             wp_enqueue_style($this->plugin_page . '-core', $plugin_url . 'includes/admin/exnano.css', null, $version . 'x' . date('md'));
        //         }
        //     }
        // );

        add_filter('manage_' . $post_type . '_posts_columns', function ($columns) use ($prefix) {
            unset($columns['date']);

            $columns[$prefix . 'word_counts'] = __('Word Counts', 'exn-word-counts');
            $columns['date']                  = __('Date');

            return $columns;
        });

        add_action('manage_' . $post_type . '_posts_custom_column', function ($column, $post_id) use ($prefix) {
            switch ($column) {
                case $prefix . 'word_counts':
                    echo number_format_i18n($this->count_the_words($post_id));
                    break;

                default:
            }
        }, 10, 2);

        // add_filter('manage_edit-' . $post_type . '_sortable_columns', [$this, 'filter_sortable_columns']);
    }

    private function count_the_words($post_id)
    {
        $post = get_post($post_id);

        $post_content = $post->post_content;
        $post_content = wp_strip_all_tags($post_content);
        $post_content = preg_replace('/\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $post_content);
        $post_content = str_replace(["\r\n"], '', $post_content);
        $post_content = trim($post_content);

        $word_counts = str_word_count($post_content);

        return $word_counts;
    }

    private function remove_transient()
    {
        // delete_site_transient('exncf/fetchdata');
        // delete_site_transient('exncf/fetchdomain');
        // delete_site_transient('exncf/dnsrecord');
    }

    /**
     * deactivate_cleanup.
     */
    private function deactivate_cleanup($is_uninstall = false)
    {
        // delete_site_option('exn_cf_api_token');
        // delete_site_option('exn_cf_domain_id');

        $this->remove_transient();
    }

    /**
     * plugin uninstall.
     */
    public static function uninstall()
    {
        (new self())->deactivate_cleanup(true);
    }

    /**
     * plugin deactivate.
     */
    public function deactivate()
    {
        $this->deactivate_cleanup();
    }

    /**
     * plugin activate.
     */
    public function activate()
    {
    }

    private function init_hooks()
    {
        register_activation_hook($this->hook, [$this, 'activate']);
        register_deactivation_hook($this->hook, [$this, 'deactivate']);
        register_uninstall_hook($this->hook, [__CLASS__, 'uninstall']);
    }

    /**
     * initialize.
     */
    public function init()
    {
        $this->init_hooks();
        $this->init_actions();
    }
}
