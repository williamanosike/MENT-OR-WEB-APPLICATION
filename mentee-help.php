<?php
include_once "header.php";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected level and course
    $level = $_POST["level"];
    $semester = $_POST["semester"];
    $course = $_POST["course"];

    // Generate a list of relevant courses based on the selected level and course
    $relevantCourses = generateRelevantCourses($level, $course);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Need Help?</title>
    <!-- Include any necessary CSS styles or external files -->
</head>
<body>
<div class="wrapper">
        <section class="form mentee">
            <header style="display: flex; justify-content: center;">
                <img src="img/logo-no-background.png" width="50px" display="block" margin="0" alt="Header Image">
            </header>
    
    <form action="users.php" method="POST" enctype="multipart/form-data" autocomplete="off">
    <div class="error-text"></div>
        <h4>SELECT THE COURSES YOU WANT TO HELP WITH</h3>
        <br>

        <?php foreach ($relevantCourses as $course) { ?>
            <label>
                <input type="checkbox" name="selectedCourses[]" value="<?php echo $course; ?>">
                <?php echo $course; ?>
            </label><br>
        <?php } ?>
        <br>
        <div class="field button">
                    <input type="submit" name="submit" value="Continue">
        </div>
        
    </form>
</body>
</html>

<?php
} else {
    // Redirect back to the mentee.php page if the form was not submitted
    header("Location: mentee.php");
    exit();
}

// Function to generate relevant courses based on the selected level and course
function generateRelevantCourses($level) {
    // Here, you can implement your own logic to generate the relevant courses based on the selected level and course
    // This can be done using if-else conditions, database queries, or any other method suitable for your application

    // For demonstration purposes, we'll use a simple array of courses
    $relevantCourses = array();

    if ($level == "100") {
            $relevantCourses = array("Communication Skills", "Python Programming Language", " French Language");
        }
    
    elseif ($level == "200") {
            $relevantCourses = array("Emerging Technologies", "Text and Meaning", " Logic and Critical Thinking");
        }

    elseif ($level == "300") {
            $relevantCourses = array("Leadership Seminar 1", "Introduction to Data Science", "Project Management");
    }

    elseif ($level == "400") {
        $relevantCourses = array("Human Computer Interaction", "Project Phase 1", "Ethical and Legal Isuues in Computing");
    }
    // Add conditions for other levels

    return $relevantCourses;
}
?>
