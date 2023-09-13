<?php
/**
 * referer.php
 *
 * Make a blank referer
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */

// start session
session_start();


if(!isset($_SESSION['sessionid']) || ($_SESSION['sessionid'] != 'jkfg89e58tegER%$rgeikg"§$r3'))
{
  echo'empty.';
  die();
} 

$url = $_GET['urll87bvAs'];
if($url != '')
{
  echo"<html><head><meta http-equiv=\"refresh\" content=\"0; URL=$url\"></head><body></body></html>";
}
?>