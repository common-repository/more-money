<?php
/*
  Plugin Name: More Money
  Plugin URI: http://www.thulasidas.com/adsense
  Description: More Money is now re-released as <a href="http://wordpress.org/extend/plugins/easy-ads/">Easy Ads</a>.
  Version: 1.06
  Author: Manoj Thulasidas
  Author URI: http://www.thulasidas.com
*/

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

if (class_exists("moreMoney")) {
  echo "\nError from <b>More Money</b> plugin. Please deactivate it to continue.\n" ;
  echo "\n<pre>Problems defining the class 'moreMoney'. Already defined?!</pre>\n" ;
  exit ;
}
else {
  function niceInclude($name) {
    $fname = dirname (__FILE__) . '/' . $name . '.php' ;
    if (file_exists($fname)) {
    include_once($fname);
    }
    else {
    echo '<div class="error"><p><b><em>More Money</em></b>: '.
      "Error locating or loading the $name classes! Ensure <code>$fname</code> exists, " .
      'or reinstall (or deactivate) the plugin <code>More-Money</code>.</p></div>' ;
    exit ;
    }
  }

  niceInclude('options') ;
  niceInclude('providers') ;

  class adBlock extends option { // an ad block that will be displayed
    var $comment ;
    function adBlock($name) {// constructior
      parent::option('adBlock', $name) ;
    }
    function decorate() {// apply styles
      if (empty($this->style))
        $inline = '' ;
      else
        $inline = 'style="' . $this->style . '"' ;
      if (!empty($this->value))
        $this->value = '<div class="more-money more-money-' . strtolower($this->name)  . '" ' .
          $inline . '>' . $this->value . "</div>\n" ;
    }
  } // End: Class adBlock

  class moreMoney {
    var $plugindir, $locale, $hide, $tabID,$errorMessage ;
    var $providers = array() ;
    var $defaultStack = array() ;
    var $top = array() ;
    var $middle = array() ;
    var $bottom  = array() ;

    function moreMoney() { // Constructor
      if (file_exists (dirname (__FILE__).'/defaults.php')) {
        include (dirname (__FILE__).'/defaults.php');
        $defaults =
          unserialize(gzuncompress(base64_decode(str_replace( "\r\n", "", $str_init)))) ;
        $info =
          unserialize(gzuncompress(base64_decode(str_replace( "\r\n", "", $str_info)))) ;
      }
      if (empty($defaults) || empty($info)) {
        $this->errorMessage = '<div class="error"><p><b><em>More Money</em></b>: '.
          'Error locating or loading the defaults! Ensure <code>defaults.php</code> exists, ' .
          'or reinstall the plugin.</p></div>' ;
        return ;
      }

      foreach ($info['Unreal'] as $v)
        if (!empty($v)) $this->defaultStack[] = $v ;

      foreach ($defaults as $k => $v)
        if (!empty($v))  {
          $this->defaultStack[] = $v['default'] ;
          $this->defaultStack[] = $v['default'] ;
        }

      foreach ($defaults as $k => $v) {
        if (class_exists($k)) $this->providers[$k] =& new $k() ;
        else $this->providers[$k] =& new provider($k,$v) ;
        if ($this->providers[$k]->isActive) {
          $this->providers[$k]->buildAdBlocks($this->defaultStack) ;
          $this->providers[$k]->applyAdminOptions() ;
        }
        if (!empty($this->providers[$k]->options['active']))
          $this->providers[$k]->isActive = $this->providers[$k]->options['active']->value ;
      }
    }

    function writeAdminHeader() {
      echo "<link rel='stylesheet' type='text/css' href=\"" .
        get_option('siteurl') . '/' . PLUGINDIR . '/' .
        basename(dirname(__FILE__)) . '/mm_tabs.css" />' . "\n";
      echo '<script type="text/javascript" src="'.
        get_option('siteurl') . '/' . PLUGINDIR . '/' .
        basename(dirname(__FILE__)) . '/mm_tabs.js"></script>' . "\n";
    }

    function renderAdminPage() {
      echo '<script type="text/javascript" src="'.
        get_option('siteurl') . '/' . PLUGINDIR . '/' .
        basename(dirname(__FILE__)) . '/wz_tooltip.js"></script>' . "\n";
      echo '<div class="wrap" style="width:900px">' . "\n";

      echo "<h2>More Money Setup</h2><br />" ;

      echo $this->errorMessage ;

      echo '<div><ul class="tabs" id="tabs">' . "\n";
      $this->tabID = 0 ;
      $class = 'class="current"' ;
      foreach ($this->providers as $p) {
        if ($p->isActive) $style = '' ;
        else if ($p->isAdmin) $style = " style='color:green;font-weight:bold;'" ;
        else $style = " style='color:red;'" ;
        echo '<li><a href="#" ' . $class . $style . ' id="tab' . $this->tabID++ . '_link">' .
          $p->name . "</a></li>\n";
        $class = '' ;
      }
      echo "</ul>\n</div><!-- of ul tabs -->\n" ;

      $this->tabID = 0 ;
      foreach ($this->providers as $p) {
        $p->render() ;
      }

      include (dirname (__FILE__).'/tail-text.php');
    }

    function addAdminPage() {
      $plugin_page = add_options_page('More Money', 'More Money', 9,
                     basename(__FILE__), array(&$this, 'renderAdminPage'));
      add_action('admin_head-'. $plugin_page, array(&$this, 'writeAdminHeader'));
    }

    function findPara($content, $midpoint) {
      $para = '<p' ;
      $content = strtolower($content) ;  // not using stripos() for PHP4 compatibility
      $paraPosition = strpos($content, $para, $midpoint) ;
      if ($paraPosition === FALSE) {
        $para = '<br' ;
        $paraPosition = strpos($content, $para, $midpoint) ;
      }
      return $paraPosition ;
    }

    function filterContent($content) {
      $midpoint = strlen($content)/2 ;
      $paraPosition = $this->findPara($content, $midpoint) ;
      foreach ($this->providers as $p) {
        if ($p->isActive) $p->buildAdStacks($p) ;
        $positions = $p->positions ; // "borrow" the position labels from the last provider!
      }
      $adsPerSlot = 2 ;
      foreach ($positions as $pos) { // pick random ads to fill the ad slots ($pos)
        $adStack = $this->$pos ;
        $adKeys = array_keys($adStack);
        if (count($adStack) >= $adsPerSlot) $adKeys = array_rand($adStack, $adsPerSlot);
        $$pos = '' ;
        foreach ($adKeys as $k) {
          $$pos .= $adStack[$k] ;
        }
      }
      return $top . substr_replace($content, $middle, $paraPosition, 0) . $bottom ;
    }
  } //End: Class moreMoney
}

if (class_exists("moreMoney")) {
  // error_reporting(E_ALL);
  global $mAd ;
  $mAd =& new moreMoney() ;
  if (isset($mAd)) {
    add_action('admin_menu', array(&$mAd, 'addAdminPage')) ;
    add_filter('the_content', array(&$mAd, 'filterContent')) ;
  }
}

?>
