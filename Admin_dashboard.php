<?php
session_start(); 

if (!isset($_SESSION['user'])) {
    header("Location: login_page.html"); 
    exit();
}

$loggedInUser = htmlspecialchars($_SESSION['user']); 


$updateMessage = '';
if (isset($_GET['update']) && $_GET['update'] === 'success') {
    $updateMessage = 'Profile picture updated successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - EduNexus</title>
    <link rel="icon" type="image/x-icon" href="https://icon-library.com/images/admin-login-icon/admin-login-icon-15.jpg">
    <link rel="stylesheet" href="Dashboard-EduNexus.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(45deg, #121212, #1e1e1e, #333);
    color: #e0e0e0;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    animation: backgroundFade 15s ease-in-out infinite;
}

/* Keyframe Animation for Background */
@keyframes backgroundFade {
    0% { background: linear-gradient(45deg, #121212, #1e1e1e, #333); }
    25% { background: linear-gradient(45deg, #2c3e50, #1e1e1e, #34495e); }
    50% { background: linear-gradient(45deg, #1a1a1a, #2c3e50, #2d3436); }
    75% { background: linear-gradient(45deg, #333, #1e1e1e, #2c3e50); }
    100% { background: linear-gradient(45deg, #121212, #1e1e1e, #333); }
}

/* Header Styles */
header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 25px;
    background-color: #1e1e1e;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    transition: background-color 0.5s ease;
    animation: headerHover 5s ease-in-out infinite;
}

@keyframes headerHover {
    0%, 100% { background-color: #1e1e1e; }
    50% { background-color: #34495e; }
}

header:hover {
    background-color: #222;
}

.logo img {
    height: 50px;
    width: 100px;
    transition: transform 0.3s ease, filter 0.3s ease;
}

.logo img:hover {
    transform: scale(1.5);
    filter: brightness(1.2);
}

h1 {
    color: #fff;
    font-size: 1.8em;
    font-weight: 600;
    transition: color 0.5s ease-in-out;
}

.user-info {
    display: flex;
    align-items: center;
    color: #e0e0e0;
    font-size: 1em;
}

.user-info img {
    height: 40px;
    border-radius: 50%;
    margin-left: 15px;
    transition: transform 0.3s ease, filter 0.3s ease;
}

.user-info img:hover {
    transform: scale(1.1);
    filter: brightness(1.2);
}

/* Hamburger Button Styles */
.hamburger-btn {
    background: none;
    border: none;
    cursor: pointer;
    display: none;
}

.hamburger-icon {
    width: 30px;
    height: 30px;
}

@media (max-width: 768px) {
    .hamburger-btn {
        display: block;
    }

    .user-info {
        display: none;
    }
}

/* Dropdown Menu Styles */
.dropdown-menu {
    display: none;
    position: absolute;
    top: 70px;
    right: 20px;
    background-color: #333;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    animation: dropdownSlideIn 0.3s ease-in-out;
}

@keyframes dropdownSlideIn {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.dropdown-menu li {
    padding: 10px 20px;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.dropdown-menu li:hover {
    background-color: #444;
    transform: translateX(5px);
}

/* Upload Section */
.upload-container {
    width: 100%; /* Increased width from 80% to 90% */
    max-width: 500px; /* Increased max-width from 700px to 900px */
    margin: 150px auto;
    padding: 20px;
    background-color: #1e1e1e;
    border-radius: 8px;
    box-shadow: 0 4px 12px 8px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 1s ease-in-out, pulse 2s infinite;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% { box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); }
    50% { box-shadow: 0 0 20px rgba(0, 123, 255, 0.7); }
    100% { box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); }
}

.upload-container form {
    display: flex;
    flex-direction: column; /* Corrected to 'column' */
    gap: 15px;
}
.upload-container label {
    font-size: 1em;
    color: #ccc;
    transition: color 0.3s ease;
}

.upload-container select, 
.upload-container input[type="file"],
.upload-container input[type="text"] {
    padding: 12px;
    font-size: 1em;
    border: 1px solid #555;
    border-radius: 5px;
    background-color: #333;
    color: #fff;
    transition: background-color 0.3s ease, border 0.3s ease, transform 0.3s ease;
}

.upload-container select:focus, 
.upload-container input[type="file"]:focus,
.upload-container input[type="text"]:focus {
    background-color: #444;
    border: 1px solid #aaa;
    outline: none;
    transform: translateY(-3px);
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.upload-container button {
    padding: 14px 20px;
    font-size: 1.2em;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    width: 100%;
}

.upload-container button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
    opacity: 0.9;
}

/* Filter Section */
.filter-section {
    padding: 20px;
    background-color: #1e1e1e;
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    gap: 15px;
}

.filter-btn {
    padding: 12px 20px;
    background-color: #333;
    color: #e0e0e0;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.filter-btn:hover {
    background-color: #444;
    transform: scale(1.05);
}

.filter-btn.active {
    background-color: #007bff;
    color: #fff;
}

.main-filter-btn {
    padding: 12px 20px;
    background-color: #28a745;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.main-filter-btn:hover {
    background-color: #218838;
    transform: scale(1.05);
}

/* Table Styles */
table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid #444;
}

th {
    background-color: #444;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #2a2a2a;
}

tr:hover {
    background-color: #555;
}

td a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

td a:hover {
    color: #ff5722;
    text-decoration: underline;
}

.remove-btn {
    padding: 8px 12px;
    background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.remove-btn:hover {
    background-color: #c82333;
}

.remove-btn:active {
    transform: scale(0.95);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .upload-container {
        width: 95%;
    }

    table th, table td {
        font-size: 0.9em;
    }

    .filter-section {
        flex-direction: column;
        gap: 20px;
    }

    .dropdown-menu {
        position: fixed;
        top: 50px;
        right: 10px;
    }
}
.multicolor-btn {
    background-color: transparent;
    color: transparent; /* Initially hide text */
    border: 2px solid transparent;
    padding: 12px 20px;
    font-size: 1.2em;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s ease, border-color 0.3s ease;
}

.search-icon {
    color: #fff; /* Show search icon */
    margin-right: 10px; /* Space between icon and text */
}

.search-text {
    display: none; /* Hide text initially */
    color: #fff;
}

.multicolor-btn:hover {
    color: #fff;
    border-color: #3498db; /* Border color change on hover */
}

.multicolor-btn:hover .search-icon {
    display: none; /* Hide the search icon on hover */
}

.multicolor-btn:hover .search-text {
    display: inline; /* Show the text on hover */
}
/* Style for the logout button */
#logout-btn {
    background-color: #007bff;  /* Blue background */
    color: white;               /* White text */
    border: none;               /* Remove border */
    padding: 10px 20px;         /* Add padding */
    font-size: 16px;            /* Set font size */
    cursor: pointer;           /* Change cursor to pointer on hover */
    border-radius: 5px;         /* Rounded corners */
    transition: background-color 0.3s ease;  /* Smooth transition for hover effect */
}

/* Hover effect for the logout button */
#logout-btn:hover {
    background-color: #0056b3;  /* Darker blue background on hover */
}

/* Optional: Disable the button when needed */
#logout-btn:disabled {
    background-color: #d6d6d6;  /* Grey background when disabled */
    color: #888;                /* Grey text */
    cursor: not-allowed;        /* Not-allowed cursor */
}
.marquee-container {
            background-color: #f0f8ff;
            padding: 10px;
            font-size: 18px;
            color: #333;
            text-align: center;
        }

        marquee {
            font-weight: bold;
            color: #0073e6;
            font-size: 22px;
        }


    </style>
</head>
<body>
<header>
        <div class="logo">
            <img src="educ.png" class="edunexus-logo" alt="EduNexus Logo" />
        </div>
        <h1>E.N.C.O.D.E.S.</h1>
        <!-- User Info Section -->
        <div class="user-info">
            <span>Hello, <span id="user-name"><?php echo $loggedInUser; ?></span></span>
            <!-- Display the uploaded profile picture or a default icon -->
            <img src="<?php echo isset($_SESSION['profile-pic']) ? $_SESSION['profile-pic'] : 'user_icon.png'; ?>" 
         alt="User Icon" 
         id="user-icon" 
         style="width:50px; height:50px; border-radius:50%; cursor: pointer; margin-right: 10px;" />
        </div>

        <script>
document.getElementById('user-icon').addEventListener('click', () => {
    if (confirm('Do you really want to log out?')) {
        window.location.href = 'logout.php';  // Redirect to logout page
    }
});
</script>


        <!-- Hamburger Button with Dropdown -->
        <button aria-label="Menu" class="hamburger-btn" id="hamburger-btn">
            <img src="hamburger.png" alt="Hamburger Icon" class="hamburger-icon" />
        </button>
        <!-- Dropdown Menu -->
        <div id="dropdown-menu" class="dropdown-menu">
            <ul>
                <li><a href="logout.php" id="logout-btn">Logout</a></li>
                <li><a href="update_image.php">Update Profile Picture</a></li>
            </ul>
        </div>
    </header>

    <!-- Display update message if available -->
    <?php if ($updateMessage): ?>
        <div class="update-message">
            <?php echo $updateMessage; ?>
        </div>
    <?php endif; ?>
    

    <!-- Upload Section -->
<div class="upload-container">
<div class="marquee-container">
        <marquee behavior="scroll" direction="left">
            Welcome to the Teacher's Upload Section! Please upload your course materials here.
        </marquee>
    </div>
<img src="educ.png">
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="class">Select Class:</label>
        <select name="class" id="class">
            <option value="">Select</option>
            <option value="8">Class 8</option>
            <option value="9">Class 9</option>
            <option value="10">Class 10</option>
        </select>

        <label for="subject">Select Subject:</label>
        <select name="subject" id="subject">
            <option value="">Select</option>
            <option value="Physics">Physics</option>
            <option value="Math">Math</option>
            <option value="Chemistry">Chemistry</option>
            <option value="Biology">Biology</option>
        </select>

        <label for="description">File Description:</label>
        <input type="text" name="description" id="description" required>

        <label for="file">Upload File:</label>
        <input type="file" name="file" id="file" required>

        <button type="submit">Upload</button>
    </form>
</div>


    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-container">
            <button class="filter-btn" data-filter="date">Date of Upload</button>
            <button class="filter-btn" data-filter="name">Instructor Name</button>
            <button class="filter-btn" data-filter="file-id">File Description/ID</button>
            <button class="multicolor-btn" onclick="applyFilter()">
    <span class="search-icon"><i class="fas fa-search"></i></span>
    Search
</button>

        </div>
    </div>

    <!-- Files Table Section -->
<table id="files-table">
    <thead>
        <tr>
            <th>Upload Date</th>
            <th>Uploaded By</th>
            <th>File Title</th>
            <th>Grade/Class</th>
            <th>Subject</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch files from the database 
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "education";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT date, user, fileName, class, subject FROM admin_file";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $filePath = 'uploads/' . htmlspecialchars($row['fileName']);
                $currentUser = $_SESSION['user']; // Get the logged-in user

                // Check if the current logged-in user is the owner of the file
                $isOwner = ($currentUser === $row['user']);

                if (file_exists($filePath)) {
                    echo "<tr data-date='" . htmlspecialchars($row['date']) . "' data-name='" . htmlspecialchars($row['user']) . "' data-file-id='" . htmlspecialchars($row['fileName']) . "'>
                        <td>" . htmlspecialchars($row['date']) . "</td>
                        <td>" . htmlspecialchars($row['user']) . "</td>
                        <td><a href='$filePath' download>" . htmlspecialchars($row['fileName']) . "</a></td>
                        <td>" . htmlspecialchars($row['class']) . "</td>
                        <td>" . htmlspecialchars($row['subject']) . "</td>
                        <td>";

                    // Only show the REMOVE button if the user is the owner
                    if ($isOwner) {
                        echo "<form method='POST' action='delete_file.php' style='display:inline;'>
                                <input type='hidden' name='fileName' value='" . htmlspecialchars($row['fileName']) . "'>
                                <button type='submit' class='remove-btn'>REMOVE </button>
                              </form>";
                    }

                    echo "</td></tr>";
                } else {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['date']) . "</td>
                        <td>" . htmlspecialchars($row['user']) . "</td>
                        <td>File not available</td>
                        <td>" . htmlspecialchars($row['class']) . "</td>
                        <td>" . htmlspecialchars($row['subject']) . "</td>
                        <td>";

                    // Only show the REMOVE button if the user is the owner
                    if ($isOwner) {
                        echo "<form method='POST' action='delete_file.php' style='display:inline;'>
                                <input type='hidden' name='fileName' value='" . htmlspecialchars($row['fileName']) . "'>
                                <button type='submit' class='remove-btn'>REMOVE ❌</button>
                              </form>";
                    }

                    echo "</td></tr>";
                }
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'><img src='https://img.freepik.com/free-vector/targeting-result-orientation-goal-achievement-business-strategy-aim-accomplishment-objective-pursuit-businessman-entrepreneur-cartoon-character_335657-2398.jpg' alt='No files available' style='width:30%; height:auto;'></td></tr>";
        }

        $conn->close();
        ?>
    </tbody>
</table>

    <script>
function applyFilter() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('#files-table tbody tr');

    let selectedFilter = '';
    let promptMessage = '';

    filterBtns.forEach(btn => {
        if (btn.classList.contains('active')) {
            selectedFilter = btn.getAttribute('data-filter');
            switch (selectedFilter) {
                case 'date':
                    promptMessage = 'Enter the Date:';
                    break;
                case 'name':
                    promptMessage = 'Enter the Name:';
                    break;
                case 'file-id':
                    promptMessage = 'Enter the File Name:';
                    break;
            }
        }
    });

    if (!selectedFilter) {
        alert('Please select a filter type.');
        return;
    }

    const filterValue = prompt(promptMessage)?.toLowerCase();
    if (!filterValue) return;

    tableRows.forEach(row => {
        const rowValue = row.getAttribute('data-' + selectedFilter)?.toLowerCase();
        row.style.display = rowValue && rowValue.includes(filterValue) ? '' : 'none';
    });
}

document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    });
});

document.getElementById('hamburger-btn').addEventListener('click', function (e) {
    e.stopPropagation();
    const dropdown = document.getElementById('dropdown-menu');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
});

window.addEventListener('click', function (event) {
    const dropdown = document.getElementById('dropdown-menu');
    if (dropdown.style.display === 'block' && !event.target.closest('#hamburger-btn')) {
        dropdown.style.display = 'none';
    }
});

function previewImage(event) {
    const preview = document.getElementById('preview-img');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
        };
        reader.readAsDataURL(file);
    }
}

