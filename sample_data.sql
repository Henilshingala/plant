-- Insert sample categories
INSERT INTO categories (name, description, image) VALUES
('Indoor Plants', 'Beautiful plants that thrive indoors', 'indoor-plants.jpg'),
('Outdoor Plants', 'Perfect plants for your garden', 'outdoor-plants.jpg'),
('Succulents', 'Low-maintenance desert plants', 'succulents.jpg'),
('Flowering Plants', 'Colorful plants that bloom', 'flowering-plants.jpg');

-- Insert sample products
INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'Snake Plant', 'A hardy indoor plant that purifies air', 29.99, 50, 'snake-plant.jpg'),
(1, 'Peace Lily', 'Elegant white flowers and glossy leaves', 34.99, 30, 'peace-lily.jpg'),
(1, 'Spider Plant', 'Easy to care for with arching leaves', 24.99, 40, 'spider-plant.jpg'),
(2, 'Rose Bush', 'Classic garden rose with fragrant blooms', 49.99, 20, 'rose-bush.jpg'),
(2, 'Lavender', 'Aromatic herb with purple flowers', 19.99, 35, 'lavender.jpg'),
(2, 'Japanese Maple', 'Ornamental tree with stunning foliage', 79.99, 15, 'japanese-maple.jpg'),
(3, 'Aloe Vera', 'Medicinal succulent with healing properties', 14.99, 60, 'aloe-vera.jpg'),
(3, 'Jade Plant', 'Lucky plant with thick, glossy leaves', 19.99, 45, 'jade-plant.jpg'),
(3, 'Echeveria', 'Rosette-forming succulent with pastel colors', 12.99, 55, 'echeveria.jpg'),
(4, 'Orchid', 'Exotic flowering plant with long-lasting blooms', 59.99, 25, 'orchid.jpg'),
(4, 'Gerbera Daisy', 'Cheerful daisy with large, colorful flowers', 22.99, 40, 'gerbera-daisy.jpg'),
(4, 'African Violet', 'Compact plant with velvety leaves and flowers', 18.99, 30, 'african-violet.jpg'); 