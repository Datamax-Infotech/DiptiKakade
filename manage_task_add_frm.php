<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

?>

<div class="close_box">
    <span
        onclick="document.getElementById('light').style.display ='none'; document.getElementById('fade').style.display='none'"
        class="closeic">&times;</span> &nbsp;<center></center>
</div>
<form action="manage_task.php" method="post" name="frmtask" id="frmtask" enctype="multipart/form-data">
    <div class="left_frm py-50">
        <div class="user__details">

            <div class="full_input__box">
                <span class="details">Task Title</span>
                <input size="10" type="text" name="task_title" id="task_title" value="">
            </div>
            <div class="full_input__box">
                <span class="details">Description</span>
                <?php
                //task_master new table created.
                require_once('fckeditor_new/fckeditor.php');
                $FCKeditor = new FCKeditor('txtdescription');
                $FCKeditor->BasePath = 'fckeditor_new/';
                $FCKeditor->Value = "";
                $FCKeditor->Height = "200";
                $FCKeditor->Width = "100%";
                $FCKeditor->Create();

                ?>
                <input type="hidden" name="task_desc" id="task_desc" value="" />
            </div>
            <div class="input__box">
                <span class="details">Assign To</span>
                <select id="assignto" name="assignto" width="100%">

                    <?php
                    $sql = "SELECT name, initials, employeeID FROM employees where status = 'Active' order by name";
                    db_b2b();
                    $result = db_query($sql);
                    while ($myrowsel = array_shift($result)) {
                        echo "<option value=" . $myrowsel["employeeID"] . " ";

                        if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";

                        echo " >" . $myrowsel["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input__box">

                <span class="details">Due Date</span>
                <div class="light_calbox123" style="display:relative;">
                    <input type="text" name="duedate" id="duedate" size="20" readonly value="" class="dateinput_frm">

                    <a href="javascript:void(0);"
                        onclick="cal2.select(document.frmtask.duedate,'dtanchor4xx','yyyy-MM-dd'); return false;"
                        name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
                </div>
            </div>

            <div class="input__box">
                <span class="details">Priority</span>
                <select id="task_priority" name="task_priority" width="100%">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="input__box">
                <span class="details">Stage</span>
                <select id="task_stage" name="task_stage" width="100%">
                    <option value="On Track">On Track</option>
                    <option value="At Risk">At Risk</option>
                    <option value="Off Track">Off Track</option>
                </select>
            </div>

            <div class="input__box">
                <span class="details">Task Type</span>
                <select id="task_type" name="task_type" width="100%" onchange="showtask_type(this.value)">
                    <option value="Private">Private</option>
                    <option value="Loop">Loop</option>
                    <option value="Project">Project</option>
                    <option value="Meeting">Meeting</option>
                </select>
            </div>
            <div class="input__box">
                <div id="task_type_hide" style="display:none;"></div>
            </div>
            <div class="input__box">
                <span class="details">Dependency</span>
                <select id="task_dependency" name="task_dependency" width="100%"
                    onchange="showtask_dependency(this.value)">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
                <input type="hidden" name="task_New" value="ADD">
            </div>
            <div class="input__box" id="">
                <div id="task_depd_hide" style="display:none;"></div>
            </div>

            <div class="button_box" style="text-align: right;">

                <input type="submit" value="SAVE" name="submit" style="cursor:pointer;" class="button_frm"
                    onclick="return frm_add_task_chk()">&nbsp;<input type="button" value="RESET" onclick="resetctrls()"
                    style="cursor:pointer;" class="button_frm">
            </div>

        </div>
    </div>
</form>