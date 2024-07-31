<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    // Add item to cart
    public static function addItemsCart($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();
        $existing_item_key = null;

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] === $product_id) {
                $existing_item_key = $key;
                break;
            }
        }

        if ($existing_item_key !== null) {
            $cart_items[$existing_item_key]['quantity']++;
            $cart_items[$existing_item_key]['total_amount'] = $cart_items[$existing_item_key]['quantity'] * $cart_items[$existing_item_key]['unit_amount'];
        } else {
            $product = Product::find($product_id);
            if ($product) {
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'image' => $product->image[0],
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }


    // Add item to cart with qty
    public static function addItemsCartWithQty($product_id, $qty = 1)
    {
        $cart_items = self::getCartItemsFromCookie();
        $existing_item_key = null;

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] === $product_id) {
                $existing_item_key = $key;
                break;
            }
        }

        if ($existing_item_key !== null) {
            $cart_items[$existing_item_key]['quantity'] = $qty;
            $cart_items[$existing_item_key]['total_amount'] = $cart_items[$existing_item_key]['quantity'] * $cart_items[$existing_item_key]['unit_amount'];
        } else {
            $product = Product::find($product_id);
            if ($product) {
                $cart_items[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'image' => $product->image[0],
                    'quantity' => $qty,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    // Remove item from cart
    public static function removeCartItems($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Add cart items to cookie
    public static function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode(array_values($cart_items)), 60 * 24 * 30);
    }

    // Clear cart items from cookie
    public static function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // Get all cart items from cookie
    public static function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        return $cart_items ?: [];
    }

    // Increase item quantity
    public static function increasementQuantityToCartItems($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] === $product_id) {
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Decrease item quantity
    public static function decrementQuantityToCartItems($product_id)
    {
        $cart_items = self::getCartItemsFromCookie();

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] === $product_id) {
                if ($cart_items[$key]['quantity'] > 1) {
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookie($cart_items);
        return $cart_items;
    }

    // Calculate grand total
    public static function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}
