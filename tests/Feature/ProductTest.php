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
    public function test_client_can_list_all_products(){
        // GIVEN
        // Verificar contenido de la base de datos

        // WHEN
        $this->get('/products')
             ->seeJsonStructure([
                 '*' => [
                     'id', 'name', 'price'
                 ]
             ]);
        // THEN 
        $response->assertStatus(200);

        $body = $response->decodeResponseJson();

    }
    // SHOW PRODUCT
    public function test_client_can_see_a_product(){
    
        // GIVEN
        $productData = [
            'id' => '1',
        ];
        // WHEN
        $response = $this->json('GET','api/products/{id}', $productData);
        // THEN 
        // 
        $response->assertStatus(500);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);
        $body = $response->decodeResponseJson();

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

    }
    // UPDATE PRODUCT
    public function test_client_can_update_a_product(){
        // https://medium.com/@tik/how-to-test-a-laravel-json-controller-a9d0af897ddb
        // GIVEN
      
        // WHEN
        $response = $this->json('PUT','products/1', [
            'id' => '1',
            'name' => 'Super Product EDIT',
            'price' => '10'
        ]);
        // THEN 
        $response->assertStatus(200);



        $body = $response->decodeResponseJson();
       
  

    }
    // DELETE PRODUCT
    public function test_client_can_delete_a_product(){
        // GIVEN
        $product = factory(Product::class)->create();
        // WHEN
        $this->call('delete', 'api/products/{id}', $product->id );
       // $response = $product->delete('api/products/{id}', $product->id);
       
        // THEN 
        $response-> assertStatus(204);
    }
    
    
}
