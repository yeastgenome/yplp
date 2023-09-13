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
 * Get user ip, figure out if he uses proxy , make sure not pick up internal ip
 * by http://vadimg.com/2012/05/29/php-get-ip-address-ip-block-snippet/
 *
 * @return string ip
 */
function getUserIP()
{
    $alt_ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $alt_ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches))
    {
        // make sure we dont pick up an internal IP defined by RFC1918
        foreach ($matches[0] AS $ip)
        {
            if (!preg_match('#^(10|172\.16|192\.168)\.', $ip))
            {
                $alt_ip = $ip;
                break;
            }
        }
    }
    else if (isset($_SERVER['HTTP_FROM']))
    {
        $alt_ip = $_SERVER['HTTP_FROM'];
    }

    return $alt_ip;
}

/**
 * Returns the table name without the defined prefix
 *
 * @param string $table_name name of the current table
 * @return string the clear table name
 */
function makeTblnameClear($table_name)
{
  return preg_replace("/[(YPL_TBL_)]/", "", $table_name, 9);
}

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
function findInside($start, $end, $string)
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

?>