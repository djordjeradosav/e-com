import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import FlashMessages from '@/Components/FlashMessages';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ auth, products }) {
    const [selectedQuantities, setSelectedQuantities] = useState({});
    const [processing, setProcessing] = useState(null);

    const handleQuantityChange = (productId, value) => {
        const numValue = parseInt(value) || 1;
        setSelectedQuantities(prev => ({
            ...prev,
            [productId]: numValue
        }));
    };

    const addToCart = (product) => {
        const quantity = selectedQuantities[product.id] || 1;
        
        if (quantity > product.stock_quantity) {
            alert(`Only ${product.stock_quantity} units available`);
            return;
        }

        setProcessing(product.id);
        
        router.post(route('cart.store'), {
            product_id: product.id,
            quantity: quantity
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setSelectedQuantities(prev => ({
                    ...prev,
                    [product.id]: 1
                }));
            },
            onFinish: () => {
                setProcessing(null);
            }
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Products</h2>}
        >
            <Head title="Products" />
            <FlashMessages />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {products.map((product) => (
                                    <div key={product.id} className="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                                        <h3 className="text-xl font-semibold mb-2">{product.name}</h3>
                                        <p className="text-gray-600 mb-4">{product.description}</p>
                                        
                                        <div className="mb-4">
                                            <p className="text-2xl font-bold text-green-600">${product.price}</p>
                                            <p className={`text-sm ${product.stock_quantity <= product.low_stock_threshold ? 'text-red-600 font-semibold' : 'text-gray-500'}`}>
                                                {product.stock_quantity > 0 
                                                    ? `${product.stock_quantity} in stock` 
                                                    : 'Out of stock'}
                                            </p>
                                        </div>

                                        {product.stock_quantity > 0 && (
                                            <div className="flex gap-2">
                                                <input
                                                    type="number"
                                                    min="1"
                                                    max={product.stock_quantity}
                                                    value={selectedQuantities[product.id] || 1}
                                                    onChange={(e) => handleQuantityChange(product.id, e.target.value)}
                                                    className="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                />
                                                <button
                                                    onClick={() => addToCart(product)}
                                                    disabled={processing === product.id}
                                                    className="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    {processing === product.id ? 'Adding...' : 'Add to Cart'}
                                                </button>
                                            </div>
                                        )}

                                        {product.stock_quantity === 0 && (
                                            <button
                                                disabled
                                                className="w-full bg-gray-300 text-gray-600 font-semibold py-2 px-4 rounded-md cursor-not-allowed"
                                            >
                                                Out of Stock
                                            </button>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}