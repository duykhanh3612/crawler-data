<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiki Products</title>
</head>
<body>
    <h1>Tiki Products</h1>

    @foreach ($products as $product)
        <div>
            <h2>{{ $product['name'] }}</h2>
            <p>Price: {{ number_format($product['price']) }} VND</p>
            <p>Discount: {{ number_format($product['discount']) }} VND</p>
            <p>Rating: {{ $product['rating_average'] }}</p>
            <img src="{{ $product['thumbnail_url'] }}" alt="{{ $product['name'] }}" width="100" height="100">
        </div>
        <hr>
    @endforeach
</body>
</html>
