<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
db_project_mgmt();
if (isset($_REQUEST['status'])) {

    $status = $_REQUEST['status'];

    function getUsernameByID(int $usernameID): string
    {
        $name = "";
        if (isset($usernameID) && $usernameID != '') {
            db();
            $getUsernameByUpdatedBySQL = db_query("SELECT name FROM loop_employees WHERE b2b_id = " . $usernameID . "");
            if (!empty($getUsernameByUpdatedBySQL)) {
                $getMeetingMinutesUsername = array_shift($getUsernameByUpdatedBySQL);
                $name = $getMeetingMinutesUsername['name'];
            }
            db_project_mgmt();
        }
        return $name;
    }
    db_project_mgmt();
    switch ($status) {
        case 'getCollapseTable':
            $meeting_id = isset($_REQUEST['meeting_id']) && $_REQUEST['meeting_id'] != '' ? $_REQUEST['meeting_id'] : '';
            if ($meeting_id != '') {
                $sql = db_query("SELECT * FROM `meeting_timer` WHERE meeting_id = " . $meeting_id . " ORDER BY id DESC");
                if (!empty($sql)) {
?>
                    <div class="accordion" id="meetingMinutesTable">
                        <?php
                        $meetingTimerCount = 1;
                        while ($meetings = array_shift($sql)) {
                            $meetingDate = isset($meetings['start_time']) ? date('m/d/Y', strtotime($meetings['start_time']))  : '';
                            $meetingStartTime = isset($meetings['start_time']) ? date('H:i', strtotime($meetings['start_time']))  : '';
                            $meetingEndTime = isset($meetings['end_time']) ? date('H:i', strtotime($meetings['end_time'])) : 'NA';
                        ?>
                            <div class="card mt-3">
                                <div class="card-header mb-0">
                                    <h5 class="mb-0">
                                        <button class="btn btn-collapse btn-block text-left <?php echo  $meetingTimerCount != 1 ? 'collapsed' : '' ?>" type="button" data-toggle="collapse" data-target="#meeting<?php echo $meetingTimerCount ?>" aria-expanded="true" aria-controls="meeting<?php echo $meetingTimerCount ?>">
                                            <span><i class="collapse-icon fa <?php echo  $meetingTimerCount == 1 ? 'fa-caret-down' : 'fa-caret-up' ?> " aria-hidden="true"></i> <b> Meeting: </b><?php echo $meetingDate ?> </span>
                                            <span class="float-right">
                                                <span><?php echo $meetingStartTime ?></span> - <span><?php echo $meetingEndTime ?></span>
                                            </span>
                                        </button>
                                    </h5>
                                </div>
                                <div id="meeting<?php echo $meetingTimerCount ?>" class="collapse <?php echo  $meetingTimerCount == 1 ? 'show' : '' ?>">
                                    <div class="card-body">
                                        <table class="table border-0">
                                            <thead>
                                                <tr>
                                                    <th class="meetingMinuteNoWrap">Date</th>
                                                    <th class="meetingMinuteNoWrap">User</th>
                                                    <th class="meetingMinuteNoWrap">Action</th>
                                                    <th class="meetingMinuteNoWrap">Notes</th>
                                                    <th class="meetingMinuteDetail"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($meetings['id']) && $meetings['id'] != '') {
                                                    db_project_mgmt();
                                                    $getMeetingMinutesSQL = db_query("SELECT meeting_minutes.* FROM `meeting_minutes` WHERE meeting_id =" . $meeting_id . " AND meeting_timer_id = " . $meetings['id'] . " ORDER BY meeting_minutes.id ASC");
                                                    // $getMeetingMinutesDataCount = 1;
                                                    if (!empty($getMeetingMinutesSQL)) {
                                                        while ($getMeetingMinutesData = array_shift($getMeetingMinutesSQL)) {
                                                            // if($getMeetingMinutesDataCount == 6){
                                                            $id = $getMeetingMinutesData['id'];
                                                            $date = date('m/d/Y H:i:s', strtotime($getMeetingMinutesData['updated_on']));
                                                            db();
                                                            $empDetails_qry = db_query("SELECT name from loop_employees where b2b_id='" . $getMeetingMinutesData['updated_by'] . "'");
                                                            $username = array_shift($empDetails_qry)['name'];
                                                            $action = $getMeetingMinutesData['action'];
                                                            $notes = $getMeetingMinutesData['notes'];
                                                            db_project_mgmt();

                                                            // Message
                                                            // if(isset($getMeetingMinutesData['update_on_id']) && $getMeetingMinutesData['update_on_id'] != 0){
                                                            if (isset($getMeetingMinutesData['update_on_id'])) {
                                                                $updateMsg = isset($getMeetingMinutesData['update_msg']) ? $getMeetingMinutesData['update_msg'] : 'NoMSGThere';
                                                                switch ($getMeetingMinutesData['action']) {
                                                                    case 'Create Measurable':
                                                                        // echo getdata('project_master','project_name',$getMeetingMinutesData['update_on_id']);
                                                                        $getMeaurableTitleSQL = db_query("SELECT name FROM `scorecard` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getMeaurableTitleSQL)) {
                                                                            $messageResult = array_shift($getMeaurableTitleSQL)['name'];
                                                                            $actionIconClass = 'fa fa-line-chart';
                                                                            $tableRowClass = 'tableUpdateRow';
                                                                        }
                                                                        break;
                                                                    case 'Create Project':
                                                                        // echo getdata('project_master','project_name',$getMeetingMinutesData['update_on_id']);
                                                                        $getProjectTitleSQL = db_query("SELECT project_name FROM `project_master` WHERE project_id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getProjectTitleSQL)) {
                                                                            $getProjectTitleUsername = array_shift($getProjectTitleSQL);
                                                                            $messageResult = $getProjectTitleUsername['project_name'];
                                                                            $actionIconClass = 'fa fa-plus-square';
                                                                            $tableRowClass = 'tableUpdateRow';
                                                                        }
                                                                        break;
                                                                    case 'Update Project':
                                                                        $getProjectTitleSQL = db_query("SELECT project_name FROM `project_master` WHERE project_id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getProjectTitleSQL)) {
                                                                            $getProjectTitleUsername = array_shift($getProjectTitleSQL);
                                                                            $messageResult = 'Update Project "' . $getProjectTitleUsername['project_name'] . '"<br> ' . $updateMsg;
                                                                            $actionIconClass = 'fa fa-plus-square';
                                                                            $tableRowClass = 'tableUpdateRow';
                                                                        }
                                                                        break;
                                                                    case 'Create Task':
                                                                        $getTaskTitleSQL = db_query("SELECT task_title FROM `task_master` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getTaskTitleSQL)) {
                                                                            $getTaskTitle = array_shift($getTaskTitleSQL);
                                                                            $messageResult = $getTaskTitle['task_title'];
                                                                            $actionIconClass = 'fa fa-check-square-o';
                                                                            $tableRowClass = 'tableUpdateRow';
                                                                        }
                                                                        break;
                                                                    case 'Update Task':
                                                                        $getTaskTitleSQL = db_query("SELECT task_title FROM `task_master` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getTaskTitleSQL)) {
                                                                            $getTaskTitle = array_shift($getTaskTitleSQL);
                                                                            $messageResult = "Update Task " . '"' . $getTaskTitle['task_title'] . '" <br>' . $updateMsg;
                                                                            $actionIconClass = 'fa fa-check-square-o';
                                                                            $tableRowClass = 'tableUpdateRow';
                                                                        }
                                                                        break;
                                                                    case 'Create Issue':
                                                                        $getIssueTitleSQL = db_query("SELECT issue FROM `issue_master` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getIssueTitleSQL)) {
                                                                            $getIssueTitle = array_shift($getIssueTitleSQL);
                                                                            $messageResult = $getIssueTitle['issue'];
                                                                            $actionIconClass = 'fa fa-exclamation-circle';
                                                                            $tableRowClass = 'tableIssueRow';
                                                                        }
                                                                        break;
                                                                    case 'Update Issue':
                                                                        $getIssueTitleSQL = db_query("SELECT issue FROM `issue_master` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getIssueTitleSQL)) {
                                                                            $getIssueTitle = array_shift($getIssueTitleSQL);
                                                                            $messageResult = 'Updated Issue "' . $getIssueTitle['issue'] . '" <br>' . $updateMsg;
                                                                            $actionIconClass = 'fa fa-exclamation-circle';
                                                                            $tableRowClass = 'tableIssueRow';
                                                                        }
                                                                        break;
                                                                    case 'Copy Issue':
                                                                        $getIssueTitleSQL = db_query("SELECT issue FROM `issue_master` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getIssueTitleSQL)) {
                                                                            $getIssueTitle = array_shift($getIssueTitleSQL);
                                                                            $messageResult = 'Copy Issue "' . $getIssueTitle['issue'] . '" <br>' . $updateMsg;
                                                                            $actionIconClass = 'fa fa-exclamation-circle';
                                                                            $tableRowClass = 'tableIssueRow';
                                                                        }
                                                                        break;
                                                                    case 'Ranking Issue':
                                                                        $getIssueTitleSQL = db_query("SELECT issue FROM `issue_master` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getIssueTitleSQL)) {
                                                                            $getIssueTitle = array_shift($getIssueTitleSQL);
                                                                            $messageResult = ' "' . $getIssueTitle['issue'] . '" <br>' . $updateMsg;
                                                                            $actionIconClass = 'fa fa-exclamation-circle';
                                                                            $tableRowClass = 'tableIssueRow';
                                                                        }
                                                                        break;
                                                                    case 'Matrics':
                                                                        $getMatricsNameSQL = db_query("SELECT name FROM `scorecard` WHERE id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getMatricsNameSQL)) {
                                                                            $getMatricsName = array_shift($getMatricsNameSQL);
                                                                            $messageResult = 'Updated To-do "' . $getMatricsName['name'] . '" ' . $updateMsg;
                                                                            $actionIconClass = 'fa fa-check-square-o';
                                                                            $tableRowClass = 'tableUpdateRow';
                                                                        }
                                                                        break;
                                                                    case "Rating":
                                                                        $getRatingSQL = db_query("SELECT attendee_id,rating FROM `meeting_start_atten_ratings`  WHERE meeting_start_atten_ratings.id = '" . $getMeetingMinutesData['update_on_id'] . "'");
                                                                        if (!empty($getRatingSQL)) {
                                                                            $getRating = array_shift($getRatingSQL);
                                                                            db();
                                                                            $empDetails_qry = db_query("SELECT name from loop_employees where b2b_id='" . $getRating['attendee_id'] . "'");
                                                                            $rating = isset($getRating['rating']) && $getRating['rating'] != '' ? $getRating['rating'] : '';
                                                                            $messageResult = "Rating= " . $rating . ' For Attendee "' . $getRating['name'] . '"';
                                                                            // $messageResult = $rating.' Rated by "'.getUsernameByID($getRating['attendee_id']).'"';
                                                                            $actionIconClass = 'fa fa-star-half-o';
                                                                            db_project_mgmt();
                                                                        }
                                                                        break;
                                                                    case "Start Meeting":
                                                                        $messageResult = '';
                                                                        $actionIconClass = 'fa fa-caret-right';
                                                                        $tableRowClass = 'tableStartMeetingRow';
                                                                        break;
                                                                    case "Join Meeting":
                                                                        $messageResult = $getMeetingMinutesData['notes'];
                                                                        $actionIconClass = 'fa fa-user';
                                                                        $tableRowClass = 'tableJoinMeetingRow';
                                                                        break;
                                                                    case "Meeting Ended":
                                                                        $messageResult = $getMeetingMinutesData['notes'];
                                                                        $actionIconClass = 'fa fa-sign-out';
                                                                        $tableRowClass = 'tableJoinMeetingRow';
                                                                        break;
                                                                    case "Left Meeting":
                                                                        $messageResult = $getMeetingMinutesData['notes'];
                                                                        $actionIconClass = 'fa fa-level-down';
                                                                        $tableRowClass = 'tableJoinMeetingRow';
                                                                        break;
                                                                    case "Change Page":
                                                                        $messageResult = $getMeetingMinutesData['notes'];
                                                                        /*if($getMeetingMinutesData['notes'] == 'Wrap-up'){
                                                                $tableRowClass = 'tableWrapUpRow';
                                                                $actionIconClass = 'fa fa-square';
                                                            }else{
                                                                $actionIconClass = 'fa fa-bookmark';
                                                            }*/
                                                                        $actionIconClass = 'fa fa-bookmark';
                                                                        $tableRowClass = 'tableChangePageRow';
                                                                        break;
                                                                    default:
                                                                        $messageResult = 'No Result';
                                                                        $actionIconClass = 'No';
                                                                        $tableRowClass = '';
                                                                        break;
                                                                }
                                                            }
                                                ?>
                                                            <tr class="<?php echo $tableRowClass ?>">
                                                                <td><span class="meetingMinuteNoWrap"><?php echo $date ?></span></td>
                                                                <td><span class="meetingMinuteNoWrap"><?php echo $username ?></span></td>
                                                                <td> <span class="meeting_minute_icon"><i class="<?php echo $actionIconClass ?>"></i></span></td>
                                                                <td><span class="meetingMinuteNoWrap"><b><?php echo $getMeetingMinutesData['action'] ?></b></span></td>
                                                                <td><?php echo isset($messageResult) ? $messageResult : '' ?></td>
                                                            </tr>
                                                <?php
                                                            // $getMeetingMinutesDataCount++;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $meetingTimerCount++;
                        }
                        ?>
                    </div>
<?php
                }
            }
            break;
    }
}
