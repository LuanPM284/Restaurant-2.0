<?php

$connect = mysqli_connect(
    'database', # service name
    'root', # username
    'root', # password
    'mydb' # db table
);

$table_name = "contact";

$query = "SELECT * FROM $table_name";

$response = mysqli_query($connect, $query);

echo "<strong>$table_name: </strong>";
while ($i = mysqli_fetch_assoc($response)) {
    echo "<p>" . $i['first_name'] . "</p>";
    echo "<p>" . $i['last_name'] . "</p>";
    echo "<p>" . $i['email'] . "</p>";
    echo "<p>" . $i['subject_list'] . "</p>";
    echo "<p>" . $i['message'] . "</p>";
    echo "<p>" . $i['date'] . "</p>";
    echo "<hr>";
}
