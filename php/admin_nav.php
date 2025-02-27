<?php
require 'process/db_connect.php';
?>
<nav id="sidebar" style="position: sticky;">
  <div class="custom-menu">
    <button type="button" id="sidebarCollapse" class="btn btn-primary">
      <i class="fa fa-bars"></i>
      <span class="sr-only">Toggle Menu</span>
    </button>
  </div>
  <h1><a href="admin_dashboard.php" class="logo">Inventory System</a></h1>
  <?php
    if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
    }
    // Prepare the statement
    $stmt = $conn->prepare("SELECT * FROM users where user_id = ?");
    $stmt->bind_param("i", $userId); // Bind the parameter

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows>0){
      $row = $result->fetch_assoc();
  ?>
  <ul class="list-unstyled components mb-5">
    <li>
      <a href="#">
        <span class="fa fa-id-badge"></span>
      <?php 
      echo "Welcome " . $row["username"] . "!";
    } 
    $stmt->close();
      ?>
        
      </a>
    </li>
    <li>
      <a href="admin_dashboard.php"><span class="fa fa-home mr-3"></span> Homepage</a>
    </li>
    <li>
        <a href="user_profile.php"><span class="fa fa-user mr-3"></span> Profile</a>
    </li>
    <li>
      <a href="inventory.php"><span class="fa fa-file mr-3"></span> Inventory</a>
    </li>
    <li>
      <a href="qr_scanner.php"><span class="fa fa-qrcode mr-3"></span> QR Scanner</a>
    </li>
    <li>
      <a href="test_scanner.html"><span class="fa fa-cog mr-3"></span> Settings</a>
    </li>
    <li>
      <a href="info.php"><span class="fa fa-question-circle mr-3"></span> Help</a>
    </li>
    <li><a href="test/php_code_checker.php"><span class="fa fa-puzzle-piece mr-3"></span>test</a>
    </li>
    <li>
      <a href="process/logout_process.php"><span class="fa fa-sign-out mr-3"></span> Logout</a>
    </li>
  </ul>
</nav>

<!-- Scroll to Top Button -->
<button onclick="scrollToTop()" id="scrollTopBtn" class="scroll-top-btn" title="Go to top">
  <i class="fa fa-arrow-up"></i>
</button>

<style>
.scroll-top-btn {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 99;
  border: none;
  outline: none;
  background-color: #007bff;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 50%;
  font-size: 18px;
  transition: all 0.3s ease;
}

.scroll-top-btn:hover {
  background-color: #0056b3;
  transform: translateY(-3px);
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
</style>

<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("scrollTopBtn").style.display = "block";
  } else {
    document.getElementById("scrollTopBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}
</script>

<script src="../js/jquery.min.js"></script>
<script src="../js/popper.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/admin.js"></script>
  