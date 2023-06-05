
import Modal from '@/Components/Modal';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import { notify } from '@/Commons/Notify';

export default function BlockUserForm({ currentPage, searchFilters, showModalBlock, setShowModalBlock, className = '', fetchUsers, blockUser = {} }) {
    const { data, setData, post, processing, errors, setError, clearErrors, reset } = useForm({});

    const handleFormUser = async (e) => {
        e.preventDefault();
        try {
            axios.put(`users/${blockUser.id}/block`)
                .then((response) => {
                    console.log('rs:', response);
                    closeModal()
                    fetchUsers(currentPage, searchFilters)
                    notify((blockUser.is_active ? 'Khóa ' : 'Mở khóa ') + `${blockUser.name} thành công!`, 'success')
                })
                .catch((error) => {
                    if (error.response.data.message != 'messages.errors.input') {
                        notify(error.response.data.message, 'error')
                    }
                    setError(error)
                });
        } catch (error) {
            console.error(error);
        }

    };

    const closeModal = () => {
        setShowModalBlock(false);
        reset()
    };

    return (
        <span className={`space-y-6 ${className}`}>

            <Modal show={showModalBlock} onClose={closeModal}>
                <form onSubmit={handleFormUser} className="p-6">
                    <h2 className="text-xl mb-6 font-semibold">Nhắc nhở</h2>
                    <p className="">Bạn có muốn {blockUser.is_active ? 'khóa' : 'mở khóa'} thành viên <strong className="">{blockUser.name}</strong> không ?</p>

                    <div className="flex items-center justify-end mt-4">
                        <SecondaryButton onClick={closeModal}>Hủy</SecondaryButton>

                        <PrimaryButton className="ml-4 ml-4 bg-red-500" disabled={processing}>
                            Lưu
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </span>
    );
}
