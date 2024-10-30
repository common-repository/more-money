=== More Money ===
Contributors: manojtd
Donate link: http://www.Thulasidas.com/buy
Tags: chitika, clicksor, bidvertiser, adsense, google, ad, ads, advertising, income
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1.0

More Money showcases AdSense and its alternatives on your blog: Chitika, BidVertiser, Clicksor etc.

== Description ==

More Money provides a unified and intuitive interface to manage multiple ad providers on your blog. Currently supported are Chitika, BidVertiser, Clicksor, and, of course, AdSense. The list of ad providers will be expanded later on at your request.

**More Money has been re-released as [Easy Ads](http://wordpress.org/extend/plugins/easy-ads/ "Complete solution for managing multiple ad providers in your blog.") with more features. More Money will not be updated any further.**

AdSense dumped you? Don't be heartbroken; there are other fish in the sea. You may find happiness with [Clicksor](http://www.clicksor.com/pub/index.php?ref=105268 "Careful, don't double-date with AdSense and Clicksor, they get very jealous of each other!"), [BidVertiser](http://www.bidvertiser.com/bdv/bidvertiser/bdv_ref_publisher.dbm?Ref_Option=pub&Ref_PID=229404 "Another fine ad provider") or [Chitika](http://chitika.com/publishers.php?refid=manojt "Compatible with AdSense"). Use More Money, and you may get lucky!

= Features =

1. Tabbed and intuitive interface for multiple ad providers.
2. Rich display and alignment options.
3. Robust codebase for extending to more ad providers.
4. Control over the positioning and display of ad blocks in each post or page.
5. Simple configuration interface -- nothing more than cutting and pasting ad code, and with sensible defaults for the few options present, all with clear instructions.
5. An *Admin* (Control Panel) tab with:
 - Option to selectively activate or deactivate various ad providers (in the *Admin* Tab).
 - More information about ad positions and slots (as an image in the *Admin* Tab).
 - Option to set number of ad slots per position.
 - New commands (in the *Admin* Tab) to *Reset All Options*, *Drop All Options* or *Migrate Options* to a new plugin version.

If you want to consider only AdSense, you would want to consider  my full-fledged, feature rich plugin [Easy AdSense](http://wordpress.org/extend/plugins/easy-adsenser/ "The complete solution for all things AdSense related").

If you like More Money, you may want to check out my other plugins: [Theme Tweaker](http://wordpress.org/extend/plugins/theme-tweaker/ "To tweak the colors in your theme with no CSS/PHP editing") and [Easy LaTeX](http://wordpress.org/extend/plugins/easy-latex/ "To display mathematical equations in your blog using LaTeX"). And my plugin for plugin authors: [Easy Translator](http://wordpress.org/extend/plugins/easy-translator/  "To translate any plugin (with internationalized strings) to your language.").

Note that when you activate the plugin and leave the textareas for your ad codes empty, the plugin will show random ads of mine (mainly referral requests, publicity for my book and blog as well as some AdSense blocks). To suppress these, please generate your own code and insert them in the textareas.

= Future Plans =

*These plans apply to the reincarnated version of More Money, which is [Easy Ads](http://wordpress.org/extend/plugins/easy-ads/ "Complete solution for managing multiple ad providers in your blog.").*

This initial version 1.0 provides you with the basic functionality, but much more is planned for the future. I would like to hear from you if you have any feature requests or comments.

1. Widgets: I will release options to include sidebar widgets with optional ad customization. That is, you will be able to use the same ad code for both main text and the widgets, or have different texts, to be customized on the widgets page.
1. Ad Rotation: I will provide means to rotate ads among various providers with user-defined frequency.
1. More Providers: This plugin is designed with extensibility in mind. I will keep adding more ad providers, or even let the end-users add them.
1. Provider Specificity: This initial release treats all ad providers essentially the same way. In the next release, I will start introducing more specificity, like specialized fields for HopID, PubID, colors, etc.
1. Expertise Level: I plan to introduce expertise levels (Easy, Advanced and Expert tabs) within the tab for each ad provider.
1. Max Number of Ad blocks: Since some providers require you to limit the number of ad blocks to some policy-driven ceiling, I will expose that option to you. Also to be customized is the number of ads per slot. In this initial release, there are three slots (top, middle and bottom), each of which can take two ad blocks. In a future release, you will have much more customization options.
1. Ad Block Customization: Right now, all the ad blocks are designed to display the same ad code, for which the providers will serve different text. In a future release, I will give you a means of introducing different texts for different locations, possibly in a tabbed interface.
1. Ad Space Sharing: Although the current version shows a fraction (5% by default) of your ad space you can share with me to support this plugin, I have not implemented it yet. I will do so in a future release.
1. Internationalization: Future versions will provide MO/PO files for internationalization.

= New in 1.06 =

Final release. Please use [Easy Ads](http://wordpress.org/extend/plugins/easy-ads/ "The complete solution to manage multiple ad providers in your blog.") from now on.

== Upgrade Notice ==

= 1.06 =

Final release. Please use [Easy Ads](http://wordpress.org/extend/plugins/easy-ads/ "The complete solution to manage multiple ad providers in your blog.") from now on.

== Screenshots ==

1. More Money "Overview" tab.
2. How to set the options for one provider in More Money.

== Installation ==

1. Upload the More Money plugin (the whole more-money folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Settings -> More Money and enter your ad codes and options.

== Frequently Asked Questions ==

= I get the error "Error locating or loading the defaults! Ensure 'defaults.php' exists, or reinstall the plugin." What to do? =

Please copy *all* the files in the zip archive to your plugin directory. You need all the files in the `more-money` directory. One of these files is the missing `defaults.php`.

= Can I change the defaults? =

This post might help: [Modifying Easy Adsense Defaults](http://www.glebeterrace.com/blogging/wordpress/modifying-easy-adsense-defaults/ "An external blog post that gives step-by-step instructions on how to modify the default values"). Although this post describes the defaults for another plugin of mine, I use similar techniques for More Money as well.

= How can I control the appearance of the ad blocks using CSS? =

All `<div>`s that More Money creates have the class attribute `more-money`. Furthermore, they have class attributes like `more-money-adsense-top`, `more-money-clicksor-bottom` etc., (ie, `more-money-provider-position`). You can set the style for these classes in your theme `style.css` to control their appearance.

= Ad Space sharing? =

There will be an ad-space sharing scheme implemented starting a future version of More Money, if you would like to support its future development. It will give you an option to share a small fraction of your ad slots to show the author's ads. Although the option is visible in the current version, the sharing code is not active yet.

Unless you configure the plugin (following the instructions on its admin page) and explicitly turn off the sharing, you agree to run the developer's ads on your site(s). By using the program, the users are agreeing to this condition, and confirming that their sites abide by Google's and other ad providers' policies and terms of service.

= Why does my code disappear when I switch to a new theme? =

More Money stores the ad code and options in your database indexed by the theme name, so that if you set up the options for a particular theme, they persist even when you switch to another theme. If you ever switch back to an old theme, all your options will be reused without your worrying about it.

But this unfortunately means that you do have to set the code *once* whenever you switch to a new theme.

= Can I control how the ad blocks are formatted in each page? =

Yes! In More Money, you have more options [through **custom fields**] to control ad blocks in individual posts/pages. Add custom fields with keys like **more-money-adsense-top, more-money-adsense-middle, more-money-adsense-bottom** and with values like **left, right, center** or **no** to have control how the ad blocks show up in each post or page. The value "**no**" suppresses all the ad blocks in the post or page for that provider.

= How do I report a bug or ask a question? =

Please report any problems, and share your thoughts and comments [at the plugin forum at WordPress](http://wordpress.org/tags/more-money "Post comments/suggestions/bugs on the WordPress.org forum. [Requires login/registration]") Or send an [email to the plugin author](http://manoj.thulasidas.com/mail.shtml "Email the author").

== Change Log ==

* V1.06: Final release. Please use [Easy Ads](http://wordpress.org/extend/plugins/easy-ads/ "The complete solution to manage multiple ad providers in your blog.") from now on. [Aug 31, 2010]
* V1.05: Code optimizations and minor bug fixes. [Apr 10,2010]
* V1.04:  [Apr 2, 2010]
 1. An *Admin* (Control Panel) tab and an *About* tab.
 1. Option to selectively activate or deactivate various ad providers (in the *Admin* Tab).
 1. More information about ad positions and slots (as an image in the *Admin* Tab).
 1. New commands (in the *Admin* Tab) to *Reset All Options*, *Drop All Options* or *Migrate Options* to a new plugin version.
* V1.03: Another minor bug fix: Strip slashes from the ad blocks before filtering the post content. [Mar 24, 2010]
* V1.02: A bug fix: Another PHP4 compatiblity issue in saving options. [Mar 24, 2010]
* V1.01: Minor bug fixes (Handle objects by reference to be compatible with PHP4). [Mar 20, 2010]
* V1.00: Initial release. [Mar 20, 2010]
