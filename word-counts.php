<?php

/**
 * EXN Word Counts.
 *
 * @author  Exnano Creative
 * @license MIT
 *
 * @see    https://github.com/exnano/exn-word-counts
 */

/**
 * @wordpress-plugin
 * Plugin Name:         EXN Word Counts
 * Plugin URI:          https://github.com/exnano/exn-word-counts/
 * Description:         Display each post word counts in columns
 * GitHub Plugin URI:   https://github.com/exnano/exn-word-counts
 * Version:             0.0.1
 * Author:              Exnano Creative
 * Author URI:          https://github.com/exnano/exn-word-counts/
 * License:             MIT
 * License URI:         https://raw.githubusercontent.com/exnano/exn-word-counts/master/LICENSE
 * Text Domain:         exn-word-counts
 * Domain Path:         /languages
 * Requires at least:   5.9
 * Requires PHP:        7.4
 */

namespace ExnanoCreative\ExnWordCounts;

\defined('ABSPATH') && !\defined('EXNANO_WORDCOUNTS_FILE') || exit;

if (!function_exists('get_plugin_data')) {
	require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

$plugin_data = get_plugin_data(__FILE__);

\define('EXNANO_WORDCOUNTS_FILE', __FILE__);
\define('EXNANO_WORDCOUNTS_VERSION', $plugin_data['Version']);

require __DIR__ . '/includes/load.php';

add_action(
	'plugins_loaded',
	function () {
		(new Plugin())->init();
	},
	PHP_INT_MAX
);
