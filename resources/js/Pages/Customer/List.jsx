import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import Pagination from '@/Components/Pagination';
import { useState, useEffect } from 'react';
import CreateCustomerForm from './Partials/CreateCustomerForm';
import ImportCustomerForm from './Partials/ImportCustomerForm';
import SearchCustomerForm from './Partials/SearchCustomerForm';
import { Head } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';
import {canCreate, canDelete, canEdit, canLock} from '@/Commons/RolePermission';
import { notify } from '@/Commons/Notify';

export default function Edit({ auth }) {
    const [customers, setCustomers] = useState([]);
    const [total, setTotal] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchFilters, setSearchFilters] = useState({});
    const [showModalCreate, setShowModalCreate] = useState(false);
    const [showModalImport, setShowModalImport] = useState(false);
    // for edit inline
    const [errors, setErrors] = useState({});
    const [editingId, setEditingId] = useState(null);
    const [editedCustomerName, setEditedCustomerName] = useState('');
    const [editedEmail, setEditedEmail] = useState('');
    const [editedAddress, setEditedAddress] = useState('');
    const [editedTelNum, setEditedTelNum] = useState('');


    useEffect(() => {
        fetchCustomers();
    }, []);

    const fetchCustomers = async (page = 1, filters = {}) => {
        try {
            let params_fetch = {
                page: page,
                ...filters
            };
            const response = await axios.get(`/customers?axios=1`, { params: params_fetch });
            console.log(response.data);
            setCustomers(response.data.data);
            setTotal(response.data.total);
        } catch (error) {
            console.error(error);
        }
    };

    const handleEdit = (customer) => {
        setErrors({})
        setEditingId(customer.customer_id);
        setEditedCustomerName(customer.customer_name);
        setEditedEmail(customer.email);
        setEditedAddress(customer.address);
        setEditedTelNum(customer.tel_num);
    };

    const handleCancelEdit = () => {
        setErrors({})
        setEditingId(null);
        setEditedCustomerName('');
        setEditedEmail('');
        setEditedAddress('');
        setEditedTelNum('');
    };

    const handleUpdate = async (customer) => {
        const formData = new FormData();
        formData.append("customer_name", editedCustomerName);
        formData.append("email", editedEmail);
        formData.append("address", editedAddress);
        formData.append("tel_num", editedTelNum);
        formData.append("_method", 'PUT');

        try {
            setErrors({})
            axios.post(`customers/${customer.customer_id}`, formData)
                .then((response) => {
                    console.log('rs:', response);
                    fetchCustomers(currentPage, searchFilters)
                    notify(`Update ${customer.customer_name} thành công!`)
                    handleCancelEdit();
                })
                .catch((error) => {
                    console.log('er:', error);
                    setErrors(error.response.data.errors)
                });
        } catch (error) {
            console.error(error);
        }
    };

    const reSetCurrentPage = async (page) => {
        setCurrentPage(page)
        fetchCustomers(page, searchFilters)
    };

    const reSetSearchFilters = async (filters) => {
        console.log('reSetSearchFilters:', filters)
        setSearchFilters(filters)
        fetchCustomers(currentPage, filters)
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Danh sách khách hàng</h2>}
        >
            <Head title="Profile" />

            <div className="py-3">
                <div className="space-y-6 div-main">
                    <SearchCustomerForm
                        className="max-w-xl"
                        reSetSearchFilters={reSetSearchFilters}
                        setCurrentPage={setCurrentPage}
                        fetchCustomers={fetchCustomers}
                        auth={auth}
                    />

                    { canCreate(auth.user.group_role) &&
                    <PrimaryButton onClick={() => { setShowModalCreate(true) }} className="create-user btn-add"> <i className="fa fa-plus mr-2" aria-hidden="true"></i> Thêm mới</PrimaryButton>
                    }

                    <CreateCustomerForm
                        className="max-w-xl"
                        fetchCustomers={fetchCustomers}
                        showModalCreate={showModalCreate}
                        setCurrentPage={setCurrentPage}
                        setShowModalCreate={setShowModalCreate}
                    />


                    { canCreate(auth.user.group_role) &&
                    <PrimaryButton onClick={() => { setShowModalImport(true) }} className="import-customer btn-delete-search">
                    <i className="fas fa-upload mr-3"></i> Import</PrimaryButton>
                    }

                    <ImportCustomerForm
                        className="max-w-xl"
                        fetchCustomers={fetchCustomers}
                        showModalImport={showModalImport}
                        setCurrentPage={setCurrentPage}
                        setShowModalImport={setShowModalImport}
                    />

                    { customers.length > 0 &&
                    <Pagination
                        total={total}
                        currentPage={currentPage}
                        reSetCurrentPage={reSetCurrentPage}
                    />}


                    <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr className="bg-red-500 text-white">
                                <th scope="col" className="px-6 py-3">#</th>
                                <th scope="col" className="px-6 py-3">Tên</th>
                                <th scope="col" className="px-6 py-3">Email</th>
                                <th scope="col" className="px-6 py-3">Địa chỉ</th>
                                <th scope="col" className="px-6 py-3">Điện thoại</th>
                                <th scope="col" className="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {customers.map((customer, index) => (
                                <tr key={customer.customer_id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                style={{ backgroundColor: (index % 2 == 1) ? '#f0f0f0' : '' }}>
                                    <td className="px-6 py-4">
                                        {(currentPage - 1) * 20 + index + 1}
                                    </td>
                                    <td className="px-6 py-4">
                                        {editingId === customer.customer_id ? (
                                        <div className="">
                                            <input
                                                type="text"
                                                style={{ borderColor: errors.customer_name ? 'red' : '' }}
                                                value={editedCustomerName}
                                                onChange={(e) => setEditedCustomerName(e.target.value)}
                                            />
                                            <InputError message={errors.customer_name} className="mt-2" />
                                        </div>
                                        ) : (
                                            customer.customer_name
                                        )}
                                    </td>

                                    <td className="px-6 py-4">
                                        {editingId === customer.customer_id ? (
                                            <div className="">
                                                <input
                                                    type="text"
                                                    style={{ borderColor: errors.email ? 'red' : '' }}
                                                    value={editedEmail}
                                                    onChange={(e) => setEditedEmail(e.target.value)}
                                                />
                                                <InputError message={errors.email} className="mt-2" />
                                            </div>
                                        ) : (
                                            customer.email
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        {editingId === customer.customer_id ? (
                                            <div className="">
                                                <input
                                                    type="text"
                                                    style={{ borderColor: errors.address ? 'red' : '' }}
                                                    value={editedAddress}
                                                    onChange={(e) => setEditedAddress(e.target.value)}
                                                />
                                                <InputError message={errors.address} className="mt-2" />
                                            </div>
                                        ) : (
                                            customer.address
                                        )}
                                    </td>
                                    <td className="px-6 py-4">
                                        {editingId === customer.customer_id ? (
                                            <div className="">
                                                <input
                                                    type="text"
                                                    style={{ borderColor: errors.tel_num ? 'red' : '' }}
                                                    value={editedTelNum}
                                                    onChange={(e) => setEditedTelNum(e.target.value)}
                                                />
                                                <InputError message={errors.tel_num} className="mt-2" />
                                            </div>
                                        ) : (
                                            customer.tel_num
                                        )}
                                    </td>

                                    <td className="px-6 py-4">
                                        {editingId === customer.customer_id ? (
                                        <>
                                            <button onClick={() => handleUpdate(customer)}>
                                                <i className="fas fa-save mr-3 save-icon"></i>
                                            </button>
                                            <button onClick={handleCancelEdit}>
                                                <i className="fas fa-times-circle delete-icon"></i>
                                            </button>
                                        </>
                                        ) : (
                                            canEdit(auth.user.group_role) &&
                                            <button onClick={() => handleEdit(customer)}>
                                                <i className="fas fa-edit edit-icon"></i>
                                            </button>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>

                    <Pagination
                        total={total}
                        currentPage={currentPage}
                        reSetCurrentPage={reSetCurrentPage}
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
