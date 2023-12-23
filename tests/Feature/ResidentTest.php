<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Resident;

class ResidentTest extends TestCase
{
    use RefreshDatabase; // Эта трейт используется для миграции базы данных и ее очистки после теста

    /** @test */
    public function a_resident_can_be_updated()
    {
        // Создаем пользователя
        // $resident = Resident::create();
        $resident = new stdClass();
        $resident->id = 1;
        $resident->fio = 'John Doe';
        $resident->area = 123;
        $resident->start_date = "2022-01-01";

        // Данные для обновления пользователя
        $updateData = [
            'fio' => 'Updated Name',
            'area' => 1234,
            'start_date' => "2023-01-01"
        ];

        // Отправляем запрос на обновление пользователя
        $response = $this->put("/api/residents/{$resident->id}", $updateData);

        // Проверяем, что пользователь был обновлен в базе данных
        $this->assertDatabaseHas('residents', [
            'id' => $resident->id,
            'fio' => $updateData['fio'],
            'area' => 1234,
            'start_date' => "2023-01-01"
        ]);

        // Проверяем, что ответ содержит обновленные данные
        $response->assertJsonFragment($updateData);

        // Проверяем статус ответа
        $response->assertStatus(200);
    }
}