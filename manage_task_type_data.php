<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


if ($_REQUEST['proc'] == "Project") {

    db();
    $sql = db_query("SELECT project_id, project_name FROM project_master WHERE project_status_id != 6");
    echo '<span class="details">Select Project</span>';
    echo '<select name="task_type_id" width="100%">';
    while ($pr = array_shift($sql)) {
        echo '<option value="' . $pr["project_id"] . '">' . $pr["project_name"] . '</option>';
    }
    echo '</select>';
}

if ($_REQUEST['proc'] == "Loop") {

    db_b2b();
    $sql = db_query("Select ID, company, nickname from companyInfo Where `status` IN (32,55,56,38,50,51,52,65,70,73) AND company != '' AND nickname != '' order by nickname");
    echo '<span class="details">Select Company</span>';
    echo '<select name="task_type_id" width="100%">';
    while ($pr = array_shift($sql)) {
        $cname = "";
        if ($pr["nickname"] == "") {
            $cname = $pr["company"];
        } else {
            $cname = $pr["nickname"];
        }
        echo '<option value="' . $pr["ID"] . '">' . $cname . '</option>';
    }
    echo '</select>';
}

if ($_REQUEST['proc'] == "Meeting") {

    echo '<span class="details">Select Meeting</span>';
    echo '<select name="task_type_id" width="100%">';
    echo '<option value="">Yet To Define</option>';
    echo '</select>';
}

if ($_REQUEST['proc'] == "Dependency") {

    db();
    $sql = db_query("SELECT id, task_title FROM task_master WHERE task_status = 1");
    echo '<span class="details">Select Task</span>';
    echo '<select name="task_dependency_id" width="100%">';
    while ($pr = array_shift($sql)) {
        echo '<option value="' . $pr["id"] . '">' . $pr["task_title"] . '</option>';
    }
    echo '</select>';
}