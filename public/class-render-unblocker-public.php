<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://clarknikdelpowell.com/agency/people/glenn/
 * @since      1.0.0
 *
 * @package    Render_Unblocker
 * @subpackage Render_Unblocker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Render_Unblocker
 * @subpackage Render_Unblocker/public
 * @author     Glenn Welser <glenn@clarknikdelpowell.com>
 */
class Render_Unblocker_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Stops scripts from outputting standard script tag and loads them into a global array for later use.
	 *
	 * @since 1.0.0
	 *
	 * @param $tag
	 * @param $handle
	 * @param $src
	 *
	 * @return string
	 */
	public function kill_scripts( $tag, $handle, $src ) {

		if ( is_admin() ) {
			return $tag;
		}

		$excludes = apply_filters( 'no_kill_scripts', [] );
		if ( in_array( $handle, $excludes ) ) {
			return $tag;
		}

		global $scripts;
		$scripts[] = $src;

		return '';
	}

	/**
	 * Adds pre-loading attributes to stylesheet link tags and loads standard tags into global variable for later use.
	 *
	 * @since 1.0.0
	 *
	 * @param $tag
	 * @param $handle
	 * @param $href
	 * @param $media
	 *
	 * @return string
	 */
	public function preload_styles( $tag, $handle, $href, $media ) {

		if ( is_admin() || 'print' === $media ) {
			return $tag;
		}

		global $stylesheet_no_scripts;
		$stylesheet_no_scripts .= $tag;

		return '<link id="' . $handle . '-css" rel="preload" href="' . $href . '" as="style" onload="this.rel=\'stylesheet\'" media="' . $media . '">' . "\n";
	}

	/**
	 * Outputs stylesheets and scripts.
	 *
	 * @since 1.0.0
	 */
	public function optimized_scripts() {

		$wp_scripts = wp_scripts();
		$scripts    = [];
		$excludes   = apply_filters( 'no_kill_scripts', [] );
		foreach ( $wp_scripts->in_footer as $s ) {
			if ( in_array( $s, $excludes ) ) {
				continue;
			}
			$scripts[] = $wp_scripts->registered[ $s ]->src;
		}
		if ( ! $scripts ) {
			return;
		}

		$critical_css_path = apply_filters( 'critical_css_path', get_template_directory() . '/critical.css' );

		include_once 'partials/render-unblocker-public-display.php';
	}
}
