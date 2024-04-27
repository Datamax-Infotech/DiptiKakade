<?php
require_once("mainfunctions/database.php");
require_once("mainfunctions/general-functions.php");
require_once("inc/functions_mysqli.php"); 
$mysqli = new mysqli("localhost", "root", "", "ucbdata_usedcard_production");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to get company nickname
function getnickname($warehouse_name, $b2bid){
	$nickname = "";
	if ($b2bid > 0) {
		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
		$result_comp = db_query($sql,db_b2b() );
		while ($row_comp = array_shift($result_comp)) {
			if ($row_comp["nickname"] != "") {
				$nickname = $row_comp["nickname"];
			}else {
				$tmppos_1 = strpos($row_comp["company"], "-");
				if ($tmppos_1 != false)
				{
					$nickname = $row_comp["company"];
				}else {
					if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "" ) 
					{
						$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"] ;
					}else { $nickname = $row_comp["company"]; }
				}
			}
		}
	}else {
		$nickname = $warehouse_name;
	}
	
	return $nickname;
}	

// Get the selected user's initials from the request
$userInitials = $_GET['user_initials'];

// Default query to fetch tasks for the selected user's initials
$sql = "SELECT * FROM todolist WHERE assign_to = ? AND status = 1 ORDER BY due_date DESC LIMIT 5";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $userInitials);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Generate the HTML for the table
$tableHtml = '<table width="700" border="0" cellspacing="1" cellpadding="1">
<tr align="center">
<td colspan="8" bgcolor="#C0CDDA">
    <font face="Arial, Helvetica, sans-serif" size="1">Active Tasks </font>
</td>
</tr>
                <tr align="center">
                    <th bgcolor="#C0CDDA">Company</th>
                    <th bgcolor="#C0CDDA">Task Name</th>
                    <th bgcolor="#C0CDDA">Created By</th>
                    <th bgcolor="#C0CDDA">Assigned To</th>
                    <th bgcolor="#C0CDDA">Created On</th>
                    <th bgcolor="#C0CDDA">Due Date</th>
                    <th bgcolor="#C0CDDA">Priority</th>
                    <th bgcolor="#C0CDDA">Marking</th>
                </tr>';

while ($myRowSel = $result->fetch_assoc()) {
    // Generate table rows with task data
    $company = htmlspecialchars($myRowSel['company']);
    $taskName = htmlspecialchars($myRowSel['task_name']);
    $createdBy = htmlspecialchars($myRowSel['task_created_by']);
    $assignedTo = htmlspecialchars($myRowSel['assign_to']);
    $createdOn = htmlspecialchars($myRowSel['task_added_on']);
    $dueDate = htmlspecialchars($myRowSel['due_date']);
    $priority = htmlspecialchars($myRowSel['task_priority']);

    $tableHtml .= "<tr align='center'>
                       
                        <td bgcolor='#E4E4E4' align='left'><font size='1'><a target='_blank' href='viewCompany.php?ID=" . $myRowSel["companyid"] . "'>" . getNickname('', $myRowSel["companyid"]) . "</a></font></td>
                        <td bgcolor='#E4E4E4' align='left'><font size='1'>$taskName</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>$createdBy</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>$assignedTo</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>$createdOn</font></td>";

    // Determine the color based on due date
    $days = (strtotime($dueDate) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
    if ($days == 0) {
        $tableHtml .= "<td bgcolor='green'><font size='1'>$dueDate</font></td>";
    } elseif ($days > 0) {
        $tableHtml .= "<td bgcolor='#E4E4E4'><font size='1'>$dueDate</font></td>";
    } elseif ($days < 0) {
        $tableHtml .= "<td bgcolor='red'><font size='1'>$dueDate</font></td>";
    }

    $tableHtml .= "<td bgcolor='#E4E4E4'><font size='1'>$priority</font></td>
                    <td bgcolor='#E4E4E4' align='middle'>
                        <input type='button' value='Mark Complete' name='todo_markcompl' id='todo_markcompl' onclick='todoitem_markcomp_dash(" . $myRowSel["unqid"] . ")'>
                    </td>
                  
                </tr>";
}

$tableHtml .= '</table>';

// Close the connection
$mysqli->close();

// Send the table HTML as the response
echo $tableHtml;
?>
