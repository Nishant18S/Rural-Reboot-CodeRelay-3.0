<?php

$sourceServername = "localhost";
$sourceUsername = "root";
$sourcePassword = "";
$sourceDbname = "education";

$destinationServername = "localhost";
$destinationUsername = "root";
$destinationPassword = "";
$destinationDbname = "village";

// File directories
$sourceDir = 'C:/xampp/htdocs/Education Admin/uploads/';
$destinationDir = 'C:/xampp/htdocs/Education Student/uploads/';

// Ensure the destination directory exists
if (!is_dir($destinationDir)) {
    mkdir($destinationDir, 0777, true);
}

$sourceConn = new mysqli($sourceServername, $sourceUsername, $sourcePassword, $sourceDbname);
if ($sourceConn->connect_error) {
    die("Source connection failed: " . $sourceConn->connect_error);
}

$destinationConn = new mysqli($destinationServername, $destinationUsername, $destinationPassword, $destinationDbname);
if ($destinationConn->connect_error) {
    die("Destination connection failed: " . $destinationConn->connect_error);
}

// Step 1: Synchronize new records from admin_file to student_file
$sql = "SELECT date, user, fileName, class, subject FROM admin_file";
$result = $sourceConn->query($sql);

if ($result->num_rows > 0) {
    $insertStmt = $destinationConn->prepare("INSERT IGNORE INTO student_file (date, user, fileName, class, subject) VALUES (?, ?, ?, ?, ?)");

    while ($row = $result->fetch_assoc()) {
        $insertStmt->bind_param("sssis", $row['date'], $row['user'], $row['fileName'], $row['class'], $row['subject']);
        if ($insertStmt->execute()) {
            echo "Record for file " . $row['fileName'] . " copied successfully.<br>";
            
            $sourceFile = $sourceDir . $row['fileName'];
            $destinationFile = $destinationDir . $row['fileName'];

            if (file_exists($sourceFile)) {
                if (copy($sourceFile, $destinationFile)) {
                    echo "File " . $row['fileName'] . " copied successfully.<br>";
                } else {
                    echo "Failed to copy file " . $row['fileName'] . ".<br>";
                }
            } else {
                echo "Source file " . $row['fileName'] . " does not exist.<br>";
            }

        } else {
            echo "Failed to copy record for file " . $row['fileName'] . ": " . $destinationConn->error . "<br>";
        }
    }

    echo "Data processing complete!<br>";
} else {
    echo "No records found in source database.<br>";
}

// Step 2: Delete records and corresponding files when they no longer exist in admin_file
$sqlDelete = "SELECT fileName FROM student_file";
$deleteResult = $destinationConn->query($sqlDelete);

if ($deleteResult->num_rows > 0) {
    while ($row = $deleteResult->fetch_assoc()) {
        $fileName = $row['fileName'];

        // Check if file exists in admin_file table
        $checkFileInAdmin = "SELECT 1 FROM admin_file WHERE fileName = ?";
        $checkStmt = $sourceConn->prepare($checkFileInAdmin);
        $checkStmt->bind_param("s", $fileName);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows == 0) {
            // If not found in admin_file, delete from student_file and remove the file
            $deleteStmt = $destinationConn->prepare("DELETE FROM student_file WHERE fileName = ?");
            $deleteStmt->bind_param("s", $fileName);
            if ($deleteStmt->execute()) {
                // Delete the file from the destination directory
                $destinationFile = $destinationDir . $fileName;
                if (file_exists($destinationFile)) {
                    if (unlink($destinationFile)) {
                        echo "File " . $fileName . " deleted successfully.<br>";
                    } else {
                        echo "Failed to delete file " . $fileName . ".<br>";
                    }
                }
            } else {
                echo "Failed to delete record for file " . $fileName . ": " . $destinationConn->error . "<br>";
            }
        }

        $checkStmt->close();
    }
    echo "Deletion process complete!<br>";
} else {
    echo "No records found in student_file.<br>";
}

$insertStmt->close();
$destinationConn->close();
$sourceConn->close();
?>
