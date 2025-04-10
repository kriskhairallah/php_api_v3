<?php

namespace App\Controllers;

use App\Core\Database as DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProductController {
    public function getProducts(Request $request, Response $response) {
        $products = DB::query("SELECT * FROM products");
        return $response->withJson($products);
    }
}
