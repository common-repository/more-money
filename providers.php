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

if (class_exists("provider")) {
  echo "\nError from <b>More Money</b> plugin. Please deactivate it to continue.\n" ;
  echo "\n<pre>Problems defining the class 'provider'. Already defined?!</pre>\n" ;
  exit ;
}
else
{
  class provider {
    var $isActive, $isAdmin, $name, $desc, $referral, $maxAds = 3 ;
    var $submitMessage, $errorMessage ;
    var $options = array() ; // this var is an array of objects of class option
    var $submitButtons = array() ; // this var is again an array of objects of class option
    var $optionName ; // DB key for the options
    var $adBlocks = array() ; // ad blocks that will be injected by the content filter
    var $positions = array('top', 'middle', 'bottom') ;
    var $killOptions = array('page', 'home', 'attachment', 'front_page',
                 'category', 'tag', 'archive', 'feed') ;

    // function provider($name, $desc, $referral) {// constructor
    function provider($name, $defaults) {// constructor
      $this->isAdmin = false ;
      $this->name = $name ;
      if (is_array($defaults)) {
        $this->desc = $defaults['desc'] ;
        $this->referral = $defaults['referral'] ;
      }
      $themeName = get_settings('stylesheet') ;
      $this->optionName = 'more_money' . '_' . $themeName . '_' . $this->name ;
      $this->options = get_option($this->optionName) ;
      if (empty($this->options)) {
        $this->defineOptions() ;
        if (!empty($this->options)) update_option($this->optionName, $this->options) ;
      }
      $this->isActive = true ;
    }
    // ------------ Admin Page -----------------
    function render() {// Admin page rendering
      $this->defineSubmitButtons() ;
      $this->handleSubmits() ;
      $this->isActive = $this->options['active']->value ;

      $name = $this->name ;

      global $mAd ;
      echo '<div class="tab" id="tab', $mAd->tabID++, '">', "\n" ;

      echo $this->submitMessage ;
      echo $this->errorMessage ;

      $this->renderForm() ;

      echo "</div><!-- End: $name --> \n" ;
    }
    function renderForm($unique='') {
      $name = $this->name ;
      echo '<form method="post" name="form_', $name,
        '" action="', $_SERVER["REQUEST_URI"], '">', "\n" ;

      if (!empty($this->options)) {
        foreach ($this->options as $k => $o) {
          if (!empty($o)) $o->render() ;
        }
      }
      echo "<br /><hr />\n", '<div class="submit">', "\n" ;
      foreach ($this->submitButtons as $k => $o) {
        $o->render() ;
      }
      echo "</div><!-- End: submit --> \n" ;
      echo "</form>\n" ;
    }
    function defineOptions() { // Add all options
      unset($this->options) ;

      $option = &$this->addOption('message', 'intro') ;
      $properties = array('desc' => "Options for " . $this->name,
         'before' => '<br /><table><tr><th colspan="3"><h3>',
          'after' => '</h3></th></tr><tr align="left" valign="middle"><td width="20%">') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'active') ;
      $properties = array('desc' => '&nbsp;' . 'Activate ' . $this->name . '?',
          'title' => "Check to activate " . $this->name,
          'value' => true,
         'before' => '',
          'after' => '<br />') ;
      $option->set($properties) ;

      $option = &$this->addOption('message', 'referral') ;
      $referral = '' ;
      if (!empty($this->referral)) $referral = htmlspecialchars_decode($this->referral) ;
      $properties = array('desc' => $referral,
         'before' => '</td><td width="20%">&nbsp;' ,
          'after' => '</td>') ;
      $option->set($properties) ;

      $option = &$this->addOption('message', 'info') ;
      $desc = '' ;
      if (!empty($this->desc)) $desc = htmlspecialchars_decode($this->desc) ;
      $properties = array('desc' => $desc,
         'before' => '<td >',
          'after' => '</td></tr></table><hr />');
      $option->set($properties) ;

      $option = &$this->addOption('textarea', 'text') ;
      $properties = array('desc' => '<b>Enter your ' . $this->name . ' code here: </b>',
          'title' => 'Logon and generate advert code from ' . $this->name .
                    " and paste it in its entirity here. (When left empty and active, " .
                    "author's ads may be displayed.)",
          'value' => '' , // htmlspecialchars_decode($this->default),
         'before' => '<table><tr align="center" valign="middle"><td width="60%"><br />',
          'after' => '</td>');
      $option->set($properties) ;

      $option = &$this->addOption('message', 'alignment') ;
      $properties = array(
        'desc' => "<center>\n<b>Ad Alignment. Where to show ad blocks?</b></center>",
        'before' => '<td><table><tr><th colspan="5">',
        'after' => "</th></tr>\n" . '<tr align="center" valign="middle">' .
        '<td>&nbsp;</td><td>&nbsp;Align Left&nbsp;</td><td>&nbsp;Center&nbsp;</td>' .
        '<td>&nbsp;Align Right&nbsp;</td><td>&nbsp;Suppress&nbsp;</td></tr>');
      $option->set($properties) ;

      $radio = &$this->addOption('radio', 'show_top') ;
      $properties = array('desc' => 'Top',
          'title' => 'Where to show the top ad block?',
          'value' => "right",
         'before' => '<tr align="center" valign="middle"><td>Top</td>',
          'after' => '</tr>') ;
      $radio->set($properties) ;

      $choice = &$radio->addChoice('left') ;
      $properties = array('value' =>"left",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('center') ;
      $properties = array('value' =>"center",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('right') ;
      $properties = array('value' =>"right",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('no') ;
      $properties = array('value' =>"no",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $radio = &$this->addOption('radio', 'show_middle') ;
      $properties = array('desc' => 'Middle',
          'title' => 'Where to show the mid-text ad block?',
          'value' => "left",
         'before' => '<tr align="center" valign="middle"><td>Middle</td>',
          'after' => '</tr>') ;
      $radio->set($properties) ;

      $choice = &$radio->addChoice('left') ;
      $properties = array('value' =>"left",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('center') ;
      $properties = array('value' =>"center",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('right') ;
      $properties = array('value' =>"right",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('no') ;
      $properties = array('value' =>"no",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;


      $radio = &$this->addOption('radio', 'show_bottom') ;
      $properties = array('desc' => 'Bottom',
          'title' => 'Where to show the bottom ad block?',
          'value' => "center",
          'after' => '<br />',
         'before' => '<tr align="center" valign="middle"><td>Bottom</td>',
          'after' => '</tr></table>') ;
      $radio->set($properties) ;

      $choice = &$radio->addChoice('left') ;
      $properties = array('value' =>"left",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('center') ;
      $properties = array('value' =>"center",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('right') ;
      $properties = array('value' =>"right",
         'before' => '<td>',
          'after' => '</td>') ;
      $choice->set($properties) ;

      $choice = &$radio->addChoice('no') ;
      $properties = array('value' =>"no",
         'before' => '<td>' ,
          'after' => '</td>') ;
      $choice->set($properties) ;

      $option = &$this->addOption('message', 'show_or_hide') ;
      $properties = array(
        'desc' => "<center>\n<b>Suppress Ad Blocks in:&nbsp;&nbsp;</b></center>",
        'before' => '<table><tr><td>',
        'after' => '</td><td></td></tr>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_feed') ;
      $properties = array('desc' =>
                    'RSS feeds',
          'title' => "RSS feeds from your blog",
          'value' =>  true,
         'before' => '<tr><td>&nbsp;',
          'after' => '</td>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_page') ;
      $properties = array('desc' =>
                    '<a href="http://codex.wordpress.org/Pages" target="_blank" ' .
                    'title=" Click to see the difference between posts and pages">' .
                    'Static Pages</a>',
          'title' => "Ads appear only on blog posts, not on blog pages",
          'value' =>  true,
         'before' => '<td>&nbsp;',
          'after' => '</td></tr>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_home') ;
      $properties = array('desc' => "Home Page",
          'title' => "Home Page and Front Page are the same for most blogs",
          'value' => true,
         'before' => '<tr><td>&nbsp;' ,
          'after' => '</td>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_front_page') ;
      $properties = array('desc' => "Front Page",
          'title' => "Home Page and Front Page are the same for most blogs",
          'value' => true,
         'before' => '<td>&nbsp;' ,
          'after' => '</td></tr>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_attachment') ;
      $properties = array('desc' => "Attachment Page",
          'title' => "Pages that show attachments",
          'value' => true,
         'before' => '<tr><td>&nbsp;' ,
          'after' => '</td>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_category') ;
      $properties = array('desc' => "Category Pages",
          'title' => "Pages that come up when you click on category names",
          'value' => true,
         'before' => '<td>&nbsp;' ,
          'after' => '</td></tr>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_tag') ;
      $properties = array('desc' => "Tag Pages",
          'title' => "Pages that come up when you click on tag names",
          'value' => true,
         'before' => '<tr><td>&nbsp;' ,
          'after' => '</td>') ;
      $option->set($properties) ;

      $option = &$this->addOption('checkbox', 'kill_archive') ;
      $properties = array('desc' => "Archive Pages",
          'title' => "Pages that come up when you click on year/month archives",
          'value' => true,
         'before' => '<td>&nbsp;' ,
          'after' => '</td></tr></table></td></tr>') ;
      $option->set($properties) ;

      $option = &$this->addOption('text', 'mc') ;
      $properties = array('desc' => "Percentage of ad slots to share [Default: 5%]: ",
          'title' => "Support this plugin by donating a small fraction of your ad slots." .
                    "Suggested: 5%. Turn off sharing by entering 0.",
          'value' => 5,
         'before' => "<tr><td><table><tr><td>" .
                    "<b>Support this plugin by Donating Ad Space</b><br />",
          'style' => 'width:20px;text-align:center;',
          'after' => '%</td></tr></table></td></tr></table>') ;
      $option->set($properties) ;
    }

    function defineSubmitButtons() { // Add submit buttons
      unset($this->submitButtons) ;

      $button = &$this->addSubmitButton('submit', 'update') ;
      $properties = array('value' => 'Save Changes',
          'title' => "Save the changes as specified above.");
      $button->set($properties) ;

      $button = &$this->addSubmitButton('submit', 'reset') ;
      $properties = array('value' => 'Reset Options',
          'title' => 'DANGER: Reset all the options to default.');
      $button->set($properties) ;

      $button = &$this->addSubmitButton('submit', 'clean_db') ;
      $properties = array('value' => 'Clean Database',
          'title' => 'DANGER: Delete the options from the database.');
      $button->set($properties) ;
    }

    function handleSubmits() { // Deal with submit button clicks
      foreach ($this->submitButtons as $k => $v) {
        if (isset($_POST[$v->name])) {
          switch ($k) {
          case "update":
            // loop over options and read in the values set
            foreach ($this->options as $key => $opt) {
              $this->options[$key]->updateValue() ; // can't use $opt: PHP4 compatibility
            }
            update_option($this->optionName, $this->options) ;
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings have been updated in the database.</strong></p> </div>' ;
            break ;
          case "reset":
            $this->defineOptions() ;
            update_option($this->optionName, $this->options) ;
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings have been reset to the defaults!</strong></p> </div>' ;
            break ;
          case "clean_db":
            delete_option($this->optionName) ;
            unset($this->options) ;
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings have been discarded, and the database is clean as a whistle!<br />' .
              'You may want to uninstall the plugin now.</strong></p> </div>' ;
            break ;
          default:
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings do what? ' . $k . '</strong></p> </div>' ;
            break ;
          }
        }
      }
    }

    function &addOption($type, $key)
    {
      $name = $this->name . '_' . $key ;
      if (class_exists($type)) // Specialized class for this type of input
        $this->options[$key] =& new $type($name) ;
      else
        $this->options[$key] =& new option($type, $name) ;
      return $this->options[$key] ;
    }

    function &addSubmitButton($type, $key)
    {
      $name = $this->name . '_' . $key ;
      if (class_exists($type)) // Specialized class for this type of input
        $this->submitButtons[$key] =& new $type($name) ;
      else
        $this->submitButtons[$key] =& new option($type, $name) ;
      return $this->submitButtons[$key] ;
    }
    // ------------ Content Filter -----------------
    function get($optionName) {// Return an option value
      return $this->options[$optionName]->get() ;
    }
    function set($optionName, $value) {// Return an option value
      return $this->options[$optionName]->set($value) ;
    }
    function widget() {// Handle widget
    }
    function buildAdBlocks(&$defaultStack) { // Convert options to three ad blocks.
      if ($this->isActive) {
        foreach ($this->positions as $key) {
          $name = $this->name . '-' . $key ;
          $this->adBlocks[$key] =& new adBlock($name) ;
          $adText = stripslashes($this->get('text')) ;
          if (empty($adText) && count($defaultStack)>0)
            $adText = htmlspecialchars_decode(array_pop($defaultStack)) ;
          $this->adBlocks[$key]->set($adText) ;
        }
      }
    }
    function applyAdminOptions() { // From option values to inline style strings
      if ($this->isActive) {
        foreach ($this->positions as $key) {
          $adBlock =& $this->adBlocks[$key] ;
          $showKey = 'show_' . $key ;
          $alignment = $this->options[$showKey]->get() ;
          if ($alignment == 'no') {
            $name = $this->name ;
            $emptyText = "\n<!-- More Money: Suppressed $name by $showKey option -->\n" ;
            $adBlock->set($emptyText) ;
          }
          else {
            if ($alignment == 'left')
              $style = 'float:left;display:block;' ;
            if ($alignment == 'right')
              $style = 'float:right;display:block;' ;
            if ($alignment == 'center')
              $style = 'display:block;text-align:center;' ;
            // $style = 'display:block;text-align:center;' ;
            if (!empty($style)) {
              $properties = array() ;
              $properties['style'] = $style ;
              $adBlock->set($properties) ;
            }
          }
        }
      }
    }
    function applyMetaOptions() { // Read options given as post meta tags.
      // Post meta options are of the kind:
      //   key = provider name, pname-top, pname-bottom, pname-widget etc
      //   value = no, left, right, center etc.
      // Example: clicksor => no, adsense-bottom => left etc
      if ($this->isActive) {
        global $post;
        $metaKey = strtolower($this->name) ;
        $customStyle = get_post_custom_values($metaKey, $post->ID, true);
        if (is_array($customStyle)){
          $metaStyle = strtolower($customStyle[0]) ;
        }
        else
          $metaStyle = strtolower($customStyle) ;
        if ($metaStyle == 'no') {
          foreach ($this->positions as $key) {
            $emptyText = "\n<!-- More Money: Suppressed $key by custom tag: $metaKey -->\n" ;
            $this->adBlocks[$key]->set($emptyText) ;
          }
          return ;
        }
        foreach ($this->positions as $key) {
          $metaKey = $this->name . '-' . $key ;
          $customStyle = get_post_custom_values($metaKey, $post->ID, true);
          if (is_array($customStyle))
            $metaStyle = strtolower($customStyle[0]) ;
          else
            $metaStyle = strtolower($customStyle) ;

          if ($metaStyle == 'left')
            $style = 'float:left;display:block;' ;
          if ($metaStyle == 'right')
            $style = 'float:right;display:block;' ;
          if ($metaStyle == 'center')
            // $style = 'margin-left:atuo;margin-right:auto;' ;
            $style = 'text-align:center;display:block;' ;
          if (!empty($style)) {
            $properties = array() ;
            $properties['style'] = $style ;
            $this->adBlocks[$key]->set($properties) ;
          }
        }
      }
    }
    function decorateAdBlocks() { // From option values to inline style strings
      if ($this->isActive) {
        foreach ($this->positions as $key) {
          $this->adBlocks[$key]->decorate() ;
        }
      }
    }
    function buildAdStacks(&$provider) {// Content filter
      global $mAd ;
      if ($provider->isActive) {
        foreach ($provider->killOptions as $k) {
          $fn = 'is_' . $k ;
          $key = 'kill_' . $k ;
          if ($provider->options[$key]->get() && $fn()) return $content ;
        }
        $provider->applyMetaOptions() ; // meta options are defined only within the Loop
        $provider->decorateAdBlocks() ;
        // build an ad stack each per position (top, middle, bottom)
        foreach ($provider->positions as $k) {
          array_push($mAd->$k, $provider->adBlocks[$k]->get()) ;
        }
      }
    }
    function makeTextWithTooltip($text, $tip, $title='', $width='')
    {
      if (!empty($title))
        $titleText = "TITLE, '$title',STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true,";
      if (!empty($width))
        $widthText = "WIDTH, $width," ;
      $return = "<span style='text-decoration:underline' " .
        "onmouseover=\"Tip('". htmlspecialchars($tip) . "', " .
        "$widthText $titleText FIX, [this, 5, 5])\" " .
        "onmouseout=\"UnTip()\">$text</span>" ;
      return $return ;
    }

    function makeLIwithTooltip($text, $tip, $width='')
    {
      if (empty($width)) $width = "200" ;
      $return = "<li>" .
        $this->makeTextWithTooltip($text, $tip, $text, $width) .
        "</li>\n" ;
      return $return ;
    }
  } // End: Class provider

  class Overview extends provider {
    function Overview() {
      $this->name = "Overview" ;
      $this->isActive = false ;
      $this->isAdmin = true ;
    }

    function render()
    {
      global $mAd ;
      $name = $this->name ;
      echo '<div class="tab_current" id="tab', $mAd->tabID++, '">', "\n" ;
      $instructionText = '<h4>Instructions</h4>' .
        '<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >' .
        $this->makeLIwithTooltip('Sign up',
          'Sign up for the ad providers shown on the right (which will give me ' .
          'some referral income).') .
        $this->makeLIwithTooltip('Generate Code',
          'Generate your ad code from the ad-provider web site.') .
        $this->makeLIwithTooltip('Enter Code',
          'Enter your ad code into the text-boxes under the ad providers tabs.') .
        $this->makeLIwithTooltip('Configure',
          'If needed, configure other options for each ad provider in the corresponding tab.') .
        "</ul><br />\n" ;
      $fetureText = '<h4>Features</h4>' .
        '<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >' .
        $this->makeLIwithTooltip('Admin Control Panel',
          'The <b>Admin</b> tab gives you general options that apply to all providers, ' .
          'and common tools and actions (like Reset All Options, Clean Database etc.) ' .
          'You also have a button to migrate options between different plugin versions.') .
        $this->makeLIwithTooltip('Positions and Slots',
          'Three positions (Top, Middle and Bottom) and a configurable number of ' .
          'slots for ads. (See the Admin Tab for details and an illustration.') .
        $this->makeLIwithTooltip('Custom Field Control',
          'In More Money, you have more options [through <strong>custom fields</strong>] ' .
          'to control ad blocks in individual posts/pages. Add custom fields with keys ' .
          'like <strong>more-money-adsense-top, more-money-adsense-middle, ' .
          'more-money-adsense-bottom</strong> and with values like <strong>left, right, ' .
          'center</strong> or <strong>no</strong> to have control how the ad blocks show ' .
          'up in each post or page. The value <strong>no</strong> suppresses all the ad ' .
          'blocks in the post or page for that provider.') .
        $this->makeLIwithTooltip('CSS Control',
          'All <code>&lt;div&gt;</code>s that More Money creates have the class attribute ' .
          '<code>more-money</code>. Furthermore, they have class attributes like ' .
          '<code>more-money-adsense-top</code>, <code>more-money-clicksor-bottom</code> ' .
          'etc., (ie, <code>more-money-provider-position</code>). You can set the style ' .
          'for these classes in your theme <code>style.css</code> to control their ' .
          'appearance.') .
        "</ul><br />\n" ;
      $planText = '<h4>Future Plans</h4>' .
        '<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >' .
        $this->makeLIwithTooltip('Widgets',
          'I will release options to include sidebar widgets with optional ad ' .
          'customization. That is, you will be able to use the same ad code for ' .
          'both main text and the widgets, or have different texts, ' .
          'to be customized on the widgets page.') .
        $this->makeLIwithTooltip('Ad Rotation',
          'I will provide means to rotate ads among various providers ' .
          'with user-defined frequency.') .
        $this->makeLIwithTooltip('More Providers',
          'This plugin is designed with extensibility in mind. I will keep adding more ' .
          'ad providers, or even let the end-users add them.') .
        $this->makeLIwithTooltip('Provider Specificity',
          'This initial release treats all ad providers essentially the same way. ' .
          'In the next release, I will start introducing more specificity, ' .
          'like specialized fields for HopID, PubID, colors, etc.') .
        $this->makeLIwithTooltip('Expertise Level',
          'I plan to introduce expertise levels (Easy, Advanced and Expert tabs) ' .
          'within the tab for each ad provider.') .
        $this->makeLIwithTooltip('Max Number of Ad blocks',
          'Since some providers require you to limit the number of ad blocks to some ' .
          'policy-driven ceiling, I will expose that option to you.<br />' .
          'Also to be customized is the number of ads per slot. In this initial release, ' .
          'there are three slots (top, middle and bottom), each of which can take two ' .
          'ad blocks. In a future release, you will have much more customization options.') .
        $this->makeLIwithTooltip('Ad Block Customization',
          'Right now, all the ad blocks are designed to display the same ad code, ' .
          'for which the providers will serve different text. In a future release, ' .
          'I will give you a means of introducing different texts for different locations, ' .
          'possibly in a tabbed interface.') .
        $this->makeLIwithTooltip('Ad Space Sharing',
          'Although the current version shows a fraction (5% by default) of your ad space ' .
          'you can share with me to support this plugin, I have not implemented it yet. ' .
          'I will do so in a future release.') .
        $this->makeLIwithTooltip('Internationalization',
          'Future versions will provide MO/PO files for internationalization.') .
        "</ul><br />\n" ;
      $supportText = "<div style=\"width:270px;background-color:#cff;padding:5px;border: solid 1px\" id=\"support\"><b>Support this Plugin!</b><br /><span style='text-decoration:underline' onmouseover=\"TagToTip('share', WIDTH, 230, TITLE, 'Ad Space Sharing',STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 5, 5])\" onmouseout=\"UnTip()\">Share a small fraction of your ad space</span>, <span style=\"text-decoration:underline\" onmouseover=\"TagToTip('unreal', WIDTH, 230, TITLE, 'Buy &lt;em&gt;The Unreal Universe&lt;/em&gt;',STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 5, 5])\">Buy <b style=\"color:#a48;font-variant: small-caps;text-decoration:underline\">The Unreal Universe</b>, a remarkable book on Physics and Philosophy</span>, or <span style='text-decoration:underline' onmouseover=\"TagToTip('donate', WIDTH, 230, TITLE, 'Donation',STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 5, 5])\">Make a donation if you like this plugin</span>.</div><br />" ;

      echo '<table width="95%">', "\n" ;
      echo '<tr align="center" valign="middle">', "\n" ;
      echo '<td width="2%">', "\n" ;
      echo '<table width="100%">', "\n" ;
      echo '<tr align="center" valign="middle">', "\n" ;
      echo '<td align="left">', "\n" ;
      echo $instructionText ;
      echo $fetureText ;
      echo $planText ;
      echo $supportText ;
      echo "</td></tr></table>\n" ;
      echo "</td>\n" ;
      echo '<td width="60%">', "\n" ;
      echo '<table width="100%">' ;
      echo '<tr><th colspan="2">The following providers are supported</th></tr>', "\n" ;
      foreach ($mAd->providers as $p) {
        if (!$p->isAdmin)
        {
          echo '<tr align="center" valign="middle">', "\n" ;
          echo '<td width="33%">' ;
          echo htmlspecialchars_decode($p->referral) ;
          echo "<br /><br /></td>\n" ;
          echo "<td align='left'>", $p->options['info']->desc, "</td>\n" ;
          echo "</tr>\n" ;
        }
      }
      echo "</table></td>\n" ;
      echo "</tr></table>\n" ;
      echo "</div><!-- End: $name --> \n" ;
    }
  } // End: Class Overview

  class Admin extends provider {
    function Admin() {
      parent::provider("Admin", "") ;
      $this->isActive = false ;
      $this->isAdmin = true ;
    }
    function render() {
      global $mAd ;
      // Cannot be in the constructor because $mAd is not set yet!
      $this->defineOptions() ;

      $this->defineSubmitButtons() ;
      $this->handleSubmits() ;

      $name = $this->name ;
      echo '<div class="tab" id="tab', $mAd->tabID++, '">', "\n" ;

      echo $this->submitMessage ;
      echo $this->errorMessage ;

      $plugindir = get_option('siteurl') . '/' . PLUGINDIR . '/' . basename(dirname(__FILE__)) ;

      $infoText = '<h4>Ad Positions, Slots and Blocks</h4>' .
        'You can define ad blocks in three positions in your post - Top, Middle ' .
        'and Bottom. Each position can have multiple "slots". See the picture ' .
        'for details. By default, you have two slots per position, but you can ' .
        'change it below. In addition, you have widgets (or you will, in the ' .
        'near future) that you can place anywhere on your sidebar as many times ' .
        'as you want, by <a href="widgets.php"> Appearance &rarr; Widgets</a>.' ;
      $compText = '<h4>Competition</h4>' .
        'The providers supported in More Money may not all be compatible with ' .
        'each other, or, in particular, with AdSense. Be careful how you use ' .
        'them. But competition is probably a good thing. You may find your ' .
        'earnings go up because of it, which is the objective behind this aptly ' .
        'named plugin: More Money.' ;
      $adminText = '<h4>Admin Control Panel</h4>' .
        'This Admin tab gives you a control panel with tools and options that ' .
        'apply to the ad blocks from all the providers, en masse. You can '.
        '<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >' .
        $this->makeLIwithTooltip('Set Active',
          'By checking the box against their name below, you can set an ad provider ' .
          'as <b>Active</b> so that their ads appear on your pages. When you ' .
          'deactivate a provider, the corresponding tab header  will sport red fonts.') .
        $this->makeLIwithTooltip('Reset All Options',
          'You can reset all options to their default values if you feel that you ' .
          'have irrevocably messed them up.') .
        $this->makeLIwithTooltip('Migrate Options ',
          'When you upgrade the plugin to a newer version, some of the options ' .
          'may become incompatible. You can migrate your old options (to the ' .
          'extent possible) to the new version using this button.') .
        $this->makeLIwithTooltip('Clean Database',
          'This button gives you the option of cleaning all the More Money ' .
          'options from your databse as a prelude to deactivating or deleting ' .
          'your plugin. In a future release, you will have a button to deactivate ' .
          'the plugin directly.') .
        "</ul><br />\n" ;
      $picText = "<span id='ad-slot' style='text-decoration:underline' onmouseover=\"Tip('" .
        htmlspecialchars("<img src=\'http://blog/wp-content/plugins/more-money/ad-slots.gif\'") .
        "border=\'0\' alt=\'[ad-slots]\' />', WIDTH, 400,  FIX, [this, -40, -140], " .
        "TITLE, 'Click to close', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true)\" onmouseout=" .
        "\"UnTip()\">Hover on the picture to see details<br /><br />".
        "<img src='http://blog/wp-content/plugins/more-money/ad-slots-small.gif' " .
        "border='0' alt='[ad-slots-small]' /></span>" ;
      echo '<table width="95%" style="padding:10px;border-spacing:10px;">' . "\n" ;
      echo '<tr><th colspan="2"><h3>General Information</h3></th></tr>' . "\n" ;
      echo '<tr align="center" valign="middle">', "\n" ;
      echo '<td align="left" width="50%">', "\n" ;
      echo $infoText ;
      echo "</td>\n" ;
      echo "<td>\n" ;
      echo $picText ;
      echo "</td></tr>\n" ;
      echo '<tr><td>' ;
      echo $adminText ;
      echo "</td>\n" ;
      echo "<td>\n" ;
      echo $compText ;
      echo "</td></tr>\n" ;
      echo '<tr><th colspan="2"><h3>Activate or Deactivate providers</h3></th></tr>', "\n" ;
      echo "</table>\n" ;
      $this->renderForm('_admin') ;
      echo "</div><!-- End: $name --> \n" ;
    }
    function defineOptions() {
      unset($this->options) ;
      global $mAd ;
      if (!isset($mAd)) return ;
      foreach ($mAd->providers as $key => $p)
        if (!$p->isAdmin) {
          $this->options[$p->name] = &$mAd->providers[$key]->options['active'] ;
        }
    }
    function defineSubmitButtons() { // Add submit buttons
      unset($this->submitButtons) ;

      $button = &$this->addSubmitButton('submit', 'update') ;
      $properties = array('value' => 'Save Changes',
          'title' => "Save the changes as specified above.");
      $button->set($properties) ;

      $button = &$this->addSubmitButton('submit', 'reset') ;
      $properties = array('value' => 'Reset All Options',
          'title' => 'DANGER: Reset all the options to default.');
      $button->set($properties) ;

      $button = &$this->addSubmitButton('submit', 'migrate') ;
      $properties = array('value' => 'Migrate Options',
          'title' => 'Update the options to be compatible with ' .
                    'the current version of the plugin.');
      $button->set($properties) ;

      $button = &$this->addSubmitButton('submit', 'clean_db') ;
      $properties = array('value' => 'Clean Database',
          'title' => 'DANGER: Delete all the options from the database.');
      $button->set($properties) ;
    }
    function handleSubmits() { // Deal with submit button clicks
      global $mAd ;
      foreach ($this->submitButtons as $k => $v) {
        if (isset($_POST[$v->name])) {
          switch ($k) {
          case "update":
            // loop over options and read in the values set
            foreach ($this->options as $key => $opt) {
              $this->options[$key]->updateValue() ; // can't use $opt: PHP4 compatibility
            }
            // apply the active flags
            foreach ($mAd->providers as $key => $p)
              if (!$p->isAdmin) {
                $isActive = $this->options[$p->name]->get() ;
                $mAd->providers[$key]->isActive = $isActive ;
                $mAd->providers[$key]->set('active', $isActive) ;
                update_option($p->optionName, $p->options) ;
              }
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings have been updated in the database.</strong></p> </div>' ;
            break ;
          case "reset":
            foreach ($mAd->providers as $key => $p) { // can't use $p: PHP4 compatibility
              if ($p->isActive) {
                $mAd->providers[$key]->defineOptions() ;
                update_option($mAd->providers[$key]->optionName,
                  $mAd->providers[$key]->options) ;
              }
            }
            $this->submitMessage .= '<div class="updated"><p><strong>All' .
              ' Settings have been reset to the defaults!</strong></p> </div>' ;
            break ;
          case "clean_db":
            foreach ($mAd->providers as $key => $p) // can't use $p: PHP4 compatibility
              if ($p->isActive) {
                delete_option($mAd->providers[$key]->optionName) ;
                unset($mAd->providers[$key]->options) ;
              }
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings have been discarded, and the database is clean as a whistle!<br />' .
              'You may want to uninstall the plugin now.</strong></p> </div>' ;
            break ;
          default:
            $this->submitMessage .= '<div class="updated"><p><strong>' . $this->name .
              ' Settings do what? ' . $k . '</strong></p> </div>' ;
            break ;
          }
        }
      }
    }
  } // End: Class Admin

  class About extends provider {
    function About() {
      $this->name = "About" ;
      $this->isActive = false ;
      $this->isAdmin = true ;
    }
    function render() {
      global $mAd ;
      $name = $this->name ;
      echo '<div class="tab" id="tab', $mAd->tabID++, '">', "\n" ;
      @include (dirname (__FILE__).'/about.php');
      echo "</div><!-- End: $name --> \n" ;
    }
  } // End: Class About
}
?>
