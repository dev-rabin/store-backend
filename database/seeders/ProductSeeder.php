<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Realme Buds Air 6 Pro',
                'price' => 3799,
                'desc' => 'Premium wireless earbuds with ANC.',
                'category' => 'Earbuds',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=Realme+Buds+Air+6+Pro'
            ],
            [
                'name' => 'UBON J18 Future Pods',
                'price' => 1999,
                'desc' => 'Bluetooth earbuds with display case.',
                'category' => 'Earbuds',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=UBON+J18'
            ],
            [
                'name' => 'pTron Zenbuds Evo TWS',
                'price' => 799,
                'desc' => 'Affordable TWS earbuds.',
                'category' => 'Earbuds',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=pTron+Zenbuds'
            ],
            [
                'name' => 'Ambrane 20000mAh Power Bank',
                'price' => 1199,
                'desc' => 'Fast charging power bank.',
                'category' => 'Power Bank',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=Ambrane+PowerBank'
            ],
            [
                'name' => 'Noise Buds Combat Z',
                'price' => 799,
                'desc' => 'Gaming earbuds with low latency.',
                'category' => 'Earbuds',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=Noise+Buds+Combat+Z'
            ],
            [
                'name' => 'Portronics Luxcell Power Bank',
                'price' => 799,
                'desc' => 'Compact portable charger.',
                'category' => 'Power Bank',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=Portronics+Luxcell'
            ],
            [
                'name' => 'boAt Lunar Discovery',
                'price' => 999,
                'desc' => 'Smartwatch with AMOLED display.',
                'category' => 'Smart Watch',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=boAt+Lunar'
            ],
            [
                'name' => 'Poly Plantronics Earbuds',
                'price' => 17999,
                'desc' => 'Professional wireless earbuds.',
                'category' => 'Earbuds',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=Poly+Earbuds'
            ],
            [
                'name' => 'CMF Charger',
                'price' => 749,
                'desc' => 'Fast charging adapter.',
                'category' => 'Charger',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=CMF+Charger'
            ],
            [
                'name' => 'Boult Audio Z40 Pro',
                'price' => 899,
                'desc' => 'Wireless earbuds with long battery life.',
                'category' => 'Earbuds',
                'img' => 'https://dummyimage.com/600x600/000/fff&text=Boult+Z40+Pro'
            ],
            
    [
        'name' => 'iPhone 16',
        'price' => 99999,
        'desc' => 'Apple flagship smartphone',
        'category' => 'Mobiles',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=iPhone+16'
    ],
    [
        'name' => 'Samsung Galaxy S25',
        'price' => 89999,
        'desc' => 'Samsung premium smartphone',
        'category' => 'Mobiles',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Galaxy+S25'
    ],
    [
        'name' => 'OnePlus 13',
        'price' => 69999,
        'desc' => 'Flagship killer smartphone',
        'category' => 'Mobiles',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=OnePlus+13'
    ],

    // Laptops
    [
        'name' => 'MacBook Air M4',
        'price' => 114999,
        'desc' => 'Apple lightweight laptop',
        'category' => 'Laptops',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=MacBook+Air+M4'
    ],
    [
        'name' => 'Dell XPS 15',
        'price' => 129999,
        'desc' => 'Premium Windows laptop',
        'category' => 'Laptops',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Dell+XPS+15'
    ],
    [
        'name' => 'Lenovo Legion 5',
        'price' => 99999,
        'desc' => 'Gaming laptop',
        'category' => 'Laptops',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Legion+5'
    ],

    // Audio
    [
        'name' => 'Sony WH-1000XM5',
        'price' => 24999,
        'desc' => 'Noise cancelling headphones',
        'category' => 'Audio',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Sony+XM5'
    ],
    [
        'name' => 'JBL Tune 770NC',
        'price' => 6999,
        'desc' => 'Wireless headphones',
        'category' => 'Audio',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=JBL+770NC'
    ],
    [
        'name' => 'Realme Buds Air 6',
        'price' => 3999,
        'desc' => 'ANC wireless earbuds',
        'category' => 'Audio',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Realme+Buds'
    ],

    // Gaming
    [
        'name' => 'PlayStation 5',
        'price' => 54990,
        'desc' => 'Sony gaming console',
        'category' => 'Gaming',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=PS5'
    ],
    [
        'name' => 'Xbox Series X',
        'price' => 52990,
        'desc' => 'Microsoft gaming console',
        'category' => 'Gaming',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Xbox+Series+X'
    ],
    [
        'name' => 'Logitech G502',
        'price' => 4999,
        'desc' => 'Gaming mouse',
        'category' => 'Gaming',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=G502'
    ],

    // Camera
    [
        'name' => 'Canon EOS R10',
        'price' => 78999,
        'desc' => 'Mirrorless camera',
        'category' => 'Camera',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Canon+R10'
    ],
    [
        'name' => 'Sony Alpha A6700',
        'price' => 124999,
        'desc' => 'Professional camera',
        'category' => 'Camera',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Sony+A6700'
    ],
    [
        'name' => 'GoPro Hero 13',
        'price' => 44999,
        'desc' => 'Action camera',
        'category' => 'Camera',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=GoPro+13'
    ],

    // Smart Watch
    [
        'name' => 'Apple Watch Series 10',
        'price' => 49999,
        'desc' => 'Premium smartwatch',
        'category' => 'Smart Watch',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Apple+Watch'
    ],
    [
        'name' => 'Samsung Galaxy Watch 7',
        'price' => 29999,
        'desc' => 'Android smartwatch',
        'category' => 'Smart Watch',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=Galaxy+Watch+7'
    ],
    [
        'name' => 'boAt Lunar Pro',
        'price' => 2999,
        'desc' => 'Affordable smartwatch',
        'category' => 'Smart Watch',
        'img' => 'https://dummyimage.com/600x600/000/fff&text=boAt+Lunar+Pro'
    ]

        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}