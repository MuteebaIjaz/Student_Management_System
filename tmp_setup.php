<?php
require_once "includes/conn.php";

$query = "CREATE TABLE IF NOT EXISTS `annoucements` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `sender_id` int(11) NOT NULL,
    `sender_role` varchar(50) NOT NULL,
    `target_audience` varchar(100) NOT NULL COMMENT 'Can be All, Teachers, Students, or a specific class_id',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)";

if(mysqli_query($conn, $query)){
    echo "Table created successfully\\n";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "\\n";
}

// optionally drop the mispelled one if I created it
mysqli_query($conn, "DROP TABLE IF EXISTS `announcements`");
?>
