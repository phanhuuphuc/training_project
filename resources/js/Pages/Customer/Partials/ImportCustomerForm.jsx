import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import Modal from '@/Components/Modal';
import { useState, useEffect } from 'react';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import { notify } from '@/Commons/Notify';

export default function ImportCustomerForm({ setCurrentPage, showModalImport, setShowModalImport, className = '', fetchCustomers }) {

    const { data, setData, processing, errors, setError, clearErrors, reset } = useForm({
        file_csv: null
    });

    const handleSubmitImport = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            clearErrors()
            axios.post('customers/import', formData)
                .then((response) => {
                    fetchCustomers()
                    setCurrentPage(1)
                    closeModal()
                    notify(`Import thành công ${response.data[0]} khách hàng. Có ${response.data[1].length} dòng data khách hàng lỗi, tại dòng: ${(response.data[1])}`, 'success')
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
        setShowModalImport(false);
        reset()
    };


    return (
        <span className={`space-y-6 ${className}`}>
            <Modal show={showModalImport} onClose={closeModal}>
                <form onSubmit={handleSubmitImport} className="p-6">
                    <h3 className="font-semibold text-xl text-gray-800 leading-tight mb-6">Chọn file CSV import khách hàng</h3>

                    <div className="mt-4">
                        <InputLabel htmlFor="file_csv" value="Chọn File CSV" />
                        <input
                            id="file_csv"
                            name="file_csv"
                            className="mt-1 block w-full"
                            autoComplete="file_csv"
                            required
                            type="file"
                        />
                        <InputError message={errors.file_csv} className="mt-2" />
                    </div>


                    <div className="flex items-center justify-end mt-4">
                        <SecondaryButton onClick={closeModal}>Hủy </SecondaryButton>
                        <PrimaryButton className="ml-4" disabled={processing}>
                            Upload
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </span>
    );
}
