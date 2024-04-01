<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
</head>
<body>
    <h1>Order Management</h1>

    <!-- Formulario para crear una nueva orden -->
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <label for="recipe">Recipe:</label>
        <select name="recipe" id="recipe">
            <option value="Pizza">Pizza</option>
            <option value="Hamburger">Hamburger</option>
            <!-- Otras opciones de recetas -->
        </select>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>
        <button type="submit">Add Order</button>
    </form>

    <!-- Lista de órdenes existentes -->
    <h2>Existing Orders</h2>
    <ul>
        @foreach ($orders as $order)
            <li>
                <strong>Order Number:</strong> {{ $order->id }}<br>
                <strong>Recipe:</strong> {{ $order->recipe }}<br>
                <strong>Quantity:</strong> {{ $order->quantity }}<br>
                <!-- Otra información relevante de la orden -->
            </li>
        @endforeach
    </ul>
</body>
</html>
