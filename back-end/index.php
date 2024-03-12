<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Import CSV to Database</title>
</head>

<body>
    <form action="./uploadCSV.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" id="csv_file">
        <input type="submit" value="Upload CSV" name="submit">
    </form>
</body>

</html>