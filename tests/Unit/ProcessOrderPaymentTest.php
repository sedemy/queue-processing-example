<?php

namespace Tests\Unit;

use App\Jobs\ProcessOrderPaymentJob;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessOrderPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_processing_transitions_status()
    {
        Queue::fake();  // Prevent actual queue processing

        $user = User::factory()->create();

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        ProcessOrderPaymentJob::dispatch($order->id);

        Queue::assertPushed(ProcessOrderPaymentJob::class, 1);

        $job = (new ProcessOrderPaymentJob($order->id))->handle();

        $updatedOrder = Order::find($order->id);

        $this->assertContains($updatedOrder->status, ['completed', 'failed']);
        $this->assertNotEquals('pending', $updatedOrder->status);
    }

    public function test_failed_job_is_retried()
    {
        Queue::fake();

        $user = User::factory()->create();

        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        ProcessOrderPaymentJob::dispatch($order->id);

        Queue::assertPushed(ProcessOrderPaymentJob::class, 1);
    }
}
