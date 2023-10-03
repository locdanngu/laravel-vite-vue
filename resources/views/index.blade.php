<!DOCTYPE html>
<html>

<head>
    <title>Hello, World!</title>
</head>

<body>
    <div id="app">
        <ul>
            <li v-for="product in products">{{ product.name }}</li>
        </ul>
    </div>

    @vite('resources/js/app.js')
</body>

</html>