<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ShoppingListController extends Controller
{
    public function index()
    {
        $shoppingList = Session::get('shoppingList', []);
        return view('shopping.index', ['shoppingList' => $shoppingList]);
    }

    public function add(Request $request, $productName)
    {
        $colesProducts = $this->fetchProducts('coles.json');
        $woolworthsProducts = $this->fetchProducts('woolworths.json');

        $allProducts = array_merge(
            $this->standardizeProductData($colesProducts),
            $this->standardizeProductData($woolworthsProducts)
        );

        Log::info('All Products:', $allProducts);  // Log all products
        $product = $this->findProductByName($allProducts, $productName);

        if ($product) {
            $shoppingList = Session::get('shoppingList', []);
            $shoppingList[$productName] = $product;
            Session::put('shoppingList', $shoppingList);
            return redirect()->route('shopping-list.index')->with('success', 'Product added to shopping list.');
        }

        return redirect()->route('shopping-list.index')->with('error', 'Product not found.');
    }

    public function remove($productName)
    {
        $shoppingList = Session::get('shoppingList', []);
        if (isset($shoppingList[$productName])) {
            unset($shoppingList[$productName]);
            Session::put('shoppingList', $shoppingList);
            return redirect()->route('shopping-list.index')->with('success', 'Product removed from shopping list.');
        }

        return redirect()->route('shopping-list.index')->with('error', 'Product not found in shopping list.');
    }

    private function fetchProducts($filename)
    {
        $path = storage_path('app/' . $filename);
        return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
    }

    private function standardizeProductData(array $products)
    {
        return array_map(function($product) {
            if (isset($product['image_url'])) {
                $product['imgSrc'] = $product['image_url'];
            }
            if (isset($product['unitPrice'])) {
                $product['price'] = str_replace(['$', 'per', ' '], '', $product['price']);
            }
            return $product;
        }, $products);
    }

    private function findProductByName(array $products, $productName)
    {
        foreach ($products as $product) {
            if (strcasecmp($product['name'], $productName) === 0) {
                return $product;
            }
        }
        return null;
    }
}