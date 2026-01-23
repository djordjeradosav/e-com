<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:send-daily-sales-report';

    /**
     * The console command description.
     */
    protected $description = 'Send daily sales report to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y-m-d');
        
        $orders = Order::with('items.product')
            ->whereDate('created_at', today())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found for today.');
            return 0;
        }

        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        
        Mail::to($adminEmail)->send(new DailySalesReport($orders, $today));

        $this->info("Daily sales report sent to {$adminEmail}");
        $this->info("Total orders: " . $orders->count());
        
        return 0;
    }
}