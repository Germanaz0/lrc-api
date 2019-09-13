<?php

namespace Tests\Feature;

use App\Models\Service;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class ServiceTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    const BASE_URL = 'api/services';

    const FORM_DATA = [
        "title" => "Awesome API Service!",
        "description" => "We make the best  service finder",
        "address" => "1317 Putnam Ave",
        "city" => "Brooklyn",
        "state" => "New York",
        "country" => "United States",
        "zip_code" => "11221",
        "geolocation" => [
            "lat" => 40.6933416,
            "lng" => -73.9162116
        ],
    ];

    const SERVICE_STRUCTURE = [
        'data' => [
            "id",
            "title",
            "description",
            "address",
            "city",
            "state",
            "zip_code",
            "geolocation" => [
                "type",
                "coordinates" => [],
            ],
        ],
    ];

    /**
     * Generates authentication headers
     * @return array
     */
    protected function getAuthHeaders()
    {
        $user = factory(User::class)->create();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->save();

        return [
            'Authorization' => "Bearer {$tokenResult->accessToken}",
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Test protected pages
     *
     * @return void
     */
    public function testProtectedPages()
    {
        // Create, Update and Delete Operations are not allowed by guests

        $response = $this->json('POST', self::BASE_URL, self::FORM_DATA);
        $response->assertStatus(401)->assertJsonStructure(["message"]);

        $response = $this->json('PATCH', self::BASE_URL . "/1", self::FORM_DATA);
        $response->assertStatus(401)->assertJsonStructure(["message"]);

        $response = $this->json('PUT', self::BASE_URL . "/1", self::FORM_DATA);
        $response->assertStatus(401)->assertJsonStructure(["message"]);

        $response = $this->json('DELETE', self::BASE_URL . "/1", self::FORM_DATA);
        $response->assertStatus(401)->assertJsonStructure(["message"]);
    }

    /**
     * Test service creation
     */
    public function testCreateService()
    {
        $headers = $this->getAuthHeaders();

        $response = $this->json('POST', self::BASE_URL, self::FORM_DATA, $headers);
        $response->assertStatus(201)->assertJsonStructure(self::SERVICE_STRUCTURE);
    }

    /**
     * Test service delete action
     */
    public function testDeleteService()
    {
        $service = factory(Service::class)->create();
        $headers = $this->getAuthHeaders();

        $response = $this->json('DELETE', self::BASE_URL . "/{$service->id}", self::FORM_DATA, $headers);
        $response->assertStatus(200)->assertJsonStructure(["message"]);

        $response = $this->json('DELETE', self::BASE_URL . "/aaa", self::FORM_DATA, $headers);
        $response->assertStatus(404)->assertJsonStructure(["message"]);
    }

    /**
     * Test service update action
     */
    public function testUpdateService()
    {
        $service = factory(Service::class)->create();
        $headers = $this->getAuthHeaders();

        $response = $this->json('PATCH', self::BASE_URL . "/{$service->id}", self::FORM_DATA, $headers);
        $response->assertStatus(200)->assertJsonStructure(self::SERVICE_STRUCTURE);
    }

    /**
     * Test listing
     */
    public function testList()
    {
        $services = factory(Service::class, 100)->create();
        $response = $this->json('GET', self::BASE_URL);
        $response->assertStatus(200)->assertJsonStructure(['data' => [self::SERVICE_STRUCTURE['data']]]);
    }

    /**
     * Test service update action
     */
    public function testShow()
    {
        $service = factory(Service::class)->create();

        $response = $this->json('GET', self::BASE_URL . "/{$service->id}");
        $response->assertStatus(200)->assertJsonStructure(self::SERVICE_STRUCTURE);

        $response = $this->json('GET', self::BASE_URL . "/aaa");
        $response->assertStatus(404)->assertJsonStructure(["message"]);
    }
}
