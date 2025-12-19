<?php

session_start();

$data = $_REQUEST;
$method = strtolower(htmlentities(strip_tags($data['method'] ?? '')));

$db_file = __DIR__ . '/db.sqlite';
$db = new SQLite3($db_file);


if (!empty($_POST)) {
    $name = htmlentities(strip_tags($data['name']));

    if ($method == 'search') {
        $hasRows = false;

        $fetch = $db->prepare('SELECT * FROM users WHERE name LIKE :name');
        $fetch->bindValue(':name', "%$name%");
        $rows = $fetch->execute();
        
        while ($user = $rows->fetchArray(SQLITE3_ASSOC)) {
            $hasRows = true;
            echo $user['id'] . "\t|\t" . $user['name'] . "\t|\t" . $user['email'] . "\n";
        }

        if (!$hasRows) {
            echo "No Results For the Current Search Param $name";
        }
    }

    exit();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment 01</title>
</head>

<body>
    <form method="post" enctype="multipart/form-data" onsubmit="(ev) => ev.preventDefault()">
        <label for="search">
            Name To Search: 
            <input type="text" name="search" id="search" placeholder="A Name To Search On The Database" style="width: 15vwl; padding: 5px" />
        </label>
        <br />
        <br />
        <button type="button" name="method" value="search">Search</button>
        <br />
        <br />
        <textarea name="result" id="result" placeholder="Result Will Appear Here"
            style="height: 45vh; width: 90vw; padding: 10px"
            readonly> 
        </textarea>
    </form>

    <script>
        let buttons = document.querySelectorAll('button[name="method"]')
        const url = "<?= $_SERVER['REQUEST_URI'] ?>"
        let hash, verify,
            result = document.querySelector('form > textarea[name="result"]')

        for (let btn of buttons) {
            btn.addEventListener('click', submit)
        }

        document.onkeydown = (ev) => {
            if (ev.key == 'Enter')
                ev.preventDefault()
        }

        function submit(ev) {
            ev.preventDefault();

            let name = document.querySelector('form > label > input[name="search"]'),
                body = new FormData()

            if (!name)
                throw new Error('Something Is Wrong');

            else if (name.value === "") {

                name.style.border = '2px solid red'
                return;

            } else {
                if (name.style.border === '2px solid red')
                    name.style.border = ''
            }

            body.append('method', this.value)
            body.append('name', name.value)

            fetch(url, {
                method: 'POST',
                body: body
            }).then((res) => res.text())
            .then((text) => {
                result.value = text
            })
        }
    </script>
</body>

</html>