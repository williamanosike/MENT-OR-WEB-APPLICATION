<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }

  // Check if the mentor or mentee button is clicked
if(isset($_POST['mentor'])){
  $role = "mentor";
  updateRole($_SESSION['unique_id'], $role);
  header("Location: mentor.php");
  exit();
} elseif(isset($_POST['mentee'])){
  $role = "mentee";
  updateRole($_SESSION['unique_id'], $role);
  header("Location: mentee.php");
  exit();
}

// Function to update the user's role in the database
function updateRole($unique_id, $role) {
  global $conn;
  $sql = "UPDATE users SET role = '$role' WHERE unique_id = '$unique_id'";
  mysqli_query($conn, $sql);
}

// Get the user's role from the database
function getUserRole($unique_id) {
  global $conn;
  $sql = "SELECT role FROM users WHERE unique_id = '$unique_id'";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    return $row['role'];
  }
  return null;
}

// Get the list of users based on the user's role
function getUserList($role) {
  global $conn;
  $sql = "SELECT * FROM users WHERE role = '$role'";
  $result = mysqli_query($conn, $sql);
  $users = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $users[] = $row;
    }
  }
  return $users;
}

// Check if the user is a mentor or mentee
$userRole = getUserRole($_SESSION['unique_id']);
if ($userRole == "mentor") {
  $users = getUserList("mentee");
} elseif ($userRole == "mentee") {
  $users = getUserList("mentor");
} else {
  // Invalid user role
  header("Location: users.php");
  exit();
}

?>


<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="users">
      <header>

        <div class="content">
          <?php 
            $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            $row = [];
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
          <img src="php/images/<?php echo $row['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
      </header>

      <br>
      <div class="button-container">
        <button class="button" onclick="window.location.href='mentor.php'">Mentor</button>
        <span class="or">or</span>
        <button class="button" onclick="window.location.href='mentee.php'">Mentee</button>
      </div>
      <div class="button-container">
        <button class="button" onclick="window.location.href='goals.php'">Set Goals</button>
        <span class="or">or</span>
        <button class="button" onclick="window.location.href='progress.php'">Check Progress</button>
      </div>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
        <?php foreach ($users as $user): ?>
          <!-- Display user information -->
          <div class="user">
            <img src="php/images/<?php echo $user['img']; ?>" alt="">
            <div class="details">
              <span><?php echo $user['fname'] . " " . $user['lname']; ?></span>
              <p><?php echo $user['status']; ?></p>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>
</html>

