<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('search') }}" method="get">
        <input type="text" name="search">
        <!-- <input type="number" name="age">
        <input type="text" name="job"> -->
        <button>Send</button>
    </form>    



</body>
</html>