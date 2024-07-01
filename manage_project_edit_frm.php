<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();
$rsql = db_query("SELECT * FROM `project_master` WHERE `project_id`= '" . $_REQUEST["id"] . "'");
$uprow = array_shift($rsql);

?>
<div class="close_box">
    <span
        onclick="document.getElementById('light').style.display ='none'; document.getElementById('fade').style.display='none'"
        class="closeic">&times;</span> &nbsp;<center></center><br />
</div>
<div ID="listdiv"
    STYLE="z-index:99;position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
<style>
#listdiv {
    left: auto !important;
    right: 50% !important;
    top: 450px !important;
}
</style>
<form action="manage_project.php" method="post" name="frmsort" id="frmsort" enctype="multipart/form-data">
    <div class="left_frm py-50">
        <div class="user__details">

            <div class="full_input__box">
                <span class="details">Project Title</span>
                <input size="10" type="text" name="project_title" id="project_title" value="<?php echo $uprow["
                    project_name"]; ?>">
            </div>
            <div class="full_input__box">
                <span class="details">Description</span>
                <?php

                require_once('fckeditor_new/fckeditor.php');
                $FCKeditor = new FCKeditor('txtdescription');
                $FCKeditor->BasePath = 'fckeditor_new/';
                $FCKeditor->Value = $uprow["project_description"];
                $FCKeditor->Height = "200";
                $FCKeditor->Width = "100%";
                $FCKeditor->Create();
                ?>
                <input type="hidden" name="project_desc" id="project_desc" value="" />
            </div>
            <div class="input__box">
                <span class="details">Select Owner</span>
                <select id="owner_id" name="owner_id" width="100%">
                    <option value="0">All</option>
                    <?php
                    $sql = "SELECT name, initials, employeeID FROM employees where status = 'Active' order by name";
                    db_b2b();
                    $result = db_query($sql);
                    while ($myrowsel = array_shift($result)) {
                        echo "<option value=" . $myrowsel["employeeID"] . " ";

                        if (isset($uprow["project_owner"])) {
                            if ($myrowsel["employeeID"] == $uprow["project_owner"]) echo " selected ";
                        } else {
                            if ($myrowsel["employeeID"] == $_COOKIE["b2b_id"]) echo " selected ";
                        }
                        echo " >" . $myrowsel["name"] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input__box">
                <span class="details">Department</span>
                <select id="dept_id" name="dept_id" width="100%">
                    <option value=""></option>
                    <?php

                    db();
                    $query = db_query("SELECT * FROM project_dept_master order by dept_order");
                    while ($rowsel_getdata = array_shift($query)) {
                        $tmp_str = "";
                        if (isset($uprow["project_dept_id"])) {
                            if ($uprow["project_dept_id"] == $rowsel_getdata["id"]) {
                                $tmp_str = " selected ";
                            }
                        }
                    ?>
                    <option value="<?php echo $rowsel_getdata[" id"]; ?>" <?php echo $tmp_str; ?>>
                        <?php echo $rowsel_getdata['dept_name']; ?>
                    </option>
                    <?php }
                    ?>
                </select>
            </div>
            <div class="input__box">

                <span class="details">Deadline</span>
                <div style="display:relative;">
                    <input type="text" name="deadline1" id="deadline1" size="20" readonly value="<?php echo $uprow["
                        project_deadline"]; ?>" class="dateinput_frm">

                    <a href="javascript:void(0);"
                        onclick="cal2.select(document.frmsort.deadline1,'dtanchor4xx','yyyy-MM-dd'); return false;"
                        name="dtanchor4xx" id="dtanchor4xx"><img border="0" src="images/calendar.jpg"></a>
                </div>
            </div>

            <div id="" class="input__box">
                <span class="details">Priority</span>
                <select id="priority_id" name="priority_id" width="100%">
                    <?php

                    db();
                    $query = db_query("SELECT * FROM project_priority_master order by unqid");
                    while ($rowsel_getdata = array_shift($query)) {
                        $tmp_str = "";
                        if (isset($uprow["project_priority_id"])) {
                            if ($uprow["project_priority_id"] == $rowsel_getdata["unqid"]) {
                                $tmp_str = " selected ";
                            }
                        }
                    ?>
                    <option value="<?php echo $rowsel_getdata[" unqid"]; ?>" <?php echo $tmp_str; ?>>
                        <?php echo $rowsel_getdata['priority_value']; ?>
                    </option>
                    <?php }
                    ?>
                </select>
            </div>

            <div id="" class="input__box">
                <span class="details">Status</span>
                <select id="pstatus_id" name="pstatus_id" width="100%">
                    <?php

                    db();
                    $query = db_query("SELECT * FROM task_status order by id");
                    while ($rowsel_getdata = array_shift($query)) {
                        $tmp_str = "";
                        if (isset($uprow["project_status_id"])) {
                            if ($uprow["project_status_id"] == $rowsel_getdata["id"]) {
                                $tmp_str = " selected ";
                            }
                        }
                    ?>
                    <option value="<?php echo $rowsel_getdata[" id"]; ?>" <?php echo $tmp_str; ?>>
                        <?php echo $rowsel_getdata['ts_name']; ?>
                    </option>
                    <?php }
                    ?>
                </select>
            </div>

            <div id="" class="input__box">
                <div class="full_input__box">
                    <span class="details">Supportive Documents</span>
                    <input type="file" name="uploadscanrep[]" id="uploadscanrep" multiple onchange="GetFileSize()">

                    <input type="hidden" name="project_edit" value="yes">
                    <input type="hidden" name="project_id" id="project_id" value="<?php echo $_REQUEST[" id"]; ?>">

                </div>
            </div>

            <div class="button_box" style="text-align: right;">

                <input type="submit" value="UPDATE" name="submit" style="cursor:pointer;" class="button_frm"
                    onclick="return frmedit_project_chk()">
            </div>


        </div>
    </div>
</form>