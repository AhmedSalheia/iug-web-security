<?php

define('ENC_KEY', 'ThesIsAKeyTHaTIHaveToKEePprIVATEFromThEOThErPeOleToKeEpITSaFEHerE');
define('CIPHER_ALGO', 'aes-256-cbc');

function encrypt($data) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(CIPHER_ALGO))?:'';
    $enc_data = openssl_encrypt($data, CIPHER_ALGO, base64_decode(ENC_KEY), 0, $iv);
    return base64_encode($enc_data . '::' . $iv);
}

function decrypt($data){
    list($enc_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
    return openssl_decrypt($enc_data, CIPHER_ALGO, base64_decode(ENC_KEY), 0, $iv);
}

if (!empty($_POST))
{
    $data = $_POST;
    $method = strtolower(htmlentities(strip_tags($data['method'])));
    $text = htmlentities(strip_tags($data['text']));

    if ($method == 'encrypt') {
        $res = encrypt($text);
    } elseif ($method == 'decrypt') {
        $res = decrypt($text);
    } else {
        throw new Exception("Submission Error, Invalid Method",400);
    }

    echo $res;
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
        <textarea name="text" id="text" placeholder="Text to Encrypt/Decrypt"
        style="height: 45vh; width: 90vw"
        ></textarea>
        <br />
        <br />
        <button type="button" name="method" value="encrypt">Encrypt</button>
        <button type="button" name="method" value="decrypt">Decrypt</button>
        <br />
        <br />
        <textarea name="result" id="text" placeholder="Result Will Appear Here"
                  style="height: 45vh; width: 90vw"
                  readonly
        ></textarea>
    </form>

    <script>
        let buttons = document.querySelectorAll('button[name="method"]')
        for (let btn of buttons) {
            btn.addEventListener('click', submit)
        }

        function submit(ev) {
            ev.preventDefault();

            let url = "<?=  $_SERVER['REQUEST_URI'] ?>"
            let textarea = document.querySelector('form > textarea[name="text"]'),
                result = document.querySelector('form > textarea[name="result"]'),
                body = new FormData()

            if (!textarea)
                throw new Error('Something Is Wrong');
            else if (textarea.value === "") {

                textarea.style.border = '2px solid red'
                return;

            } else {
                if (textarea.style.border === '2px solid red')
                    textarea.style.border = ''
            }

            body.append('method', this.value)
            body.append('text', textarea.value)

            fetch(url, {
                method: 'POST',
                body: body
            }).then((res, err) => {
                res.text().then((text) => result.value = text)
            })
        }
    </script>
</body>
</html>