document.querySelector('input[name="profile-pic"]').addEventListener('change', function(event) {
    const preview = document.getElementById('user-icon');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
        };
        reader.readAsDataURL(file);
    }
});

window.addEventListener('click', function (event) {
    const dropdown = document.getElementById('dropdown-menu');
    const hamburgerBtn = document.getElementById('hamburger-btn');

    if (dropdown.style.display === 'block' && !dropdown.contains(event.target) && !hamburgerBtn.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

document.getElementById('logout-btn').addEventListener('click', () => {
    if (confirm('Do you really want to log out?')) {
        window.location.href = 'logout.php';
    }
});

function confirmUpdate() {
    const recordId = document.getElementById('recordId').value;
    const dataField = document.getElementById('dataField').value;
    const confirmUpdate = confirm(`Do you really want to update the record with ID ${recordId} to "${dataField}"?`);

    if (confirmUpdate) {
        document.getElementById('updateForm').submit();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const loggedInUser = "<?php echo $loggedInUser; ?>"; // Get the logged-in user from PHP
    const removeButtons = document.querySelectorAll('.remove-btn');

    removeButtons.forEach(button => {
        const row = button.closest('tr');
        const userName = row.getAttribute('data-name');

        if (loggedInUser !== userName) {
            button.disabled = true; // Disable the button if the logged-in user doesn't match
            button.style.opacity = '0.6'; // Optional: Add styling to indicate disabled state
            button.style.cursor = 'not-allowed';
        }
    });
});
</script>
</body>
</html>
