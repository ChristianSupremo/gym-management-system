<?php
session_start(); // Start the session

// Include any necessary files or database connections
include 'db.php'; // Assuming you have a database connection here

// Check if there is a success message to display
$success_message = "";
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gym Management System - Dashboard</title>
    <style>
        /* Existing styles */
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
            background-color: #C0C0C0;
        }

        .header-container {
            display: flex;
            align-items: center; /* Align items vertically */
            gap: 20px; /* Add spacing between elements */
            background-color: #2F4F4F; /* Darker background color */
            padding: 20px; /* Add some padding inside the container */
            border-radius: 8px; /* Rounded corners (optional) */
            flex-direction: column; /* Stack elements vertically */
        }

        h1 {
            margin: 0; /* Remove default margin */
            border: 2px solid #333; /* Box around the h1 */
            padding: 10px; /* Add padding inside the h1 box */
            border-radius: 5px; /* Rounded corners for the h1 box (optional) */
            background-color: #4B4B4B; /* Optional background color for h1 */
            color: #FFF; /* Make the text color white for contrast */
        }

        h2 {
            margin: 0; /* Remove default margin */
            color: #FDFD96;
            font-size: 18px;
        }

        .dashboard {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px; /* Add space above the dashboard */
        }

        .card {
            border: 4px solid #333333;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            background-color: #008080;
            width: 200px;
            height: 90px;
            cursor: pointer;
        }

        .card:hover {
            background-color: #FFF275;
            border: 4px solid #FFF275;
            font-weight: bold;
            color: #191970; /* Change the text color of direct children elements */
        }

        .card:hover .ccard {
            color: #191970; /* Ensure that .ccard elements also change color */
        }

        #content-area {
            margin-top: 20px;
            padding: 20px;
            background-color: #36454F;
            border: 2px solid #333333;
            border-radius: 8px;
        }

        .ccard {
            color: #FDFD96;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1>New You Fitness Club</h1>
        <h2>Member Management Dashboard</h2>

        <div class="dashboard">
            <div class="card" onclick="loadContent('register_member.php')">
                <h3>Register New Member</h3>
                <p class="ccard">Go to Registration</p>
            </div>
            <div class="card" onclick="loadContent('member_management.php')">
                <h3>Manage Members</h3>
                <p class="ccard">View and Edit members and their Plans</p>
            </div>
            <div class="card" onclick="loadContent('payment_management.php')">  
                <h3>Track Payments</h3>
                <p class="ccard">Record Payments</p>
            </div>
            <div class="card" onclick="loadContent('view_plans.php')">
                <h3>View Plans</h3>
                <p class="ccard">View Assigned Plans</p>
            </div>
            <div class="card" onclick="loadContent('view_payments.php')">
                <h3>View Payments</h3>
                <p class="ccard">Payment History</p>
            </div>
        </div>
    </div>

    <!-- Content area where PHP files will be loaded -->
    <div id="content-area">
        <p>Select an option from above to view details here.</p>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Load content into content-area
        function loadContent(file) {
            fetch(file)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("content-area").innerHTML = data;
                })
                .catch(err => console.error("Error loading content:", err));
        }

        // Event delegation for Edit / Cancel / Delete
        document.addEventListener("click", function(e) {
            // --- Edit button clicked ---
            if (e.target.classList.contains("edit-btn")) {
                const id = e.target.getAttribute("data-id");
                const row = document.getElementById("row-" + id);
                if (row) {
                    row.querySelectorAll(".view-mode").forEach(el => el.style.display = "none");
                    row.querySelectorAll(".edit-mode").forEach(el => el.style.display = "");
                }
            }

            // --- Cancel button clicked ---
            if (e.target.classList.contains("cancel-btn")) {
                const row = e.target.closest("tr");
                if (row) {
                    row.querySelectorAll(".view-mode").forEach(el => el.style.display = "");
                    row.querySelectorAll(".edit-mode").forEach(el => el.style.display = "none");
                }
            }

            // --- Delete button clicked ---
            if (e.target.classList.contains("delete-member-btn")) {
                if (!confirm("Are you sure you want to delete this member?")) return;
                const memberId = e.target.getAttribute("data-id");

                fetch("delete_member.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "member_id=" + encodeURIComponent(memberId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        loadContent("member_management.php");
                    } else {
                        alert("Failed: " + data.message);
                    }
                })
                .catch(err => {
                    console.error("Delete error:", err);
                    alert("Failed to delete member.");
                });
            }
        });

        // Load default message (optional)
        document.getElementById("content-area").innerHTML = "<p>Select an option from above to view details here.</p>";

        // Make loadContent globally available (so your card onclick works)
        window.loadContent = loadContent;
    });
</script>

</body>
</html>
