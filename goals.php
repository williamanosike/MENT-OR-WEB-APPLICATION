<!DOCTYPE html>
<html>
<head>
    <title>Goal Setting</title>
</head>
<?php include_once "header.php"; ?>
<body>
    <div class="wrapper">
        <section class="form login">
            <header style="display: flex; justify-content: center;">
                <img src="img/logo-no-background.png" width="50px" display="block" margin="0" alt="Header Image">
            </header>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" autocomplete="off">
                <?php
                // Check if the form is submitted
                if (isset($_POST['submit'])) {
                    $goal = $_POST['goal'];

                    // Validate the goal (not empty)
                    if (!empty($goal)) {
                        // Save the goal
                        saveGoal($goal);
                        echo "<p id='success-message' style='font-weight: bold; text-align: center; margin-bottom: 20px;'>Goal saved successfully!</p>";
                        echo "<script>
                                setTimeout(function() {
                                    document.getElementById('success-message').remove();
                                }, 3000);
                            </script>";
                    } else {
                        echo "<p>Please enter a goal.</p>";
                    }
                }
                ?>
                <div class="field input">
                    <label>Enter Your Goal</label>
                    <input type="text" name="goal" id="goal" required>
                </div>
                   
                <div class="field button">
                    <input type="submit" name="submit" value="Save">
                </div>

                <div class="button-container">
                    <button class="button" onclick="window.location.href='users.php'">Go Back</button>
                </div>
            </form>
        </section>
    </div>

    <?php
    // Function to save the goal to the ment-or database
    function saveGoal($goal) {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "MENT-OR";
        
        // Create a connection to the MySQL server
        $conn = mysqli_connect($hostname, $username, $password, $dbname);
        
        // Check the connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Prepare and execute the SQL statement to insert the goal
        $stmt = $conn->prepare("INSERT INTO goals (goal_text) VALUES (?)");
        $stmt->bind_param("s", $goal);
        
        $stmt->execute();
        
        // Close the statement and the connection
        $stmt->close();
        $conn->close();
    }
    ?>
     
</body>
</html>
