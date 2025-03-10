<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrderPaymentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $order = Order::findOrFail($this->orderId);
        $order->update(['status' => 'processing']);

        sleep(2);  // assume api call

        $randomResult = (rand() % 2);  // it returns true or false result
        if ($randomResult) {
            $order->update(['status' => 'completed']);
            Log::info("Order {$this->orderId} processed successfully.");
        } else {
            $order->update(['status' => 'failed']);
            Log::error("Order {$this->orderId} processing failed.");
            $this->release(10);  // try after 10 seconds
        }
    }
}
