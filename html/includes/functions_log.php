<?php
/**
 * functions_log.php
 *
 * Holds functions for the LOG
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */

//TODO: check if param are not empty

/**
 * Return the last inserted LOG_ID
 *
 * @param nothing
 * @return string last inserted LOG_ID
 */
function get_last_insert_id()
{
  global $db_connection;
  $db_query_string = "SELECT log_id FROM ".GENERAL_TBL_PREFIX."statistic_log ORDER BY log_id DESC";
  $db_query = pg_query($db_connection, $db_query_string);
  $db_result = pg_fetch_array($db_query, 0);
  $log_id = $db_result["log_id"];
  return $log_id;
}

/**
 * Check if a given IP address is still active in the given time frame
 *
 * @param string $ip ip to check
 * @param string $timeframe time frame in minutes where ip is allowed to be active
 * @return boolean 
 */
function check_ip_active($ip, $timeframe)
{
  global $db_connection;
  $db_query_string = "SELECT timestamp FROM ".GENERAL_TBL_PREFIX."statistic_log WHERE ip = $1 ORDER BY LOG_ID DESC";
  $db_query_params = pg_query_params($db_connection, $db_query_string, array($ip));
  $db_result = pg_fetch_array($db_query_params, 0);
  $timestamp_db = '';
  $timestamp_db = $db_result["timestamp"];
  if($timestamp_db == '')
  {
    return false;
  } else
  {
    $future_time = $timestamp_db + (60 * $timeframe);
    if($future_time > time())
      return true;
    else
      return false;
  }
}

/**
 * Add a new log entry
 *
 * @param string $log_id id of the log entry
 * @param string $ip ip address
 * @param string $timestamp unix timestamp
 * @param string $http_referer http referer
 * @return nothing
 */
function add_log($log_id, $ip, $timestamp, $http_referer)
{
  global $db_connection;

  $log_id = preg_replace("/[^0-9]/", "", $log_id);
  $ip = preg_replace("/[^0-9A-Za-z.]/", "", $ip);
  $timestamp = preg_replace("/[^0-9]/", "", $timestamp);
  $http_referer = preg_replace("/[0-9A-Za-z:\/-]/", "", $http_referer);
  if($http_referer == "")
   $http_referer = " ";
/*
  $db_insert_values = array(
    'log_id' => $log_id,
    'ip' => $ip,
    'timestamp' => $timestamp,
    'http_referer' => $http_referer
  );
  $tbl = GENERAL_TBL_PREFIX.'statistic_log';
  $db_result = pg_insert($db_connection, $tbl, $db_insert_values);
*/
  $db_insert_string = "INSERT INTO yplp_statistic_log (log_id, ip, timestamp, http_referer) VALUES ('$log_id', '$ip', '$timestamp', '$http_referer')";
  $db_result = pg_query($db_connection, $db_insert_string);
}

/**
 * Add a new detail to the log entry
 *
 * @param string $log_id id of the log entry
 * @param string $timestamp unix timestamp
 * @param string $search_string user searched gene name
 * @return nothing
 */
function add_detail($log_id, $timestamp, $search_string)
{
  global $db_connection;

  $log_id = preg_replace("/[^0-9]/", "", $log_id);
  $timestamp = preg_replace("/[^0-9]/", "", $timestamp);
  $search_string = preg_replace("/[0-9A-Za-z:\/-]/", "", $search_string); 

/*
  $log_id = pg_escape_literal($log_id);
  $timestamp = pg_escape_literal($timestamp);
  $search_string = pg_escape_literal($search_string);
*/
/*
  $db_insert_values = array(
    "log_id" => "$log_id",
    "timestamp" => "$timestamp",
    "search_string" => "$search_string"
  );
  $db_result = pg_insert($db_connection, GENERAL_TBL_PREFIX.'statistic_details', $db_insert_values);
*/
  $db_insert_string = "INSERT INTO yplp_statistic_details (log_id, timestamp, search_string) VALUES ('$log_id', '$timestamp', '$search_string')";
  $db_result = pg_query($db_connection, $db_insert_string);
//  echo pg_last_error($db_connection)."--";
}

/**
 * Return last log_id to a given IP address
 *
 * @param string $ip
 * @return string log_id  
 */
