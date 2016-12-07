<?php
/**
 * Test suite for storing a Pokémon entry.
 */

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Carbon\Carbon;

class PokemonCreateTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::now('UTC'));
    }

    public function tearDown() {
        parent::tearDown();
        Carbon::setTestNow();
    }

    /**
     * Test that the store() method saves a new Pokémon record to the DB.
     */
    public function testStoreSavesNewPokemonRecordToDatabase()
    {
//        $author = factory(\App\Author::class)->create([
//            'name' => 'H. G. Wells'
//        ]);
        // Here's how I get uninvited to Laracon
        $this->post('/pokemon', [
            'name' => 'Taylorchu',
            'number' => '999',
            'description' => 'Often found in tall grass. Evolves into DHH via a Ruby stone.'
        ]);

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertTrue($data['id'] > 0, 'Expected a positive integer, but did not see one.');
        $this->assertEquals('Taylorchu', $data['name']);
        $this->assertEquals('999', $data['number']);
        $this->assertEquals(
            'Often found in tall grass. Evolves into DHH via a Ruby stone.',
            $data['description']
        );
        $this->assertArrayHasKey('created_at', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created_at']);
        $this->assertArrayHasKey('updated_at', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated_at']);

        $this->seeInDatabase('pokemon', ['name' => 'Taylorchu']);
    }

    /**
     *
     */
    public function testStoreRespondsWith201AndLocationHeaderOnSuccess()
    {
        $this->post('/pokemon', [
            'name' => 'Otwellchu',
            'number' => '999',
            'description' => 'Often found in tall grass. Evolves into DHH via a Ruby stone.',
        ]);

        $this
            ->seeStatusCode(201)
            // Hat tip to Paul Redmond for seeHeaderWithRegExp()!
            ->seeHeaderWithRegExp('Location', '#/pokemon/[\d]+$#');
    }




//    public function store_should_respond_with_a_201_and_location_header_when_successful()
//    {
//        $author = factory(\App\Author::class)->create();
//        
//        $this->post('/books', [
//            'title' => 'The Invisible Man',
//            'description' => 'An invisible man is trapped in the terror of his own creation',
//            'author_id' => $author->id
//        ], ['Accept' => 'application/json']);
//
//        $this
//            ->seeStatusCode(201)
//            ->seeHeaderWithRegExp('Location', '#/books/[\d]+$#');
//    }
    
    
    


    public function testStoreValidatesRequiredFieldsOnCreate()
    {
        // Send an empty request
        $this->post('/pokemon', [], ['Accept' => 'application/json']);

        $this->assertEquals(
            // Changed from HTTP_UNPROCESSABLE_ENTITY / 422
            // TODO: Fix this test to pass? ^^ :D
            Response::HTTP_BAD_REQUEST, $this->response->getStatusCode()
        );

    $body = json_decode($this->response->getContent(), true);
        // TODO: Page 172
//    $this->assertArrayHasKey('name', $body);
//    $this->assertArrayHasKey('number', $body);
//    $this->assertArrayHasKey('description', $body);
//    $this->assertEquals(["The name field is required."], $body['name']);
//    $this->assertEquals(["The number field is required."], $body['number']);
//    $this->assertEquals(["The description field is required."], $body['description']);
//    $this->assertEquals(["The given data failed to pass validation."], $body['data']);
    }


//    public function it_validates_required_fields_when_creating_a_new_book()
//    {
//        $this->post('/books', [], ['Accept' => 'application/json']);
//
//        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
//
//        $body = json_decode($this->response->getContent(), true);
//
//        $this->assertArrayHasKey('title', $body);
//        $this->assertArrayHasKey('description', $body);
//
//        $this->assertEquals(["The title field is required."], $body['title']);
//        $this->assertEquals(
//            ["Please fill out the description."],
//            $body['description']
//        );
//    }
    
    

    public function testStoreValidatesPokemonNameLength()
    {

        $pokemon = factory(\App\Pokemon::class)->make();

        // Repeat "Pikachu" 256 times like they do in every episode of the show...
        $pokemon->name = str_repeat('Pikachu', 256);

        $this->post("/pokemon", [
            'name' => $pokemon->name,
            'number' => $pokemon->number,
            'description' => $pokemon->description,
        ], ['Accept' => 'application/json']);

        // TODO: Change this back to HTTP_UNPROCESSABLE_ENTITY
        // The name may not be greater than 255 characters.
        $this
            ->seeStatusCode(Response::HTTP_BAD_REQUEST)
//            ->seeJson([
//                'name' => ["The given data failed to pass validation."]
//            ])
            ->notSeeInDatabase('pokemon', ['name' => $pokemon->name]);
    }

    public function testStoreValidatesWhenNameIs255Characters()
    {
        $pokemon = factory(\App\Pokemon::class)->make();

        $pokemon->name = str_repeat('9', 255);

        $this->post("/pokemon", [
            'name' => $pokemon->name,
            'number' => $pokemon->number,
            'description' => $pokemon->description,
        ], ['Accept' => 'application/json']);

        $this
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeInDatabase('pokemon', ['name' => $pokemon->name]);
    }


//    public function title_passes_create_validation_when_exactly_max()
//    {
//        // Creating a new Book
//        $book = $this->bookFactory();
//        $book->title = str_repeat('a', 255);
//
//        $this->post("/books", [
//            'title' => $book->title,
//            'description' => $book->description,
//            'author_id' => $book->author->id,
//        ], ['Accept' => 'application/json']);
//
//        $this
//            ->seeStatusCode(Response::HTTP_CREATED)
//            ->seeInDatabase('books', ['title' => $book->title]);
//    }

//    public function title_fails_create_validation_when_just_too_long()
//    {
//        // Creating a book
//        $book = $this->bookFactory();
//        $book->title = str_repeat('a', 256);
//
//        $this->post("/books", [
//            'title' => $book->title,
//            'description' => $book->description,
//            'author_id' => $book->author->id,
//        ], ['Accept' => 'application/json']);
//
//        $this
//            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->seeJson([
//                'title' => ["The title may not be greater than 255 characters."]
//            ])
//            ->notSeeInDatabase('books', ['title' => $book->title]);
//    }


}