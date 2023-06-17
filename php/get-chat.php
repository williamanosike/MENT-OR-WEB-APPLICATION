<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM messages WHERE (incoming_msg_id = {$incoming_id} AND outgoing_msg_id = {$outgoing_id})
         OR (incoming_msg_id = {$outgoing_id} AND outgoing_msg_id = {$incoming_id}) ORDER BY msg_id DESC";

        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    if ($row['document'] != '') {
                        $output .= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                        <a href="' . $row['document'] . '" class="document-link" target="_blank">View Document</a>
                                    </div>
                                </div>';
                    } else {
                        $output .= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                    }
                }else{
                    if ($row['document'] != '') {
                        $output .= '<div class="chat incoming">
                                    
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                        <a href="' . $row['document'] . '" class="document-link" target="_blank">View Document</a>
                                    </div>
                                </div>';
                    } else {
                        $output .= '<div class="chat incoming">
                                    
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                    }
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send a message, it will appear here.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }
?>
