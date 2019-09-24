<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Se obtienen los productos de la base de datos
        $products = Product::all();
        // Se dan los productos en un JSON con el código 200
        return response()->json($products, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Create a new product
        $product = Product::create($request->all());

        // Return a response with a product json
        // representation and a 201 status code   
        return response()->json($product,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Se busca el producto en tabla 
        $product = Product::find($id);

        // Se retorna el producto solicitado, con el status 200 (OK)
        return response()->json($product,200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        // Se busca el producto en la tabla
        $product = Product::find($id);
        // Se guarda el producto actualizado
        $product->update($request->all());
        // Se retorna el producto modificado, con el status 200 (OK)
        return response()->json($product,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $productToDestroy = Product::findOrFail($id);
        $productToDestroy->delete();
        return response(null,204);
    }
}
