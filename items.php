<?php
include 'includes/connect.php';

// Fetch all items from database
$sql = "SELECT * FROM items";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Items List</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Items List</h2>

<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Price (TZS)</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo number_format($row['price']); ?></td>
            <td> <img src="item_images/<?php echo $row['item_image']; ?>" width="100" height="" alt="Product"></td>
            <td>
                <a href="edit-item.php?id=<?php echo $row['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>