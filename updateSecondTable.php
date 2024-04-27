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
$sql = "SELECT * FROM todolist WHERE assign_to = ? AND status = 2 ORDER BY due_date DESC LIMIT 5";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $userInitials);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();


$tableHtml = '<table width="700" border="0" cellspacing="1" cellpadding="1">
                <tr align="center">
                <td colspan="7" bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Recently Completed Tasks</font>
                </td>
                </tr>
                <tr align="center">
                    <th bgcolor="#C0CDDA">Company</th>
                    <th bgcolor="#C0CDDA">Task Name</th>
                    <th bgcolor="#C0CDDA">Assigned To</th>
                    <th bgcolor="#C0CDDA">Created On</th>
                    <th bgcolor="#C0CDDA">Due Date</th>
                    <th bgcolor="#C0CDDA">Priority</th>
                    <th bgcolor="#C0CDDA">Completed by and On</th>
                </tr>';

while ($myRowSel = $result->fetch_assoc()) {
    // Generate table rows with task data
    $company = htmlspecialchars($myRowSel['company']);
    $taskName = htmlspecialchars($myRowSel['task_name']);
    $assignedTo = htmlspecialchars($myRowSel['assign_to']);
    $createdOn = htmlspecialchars($myRowSel['task_added_on']);
    $dueDate = date("m/d/Y", strtotime($myRowSel['due_date'])); // Format the due date

    $tableHtml .= "<tr align='center'>
                       
                        <td bgcolor='#E4E4E4' align='left'><font size='1'><a target='_blank' href='viewCompany.php?ID=" . $myRowSel["companyid"] . "'>" . getNickname('', $myRowSel["companyid"]) . "</a></font></td>
                        <td bgcolor='#E4E4E4' align='left'><font size='1'>" . $myRowSel["task_name"] . "</font></td><td bgcolor='#E4E4E4'><font size='1'>$assignedTo</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>$createdOn</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>$dueDate CT</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>"
                         . $myRowSel["task_priority"] ."</font></td>
                        <td bgcolor='#E4E4E4'><font size='1'>" . $myRowSel["mark_comp_by"] . " " . date("m/d/Y", strtotime($myRowSel["mark_comp_on"])) . " CT" . "</font></td> 
                       
                      
                    </tr>";
}

// Add the "Show All" button after all the task data rows
$tableHtml .= '<tr align="center">
                    <td colspan="7" bgcolor="#C0CDDA">
                    
                        <input type="button" id="todoshowall" onclick="todoitem_showall(\'' . $userInitials . '\')" value="Show All"/>
                    </td>
                </tr>';

$tableHtml .= '</table>';

// Close the connection
$mysqli->close();

// Send the table HTML as the response
echo $tableHtml;
?>



<script>
    function showAllRecords() {
        // Send AJAX request to fetch all records for the selected user's initials
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetchAllRecords.php?user_initials=<?= $userInitials ?>', true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Display the fetched records in a modal dialog
                var modalContent = xhr.responseText;
                // Create modal HTML content
                var modalHtml = '<div id="myModal" class="modal">' +
                                    '<div class="modal-content">' +
                                        '<span class="close">&times;</span>' +
                                        modalContent +
                                    '</div>' +
                                '</div>';
                // Append modal HTML content to the body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                // Get the modal element
                var modal = document.getElementById("myModal");
                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];
                // When the user clicks on <span> (x), close the modal
                span.onclick = function() {
                    modal.style.display = "none";
                    modal.remove(); // Remove modal element from DOM
                }
                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                        modal.remove(); // Remove modal element from DOM
                    }
                }
                // Display the modal
                modal.style.display = "block";
            }
        };
        xhr.send();
    }
</script>
