import { useEffect } from 'react';
import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset, clearErrors, setError  } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            clearErrors()
            axios.post('login', formData)
                .then((response) => {
                    window.location.replace('/products');
                })
                .catch((error) => {
                    console.log('er:', error.response.data.errors);
                    setError(error.response.data.errors)
                });
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <form onSubmit={submit}>
                <div>
                    <div className="flex">
                        <i class="fa fa-user border p-3 border-black mt-1 icon-login" aria-hidden="true"></i>
                        <input
                            id="email"
                            type="email"
                            placeholder="Email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full input-login pl-2"
                            autoComplete="username"
                            isFocused={true}
                            onChange={(e) => setData('email', e.target.value)}
                        />
                    </div>
                    <InputError message={errors.email} className="mt-2" />

                </div>

                <div className="mt-4">
                    <div className="flex">
                        <i class="fas fa-lock border border-black p-3 mt-1 icon-login"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Mật khẩu"
                            value={data.password}
                            className="mt-1 block w-full input-login pl-2"
                            autoComplete="current-password"
                            onChange={(e) => setData('password', e.target.value)}
                        />
                    </div>
                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="block mt-4">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                        />
                        <span className="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-4">

                    <PrimaryButton className="ml-4 btn-add" disabled={processing}>
                        Đăng nhập
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
