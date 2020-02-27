<?php
/**
 * Created by PhpStorm.
 * User: tuyenvv
 * Date: 2/11/19
 * Time: 3:29 PM
 */

namespace Kiotviet;


class KiotvietEndpoint
{
    const GET_TOKEN = 'https://id.kiotviet.vn/connect/token';


    //  Category API
    const GET_CATEGORIES = 'https://public.kiotapi.com/categories';

    const GET_CATEGORY = 'https://public.kiotapi.com/categories/';

    const POST_CATEGORY = 'https://public.kiotapi.com/categories';

    const PUT_CATEGORY = 'https://public.kiotapi.com/categories/';

    const DELETE_CATEGORY = 'https://public.kiotapi.com/categories/';

    //  Product API
    const GET_PRODUCTS = 'https://public.kiotapi.com/products';
    const GET_PRODUCT_BY_ID = 'https://public.kiotapi.com/products/';
    const GET_PRODUCT_BY_CODE = 'https://public.kiotapi.com/products/code';
    const POST_PRODUCT = 'https://public.kiotapi.com/products';
    const PUT_PRODUCTS = 'https://public.kiotapi.com/products/';
    const DELETE_PRODUCTS = 'https://public.kiotapi.com/products/';


}