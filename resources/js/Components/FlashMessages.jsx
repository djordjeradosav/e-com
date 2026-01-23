import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { X, CheckCircle, AlertCircle } from 'lucide-react';

export default function FlashMessages() {
    const { flash } = usePage().props;
    const [visible, setVisible] = useState(false);
    const [message, setMessage] = useState(null);
    const [type, setType] = useState(null);

    useEffect(() => {
        if (flash?.success) {
            setMessage(flash.success);
            setType('success');
            setVisible(true);
        } else if (flash?.error) {
            setMessage(flash.error);
            setType('error');
            setVisible(true);
        }

        if (flash?.success || flash?.error) {
            const timer = setTimeout(() => {
                setVisible(false);
            }, 5000);

            return () => clearTimeout(timer);
        }
    }, [flash]);

    if (!visible || !message) return null;

    return (
        <div className="fixed top-4 right-4 z-50 animate-slide-in">
            <div
                className={`flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg max-w-md ${
                    type === 'success'
                        ? 'bg-green-50 border border-green-200 text-green-800'
                        : 'bg-red-50 border border-red-200 text-red-800'
                }`}
            >
                {type === 'success' ? (
                    <CheckCircle className="w-5 h-5 text-green-600" />
                ) : (
                    <AlertCircle className="w-5 h-5 text-red-600" />
                )}
                <p className="flex-1 text-sm font-medium">{message}</p>
                <button
                    onClick={() => setVisible(false)}
                    className="text-gray-400 hover:text-gray-600 transition-colors"
                >
                    <X className="w-4 h-4" />
                </button>
            </div>
        </div>
    );
}