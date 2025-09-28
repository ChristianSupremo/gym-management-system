<?php
include 'db.php';  // Include the database connection file

// Fetch all members with their assigned plans
$sql = "SELECT Member.`MemberID`, Member.Name, Plan.PlanName, Plan.Rate, 
               Membership.StartDate, Membership.EndDate, Membership.Status
        FROM Membership
        JOIN Member ON Membership.`MemberID` = Member.`MemberID`
        JOIN Plan ON Membership.PlanID = Plan.PlanID";
$result = $conn->query($sql);

// Check for SQL errors
if (!$result) {
    echo "<p>Error fetching members: " . htmlspecialchars($conn->error) . "</p>";
    $conn->close();
    exit();
}

// Fetch all available plans with their rates for the dropdown
$plans = $conn->query("SELECT PlanID, PlanName, Rate FROM Plan");
?>

<div class="section">
    <h2>Member Management</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Current Plan</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["MemberID"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["PlanName"]) . " - ₱" . number_format($row["Rate"], 2) . "</td>";
                echo "<td>" . htmlspecialchars($row["StartDate"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["EndDate"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Status"]) . "</td>";

                echo "<td>
                        <!-- Change Plan Form -->
                        <form action='update_plan.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='member_id' value='" . htmlspecialchars($row["MemberID"]) . "'>
                            <select name='plan_id' required>";
                
                // Reset plans pointer & populate dropdown
                $plans->data_seek(0);
                while ($plan = $plans->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($plan['PlanID']) . "'>"
                        . htmlspecialchars($plan['PlanName']) . " - ₱" . number_format($plan['Rate'], 2) . "</option>";
                }

                echo "      </select>
                            <input type='submit' value='Change Plan' class='btn'>
                        </form>

                        <!-- Delete Member Button -->
                        <button 
                            type='button' 
                            class='btn btn-danger delete-member-btn' 
                            data-id='" . htmlspecialchars($row['MemberID']) . "' 
                            onclick=\"return confirm('Are you sure you want to delete this member?')\"
                        >
                            Delete
                        </button>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='no-data'>No members found</td></tr>";
        }
        ?>
    </table>
</div>

<?php $conn->close(); ?>
