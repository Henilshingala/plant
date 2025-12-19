<?php
// Create directories if they don't exist
$directories = [
    'assets/images/products',
    'assets/images/categories',
    'assets/images/about',
    'assets/images/team'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Create an HTML file that will generate our placeholder images
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Generate Placeholder Images</title>
</head>
<body>
    <canvas id="canvas" style="display:none;"></canvas>
    <script>
        function createImage(text, width, height, filename) {
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            canvas.width = width;
            canvas.height = height;
            
            // Fill background
            ctx.fillStyle = '#228B22'; // Forest green
            ctx.fillRect(0, 0, width, height);
            
            // Set text style
            ctx.fillStyle = 'white';
            ctx.font = 'bold 24px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            // Add text
            ctx.fillText(text, width/2, height/2);
            
            // Convert to data URL
            const dataURL = canvas.toDataURL('image/jpeg');
            
            // Create download link
            const link = document.createElement('a');
            link.download = filename;
            link.href = dataURL;
            link.click();
        }
        
        // Generate category images
        const categories = [
            ['Indoor Plants', 800, 400, 'indoor-plants.jpg'],
            ['Outdoor Plants', 800, 400, 'outdoor-plants.jpg'],
            ['Succulents', 800, 400, 'succulents.jpg'],
            ['Flowering Plants', 800, 400, 'flowering-plants.jpg']
        ];
        
        // Generate product images
        const products = [
            ['Snake Plant', 400, 400, 'snake-plant.jpg'],
            ['Peace Lily', 400, 400, 'peace-lily.jpg'],
            ['Spider Plant', 400, 400, 'spider-plant.jpg'],
            ['Rose Bush', 400, 400, 'rose-bush.jpg'],
            ['Lavender', 400, 400, 'lavender.jpg'],
            ['Japanese Maple', 400, 400, 'japanese-maple.jpg'],
            ['Aloe Vera', 400, 400, 'aloe-vera.jpg'],
            ['Jade Plant', 400, 400, 'jade-plant.jpg'],
            ['Echeveria', 400, 400, 'echeveria.jpg'],
            ['Orchid', 400, 400, 'orchid.jpg'],
            ['Gerbera Daisy', 400, 400, 'gerbera-daisy.jpg'],
            ['African Violet', 400, 400, 'african-violet.jpg']
        ];
        
        // Generate all images
        [...categories, ...products].forEach(([text, width, height, filename]) => {
            createImage(text, width, height, filename);
        });
    </script>
</body>
</html>
HTML;

// Save the HTML file
file_put_contents('generate_images.html', $html);

echo "Please open generate_images.html in your browser to generate the placeholder images. 
      After the images are generated, move them to their respective folders:
      - Category images go to assets/images/categories/
      - Product images go to assets/images/products/";
?> 