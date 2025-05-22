<?php

namespace App\Services\Cart;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Cart\CartItemsCollection;
use App\Http\Resources\Cart\CartItemsResource;
use App\Models\Cart\Cart;
use App\Models\Cart\Cart_items;
use App\Repositories\Cart\CartItemsRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class Cart_Items_Service
{
    use AuthTrait;

    public function __construct(protected CartItemsRepository $cartItemsRepository) {}

    public function getAllCart_items(Request $request)
    {
        $items = $request->query('items', 20);
        $column = $request->query('column', 'quantity');
        $direction = $request->query('direction', 'asc');
        $validColumns = ['quantity', 'product_name'];
        $validDirections = ['asc', 'desc'];

        if (! in_array($column, $validColumns) || ! in_array($direction, $validDirections)) {
            return ResponseHelper::jsonResponse(
                [],
                'Invalid sort column or direction. Allowed columns: '.implode(', ', $validColumns).
                '. Allowed directions: '.implode(', ', $validDirections).'.',
                400,
                false
            );
        }
        $cart_items = $this->cartItemsRepository->getAll($items, $column, $direction);

        $data = [
            'Cart_items' => CartItemsResource::collection($cart_items),
            'total_pages' => $cart_items->lastPage(),
            'current_page' => $cart_items->currentPage(),
            'hasMorePages' => $cart_items->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'Cart_items retrieved successfully!');
    }

    public function getCart_itemById(Cart_items $cart_item)
    {
        try {
            $cart = Cart::where('id', $cart_item->cart_id)->first();
            $this->checkOwnership($cart, 'Cart_items', 'perform');
            $data = ['Cart_items' => CartItemsResource::make($cart_item)];
            $response = ResponseHelper::jsonResponse($data, 'Cart_item retrieved successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function createCart_items(array $data)
    {
        try {
            $cart_item = $this->cartItemsRepository->create($data);
            $data = ['Cart_items' => CartItemsResource::make($cart_item)];
            $response = ResponseHelper::jsonResponse($data, 'Cart_item created successfully!', 201);
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function updateCart_items(Cart_items $cart_item, array $data)
    {
        try {
            $cart = Cart::where('id', $cart_item->cart_id)->first();
            $this->checkOwnership($cart, 'Cart_items', 'update');
            $cart_item = $this->cartItemsRepository->update($cart_item, $data);
            $data = ['Cart_items' => CartItemsResource::make($cart_item)];
            $response = ResponseHelper::jsonResponse($data, 'Cart_item updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteCart_items(Cart_items $cart_item)
    {
        try {
            $cart = Cart::where('id', $cart_item->cart_id)->first();
            $this->checkOwnership($cart, 'Cart_items', 'delete');
            $this->cartItemsRepository->delete($cart_item);
            $response = ResponseHelper::jsonResponse([], 'Cart_item deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function getDeletedCart_items(Request $request)
    {
        $items = $request->query('items', 20);
        $column = $request->query('column', 'quantity');
        $direction = $request->query('direction', 'asc');
        $validColumns = ['quantity', 'product_name'];
        $validDirections = ['asc', 'desc'];

        if (! in_array($column, $validColumns) || ! in_array($direction, $validDirections)) {
            return ResponseHelper::jsonResponse(
                [],
                'Invalid sort column or direction. Allowed columns: '.implode(', ', $validColumns).
                '. Allowed directions: '.implode(', ', $validDirections).'.',
                400,
                false
            );
        }
        $cart = Cart::where('user_id', auth()->id())->first();
        if (! $cart) {
            return ResponseHelper::jsonResponse([], 'Cart not found.', 404, false);
        }
        $cart_items = $this->cartItemsRepository->getDeletedProductItems($items, $column, $direction);

        $collection = new CartItemsCollection($cart_items);
        $cartItemsData = $collection->toArray($request);

        $data = [
            'Cart_items' => $cartItemsData,
            'total_pages' => $cart_items->lastPage(),
            'current_page' => $cart_items->currentPage(),
            'hasMorePages' => $cart_items->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'archived Cart_items retrieved successfully!');
    }
}
