<?php
$db = "usedcard_b2b"; //usedcard_testforbackup
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

//require ("inc/databaseb2b.php");
include("../../securedata/config_forbkp.php");

ini_set("display_errors", "1");
error_reporting(E_ALL);

$savePermissions = 0664; // Save files with the following permissions

$dumpDir = '../../bkpfiles/dbfiles' . "/" . date('m-d-Y');
if (!is_dir($dumpDir)) {
    $mr = @mkdir($dumpDir, 0755, true);
    if (!$mr) {
        errorMessage('Cannot create the Repository ' . $dumpDir);
        return false;
    }
}

$tbls = db_query_b2b_n('SHOW TABLE STATUS FROM `' . $db . '`');
$existingDBs = array();

while ($row = array_shift($tbls)) {
    $tblName = $row['Name'];
    $tblUpdate = $row['Update_time'];

    $cssql = db_query_b2b_n('CHECKSUM TABLE `' . $tblName . '`');

    while ($csrow = array_shift($cssql)) $tblChecksum = $csrow['Checksum'];

    if (isset($tblChecksum) == NULL) $tblChecksum = 0;

    if ($row['Engine'] == NULL) $row['Engine'] = 'View';

    elseif ($tblChecksum != 0 && is_file($dumpDir . '/B2b_' . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz')) {
        debug('- Repo version of ' . $db . '.' . $tblName . ' is current (' . $row['Engine'] . ')');
    } else {
        debug('+ Backing up of ' . $db . '.' . $tblName . ' (' . $row['Engine'] . ') <br/>');

        $dump_options = array(
            '-C', // Compress connection
            '-h' . isset($dbhost), // Host
            '-u' . isset($dbuserb2b), // User
            '-p' . isset($dbpwdb2b), // Password
            '--compact --add-drop-table ' // no need to database info for every table
        );

        if (strtolower($row['Engine']) == 'csv') {
            debug('- Skipping table locks for CSV table ' . $db . '.' . $tblName);
            array_push($dump_options, '--skip-lock-tables');
        } elseif (strtolower($row['Engine']) == 'memory') {
            debug('- Ignoring data for Memory table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        } elseif (strtolower($row['Engine']) == 'view') {
            debug('- Ignoring data for View table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        }

        //$temp = tempnam(sys_get_temp_dir(), 'sqlbackup-');
        $temp = $dumpDir . "/B2b_" . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz';

        //$exec = passthru("mysqldump " . implode($dump_options, ' ') . ' ' . $db . ' ' . $tblName . ' | gzip -v> ' . $temp);
        $exec = passthru("mysqldump " . implode(' ', $dump_options) . ' ' . $db . ' ' . $tblName . ' | gzip -v> ' . $temp);
        if ($exec != '') {
            //@unlink($temp);
            mail("prasad@extractinfo.com", "UCB database backup fail: " . date('l, F j, Y'), "<html><body>Following table has not been backuped: " . $temp . ' ' . $exec . "<br/></body></html>", "Content-type: text/html; charset=iso-8859-1\r\nFrom: prasad@extractinfo.com\n");
            errorMessage('Unable to dump file to ' . $temp . ' ' . $exec);
        } else {
            // Make sure only complete files get saved 
            //chmod($temp, $savePermissions);
            //rename($temp, $dumpDir.'/'.$row['Name'].'.'.$tblChecksum.'.'.strtolower($row['Engine']).'.sql');
            // Set the file timestamp if supported 
            //if(!is_null($row['Update_time']))
            //@touch($dumpDir.'/'.$row['Name'].'.'.$tblChecksum.'.'.strtolower($row['Engine']).'.sql', strtotime($row['Update_time']));
        }
    }
}

//require ("inc/database.php");
// for production database
$db = "usedcard_production"; //usedcard_testforbackup
$tbls = db_query_n('SHOW TABLE STATUS FROM `' . $db . '`');
$existingDBs = array();

while ($row = array_shift($tbls)) {
    $tblName = $row['Name'];
    $tblUpdate = $row['Update_time'];

    $cssql = db_query_n('CHECKSUM TABLE `' . $tblName . '`');

    while ($csrow = array_shift($cssql)) $tblChecksum = $csrow['Checksum'];

    if (isset($tblChecksum) == NULL) $tblChecksum = 0;

    if ($row['Engine'] == NULL) $row['Engine'] = 'View';

    elseif ($tblChecksum != 0 && is_file($dumpDir . '/Prod_' . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz')) {
        debug('- Repo version of ' . $db . '.' . $tblName . ' is current (' . $row['Engine'] . ')<br/>');
    } else {
        debug('+ Backing up of ' . $db . '.' . $tblName . ' (' . $row['Engine'] . ') <br/>');

        $dump_options = array(
            '-C', // Compress connection
            '-h' . isset($dbhost), // Host
            '-u' . isset($dbuser), // User
            '-p' . isset($dbpwd), // Password
            '--compact --add-drop-table' // no need to database info for every table
        );

        if (strtolower($row['Engine']) == 'csv') {
            debug('- Skipping table locks for CSV table ' . $db . '.' . $tblName);
            array_push($dump_options, '--skip-lock-tables');
        } elseif (strtolower($row['Engine']) == 'memory') {
            debug('- Ignoring data for Memory table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        } elseif (strtolower($row['Engine']) == 'view') {
            debug('- Ignoring data for View table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        }

        //$temp = tempnam(sys_get_temp_dir(), 'sqlbackup-');
        $temp = $dumpDir . "/Prod_" . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz';

        //$exec = passthru("mysqldump " . implode($dump_options, ' ') . ' ' . $db . ' ' . $tblName . ' | gzip -v> ' . $temp);
        $exec = passthru("mysqldump " . implode(' ', $dump_options) . ' ' . $db . ' ' . $tblName . ' | gzip -v> ' . $temp);
        if ($exec != '') {
            //@unlink($temp);
            mail("prasad@extractinfo.com", "UCB database backup fail: " . date('l, F j, Y'), "<html><body>Following table has not been backuped: " . $temp . ' ' . $exec . "<br/></body></html>", "Content-type: text/html; charset=iso-8859-1\r\nFrom: prasad@extractinfo.com\n");
            errorMessage('Unable to dump file to ' . $temp . ' ' . $exec);
        }
    }
}

// for email database
$db = "usedcard_ucbmail"; //usedcard_testforbackup
$tbls = db_query_eml("SHOW TABLE STATUS FROM `$db` where Name <> 'tblemail_body_txt'");
$existingDBs = array();

while ($row = array_shift($tbls)) {
    $tblName = $row['Name'];
    $tblUpdate = $row['Update_time'];

    $cssql = db_query_eml('CHECKSUM TABLE `' . $tblName . '`');

    while ($csrow = array_shift($cssql)) $tblChecksum = $csrow['Checksum'];

    if (isset($tblChecksum) == NULL) $tblChecksum = 0;

    if ($row['Engine'] == NULL) $row['Engine'] = 'View';

    elseif ($tblChecksum != 0 && is_file($dumpDir . '/Email_' . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz')) {
        debug('- Repo version of ' . $db . '.' . $tblName . ' is current (' . $row['Engine'] . ')<br/>');
    } else {
        debug('+ Backing up of ' . $db . '.' . $tblName . ' (' . $row['Engine'] . ') <br/>');

        $dump_options = array(
            '-C', // Compress connection
            '-h' . isset($dbhost_email), // Host
            '-u' . isset($dbuser_email), // User
            '-p' . isset($dbpwd_email), // Password
            '--compact --add-drop-table' // no need to database info for every table
        );

        if (strtolower($row['Engine']) == 'csv') {
            debug('- Skipping table locks for CSV table ' . $db . '.' . $tblName);
            array_push($dump_options, '--skip-lock-tables');
        } elseif (strtolower($row['Engine']) == 'memory') {
            debug('- Ignoring data for Memory table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        } elseif (strtolower($row['Engine']) == 'view') {
            debug('- Ignoring data for View table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        }

        //$temp = tempnam(sys_get_temp_dir(), 'sqlbackup-');
        $temp = $dumpDir . "/Email_" . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz';

        //echo "mysqldump " . implode($dump_options, ' ').' '.$db.' '.$tblName.' | gzip -v> '.$temp;

        $exec = passthru("mysqldump " . implode(' ', $dump_options) . ' ' . $db . ' ' . $tblName . ' | gzip -v> ' . $temp);

        if ($exec != '') {
            //@unlink($temp);
            mail("prasad@extractinfo.com", "UCB Email database backup fail: " . date('l, F j, Y'), "<html><body>Following table has not been backuped: " . $temp . ' ' . $exec . "<br/></body></html>", "Content-type: text/html; charset=iso-8859-1\r\nFrom: prasad@extractinfo.com\n");
            errorMessage('Unable to dump file to ' . $temp . ' ' . $exec);
        }
    }
}

// for b2c email database
$db = "usedcard_b2c_email"; //usedcard_testforbackup
$tbls = db_query_eml_b2c("SHOW TABLE STATUS FROM `$db` where Name <> 'tblemail_body_txt'");
$existingDBs = array();

while ($row = array_shift($tbls)) {
    $tblName = $row['Name'];
    $tblUpdate = $row['Update_time'];

    $cssql = db_query_eml_b2c('CHECKSUM TABLE `' . $tblName . '`');

    while ($csrow = array_shift($cssql)) $tblChecksum = $csrow['Checksum'];

    if (isset($tblChecksum) == NULL) $tblChecksum = 0;

    if ($row['Engine'] == NULL) $row['Engine'] = 'View';

    elseif ($tblChecksum != 0 && is_file($dumpDir . '/B2C_Email_' . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz')) {
        debug('- Repo version of ' . $db . '.' . $tblName . ' is current (' . $row['Engine'] . ')<br/>');
    } else {
        debug('+ Backing up of ' . $db . '.' . $tblName . ' (' . $row['Engine'] . ') <br/>');

        $dump_options = array(
            '-C', // Compress connection
            '-h' . isset($dbhost_email_b2c), // Host
            '-u' . isset($dbuser_email_b2c), // User
            '-p' . isset($dbpwd_email_b2c), // Password
            '--compact --add-drop-table' // no need to database info for every table
        );

        if (strtolower($row['Engine']) == 'csv') {
            debug('- Skipping table locks for CSV table ' . $db . '.' . $tblName);
            array_push($dump_options, '--skip-lock-tables');
        } elseif (strtolower($row['Engine']) == 'memory') {
            debug('- Ignoring data for Memory table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        } elseif (strtolower($row['Engine']) == 'view') {
            debug('- Ignoring data for View table ' . $db . '.' . $tblName);
            array_push($dump_options, '--no-data');
        }

        //$temp = tempnam(sys_get_temp_dir(), 'sqlbackup-');
        $temp = $dumpDir . "/B2C_Email_" . $tblName . '.' . date('m-d-Y') . '_' . date('h.i.s') . '.sql.gz';

        //echo "mysqldump " . implode($dump_options, ' ').' '.$db.' '.$tblName.' | gzip -v> '.$temp;

        $exec = passthru("mysqldump " . implode(' ', $dump_options) . ' ' . $db . ' ' . $tblName . ' | gzip -v> ' . $temp);

        if ($exec != '') {
            //@unlink($temp);
            mail("prasad@extractinfo.com", "UCB Email database backup fail: " . date('l, F j, Y'), "<html><body>Following table has not been backuped: " . $temp . ' ' . $exec . "<br/></body></html>", "Content-type: text/html; charset=iso-8859-1\r\nFrom: prasad@extractinfo.com\n");
            errorMessage('Unable to dump file to ' . $temp . ' ' . $exec);
        }
    }
}

// @closedir($dumpDir);
$dirHandle = @opendir($dumpDir);

if ($dirHandle) {
    @closedir($dirHandle);
}
function errorMessage(string $msg): void
{
    echo $msg . "\n";
}

function debug(string $msg): void
{
    echo $msg . "\n";
}

function db_query_b2b_n(string $query): array|false
{
    db_b2b();
    $result = db_query($query);
    if (!$result) {
        //echo mysql_error();
        return false;
    }
    return $result;
}

function db_query_n(string $query): array|false
{
    db();
    $result = db_query($query);
    if (!$result) {
        //echo mysql_error();
        return false;
    }
    return $result;
}

function db_query_eml(string $query): array|false
{
    db_email();
    $result = db_query($query);
    if (!$result) {
        //echo mysql_error();
        return false;
    }
    return $result;
}

function db_query_eml_b2c(string $query): array|false
{
    db_b2c_email_new();
    $result = db_query($query);
    if (!$result) {
        //echo mysql_error();
        return false;
    }
    return $result;
}
