<?php

$db = __DIR__ . "/db.sqlite";

if (file_exists($db))
{
    unlink($db);
}

$userTableCreateQuery = "Create Table IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$users = [
    [
        "name" => "Ahmed Salheia",
        'email' => 'ahmedsalheia.as@gmail.com'
    ],
    [
        'name' => 'mohd ali',
        'email' => 'mohd.ali@gmail.com'
    ],
    [
        'name' => 'ali saleh',
        'email' => 'ali.saleh@gmail.com'
    ]
];


$db = new SQLite3($db);
$db->exec($userTableCreateQuery);

foreach ($users as $user)
{
    $stmt = $db->prepare('INSERT INTO users(name, email) VALUES(:name, :email)');
    $stmt->bindValue(':name', $user['name']);
    $stmt->bindValue(':email', $user['email']);
    $stmt->execute();
}

$db->close();