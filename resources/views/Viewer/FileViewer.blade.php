<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview Dokumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: #ffffff;
            overflow: hidden;
        }

        iframe {
            border: none;
            width: 100%;
            height: 100vh;
            display: block;
        }
    </style>
</head>
<body>

    <iframe src="{{ $file }}"></iframe>

</body>
</html>
