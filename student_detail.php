<?php
session_start();
include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include 'get_client_name.php';
include 'format_case_number.php';
$id = $_GET['id'];
$get_student_info = mysql_query("SELECT * FROM `cm_users` WHERE `id` = '$id' LIMIT 1");
while ($line = mysql_fetch_array($get_student_info, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_student_info,$i);
        $d[$field] = $col_value;
        $i++;


    }
    }
echo <<<DETAIL

<span id="close" style="right:35px;"><a href="#" onclick="Effect.Shrink('window1');return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small_blue.png" border="0"></a></span>
<div id="container">
<div id="photo">
<h3>$d[first_name] $d[last_name]</h3>
<table border="0" width="100%">
<tr><td width="50%">
<img src="$d[picture_url]" border="0"></td><td width="50%" align="center" valign="center">
<div id="msg_notify" style="display:none;">

</div>
<a href="#" title="Send $d[first_name] $d[last_name] a message" alt="Send $d[first_name] $d[last_name] a message" onClick="Effect.Grow('messaging_window');createTargets('messaging_window','messaging_window');sendDataGet('message_new.php?direct=$d[username]&direct_full=$d[first_name] $d[last_name]');return false;"><img src="images/new_msg_blue.png" border="0"></a></td></tr></table>
<table>
<tr><td><label>Last Login</td><td>
DETAIL;
$last_login = mysql_query("SELECT * FROM `cm_logs` WHERE `username` = '$d[username]' ORDER BY `timestamp` DESC LIMIT 1");
$r = mysql_fetch_array($last_login);
if (mysql_num_rows($last_login)<1)
{echo "Never";}
else
{
formatDate($r[timestamp]);
}
ECHO <<<DETAIL

</td></tr>
DETAIL;

if ($_SESSION['class'] == 'admin')
{
$get_prof_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$d[assigned_prof]' LIMIT 1");
while ($z = mysql_fetch_array($get_prof_name))
{
echo "<tr><td><label>Professor:</td><td>$z[last_name]</td></tr>";
}
}
ECHO <<<DETAIL

<tr><td><label>Email</td><td><a href="mailto:$d[email]">$d[email]</a></td></tr>
<tr><td><label>Mobile Phone</td><td>$d[mobile_phone]</td></tr>
<tr><td><label>Home Phone</td><td>$d[home_phone]</td></tr>
</table>

</div>

<div id="detail_activity">
<div style="width:99%; height:20px; background-color:rgb(195, 217, 255);color:white;"><b>Latest Activity</b></div>
<TABLE WIDTH="99%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="15%">Date</td><td width="15%">Time Spent</td><td width="20%">Case Name</td><td width="50%">Activity</td></tr></thead></table>
DETAIL;


$show_notes = mysql_query("SELECT * FROM `cm_case_notes` WHERE `username` = '$d[username]' ORDER BY `date` DESC LIMIT 10");
while ($line = mysql_fetch_array($show_notes, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($show_notes,$i);
        $e[$field] = $col_value;
        $i++;
    }



echo <<<ACTIVITY

<div style="width:100%;height:90px;background:url(images/grade.jpg) repeat-x;">
<TABLE WIDTH="99%">
<TR><TD WIDTH="15%" STYLE="PADDING-RIGHT:3.5%;" valign="top">
ACTIVITY;

formatDateNoTime($e[date]);

ECHO <<<ACTIVITY

</td><TD WIDTH="15%" STYLE="PADDING-RIGHT:3.5%;" valign="top">
ACTIVITY;
list($new_time,$the_unit) = formatTime($e[time]);
echo "$new_time" . " " . "$the_unit";


ECHO "</TD><td WIDTH='20%' valign='top'>";

if ($e[case_id] == 'NC')
{echo "Non-Case";}
else
{
getClient($e[case_id]);
}
echo <<<ACTIVITY

</td><td WIDTH="50%"><div style="height:88px;overflow:auto">$e[description]</div></td></tr></table>
</div>

ACTIVITY;

}
if (mysql_num_rows($show_notes)<1)
{echo "Student has no activity.";}
?>
</div>

</div>


<div id="detail_events">
<div style="width:100%; height:20px; background-color:rgb(195, 217, 255);text-align:left;color:white;"><b>Upcoming Events</b></div>
<TABLE WIDTH="99%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="15%">Date Due</td><td width="15%">Status</td><td width="20%">Case Name</td><td width="50%">To Be Done</td></tr></thead></table>
<?php
$show_events = mysql_query("SELECT * FROM `cm_events`,`cm_events_responsibles`  WHERE cm_events.id = cm_events_responsibles.event_id AND cm_events_responsibles.username = '$d[username]' ORDER BY cm_events.date_due DESC LIMIT 10");
while ($line = mysql_fetch_array($show_events, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($show_events,$i);
        $f[$field] = $col_value;
        $i++;
    }






if ($f[status] == 'Done')
{
ECHO <<<DONE

<div style="width:99%;height:40px;background:url(images/grade_gray_small.jpg) repeat-x;">
DONE;

}
ELSE
{   
ECHO <<<NOTDONE
<div style="width:99%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
NOTDONE;

}

ECHO <<<EVENT

<TABLE WIDTH="99%">

<TR><TD WIDTH="15%" valign="top"

EVENT;


if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};


formatDate2($f[date_due]);
echo <<<EVENT
</td><td width="15%"  valign="top"
EVENT;

if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
ECHO <<<EVENT

$f[status]</td><td width="20%" valign="top"
EVENT;


if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};

getClient($f[case_id]);
ECHO <<<EVENT
</td><td width="50%"
EVENT;

if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
ECHO <<<EVENT
<div style="height:35px;overflow:auto;">$f[task]</div></td></tr></table></div>
EVENT;
}
if (mysql_num_rows($show_events)<1)
{echo "No events have been assigned to this student.";}


?>
</div>

<div id="detail_cases">
<div style="width:100%; height:20px; background-color:rgb(195, 217, 255);color:white;text-align:left;"><b>Assigned Cases</b></div>
<?
ECHO <<<CASES
<TABLE WIDTH="99%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="25%">Case Number</td><td width="40%">Case Name</td><td width="25%">Case Type</td><td width="10%"></td></tr></thead></table>
CASES;

$get_cases = mysql_query("SELECT * FROM `cm_cases_students` WHERE `username` = '$d[username]' AND `status` = 'active'");
while ($loop = mysql_fetch_array($get_cases))
{

$case_info = mysql_query("SELECT * FROM `cm` WHERE `id` = '$loop[case_id]'");
while ($loop2 = mysql_fetch_array($case_info))
{
ECHO <<<CASES
<div style="width:99%;height:30px;background:url(images/grade_small.jpg) repeat-x;">
<TABLE width="99%">
<TR><TD WIDTH = "25%">
CASES;
formatCaseNo($loop2[id]);
ECHO <<<CASES
</td><td width="40%">$loop2[first_name] $loop2[last_name]</td><td width = "25%">$loop2[case_type]</td><td width="10%" id="reply_$loop2[id]"><a href="#" onClick="var check=confirm('Do you wish to remove $d[first_name] $d[last_name] from the $loop2[first_name] $loop2[last_name] case?');if (check == true){createTargets('reply_$loop2[id]','reply_$loop2[id]');sendDataGet('student_remove_from_case.php?case_id=$loop2[id]&username=$d[username]');}">Unassign</a></td></tr></table></div>

CASES;
}

}
if (mysql_num_rows($get_cases)<1)
{echo "No cases have been assigned to this student.";}

?>
</div>



