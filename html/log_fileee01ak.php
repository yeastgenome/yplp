<?php
/**
 * log_fileee0.php
 *
 * Displays log data
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */
/**
 * Define YPLP
 */

// start session
session_start();

// get the header for the html output
include'standardheader.php';

if((isset($_POST['username'])) && (isset($_POST['password'])))
  if((md5($_POST['username']) == '7c93e9aafdecfea91e7b7f0d833cbaea') && (md5($_POST['password']) == '1e049ecd35df71418d596c84b02d5fff'))
    $_SESSION['sessionid'] = 'jkfg89e58tegER%$rgeikg"$r3';

if(isset($_GET['task']))
{
  if($_GET['task'] == 'logout')
  {
    session_destroy(); 
    header("Location: log_fileee01ak.php");  
  }
}

if(!isset($_SESSION['sessionid']) || ($_SESSION['sessionid'] != 'jkfg89e58tegER%$rgeikg"$r3'))
{
  echo'
<form action="log_fileee01ak.php" method="post">
<table width="300" >
  <colgroup> 
    <col width="100" />
    <col width="200" />  
  </colgroup>
  <tr class="">  
    <td><div class="standardtext">Username:</div></td>  
    <td><div class="standardtext"><input class="standardtext" type="text" name="username" id="username" value=""></input></div></td>
  </tr>
  <tr class="">  
    <td><div class="standardtext">Password:</div></td>  
    <td><div class="standardtext"><input class="standardtext" type="password" name="password" id="password" value=""></input></div></td>
  </tr>
</table>
<input type="submit" value="LogIn"></input>
</form>';
die();
} 
// get the encryptor class for encrypting some strings (e.g. the path to a picture)
include'includes/Encryptor.php';
$crypt->class_obj = new Encryptor;

// to make the database-name clear
include'includes/helpers.php';

// a couple of defines
include'includes/defines.php';
// functions for logging a user
include'includes/functions_log.php';
$limit = $_GET['limit'];
$limit = preg_replace("/[^0-9]/", "", $limit);

$resultsperpage = $_GET['resultsperpage'];
$resultsperpage = preg_replace("/[^0-9]/", "", $resultsperpage);
if($resultsperpage == '')
  $resultsperpage = '20';

echo'<a href="?task=logout">LogOut</a><br/><br/>';
$date_first_log_unix = first_log_entry();
$date_first_log = date("d.m.Y",$date_first_log_unix );
echo"Since $date_first_log<br/>";

echo'
<table width="60%" >
  <colgroup> 
    <col width="25%" />
    <col width="10%" />  
    <col width="25%" />  
  </colgroup>
  <tr class="tblstandardfuellungmitte">  
    <td><div class="standardtext">Top 10 Genes</div></td>  
    <td style="background-color:#ffffff;"><div class="standardtext">&#160;</div></td> 
    <td><div class="standardtext">Top 10 IPs</div></td>
  </tr>
  <tr class=""> ';

echo'<td class="tblrandergebnisse"><div class="standardtext">';
$hit_list_entries = hit_list2(10);
foreach($hit_list_entries as $entry=>$views)
{
  $view_txt = '';
  if($views > 1)
    $view_txt = 'Views';
  else
    $view_txt = 'View';
  echo$entry.' ('.$views.' '.$view_txt.')<br/>';
}
echo'</div></td>';

echo'<td><div class="standardtext">&#160;</div></td>';

echo'<td class="tblrandergebnisse"><div class="standardtext">';
$hit_list_entries_ip = hit_list_ip(10);
foreach($hit_list_entries_ip as $entry=>$views)
{
  $entry_txt = '';
  if($views > 1)
    $entry_txt = 'Clicks';
  else
    $entry_txt = 'Click';
  echo$entry.' ('.$views.' '.$entry_txt.')<br/>';
}
echo'</div></td>';

echo'</tr></table>';

$total_clicks = get_total_clicks();
$total_views = get_total_views();
echo"Total Clicks: $total_clicks / Total Views: $total_views";
?>
<br/><br/>

            <table border="0" width="100%">
              <colgroup>
               <col width="100%" />
              </colgroup>
            </table>
            <table width="100%" >
              <colgroup>
               <col width="10%" />
               <col width="20%" />
               <col width="45%" />
               <col width="25%" />
              </colgroup>
              <tr class="tblstandardfuellungmitte">
                <td>
                  <div class="standardtext">Date</div>
                </td>
                <td>
                  <div class="standardtext">IP</div>
                </td>
                <td>
                  <div class="standardtext">HTTP Referer</div>
                </td>
                <td>
                  <div class="standardtext">Genes</div>
                </td>
              </tr>

              <?php
              $limit = preg_replace("/[^0-9]/", "", $limit);

              if (!$limit) 
              {
                $limit = 1;
              }

              $db_query_string = "SELECT log_id, ip, timestamp, http_referer FROM yplp_statistic_log";               
             
              $db_query_string2 = "SELECT COUNT(*) FROM ($db_query_string) AS rows";
              $db_query = pg_query($db_connection, $db_query_string2);
              $db_result = pg_fetch_array($db_query, 0);
              $rowsfound = $db_result["count"];


              $limitmax = $limit+($resultsperpage*$picture_total_tmp)-$picture_total_tmp;
              $db_query_string = $db_query_string . ' ORDER BY LOG_ID DESC';
              $db_query = pg_query($db_connection, $db_query_string);
              $rowcount = 0;
              $log_id = '';
              $limitmax = $limit+$resultsperpage;
              while ($db_result = pg_fetch_array($db_query))
              { 
                $rowcount++;
                if(($rowcount >= $limit) && ($rowcount <= $limitmax+1))
                {
                    $log_id = '';
                    $search_text = '';
                    $log_id = $db_result["log_id"];
                    $db_query_string2 = "SELECT timestamp, search_string FROM YPLP_STATISTIC_DETAILS WHERE log_id = $1 ORDER BY TIMESTAMP ASC";               
                    $db_query_params2 = pg_query_params($db_connection, $db_query_string2, array($log_id));
                    while ($db_result2 = pg_fetch_array($db_query_params2))
                    { 
                      $search_text .= '['.date("H:i",$db_result2["timestamp"]).'] '.$db_result2["search_string"].'; ';
                    }

                    $http_referer = $db_result["http_referer"];
                    $http_referer = str_replace("&","%26s",$http_referer);
                    ?>
                    <tr>
                      <td class="tblrandergebnisse">
                      <div class="standardtext"><?php echo date("d.m.y H:i",$db_result["timestamp"]); ?></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="standardtext"><?php echo $db_result["ip"]; ?></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="standardtext"><a href="referer.php?urll87bvAs=<?php echo$http_referer; ?>"><?php echo $db_result["http_referer"]; ?></a></div>
                      </td>
                      <td class="tblrandergebnisse">
                      <div class="standardtext"><?php echo$search_text; ?></div>
                      </td>
                     </tr>
                    <?php
                }
              }
              echo'</table><br/>';
              $limit_next_page = $limit + $resultsperpage + 2 ;
              $limit_prev_page = $limit - $resultsperpage - 2;
              if($limit_prev_page > 0)
                 echo'<a href="?limit='.$limit_prev_page.'">Previous Page</a>&#160;|&#160;';              
              if($limit_next_page < $rowsfound)
                echo'<a href="?limit='.$limit_next_page.'">Next Page</a>';
              ?>
            
  </body>
</html>
