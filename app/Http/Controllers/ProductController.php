<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProductRequest;
use \Illuminate\Validation\Validator;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // LISTAR 
        // Se obtienen los productos de la base de datos
        // $products = Product::all();
        // Se dan los productos en un JSON con el código 200
        // return response()->json($products, 200);
        // return new ProductCollection($products->toArray());
        return ProductResource::collection(Product::all());
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
    public function store(ProductRequest $request)
    {
        // Get data from JSON
        $data = $request['data']['attributes'];

        // Create a new product
        $product = Product::create($data);

        // Save product in the DB
        $product->save();
        //Return the JSON with specific Structure
        return new ProductResource($product);
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
        $product = Product::findOrFail($id);

        // Se retorna el producto solicitado, con la representación adecuada
        return new ProductResource($product);
    
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
    public function update(ProductRequest $request, $id)
    {
        // Se busca el producto en la tabla
        $product = Product::findOrFail($id);
        // Se obtienen los datos del JSON anidado
        $data = $request['data']['attributes'];
        // Se guarda el producto actualizado
        $product->update($data);
        
        // Se retorna el producto modificado, con el status 200 (OK)
        // return response()->json($product,200);

        // se retorna el producto modificado, con la representación anidada
        return new ProductResource($product);

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
