<?php
/*
Plugin Name: ACF Rule: Page Parent is Set
Plugin URI: https://github.com/ionata/acf-rule-page-parent-is-set
Description: Page Parent is Set rule addon for Advanced Custom Fields 5+.
Version: 1.0.0
Author: Evo Stamatov @ IONATA
Author URI: https://github.com/avioli
License: MIT
License URI: https://github.com/ionata/acf-rule-page-parent-is-set/LICENSE
*/

class page_parent_is_set_rule {

	public static function instance () {
		return new page_parent_is_set_rule();
	}

	/**
	 * __construct
	 *
	 * initialize filters, action, variables and includes
	 *
	 * @type   function
	 * @date   2016-08-22
	 *
	 * @param  n/a
	 * @return n/a
	 */

	function __construct() {

		// rule filters
		add_filter('acf/location/rule_types', array($this, 'rule_types'), 10, 1);
		add_filter('acf/location/rule_values/page_parent_is_set', array($this, 'rule_values'), 10, 1);
		add_filter('acf/location/rule_match/page_parent_is_set', array($this, 'rule_match'), 10, 3);

	}


	/**
	 * acf_location_rules_types
	 *
	 * this function will add "Options Page" to the ACF location rules
	 *
	 * @type   function
	 * @date   2016-08-22
	 *
	 * @param  {array} $choices
	 * @return {array} $choices
	 */

	function rule_types( $choices ) {

		$choices[ __("Page",'acf') ]['page_parent_is_set'] = __("Page Parent is Set",'acf');

		return $choices;

	}


	/**
	 * acf_location_rules_values_online_exhibition_page
	 *
	 * this function will populate the available pages in the ACF location rules
	 *
	 * @type   function
	 * @date   2016-08-22
	 *
	 * @param  {array} $choices
	 * @return {array} $choices
	 */

	function rule_values( $choices ) {

		$choices[ 'yes' ] = __('Yes');

		// return
		return $choices;
	}


	/**
	 * rule_match
	 *
	 * description
	 *
	 * @type   function
	 * @date   2016-08-22
	 *
	 * @param  {int} $post_id
	 * @return {int} $post_id
	 */

	function rule_match( $match, $rule, $options ) {

		// vars
		// - allow acf_form to exclude the post_id param and still work as expected
		$post_parent = $options['page_parent'];


		// find post parent
		if( !$post_parent ) {

			// bail early if not a post
			if( !$options['post_id'] ) return false;


			// get post
			$post = get_post( $options['post_id'] );


			// update var
			$post_parent = $post->post_parent;

		}


		// compare
		if( $rule['operator'] == '==' ) {

			$match = ( $post_parent != 0 );

		} elseif( $rule['operator'] == '!=' ) {

			$match = ( $post_parent == 0 );

		}


		// return
		return $match;

	}
}

add_action( 'acf/init', 'page_parent_is_set_rule::instance' );
