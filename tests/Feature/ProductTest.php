<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illumintae\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    /**
     *      CREATE-1 
     */
    public function test_client_can_create_a_product()
    {
        // GIVEN
        // El Cliente Tiene tiene una representación de un producto que quiere agregar a la aplicación
        // Request Body
        $productData = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Super Product',
                    'price' => '130'
                ]
            ]
        ];


        // When
        // Call to the EndPoint to create a product
        $response = $this->json('POST', '/api/products', $productData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure and the correct values
        $response->assertJson([
        'data' => [
            'type' => 'products',
            'id' => '1',
            'attributes' => [
                'name' => 'Super Product',
                'price' => '130'
            ],
            'links' => [
                "self" => 'http://127.0.0.1:8000/api/products/1',
            ]
        ]
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
                'id' => '1',
                'name' => 'Super Product',
                'price' => '130'
            ]
        );
        
    }

    /**
     *      CREATE-2
     */
    public function test_client_dont_send_name(){
        // GIVEN
        // El cliente no envía el atributo "name" en el cuerpo de la solicitud     
       // Request Body
       $productData = [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => '',
                'price' => '130'
                ]
            ]
        ];

         // When
         $response = $this->json('POST', '/api/products', $productData);

        // Then 
        // Assert the response has the correct format
        $response->assertJson([
                "errors" => [[
                    "code" => "Error-1",
                    "title" => "Unprocessable Entity",
                ]]
        ]);

        // Assert the Status HTTP
        $response->assertStatus(422);
    }

    /**
     *      CREATE-3
     */
    public function test_client_dont_send_price(){
         // GIVEN
        // El cliente no envía el atributo "PRICE" en el cuerpo de la solicitud     
       // Request Body
       $productData = [
        'data' => [
            'type' => 'products',
            'attributes' => [
                'name' => 'Product',
                'price' => ''
                ]
            ]
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);
        
    
        // Se verifica el JSON de respuesta 
        $response->assertJson([
                "errors" => [[
                    "code" => "Error-1",
                    "title" => "Unprocessable Entity",
                ]]
        ]);
        
        // Se verifica el código HTTP
        $response->assertStatus(422);

    }
    /**
     *      CREATE-4
     */
    public function test_client_send_price_not_number(){
        // GIVEN
        // El cliente envía la representación de un producto donde el atributo "price" NO es un número   
        $productData = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Product',
                    'price' => 'NotNumber'
                    ]
                ]
            ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Se verifica el JSON de respuesta 
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ]);
        
        // Se verifica el código HTTP
        $response->assertStatus(422);
    }

    /**
     *      CREATE-5
     */
    public function test_client_send_price_negative(){
        // GIVEN
        // El cliente envía la representación de un producto donde el atributo "price" tiene un valor de "-20"   
        $productData = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Product',
                    'price' => '-20' // -20 
                    ]
                ]
            ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

            // Se verifica el JSON de respuesta 
            $response->assertJson([
                "errors" => [[
                    "code" => "Error-1",
                    "title" => "Unprocessable Entity",
                ]]
        ]);
        
        // Se verifica el código HTTP
        $response->assertStatus(422);
    }

    /**
     *      UPDATE-1
     */
    public function test_client_can_update_a_product(){

        // GIVEN
        // A product to edit with ID (Factory)
        $productData = factory(Product::class)->create([
            'name' => 'Text',
            'price' => '10'
        ]);

        // The body of the request with the values edited
        $productDataUpdate = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Text Edited',
                    'price' => '20'
                    ]
                ]
            ];
        // WHEN
        // I send a request to update the product
        $response = $this->json('PUT',"api/products/".$productData->id, $productDataUpdate);
        
        // THEN 
        // Return 200 Status
        
        $response->assertStatus(200);

        $body = $response->decodeResponseJson();
    
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links'=> [
                    'self'
                ]
            ]
        ]);

        // Assert the product was edited
        // with the correct data
    
        $response->assertJson([
            'data' => [
                'type' => 'products',
                'id' => $productData->id,
                'attributes' => [
                    'name' => 'Text Edited',
                    'price' => '20'
                ],
                'links' =>[
                    'self' => 'http://127.0.0.1:8000/api/products/'.$productData->id
                ]
            ]
        ]);
        
        // Assert the product Edited there is in the DB
        $productDataUpdateDB = [
            'name' => 'Text Edited',
            'price' => '20'
        ];
         $this->assertDatabaseHas('products', $productDataUpdateDB);
    }

    /** 
     *      UPDATE-2
     */ 
    public function test_client_send_price_not_number_update(){
        // Se crea una instancia de producto en la base de datos
        $productData = factory(Product::class)->create([
            "name" => "Text",
            "price" => "10"
        ]);  

        // GIVEN
        // El cliente envía la representación de un producto donde el atributo "PRICE" No es un número válido
        $productDataUpdate = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Text Edited',
                    'price' => 'NotNumber'
                    ]
                ]
            ];

        // WHEN
        // I send a request to update the product
        $response = $this->json('PUT',"api/products/".$productData->id, $productDataUpdate);

        // Assert the response has the correct structure
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity"
            ]]
        ]);
        
        // Assert the Status HTTP
        $response->assertStatus(422);
    }

    /**
     *      UPDATE-3
     */
    
    public function test_client_send_price_negative_update(){
        // Se crea una instancia de producto en la base de datos
        $productData = factory(Product::class)->create([
        "name" => "Text",
        "price" => "10"
        ]);  

        // GIVEN
        // El cliente envía la representación de un producto donde el atributo "PRICE" No es un número válido
        $productDataUpdate = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Text Edited',
                    'price' => '-20'
                    ]
                ]
            ];

        // WHEN
        // I send a request to update the product
        $response = $this->json('PUT',"api/products/".$productData->id, $productDataUpdate);

        // Assert the response has the correct structure
        $response->assertJson([
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity"
            ]]
        ]);

        // Assert the Status HTTP
        $response->assertStatus(422);

    }

    /**
     *      UPDATE-4
     */
    public function test_client_send_id_invalid(){
        //GIVEN 
        // EL cliente manda una solicitud solicitando editar un producto con un ID que no existe
        $productData = factory(Product::class)->create([
            "name" => "Text",
            "price" => "10"
        ]);  

        // Representación del producto a editar 
        $productDataUpdate = [
            'data' => [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Text Edited',
                    'price' => '10'
                    ]
                ]
            ];

        // WHEN
        // I send a request to update the product
        // ID inválido, debido a que la base de datos se limpia en cada prueba
        // id = id + 1 
        $response = $this->json('PUT',"api/products/".($productData->id+1), $productDataUpdate);

        // Assert the response has the correct structure
        $response->assertJson([
            "errors" => [[
                "code" => "ERROR-2",
                "title" => "Not Found"
            ]]
        ]);

        // Assert Status HTTP
        $response->assertStatus(404);

    }

    /**
     *      SHOW-1
     */
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
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links'=> [
                    'self'
                ]
            ]
        ]);

        // Assert the product was showed
        // with the correct data of Factory

        $response->assertJson([
            'data' => [
                'type' => 'products',
                'id' => $productData->id,
                'attributes' => [
                    'name' => $productData->name,
                    'price' => $productData->price
                ],
                'links' =>[
                    'self' => 'http://127.0.0.1:8000/api/products/'.$productData->id
                ]
            ]
        ]);

    }

    /**
     *      SHOW-2
     */
    public function test_client_try_show_a_product_with_id_invalid(){
        // GIVEN
        // El cliente manda una solicitud solicitando un producto con un ID que no existe
        // There is a product in the application
        $productData = factory(Product::class)->create();

        // WHEN
        // I send a request for the product 
        $response = $this->json("GET","/api/products/".($productData->id+1) );
        
        // THEN 
        // A 404 Status code is returned
        $response->assertStatus(404);

        // Assert the response has the correct structure
        $response->assertJson([
            "errors" => [[
                "code" => "ERROR-2",
                "title" => "Not Found"
            ]]
        ]);


    }

    /**
     *      DELETE-1
     */
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

        // Assert that the given string is contained within the response
        // Body empty
        $response->assertSee(null);
    }

    /**
     *      DELETE-2
     */

    public function test_client_try_delete_a_product_with_invalid_id(){
        // GIVEN
        //There is a product in the application
        $product = factory(Product::class)->create();

        // WHEN
        // El cliente manda una solicitud solicitando eliminar un producto con un ID que no existe
        // I send a request to delete the product
        $response = $this->delete('/api/products/'.($product->id+1));
       
        // THEN 
        // A 404 status code is returned
        $response->assertStatus(404); 

        // Assert the response has the correct structure
        $response->assertJson([
            "errors" => [[
                "code" => "ERROR-2",
                "title" => "Not Found"
            ]]
        ]);

    }

    /**
     *      LIST-1
     */
    public function test_client_can_list_all_products(){
        // GIVEN
        // Verificar contenido de la base de datos
        // The products already created
        $listProduct = factory(Product::class, 2)->create();
        // WHEN
        // I send a request for all products
        $response = $this->json('GET','/api/products');
        // THEN 
        // STATUS CODE 200
        $response->assertStatus(200);


        // DESPLEGAR DATOS 
        //  A list with all the products representations are returned

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'price'
                    ],
                    'links' => [
                        'self'
                    ]
                    
                ]
            ]
        ]);

    }
    /**
     *      LIST-2
     */
    public function test_client_can_list_empty(){
        // GIVEN
        // List is empty

        // WHEN
        // I send a request for all products
        $response = $this->json('GET','/api/products');

        // THEN 
        // STATUS CODE 200
        $response->assertStatus(200);

           // DESPLEGAR DATOS 
        //  A list with all the products representations are returned
        $response->assertJsonStructure([]);

    }
    
  


    
    
}
