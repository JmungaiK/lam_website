<?php
// Start session to check user authentication
session_start();

// Include header and nav bar
include_once '../components/user/user_header.php';
include_once '../components/user/user_nav.php';

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if user is logged in and is a user
if (!isset($_SESSION['login_id']) || $_SESSION['login_user_role'] !== 'user') {
    // Redirect to login page if not logged in or not a user
    header("Location: login.php");
    exit();
}

?>
<h2>Reports Page</h2>

<br>
<hr>
<br>
<h3 class='report-placeholder'>Modules Report</h3>


<section class="admin-section">
    <form class="report-form" method="post" action="../reports/pdf/pdf_modules.php" target="_blank">
        <input type="submit" name="pdf_creater" value="Modules Report" class="report-button">
    </form>
    <br>
    <hr>
    <br>
    <h3 class='report-placeholder'>Videos Report</h3>


    <form class="report-form" method="post" action="../reports/pdf/pdf_videos.php" target="_blank">
        <input type="submit" name="pdf_creater" value="Video Report" class="report-button">
    </form>
    <br>
    <hr>
    <br>
</section>


<?php
include_once '../components/footer.php';

// Close connection
mysqli_close($conn);
