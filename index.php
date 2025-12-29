<?php

session_start();

$db_file = __DIR__ . '/db.sqlite';
if (!file_exists($db_file))
{
    require './db.php';
}

$db = new SQLite3($db_file);
$db_fetch_user = 'SELECT * FROM users WHERE email=:email';

$data = $_POST;

if (isset($data['submit']) && isset($data['email']) && !empty($data['email']) && isset($data['password']) && !empty($data['password']))
{
    $email = strtolower(htmlentities(strip_tags($data['email'])));
    $password = htmlentities(strip_tags($data['password']));

    $user_stmt = $db->prepare($db_fetch_user);
    $user_stmt->bindValue(':email', $email);
    $user = ($user_stmt->execute())->fetchArray(SQLITE3_ASSOC);

    if ((!$user) || !password_verify($password, $user['password']))
    {
        echo "invalid email or password!<br/><br/>";
    } else {
        $_SESSION['user'] = base64_encode(serialize($user));
        
        echo "<a href='/report.php?type=user'>See User Reports</a><br /><br />";
        if ($user['role'] === 'admin')
            echo "<a href='/report.php?type=admin'>See Admin Reports</a>";
        
        exit();
    }
}

if (isset($data['logout']))
{
    session_destroy();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Login Page</title>
    <style>
        * {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <form action="<?=  $_SERVER['PHP_SELF']  ?>" method="post">
        Email: <input type="email" name="email" placeholder="Write Your Email Here" required />
        <br />
        <br />
        Password: <input type="password" name="password" placeholder="Write Your passowrd Here" required />
        <br />
        <br />
        <input type="submit" name="submit" value="submit" />
    </form>
</body>
</html>