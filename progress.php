<!DOCTYPE html>
<html>
<head>
<?php include_once "header.php"; ?>
    <title>Progress Tracking</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 400px;
            height: 400px;
            margin: 20px;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form {
            margin: 20px;
        }
    </style>
</head>
<body>

    <?php
    // Function to fetch all goals from the database
    function getGoals() {
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

        // Retrieve all goals from the database
        $sql = "SELECT * FROM goals";
        $result = mysqli_query($conn, $sql);

        // Store the goals in an array
        $goals = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $goals[] = $row;
            }
        }

        // Close the connection
        mysqli_close($conn);

        return $goals;
    }

    // Function to update the status of a goal in the database
    function updateGoalStatus($goalId, $status) {
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

        // Update the status of the goal in the database
        $sql = "UPDATE goals SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $goalId);
        $stmt->execute();

        // Close the statement and the connection
        $stmt->close();
        mysqli_close($conn);
    }

    $message = "";
    $showGraph = false;

    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        if (isset($_POST['goals']) && is_array($_POST['goals'])) {
            // Retrieve the selected goals
            $selectedGoals = $_POST['goals'];

            // Update the status of each selected goal to "completed"
            foreach ($selectedGoals as $goalId) {
                updateGoalStatus($goalId, "completed");
            }

            $message = "Selected goals have been marked as completed!";
            $showGraph = true;
        } else {
            $message = "No goals were selected.";
        }
    }

    // Fetch all goals from the database with "pending" status
    $goals = getGoals();
    $pendingGoals = array_filter($goals, function ($goal) {
        return $goal['status'] === 'pending';
    });
    ?>

<div class="wrapper">
    <section class="form login">
                <header style="display: flex; justify-content: center;">
                    <img src="img/logo-no-background.png" width="50px" display="block" margin="0" alt="Header Image">
                </header>
                
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h4>SELECT THE GOALS YOU HAVE COMPLETED</h3>
            <?php if (!empty($pendingGoals)): ?>
                <?php foreach ($pendingGoals as $goal): ?>
                    <label>
                        <input type="checkbox" name="goals[]" value="<?php echo $goal['id']; ?>">
                        <?php echo $goal['goal_text']; ?>
                    </label>
                    <br>
                <?php endforeach; ?>
                <br>
                <div class="message" style="text-align: center; font-weight: bold; margin-bottom: 10px;">
                        <?php if (!empty($message)) echo $message; ?>
                </div>

                <div class="field button">
                            <input type="submit" name="submit" value="Submit">
                </div>
                

            <?php else: ?>
                <p>No pending goals found.</p>
            <?php endif; ?>
            
            <div class="button-container">
                    <a href="goals.php" class="button">Set New Goals</a>
            </div>
            <br>  
        </form>  
</section>
</div>



<?php if ($showGraph && !empty($pendingGoals)): ?>
    <?php
        // Calculate the efficiency percentage
        $currentGoalsCount = count($goals);
        $completedGoalsCount = $currentGoalsCount - count($pendingGoals);
        $efficiencyPercentage = ($completedGoalsCount / $currentGoalsCount) * 100;
        $efficiencyPercentage = round($efficiencyPercentage, 2);
    ?>
        <div class="chart-container">
        <h2 style="text-align: center;">Your Efficiency: <?php echo $efficiencyPercentage; ?>%</h2>
        <canvas id="efficiencyChart"></canvas>
        </div>

    <script>
       

// Function to update the efficiency chart
function updateEfficiencyChart() {
    // Retrieve the current goals and completed goals count
    var currentGoalsCount = <?php echo count($goals); ?>;
    var completedGoalsCount = <?php echo count($goals) - count($pendingGoals); ?>;

    // Calculate the efficiency percentage
    var efficiencyPercentage = (completedGoalsCount / currentGoalsCount) * 100;
    efficiencyPercentage = efficiencyPercentage.toFixed(2); // Round to 2 decimal places

    // Update the efficiency chart
    var ctx = document.getElementById('efficiencyChart').getContext('2d');
    var efficiencyChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Completed Goals', 'Remaining Goals'],
            datasets: [{
                data: [completedGoalsCount, currentGoalsCount - completedGoalsCount],
                backgroundColor: ['#36A2EB', '#FF6384'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateRotate: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.dataset.data[context.dataIndex] === completedGoalsCount) {
                                label += efficiencyPercentage + '% (' + completedGoalsCount + ' completed)';
                            } else {
                                var remainingGoalsCount = currentGoalsCount - completedGoalsCount;
                                label += (100 - efficiencyPercentage).toFixed(2) + '% (' + remainingGoalsCount + ' remaining)';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
}




        // Call the function to update the efficiency chart initially
        updateEfficiencyChart();

        // Function to hide the message after a specific duration
        function hideMessage() {
            var messageElement = document.querySelector('.message');
            if (messageElement) {
                messageElement.style.display = 'none';
            }
        }

        // Hide the message after 3 seconds (3000 milliseconds)
        setTimeout(hideMessage, 3000);

    </script>
    <?php endif; ?>
</body>
</html>