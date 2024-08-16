<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ShoppingListController extends Controller
{
    /**
     * Display the shopping list items.
     *
     * @return \Illuminate\View\View
     */
public function show()
{
    // Retrieve the shopping list from the session
    $shoppingList = session()->get('shopping_list', []);

    // Debugging: Log the shopping list
    \Log::info('Shopping List:', ['list' => $shoppingList]);

    // Ensure the shopping list is an array
    if (!is_array($shoppingList)) {
        $shoppingList = json_decode($shoppingList, true);
    }

    // Paginate the shopping list
    $perPage = 10; // Number of items per page
    $currentPage = Paginator::resolveCurrentPage();
    $collection = collect($shoppingList);
    $paginatedItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
    $paginator = new LengthAwarePaginator(
        $paginatedItems,
        $collection->count(),
        $perPage,
        $currentPage,
        ['path' => Paginator::resolveCurrentPath()]
    );

    return view('shopping_list', ['products' => $paginator]);
}



    /**
     * Add an item to the shopping list.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
 * Add an item to the shopping list.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
 */
public function addToShoppingList(Request $request)
{
    // Validate the request
    $request->validate([
        'product.name' => 'required|string',
        'product.price' => 'required|numeric',
        // Add other validations as necessary
    ]);

    // Retrieve the product details from the request
    $product = $request->input('product');

    // Ensure product is an array and has required fields
    if (!is_array($product) || !isset($product['name'])) {
        return response()->json(['success' => false, 'message' => 'Invalid product data.'], 400);
    }

    // Retrieve the current shopping list from the session or create an empty array if none exists
    $shoppingList = session()->get('shopping_list', []);

    // Ensure $shoppingList is an array
    if (is_string($shoppingList)) {
        $shoppingList = json_decode($shoppingList, true);
    }

    // Check if the product is already in the shopping list
    $productExists = array_filter($shoppingList, function ($item) use ($product) {
        return isset($item['name']) && $item['name'] === $product['name'];
    });

    if (!empty($productExists)) {
        return response()->json(['success' => false, 'message' => 'Product is already in the shopping list.']);
    }

    // Add the new product to the shopping list
    $shoppingList[] = $product;

    // Update the session with the new shopping list
    session()->put('shopping_list', $shoppingList);

    // Return a JSON response indicating success
    return response()->json(['success' => true, 'message' => 'Product added to the shopping list']);
}




    /**
     * Remove an item from the shopping list.
     *
     * @param string $productName
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($productName)
{
    $shoppingList = session()->get('shopping_list', []);

    // Ensure $shoppingList is an array
    if (is_string($shoppingList)) {
        $shoppingList = json_decode($shoppingList, true);
    }

    // Filter out the item to be removed
    $shoppingList = array_filter($shoppingList, function($item) use ($productName) {
        return isset($item['name']) && $item['name'] !== $productName;
    });

    // Update the session with the new shopping list
    session()->put('shopping_list', $shoppingList);

    // Redirect back to the shopping list with a success message
    return redirect()->route('shoppingList.show')->with('success', 'Item removed from shopping list.');
}


}
