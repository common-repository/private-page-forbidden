<?php
/*
Plugin Name: Private Page Forbidden
Plugin URI: http://wordpress.org/extend/plugins/private-page-forbidden/
Description: Instead of serving a 404 Not Found error with the <code>404.php</code> template, send a 403 Forbidden error and set <code>$wp_query->is_403</code> and load <code>403.php</code> if it exists. <em>Plugin developed at <a href="http://www.shepherd-interactive.com/" title="Shepherd Interactive specializes in web design and development in Portland, Oregon">Shepherd Interactive</a>.</em>
Version: 0.2
Author: Weston Ruter
Author URI: http://weston.ruter.net/
Copyright: 2009, Weston Ruter, Shepherd Interactive <http://shepherd-interactive.com/>. GPL license.

GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Detects if the page is a 403 (a private page)
 */
function private_page_forbidden(){
	global $wp_query, $post;
	if(is_404() && !empty($wp_query->queried_object) && $wp_query->queried_object->post_status == 'private'){
		$wp_query->is_403 = true;
		//$wp_query->is_404 = false;
		status_header(403);
		
		$redirect = apply_filters('forbidden_redirect', '');
		if($redirect){
			wp_redirect($redirect);
			exit;
		}
		
		add_filter('404_template', 'private_page_forbidden_get_template');
		add_filter('wp_title', 'private_page_forbidden_filter_wp_title', 10, 3);
		add_filter('the_content', 'private_page_forbidden_filter_the_content');
	}
}
add_action('template_redirect', 'private_page_forbidden');

/**
 * Returns the 403.php instead of 404.php
 */
function private_page_forbidden_get_template($file){
	$template403 = locate_template(array("403.php"));
	if($template403)
		return $template403;
	return $file;
}

/**
 * Return the Forbidden page title 
 */
//function private_page_forbidden_filter_the_title($title){
//	global $wp_query;
//	#if($wp_query->in_the_loop)
//	#	return __('Forbidden');
//	#else
//		return $title;
//}

/**
 * Return the Forbidden page title 
 */
function private_page_forbidden_filter_the_content(){
	return __('You are not allowed to come to this page the way you did.');
}

/**
 * Filter for wp_title, puts on Forbidden instead of Page Not Found
 */
function private_page_forbidden_filter_wp_title($title, $sep = ' &raquo; ', $seplocation = ''){
	if($seplocation == 'right')
		return preg_replace('/.+?(?=' . preg_quote($sep) . ')/', __('Forbidden'), $title);
	else
		return preg_replace('/(?<=' . preg_quote($sep) . ').+?/', __('Forbidden'), $title);
}
