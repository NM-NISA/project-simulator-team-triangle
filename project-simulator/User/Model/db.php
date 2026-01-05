<?php
class DatabaseConnection {
    function openConnection() {
        $host = "127.0.0.1";
        $user = "root";
        $pass = "";
        $db   = "campus_marketplace";

        $conn = mysqli_connect($host, $user, $pass, $db);

        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        return $conn; 
    }

    function signUp($connection, $tableName, $name, $email, $password, $user_type) {
    $name  = mysqli_real_escape_string($connection, $name);
    $email = mysqli_real_escape_string($connection, $email);
    $user_type = mysqli_real_escape_string($connection, $user_type);

    $sql = "INSERT INTO ".$tableName." (name, email, password, user_type)
            VALUES ('$name', '$email', '$password', '$user_type')";

    $result = mysqli_query($connection, $sql);
    if (!$result) {
        die("Insert Failed: " . mysqli_error($connection));
    }

    return $result;
}

    function signin($connection, $tableName, $email) {
    $stmt = $connection->prepare(
        "SELECT * FROM $tableName WHERE email = ? AND status = 'active' LIMIT 1"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    return $stmt->get_result();
}

    function closeConnection($connection) {
        if ($connection) mysqli_close($connection);
    }
}
?>
