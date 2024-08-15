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

    public function index()
    {
        try {
            $uid = Session::get('uid');
            $user = app('firebase.auth')->getUser($uid);

            // Reading JSON files
            $json1 = File::get(storage_path('coles.json'));
            $json2 = File::get(storage_path('woolworths.json'));

            // Decode JSON data to PHP arrays
            $data1 = json_decode($json1, true);
            $data2 = json_decode($json2, true);

            // Normalize the image URLs and other keys
            $data1 = array_map([$this, 'normalizeProduct'], $data1);
            $data2 = array_map([$this, 'normalizeProduct'], $data2);

            // Combine the two datasets
            $combinedData = array_merge($data1, $data2);

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

    protected function normalizeProduct($item)
    {
        // Normalize image URL key
        if (isset($item['image_url'])) {
            $item['imgSrc'] = $item['image_url'];
            unset($item['image_url']);
        }

        // Set default values if some keys are missing
        $item['name'] = $item['name'] ?? 'No Name Available';
        $item['price'] = $item['price'] ?? 'N/A';
        $item['unitPrice'] = $item['unitPrice'] ?? 'N/A';

        return $item;
    }

    public function customer()
    {
        $userid = Session::get('uid');
        return view('customers', compact('userid'));
    }

   
}

