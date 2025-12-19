<?php
require_once 'config.php';

// Clear existing data
$conn->query("DELETE FROM cart");
$conn->query("DELETE FROM order_items");
$conn->query("DELETE FROM orders");
$conn->query("DELETE FROM products");
$conn->query("DELETE FROM categories");

// Insert categories
$categories = [
    ['name' => 'Indoor Plants', 'image' => 'indoor-plants.jpg'],
    ['name' => 'Outdoor Plants', 'image' => 'outdoor-plants.jpg'],
    ['name' => 'Succulents', 'image' => 'succulents.jpg']
];

$category_ids = [];
foreach ($categories as $category) {
    $name = $conn->real_escape_string($category['name']);
    $image = $conn->real_escape_string($category['image']);
    $conn->query("INSERT INTO categories (name, image) VALUES ('$name', '$image')");
    $category_ids[$category['name']] = $conn->insert_id;
}

// Insert products
$products = [
    // Indoor Plants
    [
        'name' => 'Peace Lily',
        'description' => 'A beautiful indoor plant known for its air-purifying qualities.',
        'price' => 29.99,
        'stock' => 50,
        'image' => 'peace-lily.jpg',
        'category_name' => 'Indoor Plants'
    ],
    [
        'name' => 'Snake Plant',
        'description' => 'A hardy indoor plant that requires minimal care.',
        'price' => 24.99,
        'stock' => 40,
        'image' => 'snake-plant.jpg',
        'category_name' => 'Indoor Plants'
    ],
    [
        'name' => 'Spider Plant',
        'description' => 'A popular indoor plant with long, arching leaves.',
        'price' => 19.99,
        'stock' => 60,
        'image' => 'spider-plant.jpg',
        'category_name' => 'Indoor Plants'
    ],
    [
        'name' => 'African Violet',
        'description' => 'A compact indoor plant with beautiful purple flowers.',
        'price' => 15.99,
        'stock' => 30,
        'image' => 'african-violet.jpg',
        'category_name' => 'Indoor Plants'
    ],
    
    // Outdoor Plants
    [
        'name' => 'Japanese Maple',
        'description' => 'A stunning outdoor tree with delicate foliage.',
        'price' => 49.99,
        'stock' => 25,
        'image' => 'japanese-maple.jpg',
        'category_name' => 'Outdoor Plants'
    ],
    [
        'name' => 'Rose Bush',
        'description' => 'A classic outdoor plant with beautiful blooms.',
        'price' => 34.99,
        'stock' => 35,
        'image' => 'rose-bush.jpg',
        'category_name' => 'Outdoor Plants'
    ],
    [
        'name' => 'Lavender',
        'description' => 'A fragrant outdoor plant perfect for gardens.',
        'price' => 19.99,
        'stock' => 45,
        'image' => 'lavender.jpg',
        'category_name' => 'Outdoor Plants'
    ],
    [
        'name' => 'Hydrangea',
        'description' => 'Beautiful flowering shrub with large, showy blooms.',
        'price' => 39.99,
        'stock' => 30,
        'image' => 'hydrangea.jpg',
        'category_name' => 'Outdoor Plants'
    ],
    
    // Succulents
    [
        'name' => 'Aloe Vera',
        'description' => 'A medicinal succulent with healing properties.',
        'price' => 14.99,
        'stock' => 70,
        'image' => 'aloe-vera.jpg',
        'category_name' => 'Succulents'
    ],
    [
        'name' => 'Jade Plant',
        'description' => 'A popular succulent known as the money plant.',
        'price' => 12.99,
        'stock' => 55,
        'image' => 'jade-plant.jpg',
        'category_name' => 'Succulents'
    ],
    [
        'name' => 'Echeveria',
        'description' => 'A beautiful rosette-forming succulent.',
        'price' => 9.99,
        'stock' => 80,
        'image' => 'echeveria.jpg',
        'category_name' => 'Succulents'
    ],
    [
        'name' => 'String of Pearls',
        'description' => 'Trailing succulent with unique pearl-like leaves.',
        'price' => 16.99,
        'stock' => 40,
        'image' => 'string-of-pearls.jpg',
        'category_name' => 'Succulents'
    ]
];

foreach ($products as $product) {
    $name = $conn->real_escape_string($product['name']);
    $description = $conn->real_escape_string($product['description']);
    $price = (float)$product['price'];
    $stock = (int)$product['stock'];
    $image = $conn->real_escape_string($product['image']);
    $category_id = $category_ids[$product['category_name']];
    
    $conn->query("INSERT INTO products (name, description, price, stock, image, category_id) 
                 VALUES ('$name', '$description', $price, $stock, '$image', $category_id)");
}

echo "Sample data has been added successfully!";
?> 