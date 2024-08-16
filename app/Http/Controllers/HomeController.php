<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\FirebaseException;
use Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
{
    try {
        $uid = Session::get('uid');
        $user = app('firebase.auth')->getUser($uid);

        // Define the providers and their corresponding JSON files
        $providers = [
            'coles' => storage_path('coles.json'),
            'woolworths' => storage_path('woolworths.json'),
            'iga' => storage_path('iga.json'),
            'aldi' => storage_path('aldi.json'),
        ];

        $combinedData = [];

        foreach ($providers as $provider => $filePath) {
            $json = File::get($filePath);
            $data = json_decode($json, true)['productInfo'] ?? [];
            $data = array_map(function($item) use ($provider) {
                return $this->normalizeProduct($item, $provider);
            }, $data);
            $combinedData = array_merge($combinedData, $data);
        }

        // Filter products based on search query
        $search = $request->input('search');
        if ($search) {
            $combinedData = array_filter($combinedData, function ($product) use ($search) {
                return stripos($product['name'], $search) !== false;
            });
        }

        // Sort products by price if sort parameter is present
        $sortByPrice = $request->input('sort_by_price');
        if ($sortByPrice) {
            usort($combinedData, function ($a, $b) use ($sortByPrice) {
                $priceA = floatval($a['price']);
                $priceB = floatval($b['price']);
                return $sortByPrice === 'asc' ? $priceA <=> $priceB : $priceB <=> $priceA;
            });
        }

        // Paginate the combined data
        $products = $this->paginate($combinedData, 20); // 20 items per page

        return view('home', compact('user', 'products'));
    } catch (\Exception $e) {
        return $e->getMessage();  // Return just the error message for simplicity
    }
}


protected function paginate($items, $perPage = 15, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof \Illuminate\Support\Collection ? $items : collect($items);
    $paginatedItems = new LengthAwarePaginator(
        $items->forPage($page, $perPage),
        $items->count(),
        $perPage,
        $page,
        $options
    );

    $paginatedItems->withPath(request()->url());

    return $paginatedItems;
}

protected function normalizeProduct($item, $provider)
{
    // Ensure $item is an array before proceeding
    if (!is_array($item)) {
        return []; // or handle this error as needed
    }

    // Normalize image URL key
    if (isset($item['image_url'])) {
        $item['imgSrc'] = $item['image_url'];
        unset($item['image_url']);
    } elseif (isset($item['img'])) { // For your JSON example
        $item['imgSrc'] = $item['img'];
        unset($item['img']);
    } else {
        $item['imgSrc'] = 'No Image Available';
    }

    // Set default values if some keys are missing
    $item['name'] = $item['name'] ?? 'No Name Available';
    $item['price'] = $item['price'] ?? 'N/A';
    $item['unitPrice'] = $item['unitPrice'] ?? 'N/A';
    $item['provider'] = $provider; // Add provider information

    return $item;
}


    public function customer()
    {
        $userid = Session::get('uid');
        return view('customers', compact('userid'));
    }

   
}

