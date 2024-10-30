<?php
/*
  Copyright (C) 2008 www.thulasidas.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or (at
  your option) any later version.

  This program is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  This program is supported by ad space sharing. Unless you configure
  the program (following the instructions on its admin page) and
  explicitly turn off the sharing, you agree to run its developer's ads
  on your site(s). By using the program, you are agreeing to this
  condition, and confirming that your sites abide by the Ad Providers'
  policies and terms of service. (Ad Providers include Google AdSense,
  Clicksor, Chitika, BidVertiser etc.)
*/

$plgname = basename(dirname(__FILE__)) ;
?>

<table class="form-table" >
<tr><th scope="row"><b><?php _e('More Money', 'easy-adsenser'); ?></b></th></tr>
<tr>
<td>

<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >

<li>
<?php _e('More Money gives you more options to monetize from your websites using AdSense and its competitors.', 'easy-adsenser') ; ?>
</li>
<li>
<?php _e(' Currently supported are <a href="http://www.clicksor.com/pub/index.php?ref=105268" title="Careful, don\'t double-date with AdSense and Clicksor, they get very jealous of each other!">Clicksor</a>, <a href="http://www.bidvertiser.com/bdv/bidvertiser/bdv_ref_publisher.dbm?Ref_Option=pub&amp;Ref_PID=229404" title="Another fine ad provider">BidVertiser</a> and <a href="http://chitika.com/publishers.php?refid=manojt" title="Compatible with AdSense">Chitika</a>, in addition to AdSense.', 'easy-adsenser') ; ?>
</li>

<li>
<?php _e('Please report any problems. And share your thoughts and comments.', 'easy-adsenser') ; ?>&nbsp;<a href="http://wordpress.org/tags/<?php echo $plgname ; ?>" title="<?php _e('Post it in the WordPress forum', 'easy-adsenser') ; ?>" target="_blank"><?php _e("[WordPress Forum]", 'easy-adsenser') ?> </a> <?php _e("Or", 'easy-adsenser') ?> <a href="#" title="<?php _e('Contact the plugin author through email', 'easy-adsenser') ; ?>" onclick="TagToTip('help7', WIDTH, 1000, TITLE, 'Contact Manoj',STICKY, 1, CLOSEBTN, true, FIX, [20,20])"><?php _e("[Email Author]", 'easy-adsenser') ?></a>
<span id="help7">
<iframe src="http://manoj.thulasidas.com/mail.shtml" width="1024px" height="1024px">
</iframe>
</span>
</li>


</ul>
</td>
</tr>

<tr><th scope="row"><b><?php _e('Other Plugins', 'easy-adsenser'); ?></b></th></tr>
<tr><td>

<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >

