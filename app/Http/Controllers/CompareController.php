<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompareController extends Controller
{
    public function comparePrices($productName)
    {
        // Fetch the product details by name
        $product = $this->getProductByName($productName);

        if (!$product) {
            return abort(404, 'Product not found');
        }

        // Fetch similar products from different stores or databases
        $similarProducts = $this->getSimilarProducts($product);

        return view('compare', ['product' => $product, 'similarProducts' => $similarProducts]);
    }

    private function getProductByName($name)
    {
        // Load JSON files
        $colesJson = File::get(storage_path('coles.json'));
        $woolworthsJson = File::get(storage_path('woolworths.json'));

        // Decode JSON data to PHP arrays
        $colesData = json_decode($colesJson, true);
        $woolworthsData = json_decode($woolworthsJson, true);

        // Merge data from both JSON files
        $allProducts = array_merge($colesData, $woolworthsData);

        // Normalize each product
        $allProducts = array_map([$this, 'normalizeProduct'], $allProducts);

        // Search for the product by name
        foreach ($allProducts as $product) {
            if (stripos($product['name'], $name) !== false) {
                return $product;
            }
        }

        return null;
    }

    private function getSimilarProducts($product)
    {
        // Load JSON files
        $colesJson = File::get(storage_path('coles.json'));
        $woolworthsJson = File::get(storage_path('woolworths.json'));

        // Decode JSON data to PHP arrays
        $colesData = json_decode($colesJson, true);
        $woolworthsData = json_decode($woolworthsJson, true);

        // Merge data from both JSON files
        $allProducts = array_merge($colesData, $woolworthsData);

        // Normalize each product
        $allProducts = array_map([$this, 'normalizeProduct'], $allProducts);

        // Find similar products in the same category but different names
        $similarProducts = array_filter($allProducts, function ($item) use ($product) {
            return $item['category'] === $product['category'] && $item['name'] !== $product['name'];
        });

        return $similarProducts;
    }

    private function normalizeProduct($product)
    {
        // Normalize the imgSrc key
        if (isset($product['imgSrc'])) {
            $product['imgSrc'] = $product['imgSrc'];
        } elseif (isset($product['image_url'])) {
            $product['imgSrc'] = $product['image_url'];
        } else {
            $product['imgSrc'] = 'default-image.jpg'; // Fallback image
        }

        // Normalize the price key
        if (!isset($product['price'])) {
            $product['price'] = 'N/A';
        } elseif (is_string($product['price'])) {
            $product['price'] = preg_replace('/[^\d.]/', '', $product['price']); // Remove any non-numeric characters
        }

        // Normalize the store key
        if (!isset($product['store'])) {
            if (isset($product['url'])) {
                $product['store'] = 'Coles';
            } elseif (isset($product['href'])) {
                $product['store'] = 'Woolworths';
            } else {
                $product['store'] = 'Unknown Store';
            }
        }

        return $product;
    }
}