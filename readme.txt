=== WP ULike ===
Contributors: alimir
Donate link: http://alimir.ir
Author: Ali Mirzaei
Tags: wp ulike, wordpress youlike plugin, like button, rating, vote, voting, most liked posts, wordpress like page, wordpress like post, wordpress vote page, wordpress vote post, wp like page, wp like post, wp like plugin
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP ULike enables you to add Ajax Like button into your WordPress and allowing your visitors to like and dislike posts,comments AND BP activities.

== Description ==

WP ULike plugin allows to integrate a beautiful Ajax Like Button into your wordPress website to allow your visitors to like and dislike pages, posts, comments AND buddypress activities. Its very simple to use and supports many options.

= Demo =

<a target="_blank" href="http://preview.alimir.ir/wp-ulike-plugin">Click Here</a>

= Features =

*   Clean Design.
*   Ajax feature to update the data without reloading.
*   Visitors do not have to register or log in to use the Like Button.
*   Compatible with WP version 3.0 & above.
*   Added automatically (no Code required).
*   Shortcode support.
*   Comment likes support.
*   Full likes logs support.
*   BuddyPress activity support.
*   Simple user like box with avatar support.
*   Custom Like-Dislike Texts.
*   Simple custom style with color picker settings.
*   Widget to show 'Most Liked Posts' And 'Most Liked Users' avatars.
*   Simple configuration panel.
*   Support RTL & language file.
*   And so on...

= How To Use? =
Just install the plugin and activate the "automatic display" in plugin configuration panel. (WP ULike has three auto options for the post, comments and buddypress activities.)
Also you can use this function and shortcode for the post likes:
<strong>Function:</strong> 
`<?php if(function_exists('wp_ulike')) wp_ulike('get'); ?>`
<strong>Shortcode:</strong> this shortcode:
`[wp_ulike]`

= Translations =
*   English
*   Persian
*   France
*   Chinese (Thanks Changmeng Hu)
*   Chinese Tradition (Thanks Arefly)

= Plugin Author =
Website: <a href="http://about.alimir.ir" target="_blank">Ali Mirzaei</a><br />
Follow on <a href="https://www.facebook.com/alimir.ir" target="_blank">Facebook</a>
You can catch catch him on twitter as @alimirir

== Installation ==

1. Open `wp-content/plugins` Folder
2. Put: `Folder: wp-ulike`
3. Activate `WP ULike` Plugin
4. Go to `WP-Admin -> WP ULike`

== Screenshots ==

Screenshots are available in <a href="http://preview.alimir.ir/wp-ulike-plugin" target="_blank">here</a>

== Frequently Asked Questions ==

= How To Use this plugin? =
Just install the plugin and activate the "automatic display" in plugin configuration panel. (WP ULike has three auto options for the post, comments and buddypress activities.)
Also you can use this function and shortcode for the post likes:
<strong>Function:</strong> 
`<?php if(function_exists('wp_ulike')) wp_ulike('get'); ?>`
<strong>Shortcode:</strong> this shortcode:
`[wp_ulike]`

= How To Change Format Number Function? =
* You can adding your changes on `wp_ulike_format_number` function with a simple filter. for example, if you want to remove the "+" character you can use this filter:
<code> 
<?php
add_filter('wp_ulike_format_number','wp_ulike_new_format_number',10,3);
function wp_ulike_new_format_number($value, $num, $plus){
	if ($num >= 1000 && get_option('wp_ulike_format_number') == '1'):
	$value = round($num/1000, 2) . 'K';
	else:
	$value = $num;
	endif;
	return $value;
}
?>
</code>

= How To Get Posts Likes Number? =
* Use this function on WP Loop:
<code> 
<?php
if (function_exists('wp_ulike_get_post_likes')):
	echo wp_ulike_get_post_likes(get_the_ID());
endif;
?>
</code>

== Changelog ==

= 1.7 =
* Added: Buddypress likes support.
* Added: Post likes logs.
* Added: Comment likes logs.
* Added: Buddypress likes logs.
* Added: pagination for the logs pages.
* Added: FAQ document on wordpress.org
* Added: get post likes function.
* Modified: New setting menu.
* Updated: language files.

= 1.6 =
* Added: Comment likes support.
* Added: BuddyPress activity support.
* Updated: language files.

= 1.5 =
* Added: Number format option to convert numbers of Likes with string (kilobyte) format.
* Updated: Persian language.

= 1.4 =
* Added: Shortcode support.

= 1.3 =
* Added: Custom style with color picker setting. (for button and counter box)
* Added: Chinese Tradition (zh_TW) language. (Thanks to Arefly)
* Updated: Persian language.

= 1.2 =
* Added: most liked users widget.
* Added: Chinese (ZH_CN) language. (Thanks to Changmeng Hu) 

= 1.1 =
* Added: loading spinner.
* Added: new database table.
* Added: user dislike support.
* Added: Simple "user avatar box" at the bottom of every post.
* Fixes: plugin security and authentication.
* Updated: language files.

= 1.0 =
* The initial version

== Upgrade Notice ==

= 1.7 =
After plugin update: If the new database table won't fixed, try deactivating the plugin and reactivating that one at a time.

= 1.6 =
After plugin update: If the new database table won't fixed, try deactivating the plugin and reactivating that one at a time.

= 1.0 =
The initial version