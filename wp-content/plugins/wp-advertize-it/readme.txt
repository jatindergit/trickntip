=== Plugin Name ===
Contributors: benohead, alexander.herdt, amazingweb-gmbh
Donate link: http://amazingweb.de/donation/
Tags: ad, ad banner, ad block, ads, ads display, ads in widgets, ads rotate, ads on website, ads plugin, adsense, adsense plugin, advertisement, advertisements, advertiser, advertising, Goggle AdSense, google ads, google adsense, insert ads, insert ads automatically, insert Google ads, publisher, widget
Requires at least: 4.2.3
Tested up to: 4.6.1
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily insert as many ads as you want anywhere on your website: in posts, pages and in the sidebar. 

== Description == 

This plugin provides allows you to easily insert ads anywhere on your website. You can define ad blocks containing ads from Google Adsense, Amazon, Commissure Junction... The plugin also support you by selling your Ad Blocks directly to your visitors.
NEW in v1.2: Priority based limit of overall Ads shown on the site!
NEW in v1.0: Time rotation for Ad Blocks and AngularJS Admin UI! 

Enabling advertising on your website is as easy as:

* Defining a few ad blocks copy&pasting code from Google, Amazon, CJ...
* Selecting for each of the predefined positions which ad block should be shown.
* Setting options where ads should be suppressed.

Sell your placements directly from your site:

* define "Self promotion" HTML displayed in rotation with other Ad Blocks
* after a block is sold, put the Ad code to "Sold Ad" field. Other Ads for the block will be disabled automatically

The following locations are currently supported:

* Home page below title	
* Posts below title	
* After first post paragraph	
* Middle of post	
* Before last post paragraph	
* Before last post sentence --> disabled because it created some stability problems.
* Posts below content	
* Posts below comments	
* Pages below title	
* After first page paragraph	
* Middle of page	
* Before last page paragraph	
* Before last page sentence --> disabled because it created some stability problems.
* Pages below content	
* Pages below comments	
* Below footer	

Note that sentences might not be identified correctly if there are dots within sentences e.g. "it is a country with 80.6 million inhabitants." - this would be identified as two sentences instead of one. Instead of this placement you may want to use the "before last post/page paragraph" placement and make sure the last sentence is on its own paragraph.

Update: disabled last sentence placements because it created some stability problems.

A widget is also available to display in a sidebar.

And the following options are available:

* Suppress ads on posts
* Suppress ads on pages
* Suppress ads on attachment pages
* Suppress ads on category pages
* Suppress ads on tag pages
* Suppress ads on home page
* Suppress ads on front page
* Suppress ads on archive pages
* Suppress ads for logged in users
* Suppress ads for specific posts/pages by ID
* Suppress ads for posts/pages in a specific category
* Suppress ads for posts/pages with a specific tag
* Suppress ads for posts/pages by a specific author
* Suppress ads for posts/pages with a specific post format
* Suppress ads for posts/pages of a specific post type
* Suppress ads which URL contains one of the specified strings
* Suppress ads in the post for small posts (minimum number of characters, words, paragraphs)

If you need more fine grain control on where and whether ads are displayed or not, this plugin also defines supports:

* Inserting an ad block calling a PHP function
* Inserting an ad block using a short code
* Inserting an ad block in the visual editor using a button
* Adding a comment in the text editor to:
    * suppress all ads when displaying this post (except in a list of posts)
    * suppress the ad below the post or page title
    * suppress the ad after the first paragraph
    * suppress the ad in the middle of the post or page
    * suppress the ad before the last paragraph
    * suppress the ad below the post or page content
    * suppress the ad below the comments
    * suppress the ad widget
    * suppress the footer

You can also create your own sidebar ads by using the new ad image widget:

