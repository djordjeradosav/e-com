<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-8 text-center">
                <h1 class="text-4xl font-bold flex items-center justify-center gap-3 mb-2">
                    <span class="text-5xl">📊</span>
                    <span>Daily Sales Report</span>
                </h1>
                <p class="text-green-100 text-lg font-semibold">{{ $date }}</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                <p class="text-gray-700 text-lg mb-6">Hello Admin,</p>
                
                <!-- Summary Section -->
                <div class="bg-green-50 border-l-4 border-green-500 p-6 mb-8 rounded-r">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <p class="text-gray-600 text-sm font-medium mb-1">Total Orders</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $orderCount }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <p class="text-gray-600 text-sm font-medium mb-1">Total Revenue</p>
                            <p class="text-3xl font-bold text-green-600">${{ $totalRevenue }}</p>
                        </div>
                    </div>
                </div>

                @if($orders->count() > 0)
                <!-- Order Details -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Order Details</h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $order->items->count() }} items
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Products Sold -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Products Sold</h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $productSales = [];
                                    foreach($orders as $order) {
                                        foreach($order->items as $item) {
                                            $productName = $item->product->name;
                                            if(!isset($productSales[$productName])) {
                                                $productSales[$productName] = [
                                                    'quantity' => 0,
                                                    'revenue' => 0
                                                ];
                                            }
                                            $productSales[$productName]['quantity'] += $item->quantity;
                                            $productSales[$productName]['revenue'] += $item->subtotal;
                                        }
                                    }
                                @endphp
                                
                                @foreach($productSales as $name => $data)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $data['quantity'] }} units
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($data['revenue'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-700">
                        Best regards,<br>
                        <span class="font-semibold text-gray-900">E-commerce System</span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-800 text-gray-400 text-center p-4 text-sm">
                <p>Automated daily report generated by your e-commerce system</p>
            </div>
        </div>
    </div>
</body>
</html>