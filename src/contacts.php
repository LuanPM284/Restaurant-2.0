<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "database"; // This should match the service name in docker-compose.yml
    $username = "root";
    $password = "root";
    $dbname = "mydb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contacts (firstName, lastName, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $subject, $message);

    // Set parameters and execute
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if ($stmt->execute()) {
        echo "<p class='alert alert-success'>New record created successfully</p>";
    } else {
        echo "<p class='alert alert-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
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
                        href="contacts.html">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover display-5 p-3"
                        href="http://localhost/back-office.php">Back-office</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<h3 class="display-4 text-clear text-center m-2">Welcome to the Contact Zone! </h3>
<h2 class="display-5 text-white text-center m-3">Make sure to stay free from other landig
    ships. <span><i class="bi bi-box-fill"></i></span></h2>

<body class="bg-dark"
    style="background-image: url(./assets/images/nasa--hI5dX2ObAs-unsplash.jpg);width: 100%; background-size: cover;">
    <div class=" container bg-warning rounded p-3">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="firstName" class="form-label">First name (or serial number, for non humanoids)</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
                <div id="formHelp" class="form-text">For legal purposes</div>
            </div>

            <div class="mb-3">
                <label for="lastName" class="form-label">Last name (or date of fabrication, for non felines)</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
                <div id="formHelp" class="form-text">For fun purposes</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address (yes, that old type of email with the @)</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else</div>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Subject of the message</label>
                <select class="form-select" id="subject" name="subject" required>
                    <option value="This Restaurant">This Restaurant</option>
                    <option value="Other Restaurant">Other Restaurant</option>
                    <option value="Closest Star too bright">Closest Star too bright</option>
                    <option value="Food origins">Food origins</option>
                    <option value="Complain">Complain</option>
                    <option value="Rating">Rating</option>
                    <option value="Therapy Section">Therapy Section</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Feel free to add to the subject of the message</label>
                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                <div id="formHelp" class="form-text">We will read and respond (in 3 to 28 Earth Years), unless message
                    lost when crossing space-time-continuum.</div>
            </div>

            <button type="submit" class="btn btn-danger">Submit!</button>
        </form>
    </div>
</body>
<footer class="pt-3 mt-4 text-white border-top display-6">
    © 3024 by Earth Years
</footer>
$nom = $_Post[""]

</html>