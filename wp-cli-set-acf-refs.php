<?php
/**
 * Plugin Name:     WP-CLI Set ACF References
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Sets ACF reference fields for postmeta where missing - useful after importing post meta somehow
 * Author:          Ross Wintle
 * Author URI:      https://rosswintle.uk/
 * Text Domain:     wp-cli-set-acf-refs
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Cli_Set_Acf_Refs
 */


if (!class_exists('WP_CLI')) {
	return;
}

/**
 * Assign ACF references to postmeta fields where missing
 *
 * ## OPTIONS
 *
 * [--post-type=<type>]
 * : The post type to process
 * ---
 * default: post
 * ---
 *
 * [--dry-run]
 * : If this is set, run the script, but don't commit anything to the database
 *
 */
$set_acf_reference_fields = function ($args, $assoc_args) {
	WP_CLI::log("Starting.");

	$dryRun = false;
	if (isset($assoc_args['dry-run'])) {
		WP_CLI::log("In DRY RUN mode - no action will be taken");
		$dryRun = true;
	}

	$post_type = isset($assoc_args['post-type']) ? $assoc_args['post-type'] : 'post';
	WP_CLI::log( 'Post type is: ' . $post_type );

	$posts = get_posts([
		'posts_per_page' => -1,
		'post_type' => 'mystery-worshipper',
	]);

	// We'll need to do several passes

	// First build list of meta keys and references

	$references = [];

	foreach ($posts as $post) {
		$postmeta = get_post_meta( $post->ID );

		if ($postmeta) {
			foreach ($postmeta as $key => $values) {
				if (substr($key, 0, 1) == '_' && substr($values[0], 0, 6) == 'field_') {
					$references[$key] = $values[0];
				}
			}
		}
	}

	// Now fix missing references
	foreach ($posts as $post) {
		$postmeta = get_post_meta($post->ID);

		if ($postmeta) {
			foreach ($postmeta as $key => $values) {
				$referenceKey = '_' . $key;
				if (
					substr($key, 0, 1) != '_' &&
					!isset($postmeta[ $referenceKey ]) &&
					isset($references[ $referenceKey ])) {

					WP_CLI::log('Setting ' . $referenceKey . ' to ' . $references[ $referenceKey ] . ' for post ID ' . $post->ID);
					if (!$dryRun) {
						update_post_meta( $post->ID, $referenceKey, $references[ $referenceKey ] );
					}
				}
			}
		}
	}
};

WP_CLI::add_command('set-acf-references', $set_acf_reference_fields);

