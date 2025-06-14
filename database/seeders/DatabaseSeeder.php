<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Models\ServiceCategory;
use App\Models\Blog;
use App\Models\Service;
use Faker\Factory as Faker;


use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\OptionType;
class DatabaseSeeder extends Seeder
{
    /** Pick one random gallery image from 1.jpg…10.jpg */
    protected function randomGallery(int $count = 4): array
    {
        $photos = [];
        while (count($photos) < $count) {
            $n = rand(1, 10) . '.jpg';
            if (! in_array($n, $photos)) {
                $photos[] = $n;
            }
        }
        return $photos;
    }

    public function run()
    {
        // build category maps by slug
        $catMap = ProductCategory::pluck('id', 'slug')->toArray();
        // build option types map: name → id
        $optMap = OptionType::pluck('id', 'name')->toArray();

        $products = [
            [
                'name'        => 'Xbox Series X',
                'slug'        => 'xbox-series-x',
                'category'    => 'gaming-consoles',
                'subcategory' => 'home-consoles',
                'type'        => 'console',
                'price'       => 499.00,
                'quantity'    => 50,
                'tags'        => ['xbox','console','microsoft'],
                'checks'      => ['featured','postage_eligible'],
                'description' => 'Experience next-gen gaming with ultra-high speed SSD and stunning visuals on Xbox Series X.',
                'variations'  => [
                    'Storage' => [
                        ['value'=>'1TB','price'=>40],
                        ['value'=>'2TB','price'=>100],
                    ],
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>50],
                        ['value'=>'+2 years','price'=>90],
                    ],
                ],
            ],
            [
                'name'        => 'PlayStation 5',
                'slug'        => 'playstation-5',
                'category'    => 'gaming-consoles',
                'subcategory' => 'home-consoles',
                'type'        => 'console',
                'price'       => 499.00,
                'quantity'    => 40,
                'tags'        => ['ps5','sony','console'],
                'checks'      => ['featured'],
                'description' => 'Dive into immersive gaming with PlayStation 5, featuring lightning-fast loading and 3D audio.',
                'variations'  => [
                    'Storage' => [
                        ['value'=>'825GB','price'=>40],
                        ['value'=>'1.5TB','price'=>150],
                    ],
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>60],
                        ['value'=>'+2 years','price'=>110],
                    ],
                ],
            ],
            [
                'name'        => 'Gaming Laptop Pro 16"',
                'slug'        => 'gaming-laptop-pro-16',
                'category'    => 'computers',
                'subcategory' => 'laptops',
                'type'        => 'accessory',
                'price'       => 1299.00,
                'quantity'    => 25,
                'tags'        => ['laptop','gaming','16inch'],
                'checks'      => ['postage_eligible'],
                'description' => 'Powerful 16-inch gaming laptop with high-refresh-rate display and advanced cooling.',
                'variations'  => [
                    'Memory (RAM)' => [
                        ['value'=>'16GB','price'=>40],
                        ['value'=>'32GB','price'=>200],
                    ],
                    'Storage' => [
                        ['value'=>'512GB SSD','price'=>40],
                        ['value'=>'1TB SSD','price'=>150],
                    ],
                    'Color' => [
                        ['value'=>'Black','price'=>40],
                        ['value'=>'White','price'=>40],
                    ],
                ],
            ],
            [
                'name'        => 'Wireless Xbox Controller',
                'slug'        => 'wireless-xbox-controller',
                'category'    => 'accessories',
                'subcategory' => 'controllers',
                'type'        => 'accessory',
                'price'       => 59.99,
                'quantity'    => 120,
                'tags'        => ['controller','wireless','xbox'],
                'checks'      => ['postage_eligible'],
                'description' => 'Ergonomic wireless controller with textured grips and responsive thumbsticks.',
                'variations'  => [
                    'Color' => [
                        ['value'=>'Black','price'=>40],
                        ['value'=>'White','price'=>40],
                        ['value'=>'Blue','price'=>5],
                    ],
                ],
            ],
            [
                'name'        => '16GB DDR4 RAM Kit',
                'slug'        => '16gb-ddr4-ram-kit',
                'category'    => 'computers',
                'subcategory' => 'components',
                'type'        => 'repair_part',
                'price'       => 79.99,
                'quantity'    => 200,
                'tags'        => ['ram','memory','ddr4'],
                'checks'      => [],
                'description' => 'High-performance DDR4 memory kit for smoother multitasking and gaming.',
                'variations'  => [
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>10],
                        ['value'=>'+2 years','price'=>18],
                    ],
                ],
            ],
            [
                'name'        => '512GB NVMe SSD',
                'slug'        => '512gb-nvme-ssd',
                'category'    => 'computers',
                'subcategory' => 'components',
                'type'        => 'repair_part',
                'price'       => 89.99,
                'quantity'    => 150,
                'tags'        => ['ssd','nvme','storage'],
                'checks'      => ['postage_eligible'],
                'description' => 'Ultra-fast NVMe solid-state drive with 512GB capacity for quick load times.',
                'variations'  => [
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>12],
                        ['value'=>'+2 years','price'=>20],
                    ],
                ],
            ],
            [
                'name'        => 'PlayStation DualSense Controller',
                'slug'        => 'ps5-dualsense-controller',
                'category'    => 'accessories',
                'subcategory' => 'controllers',
                'type'        => 'accessory',
                'price'       => 69.99,
                'quantity'    => 80,
                'tags'        => ['controller','sony','dualsense'],
                'checks'      => ['featured','postage_eligible'],
                'description' => 'Haptic feedback and adaptive triggers bring games to life in your hands.',
                'variations'  => [
                    'Color' => [
                        ['value'=>'White','price'=>40],
                        ['value'=>'Midnight Black','price'=>5],
                    ],
                ],
            ],
            [
                'name'        => 'Xbox Game Pass Ultimate (3 Months)',
                'slug'        => 'xbox-game-pass-3m',
                'category'    => 'subscriptions',
                'subcategory' => 'game-passes',
                'type'        => 'accessory',
                'price'       => 44.99,
                'quantity'    => 999,
                'tags'        => ['subscription','xbox','gamepass'],
                'checks'      => [],
                'description' => 'Get unlimited access to over 100 high-quality games on console and PC.',
                'variations'  => [
                    'Controller Bundle' => [
                        ['value'=>'Include Controller','price'=>59.99],
                        ['value'=>'No Controller','price'=>40],
                    ],
                ],
            ],
            [
                'name'        => 'PS Plus Membership (12 Months)',
                'slug'        => 'ps-plus-12m',
                'category'    => 'subscriptions',
                'subcategory' => 'game-passes',
                'type'        => 'accessory',
                'price'       => 59.99,
                'quantity'    => 999,
                'tags'        => ['subscription','ps5','psplus'],
                'checks'      => [],
                'description' => 'Enjoy free monthly games, exclusive discounts, and online multiplayer.',
                'variations'  => [
                    'Controller Bundle' => [
                        ['value'=>'Include DualSense','price'=>69.99],
                        ['value'=>'No Controller','price'=>40],
                    ],
                ],
            ],
            [
                'name'        => 'Valve Steam Deck',
                'slug'        => 'valve-steam-deck',
                'category'    => 'gaming-consoles',
                'subcategory' => 'handheld-consoles',
                'type'        => 'console',
                'price'       => 399.99,
                'quantity'    => 30,
                'tags'        => ['steam','handheld','portable'],
                'checks'      => ['featured'],
                'description' => 'Portable PC gaming device with custom AMD APU and ergonomic controls.',
                'variations'  => [
                    'Storage' => [
                        ['value'=>'64GB eMMC','price'=>40],
                        ['value'=>'256GB NVMe','price'=>100],
                        ['value'=>'512GB NVMe','price'=>200],
                    ],
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>45],
                        ['value'=>'+2 years','price'=>80],
                    ],
                ],
            ],
            [
                'name'        => 'HyperX Cloud II Gaming Headset',
                'slug'        => 'hyperx-cloud-ii-gaming-headset',
                'category'    => 'accessories',
                'subcategory' => 'controllers',
                'type'        => 'accessory',
                'price'       => 99.99,
                'quantity'    => 100,
                'tags'        => ['headset','gaming','audio'],
                'checks'      => ['postage_eligible'],
                'description' => '7.1 surround sound headset with memory foam ear cushions for comfort.',
                'variations'  => [
                    'Color' => [
                        ['value'=>'Black','price'=>40],
                        ['value'=>'Red','price'=>40],
                    ],
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>15],
                        ['value'=>'+2 years','price'=>25],
                    ],
                ],
            ],
            [
                'name'        => 'Razer Gaming Mouse V2',
                'slug'        => 'razer-gaming-mouse-v2',
                'category'    => 'accessories',
                'subcategory' => 'controllers',
                'type'        => 'accessory',
                'price'       => 59.99,
                'quantity'    => 150,
                'tags'        => ['mouse','gaming','razer'],
                'checks'      => ['featured','postage_eligible'],
                'description' => 'Lightweight ergonomic mouse with ultra-precise optical sensor.',
                'variations'  => [
                    'Color' => [
                        ['value'=>'Black','price'=>40],
                        ['value'=>'White','price'=>40],
                    ],
                    'Warranty Extension' => [
                        ['value'=>'+1 year','price'=>12],
                        ['value'=>'+2 years','price'=>20],
                    ],
                ],
            ],
        ];

        foreach ($products as $p) {
            $gallery = $this->randomGallery();
            $main    = $gallery[0];

            $prod = Product::create([
                'name'            => $p['name'],
                'slug'            => $p['slug'],
                'sku'             => 'PRD'.Str::upper(Str::random(6)),
                'category_id'     => $catMap[$p['category']] ?? null,
                'subcategory_id'  => $catMap[$p['subcategory']] ?? null,
                'product_type'    => $p['type'],
                'description'     => $p['description'],
                'price'           => $p['price'],
                'quantity'        => $p['quantity'],
                'tags'            => json_encode($p['tags']),
                'checks'          => json_encode($p['checks']),
                'gallery'         => json_encode($gallery),
                'main_image'      => $main,
                'status'          => 1,
            ]);

            // build and save variations
            $variations = [];
            foreach ($p['variations'] as $typeName => $vals) {
                $typeId = $optMap[$typeName] ?? null;
                if (! $typeId) continue;
                $values = [];
                foreach ($vals as $v) {
                    $values[] = [
                        'value'            => $v['value'],
                        'additional_price' => $v['price'],
                        'status'           => 1,
                    ];
                }
                $variations[] = [
                    'option_type_id'   => $typeId,
                    'option_type_name' => $typeName,
                    'values'           => $values,
                ];
            }
            $prod->update(['variations' => json_encode($variations)]);
        }
    }
}