function logid_to_ip($ip)
{
  global $db_connection;
  $db_query_string = "SELECT log_id FROM ".GENERAL_TBL_PREFIX."statistic_log WHERE ip = $1 ORDER BY log_id DESC";
  $db_query_params = pg_query_params($db_connection, $db_query_string, array($ip));
  $db_result = pg_fetch_array($db_query_params, 0);
  $log_id = $db_result["log_id"];
  return $log_id;
}

/**
 * Return the top searched gene names
 *
 * @param string $top how many to return
 * @return array array with entries
 */
function hit_list($top)
{
  $hit_list_entries = array();
  global $db_connection;
  $db_query_string = "SELECT * FROM (SELECT search_string, COUNT(*) AS views  FROM ".GENERAL_TBL_PREFIX."statistic_details WHERE SEARCH_STRING IS NOT NULL GROUP BY SEARCH_STRING ORDER BY COUNT(*) DESC ) WHERE ROWNUM <= $1";
  $db_query_params = pg_query_params($db_connection, $db_query_string, array($top));
  while($db_result = pg_fetch_array($db_query))
  {
    $search_string = $db_result["search_string"];
    $views = $db_result["views"];
    $hit_list_entries[$search_string] = $views;
  }
  return $hit_list_entries;
}


/**
 * Return the top searched gene names but cleaned -> 1 gene name / 1 log entry is count (hit_list() counts all gene names searched for)
 *
 * @param string $top how many to return
 * @return array array with entries
 */
function hit_list2($top)
{
  $hit_list_entries = array();
  global $db_connection;
  $db_query_string = "SELECT * FROM (SELECT COUNT(*), search_string FROM (SELECT   LOG_ID,SEARCH_STRING, COUNT(*) AS VIEWS  FROM ".GENERAL_TBL_PREFIX."statistic_details WHERE SEARCH_STRING IS NOT NULL GROUP BY SEARCH_STRING, LOG_ID ORDER BY COUNT(*) DESC ) AS tmp GROUP BY SEARCH_STRING) AS tmp2 ORDER BY count DESC LIMIT $1";
  $db_query_params = pg_query_params($db_connection, $db_query_string, array($top));
  while($db_result = pg_fetch_array($db_query_params))
  {
    $search_string = $db_result["search_string"];
    $views = $db_result["count"];
    $hit_list_entries[$search_string] = $views;
  }
  return $hit_list_entries;
}

/**
 * Return count of rows
 *
 * @return string count  
 */
function get_total_views()
{
  $count = 0;
  global $db_connection;
  $db_query_string = "SELECT COUNT(LOG_ID) FROM ".GENERAL_TBL_PREFIX."statistic_details";
  $db_query = pg_query($db_connection, $db_query_string);
  $db_result = pg_fetch_array($db_query, 0);
  $count = $db_result["count"];
  return $count;
}

/**
 * Return count of clicks
 *
 * @return string count  
 */
function get_total_clicks()
{
  $count = 0;
  global $db_connection;
  $db_query_string = "SELECT COUNT(LOG_ID) FROM ".GENERAL_TBL_PREFIX."statistic_log";
  $db_query = pg_query($db_connection, $db_query_string);
  $db_result = pg_fetch_array($db_query, 0);
  $count = $db_result["count"];
  return $count;
}

/**
 * Return date of first log entry
 *
 * @return string unix timestamp
 */
function first_log_entry()
{
  $timestamp = 0;
  global $db_connection;
  $db_query_string = "SELECT timestamp FROM ".GENERAL_TBL_PREFIX."statistic_log ORDER BY timestamp ASC";
  $db_query = pg_query($db_connection, $db_query_string);
  $db_result = pg_fetch_array($db_query, 0);
  $timestamp = $db_result["timestamp"];
  return $timestamp;
}

/**
 * Return the top ips
 *
 * @param string $top how many to return
 * @return array array with entries
 */
function hit_list_ip($top)
{
  $hit_list_entries = array();
  global $db_connection;
  $db_query_string = "SELECT * FROM (SELECT   IP, COUNT(*) AS VIEWS  FROM ".GENERAL_TBL_PREFIX."statistic_log WHERE IP IS NOT NULL GROUP BY IP ORDER BY COUNT(*) DESC ) AS tmp2 ORDER BY views DESC LIMIT  $1";
  $db_query_params = pg_query_params($db_connection, $db_query_string, array($top));
  while ($db_result = pg_fetch_array($db_query_params))
  {
    $search_string = $db_result["ip"];
    $views = $db_result["views"];
    $hit_list_entries[$search_string] = $views;
  }
  return $hit_list_entries;
}

?>
