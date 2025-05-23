<?php

namespace App\Services\Cart;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart\Cart;
use App\Repositories\Cart\CartRepository;
use App\Traits\AuthTrait;
use Illuminate\Http\Exceptions\HttpResponseException;

class CartService
{
    use AuthTrait;

    public function __construct(protected CartRepository $cartRepository) {}

    public function getCartById()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        if (! $cart) {
            return ResponseHelper::jsonResponse([], 'Cart not found.', 404, false);
        }
        $data = ['Cart' => CartResource::make($cart)];

        return ResponseHelper::jsonResponse($data, 'Cart retrieved successfully!');
    }

    public function createCart()
    {
        $carts = Cart::where('user_id', auth()->id())->get();
        if ($carts->isEmpty()) {
            $cart = $this->cartRepository->create();
            $data = [
                'Cart' => CartResource::make($cart),
            ];

            return ResponseHelper::jsonResponse($data, 'Cart created successfully!', 201);
        }

        return ResponseHelper::jsonResponse([], 'This User have already Cart', 403, false);
    }

    public function updateCart()
    {
        try {
            $cart = Cart::where('user_id', auth()->id())->with('cart_items.product')->first();
            if (! $cart) {
                return ResponseHelper::jsonResponse([], 'Cart not found.', 404, false);
            }
            $this->checkAndUpdateProductAmounts($cart->cart_items);
            $cart = $this->cartRepository->update($cart);
            $data = [
                'Cart' => CartResource::make($cart),
            ];

            $response = ResponseHelper::jsonResponse($data, 'Cart updated successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }

    public function deleteCart()
    {
        try {
            $cart = Cart::where('user_id', auth()->id())->first();
            if (! $cart) {
                return ResponseHelper::jsonResponse([], 'Cart not found.', 404, false);
            }
            $this->cartRepository->delete($cart);
            $response = ResponseHelper::jsonResponse([], 'Cart deleted successfully!');
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
        }

        return $response;
    }
}
