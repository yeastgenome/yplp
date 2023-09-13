<?php
/**
 * print_table_link.php
 *
 * Prints the existing tables
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c)2009 Yeast Genetics and Molecular Biology Group University Graz
 */
 
$tbl_name = ociresult($oracle_command, "ALIAS");
$tbl_id = ociresult($oracle_command, "NR");
$sub_tbl_names = '';
$sub_tbl_names = explode('#',$tbl_name);
// checks if the table is a sub-table
if($sub_tbl_names[1] != '')
{
  $x = 0;
  foreach($sub_tbl_names as $sub_tbl_name)
  {
    if($foldernamelevel[$x] != $sub_tbl_names[$x])
    {
      $blankstoinsert = '';
      for($y = 0; $y <= $x; $y++)
        $blankstoinsert .= '&#160;&#160;&#160;';
      if($x == 0)
      {
        $oracle_command_check_table_exists = OCIParse($oracle_connection, "select count(*) from all_tables where TABLE_NAME = :table_name")  or die('error 2');
        OCI_Bind_by_name($oracle_command_check_table_exists, ':table_name', $table_name, 60);
        OCIExecute($oracle_command_check_table_exists, OCI_DEFAULT);
        OCIFetch($oracle_command_check_table_exists);
        $tbl_exist = OCIResult ($oracle_command_check_table_exists,1);
        if($tbl_exist != '0')
          $table_link_txt .='
            <tr>
              <td>'.$blankstoinsert.'<a href="array_data.php?&amp;tbl_id='.$tbl_id.'&amp;showgallery=yes&amp;showinfos=&amp;newsite=yes&amp;" ><span class="main_url">'.makeTblnameClear($sub_tbl_names[$x]).'</span></a></td>
            </tr>';
        else
          $table_link_txt .='
            <tr>
              <td>'.$blankstoinsert.'<span class="main_url">'.makeTblnameClear($sub_tbl_names[$x]).'</span></td>
            </tr>';
      }else{
        $table_link_txt .='
          <tr>
            <td>'.$blankstoinsert.'<a href="array_data.php?&amp;tbl_id='.$tbl_id.'&amp;showgallery=yes&amp;showinfos=&amp;newsite=yes&amp;" ><span class="main_url">'.$sub_tbl_names[$x].'</span></a></td>
          </tr>';
      }
      $foldernamelevel[$x] = $sub_tbl_names[$x];
    }
    $x++;
  }  
}else{
  // don't print the 'PICS_INDIVIDUAL'-table
  if(makeTblnameClear($tbl_name) != 'PICS_INDIVIDUAL')
    $table_link_txt .='
      <tr>
        <td>&#160;&#160;&#160;<a href="array_data.php?&amp;tbl_id='.$tbl_id.'&amp;showgallery=yes&amp;showinfos=&amp;newsite=yes&amp;" ><span class="main_url">'.makeTblnameClear($tbl_name).'</span></a></td>
      </tr>';
}
?>