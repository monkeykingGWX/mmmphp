<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>上传单个文件</p>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file" />
    <input type="submit" value="上传文件" />
</form>
<p>上传多个文件</p>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file[]" multiple="multiple" accept="image/*" />
    <input type="submit" value="上传文件" />
</form>
</body>
</html>