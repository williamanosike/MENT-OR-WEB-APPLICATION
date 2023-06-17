<?php 
session_start();
include_once "php/config.php";

if(isset($_SESSION['unique_id'])){
  header("location: users.php");
  exit();
}

if(isset($_POST['submit'])){
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  // Process the form data and perform necessary validations

  // Assuming the form data is valid and successful
  // Insert the user data into the database and obtain the unique_id
  $sql = "INSERT INTO users (unique_id, fname, lname, email, password, role) VALUES (UUID(), '$fname', '$lname', '$email', '$password', '$role')";
  mysqli_query($conn, $sql);

  // Get the unique_id of the inserted user
  $unique_id = mysqli_insert_id($conn);

  // Store the unique_id in the session
  $_SESSION['unique_id'] = $unique_id;

  // Redirect based on the selected role
  if($role === "mentor") {
    header("Location: mentor.php");
    exit();
  } elseif($role === "mentee") {
    header("Location: mentee.php");
    exit();
  }
}
?>
  

<?php include_once "header.php"; ?>
<body style="background-image: url('img/new.jpg'); background-size: cover; " >
  <div class="wrapper">
    <section class="form signup">
      <header style="display: flex; justify-content: center;">
        <img src="img/logo-no-background.png" width="50px" display="block" margin="0" alt="Header Image">
      </header>
      <form action="php/signup.php" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="name-details">
          <div class="field input">
            <label>First Name</label>
            <input type="text" name="fname" placeholder="First name" required>
          </div>
          <div class="field input">
            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Last name" required>
          </div>
        </div>
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter new password" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field input">
              <label>Role</label>
              <select name="role" required>
                <option value="">Select role</option>
                <option value="mentor">Mentor</option>
                <option value="mentee">Mentee</option>
              </select>
        </div>
        <div class="field image">
          <label>Select Image</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Continue">
        </div>
      </form>
      <div class="link">Already signed up? <a href="login.php">Login now</a></div>
    </section>
  </div>

  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>

</body>
</html>
