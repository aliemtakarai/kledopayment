<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use App\Models\Payment;
use App\Jobs\DeletePayment;

class PaymentTest extends TestCase
{
    /**
     * Test for get data.
     *
     */
    public function testGetPayment()
    {
        $response = $this->get('api/payment', ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }

    /**
     * Test For Validation Input Payment
     */
    public function testValidationPayment()
    {
        $response = $this->post('api/payment', ['Accept' => 'application/json']);

        $response->assertStatus(500)
                 ->assertJson(['message' => 'fill in the blank required fields']);
    }

    /**
     * Test Store Payment
     */
    public function testStorePayment()
    {
        $payment = [
            'name' => 'BNI'
        ];
        $response = $this->post('api/payment',$payment,['Accept' => 'application/json']);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'success']);

    }
    /**
     * Test Delete Payment
     */
    public function testDeletePayment()
    {
        $id = '1,2,3';
        $response = $this->delete('api/payment/'.$id,['Accept' => 'application/json']);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'success']);
    }
    /**
     * Job Test
     */
    public function testJob()
    {
        Queue::fake();
        DeletePayment::dispatch(1);
        Queue::assertPushed(DeletePayment::class);
    }
}
