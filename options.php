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

if (class_exists("option")) {
  echo "\nError from <b>More Money</b> plugin. Please deactivate it to continue.\n" ;
  echo "\n<pre>Problems defining the class 'option'. Already defined?!</pre>\n" ;
  exit ;
}
else
{
  class option { // base option class
    var $name, $desc, $title, $value, $type ;
    var $width, $height, $x, $y, $before, $after, $style ; // display attributes
    function option($type, $name) {// constructior
      $this->type = $type ;
      $this->name = $name ;
    }
    function get() {// get the value of an option
      return $this->value ;
    }
    function set($properties) {// set the value or the attributes of an option
      if (!isset($properties)) return ;
      if (is_array($properties)) {
        foreach ($properties as $k => $v) {
          $key = strtolower($k) ;
          if (floatval(phpversion()) > 5.3) {
            if (property_exists($this, $key)) $this->$key = $v ;
          }
          else {
            if (array_key_exists($key, $this)) $this->$key = $v ;
          }
        }
      }
      else
      {
        $this->value = $properties ;
      }
    }
    function render() {// admin page rendering
      if (!empty($this->before)) echo $this->before, "\n" ;
      echo '<label for="', $this->name,
        '" title="', $this->title, '">', "\n",
        '<input type="', $this->type, '" id="', $this->name,
        '" name="', $this->name, '" ' ;
      if (!empty($this->style)) echo ' style="', $this->style, '"' ;
      echo ' value="', $this->value, '"' ;
      echo ' />', $this->desc, "\n</label>\n" ;
      if (!empty($this->after)) echo $this->after, "\n" ;
    }
    function updateValue() {// Update the value from the admin page
      if (isset($_POST[$this->name])) $this->value = $_POST[$this->name] ;
    }
  } // End: Class option

  class checkbox extends option { // Checkbox
    function checkbox($name) {// Constructior
      parent::option('checkbox', $name) ;
    }
    function render($unique='') {// admin page rendering
      if (!empty($this->before)) echo $this->before, "\n" ;
      $name = $this->name . $unique ;
      echo '<label for="', $name, '" title="', $this->title, '">', "\n",
        '<input type="', $this->type, '" id="', $name,
        '" name="', $this->name, '" ' ;
      if (!empty($this->style)) echo ' style="', $this->style, '"' ;
      if ($this->value) echo 'checked="checked"' ;
      echo ' /> ', $this->desc, "\n</label>\n" ;
      if (!empty($this->after)) echo $this->after, "\n" ;
    }
    function updateValue() {// Update the value from the admin page
      $this->value = isset($_POST[$this->name]) ;
    }
  } // End: Class checkbox

  class radio extends option { // Radiobox
    var $choices ;
    function radio($name) {// Constructior
      parent::option('radio', $name) ;
    }
    function &addChoice($name) {
      $subname = $this->name . '_' . $name ;
      $this->choices[$subname] =&
        new option('radio', $subname) ;
      return $this->choices[$subname] ;
    }
    function render() {// Admin page rendering
      if (!empty($this->before)) echo $this->before, "\n" ;
      // echo '<label for="', $this->name, '" title="', $this->title, '">', "\n" ;
      foreach ($this->choices as $k => $v) {
        echo $v->before, "\n" ;
        echo '<label for="', $k, '" title="', $this->title, '">', "\n" ;
        echo '<input type="', $v->type, '" id="', $k, '" name="', $this->name, '" ' ;
        if ($this->value == $v->value) echo 'checked="checked"' ;
        echo ' value="', $v->value, '" /> ', $v->desc ;
        echo "\n</label>\n" ;
        echo $v->after, "\n" ;
      }
      if (!empty($this->after)) echo $this->after, "\n" ;
      // echo "</label>\n" ;
    }
  } // End: Class radio

  class select extends option { // Drop-down menu. Not used in this plugin.
    function select($name) {// Constructior
      parent::option('select', $name) ;
    }
    function addChoice($name) {
      $subname = $this->name . '_' . $name ;
      $this->choices[$subname] =&
        new option('option', $subname) ;
    }
    function render() {// Admin page rendering
      if (!empty($this->before)) echo $this->before, "\n" ;
      echo '<label for="', $this->name, '" title="', $this->title, '">' ;
      foreach ($this->choices as $k => $v) {
        echo $v->before, '<input type="', $v->type, '" id="', $k,
          '" name="', $this->name, '" ' ;
        if ($this->value == $v->value) echo 'selected="selected"' ;
        echo ' />', $v->desc, $v->after, "\n" ;
      }
      echo "</label>\n" ;
      if (!empty($this->after)) echo $this->after, "\n" ;
    }
  } // End: Class select

  class message extends option { // Not an option, but a message in the admin panel
    function message($name) { // constructor
      parent::option('select', $name) ;
    }
    function render() {// admin page rendering
      if (!empty($this->before)) echo $this->before, "\n" ;
      echo $this->value, "\n" ;
      echo $this->desc, "\n" ;
      if (!empty($this->after)) echo $this->after, "\n" ;
    }
  } // End: Class message

  class textarea extends option { // Multi-line textareas
    function textarea($name) {// Constructior
      parent::option('textarea', $name) ;
      $this->width = 50 ;
      $this->height = 5 ;
      $this->style = "width: 96%; height: 180px;" ;
    }
    function render() {
      if (!empty($this->before)) echo $this->before, "\n" ;
      echo $this->desc, '<textarea cols="', $this->width,
        '" rows="', $this->height, '" name="', $this->name,
        '" style="', $this->style, '" title="', $this->title, '">',
        stripslashes(htmlspecialchars($this->value)),
        "</textarea>\n" ;
      if (!empty($this->after)) echo $this->after, "\n" ;
    }
  } // End: Class textarea

  class text extends option { // Multi-line texts
    function text($name) {// Constructior
      parent::option('text', $name) ;
    }
    function render() {// admin page rendering
      if (!empty($this->before)) echo $this->before, "\n" ;
      echo $this->desc, '<label for="', $this->name,
        '" title="', $this->title, '">', "\n",
        '<input type="', $this->type, '" id="', $this->name,
        '" name="', $this->name, '" ' ;
      if (!empty($this->style)) echo ' style="', $this->style, '"' ;
      echo ' value="', $this->value, '"' ;
      echo " />\n</label>\n" ;
      if (!empty($this->after)) echo $this->after, "\n" ;
    }
  } // End: Class text
}
?>
