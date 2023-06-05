import Modal from '@/Components/Modal';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import { notify } from '@/Commons/Notify';

export default function DeleteUserForm({ showModalDelete, setShowModalDelete, className = '', fetchUsers, deleteUser = {} }) {
    const { processing, setError, reset } = useForm({});

    const handleFormUser = async (e) => {
        e.preventDefault();
        try {
            axios.delete(`users/${deleteUser.id}`)
                .then((response) => {
                    console.log('rs:', response);
                    fetchUsers()
                    closeModal()
                    notify(`Delete ${deleteUser.name} thành công!`, 'success')
                })
                .catch((error) => {
                    console.log('er:', error.response.data.errors);
                    if (error.response.data.message != 'messages.errors.input') {
                        notify(error.response.data.message, 'error')
                    }
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
                <form onSubmit={handleFormUser} className="p-6">
                    <h2 className="text-xl mb-6 font-semibold">Nhắc nhở</h2>
                    <p className="">Bạn có muốn xoá thành viên <strong className="">{deleteUser.name}</strong> không? </p>

                    <div className="flex items-center justify-end mt-4">
                        <SecondaryButton onClick={closeModal}>Hủy</SecondaryButton>

                        <PrimaryButton className="ml-4 bg-red-500" disabled={processing}>
                            Xóa User
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </span>
    );
}
