<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-red-600 text-white p-6 text-center">
                <h1 class="text-3xl font-bold flex items-center justify-center gap-2">
                    <span class="text-4xl">⚠️</span>
                    <span>Low Stock Alert</span>
                </h1>
            </div>

            <!-- Content -->
            <div class="p-6 bg-gray-50">
                <p class="text-gray-700 mb-6">Hello Admin,</p>
                
                <!-- Alert Box -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r">
                    <p class="text-gray-800 font-semibold mb-4">
                        The following product is running low on stock:
                    </p>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900">Product:</span>
                            <span class="text-gray-800">{{ $productName }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900">Current Stock:</span>
                            <span class="text-2xl font-bold text-red-600">{{ $currentStock }} units</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900">Low Stock Threshold:</span>
                            <span class="text-gray-800">{{ $threshold }} units</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-semibold">
                        ⚡ Action Required: Please restock this item as soon as possible to avoid running out of inventory.
                    </p>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-700">
                        Best regards,<br>
                        <span class="font-semibold text-gray-900">E-commerce System</span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-800 text-gray-400 text-center p-4 text-sm">
                <p>This is an automated notification from your e-commerce system</p>
            </div>
        </div>
    </div>
</body>
</html>