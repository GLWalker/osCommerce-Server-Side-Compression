<?php 
/**
   * Callback function for output buffering to compress output
   * {@source}
   * @author Bobby Easland
   * @copyright Copyright (c) 2008-2009, Bobby Easland
   * @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License
   * @link http://www.oscommercespecialist.com/ osCommerce Specialist
   * @param string $buffer Current buffer layer
   * @return string
   */

  function chemoCompress($buffer){
    preg_match_all("!<style[^>]+>.*?</style>!is", $buffer, $match);
    $styles = $match[0];

    preg_match_all("!<script[^>]+>.*?</script>!is", $buffer, $match);
    $scripts = $match[0];

    preg_match_all("!<pre[^>]*>.*?</pre>!is", $buffer, $match);
    $pre = $match[0];

    preg_match_all("!<textarea[^>]+>.*?</textarea>!is", $buffer, $match);
    $textareas = $match[0];

    $search = array("!<style[^>]+>.*?</style>!is", "!<script[^>]+>.*?</script>!is", "!<pre[^>]*>.*?</pre>!is", "!<textarea[^>]+>.*?</textarea>!is");
    $replace = array('@TRIM:STYLE@', '@TRIM:SCRIPT@', '@TRIM:PRE@', '@TRIM:TEXTAREA@');
    $buffer = preg_replace($search, $replace, $buffer);

    $buffer = trim(preg_replace(array('/((?<!\?\>)\n)[\s]+/m', '/\>\s+\</'), array('\1', '><'), $buffer));


    $searches = array('@TRIM:STYLE@' => $styles, '@TRIM:SCRIPT@' => $scripts, '@TRIM:PRE@' => $pre, '@TRIM:TEXTAREA@' => $textareas);

    foreach($searches as $search => $replace ){
      $len = strlen($search);
      $pos = 0;
      $count = count($replace);

      if ($count < 1){
        continue;
      }

      for ($i = 0; $i < $count; $i++) {
        if ( false !== ($pos = strpos($buffer, $search, $pos)) ) {
          $buffer = substr_replace($buffer, $replace[$i], $pos, $len);
        } else {
          continue;
        }
      }
    } # end foreach

    return $buffer;
  } # end function