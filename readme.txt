=== Private Page Forbidden ===
Contributors: westonruter
Tags: 403, access control
Tested up to: 2.8
Requires at least: 2.7
Stable tag: trunk

Instead of serving a 404 Not Found error with the 404.php template,
send a 403 Forbidden error and set $wp_query->is_403 and load
403.php if it exists.

== Description ==

<em>This plugin is developed at
<a href="http://www.shepherd-interactive.com/" title="Shepherd Interactive specializes in web design and development in Portland, Oregon">Shepherd Interactive</a>
for the benefit of the community. <b>No support is available. Please post any questions to the <a href="http://wordpress.org/tags/private-page-forbidden?forum_id=10">support forum</a>.</b></em>

Instead of serving a 404 Not Found error with the <code>404.php</code> template,
send a 403 Forbidden error and set <code>$wp_query->is_403</code> and load
<code>403.php</code> if it exists.

Provides a filter <code>forbidden_redirect</code> which if results in a non-empty
filtered value will result in the user being redirected if attempting to visit a
forbidden page; the default value is <code>""</code> (no redirect).

Useful with a filter which selectively prevents a private post from being forbidden,
so that the page will not show up in the navigation and won't be included in
XML Sitemaps, for example.

<pre>function my_filter_private_posts($posts){
	if(is_singular() && $posts[0]->post_status == 'private'
	   && #Now optionally allow/disallow based on user session:
	   in_array($_SERVER['REQUEST_URI'], (array)@$_SESSION['allowed_private_uris']))
	){
		header('Cache-Control: private'); #Prevent proxies from caching this private page
		$posts[0]->post_status = 'publish';
	}
	return $posts;
}
add_filter('posts_results', 'my_filter_private_posts');</pre>

== Changelog ==

= 2009-09-28: 0.2 =
* Initial release