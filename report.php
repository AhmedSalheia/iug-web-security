<?php

session_start();

if (isset($_SESSION['user']))
{

    $user = unserialize(base64_decode($_SESSION['user']));

} else {
    header('Location: /index.php');
    exit();
}

$data = $_GET;
$type = strtolower(htmlentities(strip_tags($data['type'] ?? 'user')));

if ($type === 'admin')
{
    if ($user['role'] !== 'admin')
        header('Location: /report.php?type=user');
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
</head>
<body>
<h1>Hi, <?=  $user['name']  ?></h1>
<?php


if ($type === 'user')
{
    ?>
    <h2>User Report</h2>
    <p>Here's Some User Report, All Logged In Users Should Be Able To See It</p>
    <?php

} elseif ($type === 'admin') {

    ?>
    <h2>Admin Report</h2>
    <p>This is a pretty critical report that only admins should ever be able to see ... If you're seeing this and you're not an admin, We'll pay you a visit, with the police!!</p>
    <?php

} else {
    throw new Exception("Bad Request", 400);
}

?>
<h4><a href="/index.php?logout=">logout</a></h4>
</body>
</html>