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
    password TEXT NOT NULL,
    role TEXT CHECK(role IN ('admin','user')) DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$users = [
    [
        "name" => "Ahmed Salheia",
        'email' => 'ahmedsalheia.as@gmail.com',
        'password' => 'PASSWORD',
        'role' => "admin"
    ],
    [
        'name' => 'mohd ali',
        'email' => 'mohd.ali@gmail.com',
        'password' => 'PASSWORD'
    ],
    [
        'name' => 'ali saleh',
        'email' => 'ali.saleh@gmail.com',
        'password' => 'PASSWORD'
    ]
];


$db = new SQLite3($db);
$db->exec($userTableCreateQuery);

foreach ($users as $user)
{
    $stmt = $db->prepare('INSERT INTO users(name, email,password, role) VALUES(:name, :email, :password, :role)');
    $stmt->bindValue(':name', $user['name']);
    $stmt->bindValue(':email', $user['email']);
    $stmt->bindValue(':password', password_hash($user['password'], PASSWORD_BCRYPT));
    $stmt->bindValue(':role', $user['role'] ?? null);
    $stmt->execute();
}

$db->close();