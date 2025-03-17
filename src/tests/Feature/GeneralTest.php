<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\RegisterRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\Breaktime;

class GeneralTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @dataProvider registerDataProvider
     */
    public function test_register($keys, $values, $expect): void
    {
        $dataList = array_combine($keys, $values);

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ];

        $validator = Validator::make($dataList, $rules);
        $result = $validator->passes();

        $this->assertEquals($expect, $result);
    }

    public static function registerDataProvider(): array
    {
        return [
            'valid data' => [
                ['name', 'email', 'password', 'password_confirmation'],
                ['John Doe', 'john@example.com', 'password123', 'password123'],
                true
            ],
            'missing name' => [
                ['email', 'password', 'password_confirmation'],
                ['john@example.com', 'password123', 'password123'],
                false
            ],
            'invalid email' => [
                ['name', 'email', 'password', 'password_confirmation'],
                ['John Doe', 'invalid-email', 'password123', 'password123'],
                false
            ],
        ];
    }

    public function test_user_can_login_with_correct_credentials()
    {
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'), 
        ]);

        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        
        $response->assertRedirect('/attendance'); 
        $this->assertAuthenticatedAs($user); 
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

       
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        
        $response->assertSessionHasErrors('email'); 
        $this->assertGuest(); 
    }

    public function test_user_cannot_login_with_nonexistent_email()
    {
        
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
    public function test_user_can_punch_in()
{
    $user = User::factory()->create(['id' => 1]);
    $this->actingAs($user);

    $response = $this->post('/save-time');

    
    dump($response->json()); 
    dump(DB::table('attendances')->get()); 
    $response->assertStatus(200);
    
    $this->assertDatabaseHas('attendances', [
        'user_id' => 1,
    ]);
}
    
}

