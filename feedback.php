<!DOCTYPE html>
<html>
<head>
    <title>User Feedback</title>
</head>
<?php include_once "header.php"; ?>
<body>
    <div class="wrapper">
        <section class="form login">
            <header style="display: flex; justify-content: center;">
                <img src="img/logo-no-background.png" width="50px" display="block" margin="0" alt="Header Image">
            </header>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" autocomplete="off">
                <?php
                // Check if the form is submitted
                if (isset($_POST['submit'])) {
                    $feedback = $_POST['feedback'];

                    // Validate the feedback (not empty)
                    if (!empty($feedback)) {
                        // Save the feedback
                        saveFeedback($feedback);
                        echo "<p id='success-message' style='font-weight: bold; text-align: center; margin-bottom: 20px;'>Thank you for your feedback!</p>";
                        echo "<script>
                                setTimeout(function() {
                                    document.getElementById('success-message').remove();
                                }, 3000);
                            </script>";
                    } else {
                        echo "<p>Please enter your feedback.</p>";
                    }
                }
                ?>
                <div class="field input">
                    <label>Enter Your Feedback</label>
                    <textarea name="feedback" id="feedback" rows="5" cols="30" required></textarea>
                </div>

                <div class="field button">
                    <input type="submit" name="submit" value="Submit">
                </div>

                <div class="button-container">
                    <button class="button" onclick="window.location.href='users.php'">Go Back</button>
                </div>
            </form>
        </section>
    </div>

    <?php
    // Function to save the feedback to the MENT-OR database
    function saveFeedback($feedback) {
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

        // Prepare and execute the SQL statement to insert the feedback
        $stmt = $conn->prepare("INSERT INTO feedback (feedback_text) VALUES (?)");
        $stmt->bind_param("s", $feedback);

        $stmt->execute();

        // Close the statement and the connection
        $stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>
