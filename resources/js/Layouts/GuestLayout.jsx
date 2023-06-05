import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function Guest({ children }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div className="bg-white px-40 py-20 border">
                <div className="p-5">
                    <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
                </div>

                <div className="w-full sm:max-w-md mt-6 overflow-hidden">
                    {children}
                </div>
            </div>
        </div>
    );
}