<?php if ($plgname != 'easy-adsenser') { ?>

<li>
<a href="http://www.Thulasidas.com/adsense" target="_blank" title="<?php _e('The simplest way to put AdSense to work for you!', 'easy-adsenser') ; ?>"><b>Easy AdSenser</b></a>: <?php _e('The simplest way to put AdSense to work for you!', 'easy-adsenser') ; ?> <?php _e('It puts ads like this into your <em>existing</em> posts.', 'easy-adsenser') ; ?>
</li>

<?php }
if ($plgname != 'easy-translator') { ?>

<li>
<a href="http://wordpress.org/extend/plugins/easy-translator/" target="_blank" title="<?php _e('Translate any plugin!', 'easy-adsenser') ; ?>"><b>Easy Translator</b></a>: <?php _e('To translate any plugin (with internationalized strings) to your language.', 'easy-adsenser') ; ?>
</li>

<?php }
if ($plgname != 'more-money') { ?>

<li>
<a href="http://wordpress.org/extend/plugins/more-money/" target="_blank" title="<?php _e('A powerful multi-provider plugin', 'easy-adsenser') ; ?>"><b> More Money</b></a>: <?php _e('More options to monetize from your websites using ad providers other than AdSense. AdSense dumped you? Don\'t be heartbroken; there are other fish in the sea. You may find happiness with <a href="http://www.clicksor.com/pub/index.php?ref=105268" title="Careful, don\'t double-date with AdSense and Clicksor, they get very jealous of each other!">Clicksor</a>, <a href="http://www.bidvertiser.com/bdv/bidvertiser/bdv_ref_publisher.dbm?Ref_Option=pub&amp;Ref_PID=229404" title="Another fine ad provider">BidVertiser</a> or <a href="http://chitika.com/publishers.php?refid=manojt" title="Compatible with AdSense">Chitika</a>. Use <a href="http://wordpress.org/extend/plugins/more-money/" title="A new plugin to handle AdSense and its alternatives">More Money</a>, and you may get lucky!', 'easy-adsenser') ;
echo ('<p style="text-align:center;vertical-align:middle"><!-- Clicksor.COM -->
<a href="http://signup.clicksor.com/pub/index.php?ref=105268" target="_blank">
<img src="http://myad.clicksor.net/publisher/images/pub/120x60_2.gif" border=0></a>
<!-- Clicksor.COM -->') ;
echo('<a href="https://chitika.com/publishers.php?refid=manojt" style="text-decoration: none;" title="Get Chitika | Premium"><img src="http://scripts.chitika.net/eminimalls/logos/120x90.png" border="0"alt="Get Chitika | Premium" title="Get Chitika | Premium" /></a>');
echo('<!-- Begin BidVertiser Referral code -->
<script language="JavaScript">var bdv_ref_pid=229404;var bdv_ref_type=\'i\';var bdv_ref_option=\'p\';var bdv_ref_eb=\'0\';var bdv_ref_gif_id=\'ref_120x60_black_pbl\';var bdv_ref_width=120;var bdv_ref_height=60;</script>
<script language="JavaScript" src="http://srv.bidvertiser.com/bidvertiser/referral_button.html?pid=229404"></script>
<noscript><a href="http://www.bidvertiser.com">affiliate program</a></noscript>
<!-- End BidVertiser Referral code --></p>') ;
?>
</li>

<?php }
if ($plgname != 'theme-tweaker') { ?>

<li>
<a href="http://wordpress.org/extend/plugins/theme-tweaker/" target="_blank" title="<?php _e('Tweak your color scheme', 'easy-adsenser') ; ?>"><b>Theme Tweaker</b></a>: <?php _e('To modify the color scheme of your themes with no CSS/Stylesheet editing.', 'easy-adsenser') ; ?>
</li>

<?php }
if ($plgname != 'easy-latex') { ?>

<li>
<a href="http://wordpress.org/extend/plugins/easy-latex/" target="_blank" title="<?php _e('LaTeX in your posts', 'easy-adsenser') ; ?>"><b> Easy LaTeX</b></a>: <?php _e('To translate LaTeX formulas like this [math](a+b)^2 = a^2 + b^2 + 2ab[/math] into this:', 'easy-adsenser') ; ?> <br/>&nbsp;&nbsp;&nbsp;&nbsp;<img src="http://l.wordpress.com/latex.php?latex=(a%2bb)^2%20=%20a^2%20%2b%20b^2%20%2b%202ab&amp;bg=FFFFFF&amp;s=1" style="vertical-align:-70%;" alt="(a+b)^2 = a^2 + b^2 + 2ab" />
</li>

<?php }
if ($plgname != 'adsense-now') { ?>

<li>
<a href="http://wordpress.org/extend/plugins/adsense-now/" target="_blank" title="<?php _e('The simplest possible way to AdSense enable your blog', 'easy-adsenser') ; ?>"><b> AdSense Now!</b></a>: <?php _e('My lean and mean AdSense plugin. No mess, no fuss.', 'easy-adsenser') ; ?>
</li>

<?php } ?>

</ul>
</td>
</tr>

<tr><th scope="row"><b><?php _e('Credits', 'easy-adsenser'); ?></b></th></tr>
<tr><td>
<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >
<li>
<?php printf(__('%s uses the excellent Javascript/DHTML tooltips by %s', 'easy-adsenser'), '<b>More Money</b>', '<a href="http://www.walterzorn.com" target="_blank" title="Javascript, DTML Tooltips"> Walter Zorn</a>.') ;
?>
</li>
<li>
<?php printf(__('%s uses a modified version JavaScript tabs as described in %s', 'easy-adsenser'), '<b>More Money</b>', '<a href="http://webdevel.blogspot.com/2009/03/pure-accessible-javascript-tabs.html" title="Pure Accessible JavaScript Tabs"> Web Developer Blog</a>.') ;
?>
</li>
</ul>
</td>
</tr>
<?php echo '</table>' ; ?>

