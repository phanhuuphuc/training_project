import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import Modal from '@/Components/Modal';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import { notify } from '@/Commons/Notify';

export default function CreateCustomerForm({ setCurrentPage, showModalCreate, setShowModalCreate, className = '', fetchCustomers }) {

    const { data, setData, processing, errors, setError, clearErrors, reset } = useForm({
        customer_name: '',
        email: '',
        address: '',
        is_active: 1,
        tel_num: '',
    });

    const handleFormCustomer = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.set("is_active", formData.get("is_active") ? 1 : 0);
        try {
            clearErrors()
            axios.post('customers', formData)
                .then((response) => {
                    fetchCustomers()
                    setCurrentPage(1)
                    closeModal()
                    notify(`Tạo khách hàng ${formData.get('customer_name')} thành công!`, 'success')
                })
                .catch((error) => {
                    console.log('er:', error.response.data.errors);
                    setError(error.response.data.errors)
                });
        } catch (error) {
            console.error(error);
        }

    };

    const closeModal = () => {
        setShowModalCreate(false);
        reset()
    };

    return (
        <span className={`space-y-6 ${className}`}>

            <Modal show={showModalCreate} onClose={closeModal}>
                <form onSubmit={handleFormCustomer} className="p-6">
                    <h2 className="text-xl mb-6 font-semibold">Thêm Khách Hàng</h2>
                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="customer_name" value="Tên" />
                        <div className="w-full">
                            <TextInput
                                id="customer_name"
                                name="customer_name"
                                value={data.customer_name}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.customer_name ? 'red' : '' }}
                                autoComplete="customer_name"
                                isFocused={true}
                                onChange={(e) => setData('customer_name', e.target.value)}
                                required
                            />

                            <InputError message={errors.customer_name} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="email" value="Email" />
                        <div className="w-full">
                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.email ? 'red' : '' }}
                                autoComplete="customername"
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />

                            <InputError message={errors.email} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="tel_num" value="Điện thoại" />
                        <div className="w-full">
                            <TextInput
                                id="tel_num"
                                name="tel_num"
                                value={data.tel_num}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.tel_num ? 'red' : '' }}
                                autoComplete="tel_num"
                                onChange={(e) => setData('tel_num', e.target.value)}
                                required
                            />

                            <InputError message={errors.tel_num} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="address" value="Địa chỉ" />
                        <div className="w-full">
                            <TextInput
                                id="address"
                                name="address"
                                value={data.address}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.address ? 'red' : '' }}
                                autoComplete="address"
                                onChange={(e) => setData('address', e.target.value)}
                                required
                            />

                            <InputError message={errors.address} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="isActive" value="Trạng thái" />
                        <div className="w-full">
                            <TextInput
                                id="is_active"
                                type="checkbox"
                                name="is_active"
                                checked={data.is_active}
                                className="mt-1 inline-block mt-5"
                                style={{ borderColor: errors.is_active ? 'red' : '' }}
                                autoComplete="is_active"
                                onChange={() => setData('is_active', !data.is_active)}
                            />
                            <InputError message={errors.is_active} className="mt-2" />
                        </div>
                    </div>

                    <div className="flex items-center justify-end mt-4">
                        <SecondaryButton onClick={closeModal}>Hủy</SecondaryButton>

                        <PrimaryButton className="ml-4 bg-red-500" disabled={processing}>
                            Lưu
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </span>
    );
}
