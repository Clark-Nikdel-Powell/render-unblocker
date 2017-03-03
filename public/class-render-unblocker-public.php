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

		$optimize_styles = apply_filters( 'optimize_styles', true );
		if ( ! $optimize_styles || is_admin() || 'print' === $media ) {
			return $tag;
		}

		global $stylesheet_no_scripts;
		$stylesheet_no_scripts .= $tag;

		return '<link id="' . $handle . '-css" rel="preload" href="' . $href . '" as="style" onload="this.rel=\'stylesheet\'" media="' . $media . '">' . "\n";
	}

	/**
	 * Outputs critical css
	 *
	 * @since 1.1.0
	 */
	public function critical_css() {

		$optimize_styles = apply_filters( 'optimize_styles', true );
		if ( ! $optimize_styles ) {
			return;
		}

		$critical_css_path = apply_filters( 'critical_css_path', get_template_directory() . '/critical.css' );
		?>
		<style><?php include $critical_css_path; ?></style>
		<?php
	}

	/**
	 * Outputs stylesheets and scripts.
	 *
	 * @since 1.0.0
	 */
	public function optimized_scripts() {

		$this->style_scripts();

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
	 * Outputs stylesheet links and required js for loading of optimized styles
	 *
	 * @since 1.5.0
	 */
	private function style_scripts() {

		$optimize_styles = apply_filters( 'optimize_styles', true );
		if ( ! $optimize_styles ) {
			return;
		}

		global $stylesheet_no_scripts;

		// @formatter:off
		?>
		<script>!function(e){"use strict";var t=function(t,n,r){function o(e){return i.body?e():void setTimeout(function(){o(e)})}function a(){d.addEventListener&&d.removeEventListener("load",a),d.media=r||"all"}var l,i=e.document,d=i.createElement("link");if(n)l=n;else{var s=(i.body||i.getElementsByTagName("head")[0]).childNodes;l=s[s.length-1]}var u=i.styleSheets;d.rel="stylesheet",d.href=t,d.media="only x",o(function(){l.parentNode.insertBefore(d,n?l:l.nextSibling)});var f=function(e){for(var t=d.href,n=u.length;n--;)if(u[n].href===t)return e();setTimeout(function(){f(e)})};return d.addEventListener&&d.addEventListener("load",a),d.onloadcssdefined=f,f(a),d};"undefined"!=typeof exports?exports.loadCSS=t:e.loadCSS=t}("undefined"!=typeof global?global:this),function(e){if(e.loadCSS){var t=loadCSS.relpreload={};if(t.support=function(){try{return e.document.createElement("link").relList.supports("preload")}catch(t){return!1}},t.poly=function(){for(var t=e.document.getElementsByTagName("link"),n=0;n<t.length;n++){var r=t[n];"preload"===r.rel&&"style"===r.getAttribute("as")&&(e.loadCSS(r.href,r),r.rel=null)}},!t.support()){t.poly();var n=e.setInterval(t.poly,300);e.addEventListener&&e.addEventListener("load",function(){e.clearInterval(n)}),e.attachEvent&&e.attachEvent("onload",function(){e.clearInterval(n)})}}}(this);</script>
		<noscript><?php echo apply_filters( 'noscript_stylesheet_links', $stylesheet_no_scripts ) ?></noscript>
		<?php
		// @formatter:on
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
