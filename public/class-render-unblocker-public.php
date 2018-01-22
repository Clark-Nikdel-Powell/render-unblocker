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
	 * Filters the rendering of stylesheet LINK tags from HEAD.
	 *
	 * @since 2.0
	 *
	 * @param $tag
	 *
	 * @return string
	 */
	public function filter_head_style_tag( $tag ) {

		if ( is_admin() ) {
			return $tag;
		}

		global $footer_enqueued_styles;
		$footer_enqueued_styles .= $tag;

		return '';
	}

	/**
	 * Output stylesheet LINK tags.
	 *
	 * @since 2.0
	 */
	public function footer_styles() {

		global $footer_enqueued_styles;
		?>
		<noscript id="deferred-styles">
			<?php echo $footer_enqueued_styles; ?>
		</noscript>
		<?php
	}

	/**
	 * Output JS to load stylesheet.
	 *
	 * @since 2.0
	 */
	public function script_load_deferred_styles() {

		?>
		<script><?php include 'js/render-unblocker-public.min.js'; ?></script>
		<?php
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

		$optimize_scripts = apply_filters( 'optimize_scripts', true );
		if ( ! $optimize_scripts || is_admin() ) {
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
	 * Outputs stylesheets and scripts.
	 *
	 * @since 1.0.0
	 */
	public function optimized_scripts() {

		$optimize_scripts = apply_filters( 'optimize_scripts', true );
		if ( ! $optimize_scripts ) {
			return;
		}

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

		$this->script_scripts();
	}

	/**
	 * Outputs required js for loading of optimized scripts
	 * @since 1.5.0
	 */
	private function script_scripts() {

		$optimize_scripts = apply_filters( 'optimize_scripts', true );
		if ( ! $optimize_scripts ) {
			return;
		}

		global $scripts;

		// @formatter:off
		?>
		<script>
			var scripts = ["<?php echo implode( '","', $scripts ); ?>"];
			!function (e, t, r) {function n() {for (; d[0] && "loaded" == d[0][f];)c = d.shift(), c[o] = !i.parentNode.insertBefore(c, i)}for (var s, a, c, d = [], i = e.scripts[0], o = "onreadystatechange", f = "readyState"; s = r.shift();)a = e.createElement(t), "async" in i ? (a.async = !1, e.head.appendChild(a)) : i[f] ? (d.push(a), a[o] = n) : e.write("<" + t + ' src="' + s + '" defer></' + t + ">"), a.src = s}(document, "script", scripts);
		</script>
		<?php
		// @formatter:on
	}
}
