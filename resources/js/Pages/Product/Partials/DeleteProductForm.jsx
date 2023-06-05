import Modal from '@/Components/Modal';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import { notify } from '@/Commons/Notify';

export default function DeleteProductForm({ showModalDelete, setShowModalDelete, className = '', fetchProducts, deleteProduct = {} }) {

    const { processing, setError, reset } = useForm({});

    const handleFormProduct = async (e) => {
        e.preventDefault();
        try {
            axios.delete(`products/${deleteProduct.product_id}`)
                .then((response) => {
                    console.log('rs:', response);
                    fetchProducts()
                    closeModal()
                    notify(`Delete ${deleteProduct.product_name} thành công!`, 'success')
                })
                .catch((error) => {
                    console.log('er:', error.response.data.errors);
                    notify(`Delete ${deleteProduct.product_name} thất bại!`, 'error')
                    setError(error.response.data.errors)
                });
        } catch (error) {
            console.error(error);
        }

    };

    const closeModal = () => {
        setShowModalDelete(false);
        reset()
    };

    return (
        <span className={`space-y-6 ${className}`}>

            <Modal show={showModalDelete} onClose={closeModal}>
                <form onSubmit={handleFormProduct} className="p-6">
                    <h2 className="text-xl mb-6 font-semibold">Nhắc nhở</h2>
                    <p className="">Bạn có muốn xoá sản phẩm <strong className="">{deleteProduct.product_name}</strong> không? </p>

                    <div className="flex items-center justify-end mt-4">
                        <SecondaryButton onClick={closeModal}>Hủy</SecondaryButton>

                        <PrimaryButton className="ml-4" disabled={processing}>
                            OK
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </span>
    );
}
