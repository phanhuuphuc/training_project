
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import {canCreate} from '@/Commons/RolePermission';

export default function SearchCustomerForm({ auth, reSetSearchFilters, fetchCustomers, setCurrentPage }) {
    const { data, setData, processing, errors, reset } = useForm({
        customer_name: '',
        email: '',
        address: '',
        is_active: ''
    });

    const exportCustomers = async (e) => {
        let paramsString=`&customer_name=${data.customer_name}&email=${data.email}&address=${data.address}&is_active=${data.is_active}`;
        window.open(`/customers?export_csv=1${paramsString}`);
    };

    const searchCustomer = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const filters = {
            customer_name: formData.get("customer_name"),
            email: formData.get("email"),
            address: formData.get("address"),
            is_active: formData.get("is_active")
        }
        console.log(filters)
        reSetSearchFilters(filters)
    };

    const deleteSearch = async (e) => {
        e.preventDefault();
        reSetSearchFilters({})
        setCurrentPage(1)
        reset()
        fetchCustomers()
    };

    return (
        <section className={`space-y-2`}>
            <form onSubmit={searchCustomer} className="px-6 py-3">
                <div className="grid grid-cols-4 gap-4 w-full">
                    <div className="mt-4">
                        <InputLabel htmlFor="customer_name" value="Họ Tên" />

                        <TextInput
                            id="customer_name"
                            placeholder="Nhập họ tên"
                            name="customer_name"
                            value={data.name}
                            className="mt-1 block w-full"
                            autoComplete="customer_name"
                            isFocused={true}
                            onChange={(e) => setData('customer_name', e.target.value)}
                        />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="email" value="Email" />

                        <TextInput
                            id="email"
                            name="email"
                            placeholder="Nhập email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="customername"
                            onChange={(e) => setData('email', e.target.value)}
                        />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="isActive" value="Trạng thái" />

                        <select
                            id="is_active"
                            name="is_active"
                            value={data.is_active}
                            className="mt-1 block w-full"
                            autoComplete="is_active"
                            onChange={(e) => setData('is_active', e.target.value)}
                        >
                            <option value="">Chọn trạng thái</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Tạm ngừng</option>
                        </select>

                        <InputError message={errors.address} className="mt-2" />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="address" value="Địa chỉ" />

                        <TextInput
                            id="address"
                            name="address"
                            placeholder="Nhập địa chỉ"
                            value={data.name}
                            className="mt-1 block w-full"
                            autoComplete="address"
                            onChange={(e) => setData('address', e.target.value)}
                        />
                    </div>

                </div>
                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton onClick={()=>exportCustomers()}
                    className="export-customer btn-delete-search"
                    style={{left: !canCreate(auth.user.group_role) ? "25px" : ""}}
                    >
                    <i className="fas fa-download mr-2"></i> Export
                    </PrimaryButton>

                    <PrimaryButton className="ml-4 btn-search" disabled={processing}>
                    <i className="fa fa-search mr-2"></i> Tìm kiếm
                    </PrimaryButton>
                    <PrimaryButton className="ml-4 btn-delete-search" onClick={deleteSearch} disabled={processing}>
                    <i className='far fa-trash-alt mr-2'></i> Xóa Tìm
                    </PrimaryButton>
                </div>
            </form>
        </section>
    );
}
