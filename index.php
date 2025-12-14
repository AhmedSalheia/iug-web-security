<?php

session_start();

$data = $_REQUEST;
$method = strtolower(htmlentities(strip_tags($data['method'] ?? '')));

if (!empty($_POST)) {
    $password = htmlentities(strip_tags($data['password']));

    if ($method == 'hash') {

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $_SESSION['hash'] = $hashed;
        echo 'H: ' . $hashed;

    } elseif ($method == 'verify') {

        if (!isset($_SESSION['hash'])){
            echo 'E: Please Run Tha Hash Method First To Save A Hash To Verify To';
            exit();
        }

        $hash = $_SESSION['hash'];
        $res = password_verify($password, $hash);
        echo "V: " . ($res?'True':'False');
    } else {
        throw new Exception("Submission Error, Invalid Method", 400);
    }

    exit();
} elseif (!empty($_GET)) {
    if ($method == 'get_hash') {
        echo $_SESSION['hash'] ?? 'H: No Value Yet (Click Hash To Save a Value)';
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
        <label for="password">
            Password: 
            <input type="text" name="password" id="password" placeholder="Your Password" style="width: 15vwl; padding: 5px" />
        </label>
        <br />
        <br />
        <button type="button" name="method" value="hash">Hash</button>
        <button type="button" name="method" value="verify">Verify</button>
        <br />
        <br />
        <textarea name="hash" id="hash" placeholder="Result Will Appear Here"
            style="height: 45vh; width: 90vw; padding: 10px"
            readonly>
            Hash: 
            Verify: 
        </textarea>
    </form>

    <script>
        let buttons = document.querySelectorAll('button[name="method"]')
        const url = "<?= $_SERVER['REQUEST_URI'] ?>"
        let hash, verify,
            result = document.querySelector('form > textarea[name="hash"]')

        for (let btn of buttons) {
            btn.addEventListener('click', submit)
        }

        function update_result(hash_value = null, verify_value = null) {
            if (hash_value != null)
                hash = hash_value

            if (verify_value != null)
                verify = verify_value
            else
                verify = 'No Verification Request Yet'

            result.value = `Hash: ${hash}\nVerify: ${verify}`
        }

        function get_saved_hash() {
            fetch(url + "?method=get_hash")
                .then((res) => res.text())
                .then(text => update_result(text.substr(3)))
        }

        get_saved_hash()

        function submit(ev) {
            ev.preventDefault();

            let password = document.querySelector('form > label > input[name="password"]'),
                body = new FormData()

            if (!password)
                throw new Error('Something Is Wrong');

            else if (password.value === "") {

                password.style.border = '2px solid red'
                return;

            } else {
                if (password.style.border === '2px solid red')
                    password.style.border = ''
            }

            body.append('method', this.value)
            body.append('password', password.value)

            fetch(url, {
                method: 'POST',
                body: body
            }).then((res) => res.text())
            .then((text) => {
                let message = text.substr(3);

                if (text.startsWith('E: '))
                    alert(message);

                else if (text.startsWith('H: '))
                    update_result(message)

                else if (text.startsWith('V: '))
                    update_result(null,message)
            })
        }
    </script>
</body>

</html>