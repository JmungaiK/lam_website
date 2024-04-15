<?php
// Start session to check user authentication
session_start();

// Include the admin header
include_once '../components/admin/admin_header.php';

// Include the admin nav
include_once '../components/admin/admin_nav.php';

// Include any necessary libraries for generating reports
// Example: Include PHPExcel library for Excel report generation
// include_once '../libraries/PHPExcel/PHPExcel.php';

// Check if the user is logged in as admin, if not redirect them to the login page
if (
    !isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'
) {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}
?>

<br>
<hr>
<br>
<h1 class='report-placeholder'>User Report</h1>

<form class="report-form" method="post" action="../reports/pdf/pdf_users.php" target="_blank">
    <input type="submit" name="pdf_creater" value="Users Report" class="report-button">
</form>

<br>
<hr>
<br>