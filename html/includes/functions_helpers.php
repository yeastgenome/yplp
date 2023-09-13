<?php
/**
 * helpers.php
 *
 * Holds miscellaneous functions
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c)2009 Yeast Genetics and Molecular Biology Group University Graz
 */

/**
 * Returns the table name without the defined prefix
 *
 * @param string $table_name name of the current table
 * @return string the clear table name
 */
function make_tblname_clear($table_name)
{
  return preg_replace("/[(YPL_TBL_)]/", "", $table_name, 9);
}

/**
 * Finds a string between the given start and end string
 *
 * @param string $start start-string
 * @param string $end end-string
 * @param string $string string to search 
 * @return string the result string 
 */
function find_inside($start, $end, $string)
{
  preg_match_all('/' . preg_quote($start, '/') . '([^\.:=]+)'.preg_quote($end, '/').'/i', $string, $m);
  return $m[1];
}

/**
 * Deletes all files and folders of a given path
 *
 * @author http://aktuell.de.selfhtml.org/artikel/php/verzeichnisse/
 * @param string $path the path to delete all files and folders
 * @return nothing 
 */
function rec_rmdir ($path)
{
  if (!is_dir ($path))
    return -1;
  $dir = opendir ($path);
  if (!$dir)
    return -2;
  while (($entry = readdir($dir)) !== false)
  {
    if ($entry == '.' || $entry == '..') continue;
    if (is_dir ($path.'/'.$entry)) 
    {
      $res = rec_rmdir ($path.'/'.$entry);
      if ($res == -1) 
      { 
          closedir ($dir); 
          return -2; 
      } else if ($res == -2) 
      { 
          closedir ($dir);
          return -2; 
      } else if ($res == -3) 
      { 
          closedir ($dir);
          return -3;
      } else if ($res != 0) 
      { 
          closedir ($dir); 
          return -2;
      }
    } else if (is_file ($path.'/'.$entry) || is_link ($path.'/'.$entry)) 
    {
      $res = unlink ($path.'/'.$entry);
      if (!$res) 
      {
        closedir ($dir); 
        return -2;
      }
    } else {
      closedir ($dir); 
      return -3;
    }
  }
  closedir ($dir);
  $res = rmdir ($path);
  if (!$res)
    return -2;
  return 0;
}

/**
 * Checks if an emailaddress is valid
 *
 * @author http://www.ilovejackdaniels.com/php/email-address-validation
 * @param string $email emailaddress to check
 * @return true or false 
 */
function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
?([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}


?>