<?php
session_start();

if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $document = $_FILES['document'];

    // Check if a document file was uploaded
    if (!empty($document['name'])) {
        $documentPath = 'uploads/' . $document['name'];
        move_uploaded_file($document['tmp_name'], $documentPath);
    } else {
        $documentPath = '';
    }

    if (!empty($message) || !empty($documentPath)) {
        // Insert the message into the messages table
        $query = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, document, img)
                  VALUES ('$incoming_id', '$outgoing_id', '$message', '$documentPath', '$img')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Message inserted successfully

            // Update the last message and last message time for the sender
            $updateSenderQuery = "UPDATE users SET last_msg = '$message', last_msg_time = NOW() WHERE unique_id = '$outgoing_id'";
            mysqli_query($conn, $updateSenderQuery);

            // Update the last message and last message time for the receiver
            $updateReceiverQuery = "UPDATE users SET last_msg = '$message', last_msg_time = NOW() WHERE unique_id = '$incoming_id'";
            mysqli_query($conn, $updateReceiverQuery);
        } else {
            die("Error: " . mysqli_error($conn));
        }
    }
} else {
    header("location: ../login.php");
}
?>
