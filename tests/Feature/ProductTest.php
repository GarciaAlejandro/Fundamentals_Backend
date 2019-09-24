<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_client_can_create_a_product()
    {
        // GIVEN
        $productData = [
            'name' => 'Super Product',
            'price' => '130'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Then
            // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '130'
        ]);

        $body = $response->decodeResponseJson();
       
        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '130'
            ]
        );
        
    }

    // LIST ALL PRODUCTS
    // OK
    public function test_client_can_list_all_products(){
        // GIVEN
        // Verificar contenido de la base de datos
        // The products already created
        $listProduct = factory(Product::class, 10)->create();
        // WHEN
        // I send a request for all products
        $response = $this->json('GET','/api/products');
        // THEN 
        // STATUS CODE 200
        $response->assertStatus(200);


        // DESPLEGAR DATOS 
        //  A list with all the products representations are returned
        $response->assertJsonStructure([
              '*' => [
                'id',
                'name',
                'price',
                'created_at',
                'updated_at',
              ]
          ]);
    }

    // SHOW PRODUCT
    public function test_client_can_show_a_product(){
   
        // GIVEN
        // There is a product in the application
        $productData = factory(Product::class)->create();

        // WHEN
        // I send a request for the product 
        $response = $this->json("GET","/api/products/".$productData->id);
        // THEN 
        // A 200 Status code is returned
        $response->assertStatus(200);

        // A product representation is returned
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was showed
        // with the correct data of Factory

        $response->assertJson([
            'name' => $productData->name,
            'price' => $productData->price,
        ]);

        
     

    }
    
    // UPDATE PRODUCT
    public function test_client_can_update_a_product(){
        
        // GIVEN
        // A product to edit with ID (Factory)
        $productDataUpdate = factory(Product::class)->create([
            'name' => 'Text EDITED',
            'price' => '10'
        ]);
        // WHEN
        // I send a request to update the product
        $response = $this->json('PUT',"api/products/".$productDataUpdate->id);

        // THEN 
        // Return 200 Status
        
        $response->assertStatus(200);

        $body = $response->decodeResponseJson();
       
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was edited
        // with the correct data
       
        $response->assertJson([
            'name' => 'Text EDITED',
            'price' => '10'
        ]);

    }

    // DELETE PRODUCT
    public function test_client_can_delete_a_product(){

        // GIVEN
        //There is a product in the application
        $product = factory(Product::class)->create();

        // WHEN
        // I send a request to delete the product
        $response = $this->delete('/api/products/'.$product->id);
       
        // THEN 
        // A 204 status code is returned
        $response->assertStatus(204);
    }
    
    
}
