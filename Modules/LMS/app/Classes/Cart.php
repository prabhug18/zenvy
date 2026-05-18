<?php

namespace Modules\LMS\Classes;

class Cart
{
    protected static $instance;

    public function __construct()
    {
        if (self::get() === null) {
            self::set(self::empty());
        }
    }

    /**
     * add
     *
     * @param  $course  $course
     */
    public static function add($course)
    {
        $cart = self::get();
        $cart = isset($cart) ? $cart : $cart = ['courses' => []];
        array_push($cart['courses'], $course);
        self::set($cart);
    }

    /**
     *  remove
     */
    public static function remove(int $courseId)
    {
        $cart = self::get();
        $searchItem = self::checkCartExist($courseId);
        if ($searchItem !== false) {
            array_splice($cart['courses'], $searchItem, 1);
            self::set($cart);
            return true;
        }
        return false;
    }

    /**
     * clear
     * set of cart empty after order
     */
    public static function clear()
    {
        self::set(self::empty());
    }

    /**
     * empty
     */
    public static function empty(): array
    {
        session()->flash('discount_amount');
        return [
            'courses' => [],
        ];
    }

    /**
     * get
     * get of session data
     */
    public static function get(): ?array
    {
        return request()->session()->get('cart');
    }

    /**
     * totalPrice
     */
    public static function totalPrice()
    {
        $cart = self::get();
        if (isset($cart['courses']) && ! empty($cart['courses'])) {
            $totalPrice = 0;
            foreach ($cart['courses'] as $course) {
                $totalPrice +=  isset($course['discount_price']) && $course['discount_price'] !== 0 ? $course['discount_price'] : $course['price'];
            }

            return $totalPrice;
        }
    }

    /**
     * getInstance
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Cart;
        }

        return self::$instance;
    }

    /**
     * set
     *
     * @param  array  $cart
     */
    private static function set($cart)
    {
        request()->session()->put('cart', $cart);
    }

    /**
     * discountAmount
     */
    public static function discountAmount()
    {
        if (session()->has('discount_amount')) {
            return session()->get('discount_amount');
        }
        return 0;
    }


    /**
     * checkCartExist
     *
     * @param int $id
     * @param string|null $type
     *
     */
    public static function checkCartExist($id, $type = null)
    {
        $cart = self::get();
        if (empty($cart) || empty($cart['courses'])) {
            return false;
        }
        
        foreach ($cart['courses'] as $index => $item) {
            if ($item['id'] == $id && ($type === null || $item['type'] == $type)) {
                return $index;
            }
        }
        return false;
    }

    /**
     * checkCartExist
     *
     * @param int $id
     *
     */
    public static function cartQty()
    {
        $cart = self::get();
        $cart = isset($cart) ? $cart : $cart = ['courses' => []];
        return count($cart['courses']);
    }
}