* Add the widget to the sidebar.
* Upload an image, select an image from the media library or just type in a URL pointing to an image
* Specify a description (shown when the image doesn't load and as tooltip on mouseover)
* Specify a link URL which will be opened when clicked
* Decide whether to maximize the width of the image and whether to open the link in  a new window.

Translations:

* German
* French

== Installation ==

1. Upload the folder `wp-advertize-it` to the `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Define some ad blocks in the settings and place them using the settings UI, the php function or the short code.

== Frequently Asked Questions ==

= How can I contact you with a complaint, a question or a suggestion? =
Send an email to henri.benoit@gmail.com

== Screenshots ==

1. Define multiple ad blocks, with options to sell the block incl. self promotion html.

2. Define which ad block should be displayed where.

3. Set options about when/where ads should not be displayed.

4. Widget to drag and drop

5. Widget configuration

== Changelog ==

= 1.2.1 =

* Feature: Added "Max. number of displayed ads" to limit the overall displayed number of Ads (based on Placements' priority)
* Feature: The sorting of Ads to be displayed if limit is activ is done based on priority of Placements
* Feature: Added Settings per Placement to override the common setting with Placement specific.   
* Feature: Added "Alignment" and "Margin" CSS Ad block settings
* Feature: Translation of Settings added 
* Corrected: displaying of the "Between Posts" Placement (now not displayed in non-main loop, e.g. in Recent Posts widget)
* Corrected: split() replaced by explode() to support PHP7 
* Upgraded libs: AngularJS 1.5.8, UI Bootstrap 2.2.0, Bootstrap 3.3.7

= 1.1.2 =

* fixed some more errors in suppress settings

= 1.1.1 =

* fixed errors in suppress settings

= 1.1 =

* fixed update issues, we apologize for the trouble caused

= 1.0.6 =

* fixed a few more compatibility issues with PHP 5.3

= 1.0.5 =

* fixed one more compatibility issue with PHP 5.3

= 1.0.4 =

* fixed more compatibility issues with PHP 5.3

= 1.0.3 =

* bootstrap css only loaded in own settings page

= 1.0.2 =

* fixed compatibility issue with PHP 5.3 in db upgrade

= 1.0.1 =

* fixed bold labels issue in settings
* fixed white background issue in settings

= 1.0 =

* UI rewritten with AngularJS (check the responsiveness, it's cool!)
* Added "Sold" Ad for blocks
* Added "Self promotion" possibility for blocks
* More Ads for one block with time rotation (rotates every X sec)
* Multi-language enabled, English, German and French translations added

= 0.9.7 =

* paragraphs in blockquote, pre or code tags are now ignored.

= 0.9.6 =

* before upgrading ad blocks additionally check whether it's already an array.

= 0.9.5 =

* Disabled last sentence placements because it created some stability problems.
* Fixed loading of unassigned placement in settings page.

= 0.9.4 =

* Ad blocks can be renamed in the text field below the editor. Renaming occurs only when the settings are saved.
* Added placement option for before last sentence. Sentences might not be identified correctly if there are dots within sentences e.g. "it is a country with 80.6 million inhabitants." - this would be identified as two sentences instead of one. Instead of this placement you may want to use the "before last paragraph" placement and make sure the last sentence is on its own paragraph.

= 0.9.3 =

* Fixed error displaying wrong blocks in placement settings

= 0.9.2 =

* Fixed error when add a new block
* Clearing cache when settings are updated
* Added preview button for ad blocks

= 0.9.1 =

* Button (icon) in visual editor can be disabled in the plugin settings

= 0.9 =

* New ad placement shown above everything in the page (as the first element in the <body> tag).
* Specific ads can be suppressed on a post adding a comment in the post contents

= 0.8.2 =

* Setting ads between posts to none now works

= 0.8.1 =

* Removed output to error log.

= 0.8 =

* Support ads between posts (Show X ads, each after Y posts).

= 0.7.5 =

* Disabling of ads with HTML comments or post ID does not affect the list of posts (home page) anymore.

= 0.7.4 =

* Fixed more php warnings and notices

= 0.7.3 =

* Fixed "Call to undefined function get_the_permalink()" error in WordPress versions less than 3.9

= 0.7.2 =

* Fixed more php warnings and notices
* Fixed category filter

= 0.7.1 =

* Fixed php warnings and notices

= 0.7 =

* Moved from EditArea to ACE editor

= 0.6.1 =

* Fixed incompatibility with WP Site Mapping

= 0.6 =

* Added new widget to where you can upload an image (or choose an existing image in your media library) and specify a link URL.

= 0.5.1 =

* Bug fix

= 0.5 =

* Suppress ads on error page
* Suppress ads on author page
* Suppress ads for specific languages (this option is only available with the plugin qTranslate or mqTranslate)
* Suppress ads on WPtouch mobile site
* Suppress ads for specific referrers
* Suppress ads for specific IP addresses

= 0.4.1 =

* Bug fix

= 0.4 =

* Suppress ads by post format and post type
* Suppress based on substrings in URL
* Suppress for small posts (with a minimum number characters, words and paragraphs)

= 0.3 =

* Suppress ads by category, tag and author

= 0.2 =

* Suppress ads by post/page ID

= 0.1 =

* First version.

== Upgrade Notice ==

n.a.
