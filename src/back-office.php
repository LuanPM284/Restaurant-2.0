<?php
// Database connection
$servername = "database";
$username = "root";
$password = "root";
$dbname = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle delete action
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Fetch all messages
    $stmt = $conn->query("SELECT * FROM contacts ORDER BY reg_date DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office - Message Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
</head>

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-white">
        <div class="container-fluid text">
            <a class="navbar-brand text-center" href="index.html">
                <img src="./assets/images/ResStars.webp" alt="Logo"
                    class=" d-inline-block align-text-top rounded float-right" width="60vh" height="55vh">
            </a>
            <ul class="navbar-nav flex">
                <li class=" nav-item">
                    <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover display-5 p-3"
                        href="menu.html">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover display-5 p-3"
                        href="pictures.html">Pictures</a>
                </li>
                <li class="nav-item">
                    <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover display-5 p-3"
                        href="restaurants.html">Restaurants</a>
                </li>
                <li class="nav-item">
                    <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover display-5 p-3"
                        href="http://localhost/contacts.php">Contact</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<body style="background-image: url(./assets/images/nasa--hI5dX2ObAs-unsplash.jpg);width: 100%; background-size: cover;">

    <div class="container mt-5 bg-warning">
        <div class="d-flex align-items-center mb-4">
            <div class="logo"></div>
            <h1>Back-office</h1>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message) : ?>
                <tr>
                    <td><?= htmlspecialchars($message['reg_date']) ?></td>
                    <td><?= htmlspecialchars($message['firstName'] . ' ' . $message['lastName']) ?></td>
                    <td><?= htmlspecialchars($message['email']) ?></td>
                    <td><?= htmlspecialchars($message['subject']) ?></td>
                    <td><?= htmlspecialchars($message['message']) ?></td>
                    <td>
                        <form method="POST">
                            <button type="submit" name="delete" value="<?= $message['id'] ?>"
                                class="btn btn-danger">X</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>