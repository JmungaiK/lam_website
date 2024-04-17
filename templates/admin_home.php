<?php
// Start session to check user authentication
session_start();

include_once '../components/admin/admin_header.php';
include_once '../components/admin/admin_nav.php';

// Include your PHP database connection file
include_once '../includes/conn.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['login_id']) || $_SESSION['login_user_role'] !== 'admin') {
    // Redirect to login page if not logged in or not an admin
    header("Location: login.php");
    exit();
}

?>

<div class="admin-content">
    <h2 class="admin-title">Welcome, Admin!</h2>

    <section class="admin-section">
        <h3 class="admin-section-title">Quick Links</h3>
        <ul class="admin-links">
            <br>
            <hr><br>
            <li><a href="admin_modules.php">Manage Modules</a></li>
            <br>
            <hr><br>
            <li><a href="admin_videos.php">Manage Videos</a></li>
            <br>
            <hr><br>
            <li><a href="admin_users.php">Manage Users</a></li>
            <br>
            <hr><br>
            <li><a href="admin_reports.php">View Report</a></li>
            <br>
            <hr><br>
            <li><a href="create_user.php">Create User</a></li>
            <br>
            <hr><br>
            <li><a href="create_video.php">Create Video</a></li>
            <br>
            <hr><br>
            <li><a href="create_module.php">Create Module</a></li>
            <br>
            <hr><br>
        </ul>
    </section>
</div>

<?php include_once '../components/footer.php'; ?>