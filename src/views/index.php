<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>upload</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?= $foo ?></h1>
<form action="/upload" method="post" enctype="multipart/form-data">
    <input type="file" name="receipt"/>
    <button type="submit">Upload</button>
</form>
</body>
</html>