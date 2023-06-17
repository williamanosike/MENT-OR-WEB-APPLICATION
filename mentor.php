<?php
include_once "header.php";
include_once "php/config.php"; // Include your database configuration file

// Define an empty variable to store the error message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $level = $_POST["level"];
    $course = $_POST["course"];
    $semester = $_POST["semester"];

    // Validate the selected options
    if (empty($level) || empty($course) || empty($semester)) {
        $error = "Please select all the required fields.";
    } else {
        // Prepare and execute the SQL statement to insert the data into the database
        $stmt = $conn->prepare("INSERT INTO mentors (unique_id, level, course, semester) VALUES (:unique_id, level, :course, :semester)");
        $stmt->bindParam(':unique_id', $_SESSION['unique_id']);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':semester', $semester);
        $stmt->execute();

        // Check if the data was inserted successfully
        if ($stmt->rowCount() > 0) {
            // Redirect to a success page or display a success message
            header("Location: success.php");
            exit(); // Stop executing the current script
        } else {
            $error = "Failed to insert data into the database.";
        }
    }
}

// Close the database connection
$conn = null;
?>


<body>
    <div class="wrapper">
        <section class="form mentee">
            <header style="display: flex; justify-content: center;">
                <img src="img/logo-no-background.png" width="50px" display="block" margin="0" alt="Header Image">
            </header>
            <form action="mentee-help.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>

                <div class="field input">
                    <label>What Level Are You In?</label>
                    <select id="level" name="level" required>
                        <option value="">Select Level---</option>
                        <option value="100">Level 100</option>
                        <option value="200">Level 200</option>
                        <option value="300">Level 300</option>
                        <option value="400">Level 400</option>
                    </select>
                </div>
                <div class="field input">
                    <label>What Programme Are You Enrolled In?</label>
                    <select name="course" required>
                        <option value="">Select---</option>
                        <optgroup label="ENGINEERING">
                            <option value="e.c">BSc. Electronics & Communication Engineering</option>
                            <option value="c.e">BSc. Computer Engineering</option>
                            <option value="m.e">Bsc. Mechanical Engineering</option>
                            <option value="e.e">Bsc. Electrical & Electronics Engineering</option>
                            <option value="i.e">Bsc. Industrial & Systems Engineering</option>
                            <option value="r.e">Bsc. Robotics Engineering</option>
                            <option value="b.e">Bsc. Biomedical Engineering</option>
                        </optgroup>
                        <optgroup label="INFORMATION TECHNOLOGY & COMPUTING">
                            <option value="i.t">BSc. Information Technology</option>
                            <option value="c.s">BSc. Computer Science</option>
                            <option value="a.i">BSc. Artificial Intelligence</option>
                        </optgroup>
                        <optgroup label="BUSINESS">
                            <option value="a">B.B.A - Accounting</option>
                            <option value="b.f">B.B.A - Banking & Finance</option>
                            <option value="h.r.m">B.B.A - Human Resource Management</option>
                            <option value="m">B.B.A - Marketing</option>
                            <option value="e">B.B.A - Entrepreneurship</option>
                        </optgroup>
                        <optgroup label="COMMUNICATION ARTS">
                            <option value="j.m">B.A - Journalism & Mass Communication</option>
                            <option value="a.p">B.A - Advertising & Public Relations</option>
                        </optgroup>
                    </select>
                </div>

                <div class="field input">
                    <label>What Semester Are You In?</label>
                    <select id="semester" name="semester" required>
                        <option value="">Select Semester---</option>
                        <option value="mentor">Semester 1</option>
                        <option value="mentor">Semester 2</option>
                        <option value="mentor">Semester 3</option>
                        <option value="mentor">Semester 4</option>
                        <option value="mentor">Semester 5</option>
                        <option value="mentor">Semester 6</option>
                        <option value="mentor">Semester 7</option>
                        <option value="mentor">Semester 8</option>
                    </select>
                </div>
                <div class="field button">
                    <input type="submit" name="submit" value="Continue">
                </div>
            </form>
        </section>
    </div>

    <script src="javascript/pass-show-hide.js"></script>
    <script src="javascript/login.js"></script>

    <script>
        var levelDropdown = document.getElementById("level");
        var semesterDropdown = document.getElementById("semester");

        var levelSemesterOptions = {
            "100": ["Semester 1", "Semester 2"],
            "200": ["Semester 3", "Semester 4"],
            "300": ["Semester 5", "Semester 6"],
            "400": ["Semester 7", "Semester 8"]
        };

        function updateSemesterOptions() {
            var selectedLevel = levelDropdown.value;
            var semesterOptions = levelSemesterOptions[selectedLevel];

            semesterDropdown.innerHTML = "";

            for (var i = 0; i < semesterOptions.length; i++) {
                var option = document.createElement("option");
                option.value = semesterOptions[i];
                option.text = semesterOptions[i];
                semesterDropdown.appendChild(option);
            }
        }

        levelDropdown.addEventListener("change", updateSemesterOptions);
        updateSemesterOptions();
    </script>
</body>

</html>
