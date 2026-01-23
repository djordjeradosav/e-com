import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import FlashMessages from '@/Components/FlashMessages';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ auth, cartItems, total }) {
    const [processing, setProcessing] = useState(false);
    const [updatingId, setUpdatingId] = useState(null);

    const updateQuantity = (cartItem, newQuantity) => {
        if (newQuantity < 1) return;
        if (newQuantity > cartItem.product.stock_quantity) {
            alert(`Only ${cartItem.product.stock_quantity} units available`);
            return;
        }

        setUpdatingId(cartItem.id);
        
        router.patch(route('cart.update', cartItem.id), 
            { quantity: newQuantity },
            {
                preserveScroll: true,
                onFinish: () => setUpdatingId(null),
            }
        );
    };

    const removeItem = (cartItemId) => {
        if (!confirm('Remove this item from cart?')) return;

        setProcessing(true);
        
        router.delete(route('cart.destroy', cartItemId), {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const checkout = () => {
        if (processing) return;
        
        setProcessing(true);
        
        router.post(route('checkout.store'), {}, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    if (!cartItems || cartItems.length === 0) {
        return (
            <AuthenticatedLayout
                user={auth.user}
                header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Shopping Cart</h2>}
            >
                <Head title="Shopping Cart" />
                <FlashMessages />

                <div className="py-12">
                    <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-8 text-center">
                                <svg
                                    className="mx-auto h-24 w-24 text-gray-400 mb-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                <h3 className="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h3>
                                <p className="text-gray-600 mb-6">Start adding some products!</p>
                                <a
                                    href={route('products.index')}
                                    className="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-md transition-colors"
                                >
                                    Browse Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Shopping Cart</h2>}
        >
            <Head title="Shopping Cart" />
            <FlashMessages />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="space-y-4">
                                {cartItems.map((item) => {
                                    const subtotal = (item.quantity * parseFloat(item.product.price)).toFixed(2);
                                    
                                    return (
                                        <div key={item.id} className="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4 gap-4">
                                            <div className="flex-1 min-w-0">
                                                <h3 className="text-lg font-semibold text-gray-900 truncate">
                                                    {item.product.name}
                                                </h3>
                                                <p className="text-gray-600 mt-1">
                                                    ${parseFloat(item.product.price).toFixed(2)} each
                                                </p>
                                                <p className="text-sm text-gray-500 mt-1">
                                                    Stock: {item.product.stock_quantity} available
                                                </p>
                                            </div>

                                            <div className="flex items-center gap-4 w-full sm:w-auto">
                                                <div className="flex items-center gap-2">
                                                    <button
                                                        onClick={() => updateQuantity(item, item.quantity - 1)}
                                                        disabled={updatingId === item.id || item.quantity <= 1}
                                                        className="w-8 h-8 rounded-md bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed font-semibold transition-colors"
                                                    >
                                                        -
                                                    </button>
                                                    <span className="w-12 text-center font-semibold">
                                                        {item.quantity}
                                                    </span>
                                                    <button
                                                        onClick={() => updateQuantity(item, item.quantity + 1)}
                                                        disabled={updatingId === item.id || item.quantity >= item.product.stock_quantity}
                                                        className="w-8 h-8 rounded-md bg-gray-200 hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed font-semibold transition-colors"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <div className="w-24 text-right">
                                                    <p className="font-semibold text-gray-900">
                                                        ${subtotal}
                                                    </p>
                                                </div>

                                                <button
                                                    onClick={() => removeItem(item.id)}
                                                    disabled={processing}
                                                    className="text-red-600 hover:text-red-800 font-semibold disabled:opacity-50 transition-colors whitespace-nowrap"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>

                            <div className="mt-8 border-t pt-6">
                                <div className="flex justify-between items-center mb-6">
                                    <span className="text-xl font-semibold text-gray-900">Total:</span>
                                    <span className="text-3xl font-bold text-green-600">${total}</span>
                                </div>

                                <div className="flex flex-col sm:flex-row gap-4">
                                    <a
                                        href={route('products.index')}
                                        className="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-md text-center transition-colors"
                                    >
                                        Continue Shopping
                                    </a>
                                    <button
                                        onClick={checkout}
                                        disabled={processing}
                                        className="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {processing ? 'Processing...' : 'Checkout'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}