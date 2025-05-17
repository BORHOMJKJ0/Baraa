<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart_items;
use App\Services\Cart\Cart_Items_Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartItemsController extends Controller
{

    public function __construct( protected  Cart_Items_Service $cart_items_Service){}

    public function index(Request $request): JsonResponse
    {
        return $this->cart_items_Service->getAllCart_items($request);
    }

    public function store(Request $request): JsonResponse
    {
        return $this->cart_items_Service->createCart_items($request->all());
    }

    public function update(Cart_items $cart_item, Request $request): JsonResponse
    {
        return $this->cart_items_Service->updateCart_items($cart_item, $request->all());
    }

    public function show(Cart_items $cart_item): JsonResponse
    {
        return $this->cart_items_Service->getCart_itemById($cart_item);
    }

    public function destroy(Cart_items $cart_item): JsonResponse
    {
        return $this->cart_items_Service->deleteCart_items($cart_item);
    }
}
