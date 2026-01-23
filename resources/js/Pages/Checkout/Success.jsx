import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Success({ auth }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Order Successful</h2>}
        >
            <Head title="Order Successful" />

            <div className="py-12">
                <div className="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-8 text-center">
                            <div className="mb-6">
                                <svg
                                    className="mx-auto h-16 w-16 text-green-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>

                            <h1 className="text-3xl font-bold text-gray-900 mb-4">  
                                Order Placed Successfully!
                            </h1>

                            <p className="text-gray-600 mb-8">
                                Thank you for your purchase. Your order has been confirmed and is being processed.
                            </p>

                            <div className="flex gap-4 justify-center">
                                <a
                                    href={route('products.index')}
                                    className="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-md transition-colors"
                                >
                                    Continue Shopping
                                </a>
                                <a
                                    href={route('dashboard')}
                                    className="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-md transition-colors"
                                >
                                    Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}