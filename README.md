# Restaurant-LLM

## LLM 
- Clause Sonnet (https://claude.ai) - Jully 2024
- GPT 4o (https://chatgpt.com) - July 2024

## Docker
Start by creating a container with all the necessary for a server in order to use php.

On this case we used:
- php apache
- phpmyadmin
- mariadb

Here is the .yml file and also the necessat files to create a functionning container for this project:

> docker-compose.yml
```yml
version: '3'
services:
  web: # also www, this will allow us to use php, as if in a server
    build:
      context: .
      dockerfile: Dockerfile-php
    ports:
      - "80:80" # the ports that will be used, by defaukt http protocole 80 for local : 80 for container linux
    volumes:
      - ./src:/var/www/html # on local ./src directory where the pages will be taken from, /var/www/html is a default for linux
    depends_on:
      - database
  database:
    image: mariadb:10.5 # for or database type, most often seen on videos is mysql instead
    env_file:
      - docker.env # this calls another file that provides the correct logins for phpmyadmin and mysql
    volumes:
      - ./mariadb_data:/var/lib/mysql # local database directory, docker satabase location and the name of it mydb

  phpmyadmin:
    image: phpmyadmin/phpmyadmin # this allows a visual interpreter of the database
    ports:
      - "8080:80"
    env_file:
      - docker.env
    depends_on:
      - database

```
>docker.env
```
# MySQL
MYSQL_ROOT_PASSWORD=root
MYSQL_ROOT_USER=root
MYSQL_HOST=database
MYSQL_DATABASE=mydb

# phpmyadmin
PMA_HOST=database
PMA_PORT=3306
PMA_USER=root
PMA_PASSWORD=root
```
This is used to correctly install mysqli or PDO on the server side in order to be able to call the database when on local
>Dockerfile-php
```
FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable mysqli extension
RUN docker-php-ext-enable mysqli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql
```
---
### HTML + PHP
For the initial page the `contacts.html` was used since the goal was to increment the already existing restaurant page.
Changing `contacts.html` changed to `contacts.php` in order to use <?php>.

This is the [repos](https://github.com/LuanPM284/restaurant-css-framework) for the first restaurant attempt.

In order for the original page to work the following was added:
>form
```HTML
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <!-- post will call to the global variable $_POST -->
    <!-- $_SERVER is a global variable that collects all information going throuw the server -->
    <!-- htmlspecialchars() is function that prevents the use of sql syntax to prompt the database, transforming characters into thier HTML equivalent   -->
```
>input
```HTML
<!-- every input recieved a name="", this is what PHP will call, JS calls id=""-->
<div class="mb-3">
    <label for="lastName" class="form-label">Last name (or date of fabrication, for non felines)</label>
    <input type="text" class="form-control" id="lastName" name="lastName" required>
    <div id="formHelp" class="form-text">For fun purposes</div>
</div>

```
The php tag was used in or
>php
```PHP
<?php
// retrieve data going to server for post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "database"; // This should match the service name in docker-compose.yml # some use just db 
    $username = "root";
    $password = "root";
    $dbname = "mydb"; # the database inside mariadb, can be seen on phpmyadmin localhost:8080, for this case the port is 8080

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname); # a variale thay takes new sql entry
    # before we used: $conn = mysqli_connect(db_assigned_name : $variable,...) 
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    # stmt statement; this is using prepared statements, in order to avoid sql injection attacks, `?` is used as placeholders
    $stmt = $conn->prepare("INSERT INTO contacts (firstName, lastName, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    # we need to bind the correct values for the placeholders, by calling mysqli_stmt_bind_param
    # i int d double s string b blob and will be sent in packets, here we have 5 variables so 5 `s` and their correspoding variables
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $subject, $message);
    #This is also possible, used before : 
    #$stmt = mysqli_stmt_init($conn);
    #msqli_stmt_bind_param($stmt,"sssss", 
                        #  $firstName, 
                        #  $lastName, 
                        #  $email, 
                        #  $subject, 
                        #  $message);
    #mysqli_stmt_execute($stmt) 
    #Set parameters and execute
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    # a more modern version of mysqli_stmt_execute, execute($stmt)
    if ($stmt->execute()) {
        echo "<p class='alert alert-success'>New record created successfully</p>";
    } else {
        echo "<p class='alert alert-danger'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
```
---
We create a new page that will allow the visualisation of the collected data from the `contacts.php` form request.

The `back-office.php` will allow us to retrieve the data from the database and display it using HTML
>php
```PHP
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
```

### Database

