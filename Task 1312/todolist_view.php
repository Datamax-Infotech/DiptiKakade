<? 
require ("inc/header_session.php");
require ("mainfunctions/database.php");
require ("mainfunctions/general-functions.php");

db();

?>
	<table width="700" border="0" cellspacing="1" cellpadding="1">
		<tr align="center">
			<td colspan="5" bgcolor="#C0CDDA">
			<? if (isset($_REQUEST["fromrep"])){ ?>
				<font face="Arial, Helvetica, sans-serif" size="1">Tasks</font>
			<? }else { ?>
				<font face="Arial, Helvetica, sans-serif" size="1">Recently Completed Tasks</font>
			<? } ?>
			</td>
		</tr>
		<tr align="center">
			<td bgcolor="#C0CDDA">
				<font size="1">Task Name</font>
			</td>
			<td bgcolor="#C0CDDA">
				<font size="1">Assigned To</font>
			</td>
			<td bgcolor="#C0CDDA">
				<font size="1">Due Date</font>
			</td>
			<td bgcolor="#C0CDDA">
				<font size="1">Priority</font>
			</td>
			<td bgcolor="#C0CDDA">
				<font size="1">Completed</font>
			</td>
		</tr>
		<?
		if (isset($_REQUEST["fromdash"])){
			if ($_REQUEST["fromdash"] == "y"){
				$sql = "SELECT * FROM todolist where assign_to = '" . $_COOKIE["userinitials"] . "' and status = 2 order by due_date desc" ;
			}
		}else if (isset($_REQUEST["fromrep"])){
			if ($_REQUEST["fromrep"] == "y"){
				$sql = "SELECT * FROM todolist where companyid = ? order by due_date desc" ;
			}
		}else{
			$sql = "SELECT * FROM todolist where companyid = ? and status = 2 order by due_date desc" ;
		}		
		$result = db_query($sql , array("i"), array($_REQUEST["compid"]));
		while ($myrowsel = array_shift($result)) {
			$date1 = new DateTime($myrowsel["due_date"]);
			$date2 = new DateTime();

			$days = (strtotime($myrowsel["due_date"]) - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
			?>
				<tr align="center">
					<td bgcolor="#E4E4E4" align="left"><font size="1"><?=$myrowsel["task_name"]?></font></td>
					<td bgcolor="#E4E4E4" ><font size="1"><?=$myrowsel["assign_to"]?></font></td>

					<? 
					if ($myrowsel["status"] == 2) {
					?>
							<td bgcolor="#E4E4E4" ><font size="1"><? echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT";?></font></td>
					<?
					}else{
					
						if ($days == 0){?>
							<td bgcolor="green" ><font size="1"><? echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT";?></font></td>
						<? }

							if ($days > 0){?>
							<td bgcolor="#E4E4E4" ><font size="1"><? echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT";?></font></td>
						<? }
						
							if ($days < 0){?>
							<td bgcolor="red" ><font size="1"><? echo date("m/d/Y", strtotime($myrowsel["due_date"])) . " CT";?></font></td>
						<? }?>
					<? }?>
					
					<td bgcolor="#E4E4E4" ><font size="1"><?=$myrowsel["task_priority"]?></font></td>
					<? if ($myrowsel["mark_comp_on"] != ""){ ?>
						<td bgcolor="#E4E4E4" ><font size="1"><? echo $myrowsel["mark_comp_by"] . " " . date("m/d/Y", strtotime($myrowsel["mark_comp_on"])) . " CT";?></font></td>
					<? } else {?>
						<td bgcolor="#E4E4E4" ><font size="1">&nbsp;</font></td>					
					<? } ?>
				</tr>	
			<?
		}
		?>
	</table>	
	
