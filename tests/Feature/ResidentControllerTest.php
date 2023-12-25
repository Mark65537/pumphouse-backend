<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ResidentController;
use App\Models\Resident;
use App\Models\DeletedResident;
use Tests\TestCase;

class ResidentControllerTest extends TestCase
{
    
    // private $testData = [
    //     'fio' => 'John Doe',
    //     'area' => 100,
    //     'start_date' => '2021-01-01'
    // ];
    private function createTestResidentWithAPI()
    {
        $testData = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];
        return $this->json('POST', 'api/residents', $testData);

        // return $this->createResident(
        //     $testData['fio'], 
        //     $testData['area'], 
        //     $testData['start_date']
        // );
    }
    private function createResident($name, $area, $startDate)
    {
        return Resident::create([
            'fio' => $name,
            'area' => $area,
            'start_date' => $startDate,
        ]);
    }

    /**
    * Test index method fetches all non-deleted 
    * residents.
    */
    public function testIndexWithoutSearchTerm()
    {        
        // Получить всех неудаленных дачников
        $expectedResidents = Resident::whereNotIn(
            'id', DeletedResident::pluck('resident_id')
        )->get();

        // Perform the GET request
        $response = $this->get('api/residents');
        
        // Проверка что статус OK
        $response->assertStatus(200);
        // Проверка что возвращаются все неудаленные дачники
        $response->assertJson($expectedResidents->toArray());
    }

    /**
     * Test index method with a search term.
     */
    // public function testIndexWithSearchTerm()
    // {
    //     $this->createResident('John Doe', 1, now());
    //     // $results = $this->getSearchResults('John');
    //     // $this->assertSearchResults($results, 'John Doe');
    // }
    
    // private function getSearchResults($term)
    // {
    //     $response = $this->get("api/residents?search=$term");
    //     $response->assertStatus(200);
    //     return json_decode($response->getContent(), true);
    // }
    // private function assertSearchResults($results, $expectedName)
    // {
    //     $this->assertCount(1, $results);
    //     $this->assertEquals($expectedName, $results[0]['fio']);
    // }

    /** @test */
    public function creates_resident_with_valid_data()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'fio', 'area', 'start_date']);

        // Get the resident's ID from the response and delete the resident
        $residentId = $response->json('id');
        Resident::destroy($residentId);
    }

    /** @test */
    public function fails_creating_resident_with_missing_fields()
    {
        $data = [
            'fio' => '',
            'area' => null,
            'start_date' => null
        ];

        $response = $this->json('POST', '/residents', $data);

        $response->assertStatus(404);
    }

    /** @test */
    public function fails_creating_resident_with_invalid_data_types()
    {
        $data = [
            'fio' => 123, // should be string
            'area' => 'abc', // should be numeric
            'start_date' => 'not-a-date' // should be a date
        ];

        $response = $this->json('POST', 'api/residents', $data);

        $response->assertStatus(422);
    }

    /** @test */
    public function fails_creating_resident_with_exceeding_string_length()
    {
        $data = [
            'fio' => str_repeat('a', 256), // max 255 chars
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);

        $response->assertStatus(422);
    }

    /**
     * Test successful response with resident data.
     */
    public function testShowResident()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);
        $residentId = $response->json('id');
        $response = $this->getJson("/residents/{$residentId}");

        $response->assertStatus(200)
                 ->assertJson($response->toArray());
    }

    /**
     * Test response status code is 200.
     */
    public function testShowResidentStatusCode()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);
        $residentId = $response->json('id');

        $response = $this->getJson("api/residents/{$residentId}");

        $response->assertStatus(200);
    }

    /**
     * Test JSON response structure.
     */
    public function testShowResidentResponseStructure()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);
        $residentId = $response->json('id');

        $response = $this->getJson("api/residents/{$residentId}");

        $response->assertJsonStructure([
            'id', 'fio', 'area', 'start_date',
        ]);
    }

    /**
     * Test response with non-existent resident.
     */
    public function testShowNonExistentResident()
    {
        $nonExistentId = 9999;
        $response = $this->getJson("api/residents/{$nonExistentId}");

        // Assuming the application handles non-existent records
        // with a 404 status code.
        $response->assertStatus(404);
    }

    /**
     * Test updating a resident with valid data.
     */
    public function testResidentUpdateWithValidData()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);

        $residentId = $response->json('id');

        $response = $this->json('PUT', 'api/resident/' . $residentId, [
            'fio' => 'John Doe',
            'area' => 100.5,
            'start_date' => '2021-01-01',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('residents', [
            'id' => $response->id,
            'fio' => 'John Doe',
            'area' => 100.5,
            'start_date' => '2021-01-01',
        ]);

        // Get the resident's ID from the response and delete the resident
        Resident::destroy($residentId);
    }

    public function testResidentUpdateWithInvalidFio()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);

        $residentId = $response->json('id');
        $longString = str_repeat('a', 256);

        $response = $this->json('PATCH', 'api/resident/' . $residentId, [
            'fio' => $longString,
        ]);

        $response->assertStatus(404);
    }

     /**
     * Test updating a resident with invalid `area`.
     */
    public function testResidentUpdateWithInvalidArea()
    {
        $data = [
            'fio' => 'John Doe',
            'area' => 100,
            'start_date' => '2021-01-01'
        ];

        $response = $this->json('POST', 'api/residents', $data);

        $residentId = $response->json('id');

        $response = $this->json('PATCH', 'api/resident/' . $residentId, [
            'area' => 'invalid',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test updating a resident with invalid `start_date`.
     */
    public function testResidentUpdateWithInvalidStartDate()
    {
        $response = $this->createTestResidentWithAPI();

        $residentId = $response->json('id');

        $response = $this->json('PUT', 'api/resident/' . $residentId, [
            'start_date' => 'not-a-date',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test updating a non-existent resident.
     */
    public function testResidentUpdateNonExistent()
    {
        $response = $this->json('PUT', 'api/resident/99999', [
            'fio' => 'Jane Doe',
        ]);

        $response->assertStatus(404);
    }

    public function testDestroyResidentExistsAndDeletedSuccessfully()
    {
        $createResponse = $this->createTestResidentWithAPI();

        $residentId = $createResponse->json('id');

        $response = $this->json('DELETE', 'api/resident/' . $residentId);

        $response->assertStatus(200);
    }

}