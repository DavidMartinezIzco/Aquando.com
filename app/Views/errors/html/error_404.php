<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Error</title>
    <link rel="stylesheet" type="text/css" href="css/fontawesome/css/all.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome/css/all.css">
    <style>
    div.logo {
        height: 200px;
        width: 155px;
        display: inline-block;
        opacity: 0.08;
        position: absolute;
        top: 2rem;
        left: 50%;
        margin-left: -73px;
    }

    body {
        height: 100%;
        background: #fafafa;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        color: #777;
        font-weight: 300;
    }

    h1 {
        font-weight: lighter;
        letter-spacing: 0.8;
        font-size: 3rem;
        margin-top: 0;
        margin-bottom: 0;
        color: #222;
    }

    .wrap {
        max-width: 1024px;
        margin: 5rem auto;
        padding: 2rem;
        background: #fff;
        text-align: center;
        border: 1px solid #efefef;
        border-radius: 0.5rem;
        position: relative;
    }

    pre {
        white-space: normal;
        margin-top: 1.5rem;
    }

    code {
        background: #fafafa;
        border: 1px solid #efefef;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        display: block;
    }

    p {
        margin-top: 1.5rem;
    }

    .footer {
        margin-top: 2rem;
        border-top: 1px solid #efefef;
        padding: 1em 2em 0 2em;
        font-size: 85%;
        color: #999;
    }

    a:active,
    a:link,
    a:visited {
        color: #dd4814;
    }
    </style>
</head>

<body>
    <div class="wrap">
        <h1>Sección desconocida</h1>
        <p>
            <?php if (!empty($message) && $message !== '(null)') : ?>
            <?= nl2br(esc($message)) ?>
            <?php else : ?>
            Parece que algo no ha ido como debía, revisa tu conexión

            <?php endif ?>
        </p>
        <br>
            <a href="http://dateando.ddns.net:3000/Aquando.com/index.php/Inicio/">Volver al inicio</a>
        <br>
            <i style="margin-top:0.5em;text-align:center;font-size:12em" class="fas fa-unlink"></i>
    </div>
</body>

</html>