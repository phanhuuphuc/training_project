import { useEffect } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import Modal from '@/Components/Modal';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import { notify } from '@/Commons/Notify';

export default function EditUserForm({ showModalEdit, setShowModalEdit, className = '', fetchUsers, currentPage, searchFilters, editUser = {} }) {

    const { data, setData, processing, errors, setError, clearErrors, reset } = useForm({
        name: '',
        email: '',
        group_role: '',
        is_active: 1,
        password: '',
        password_confirmation: '',
    });

    const handleFormUser = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.set("is_active", formData.get("is_active") ? 1 : 0);
        try {
            clearErrors()

            formData.append("_method", 'PUT');
            if (!formData.get("password")) {
                formData.delete('password');
                formData.delete('password_confirmation')
            }
            axios.post(`users/${editUser.id}`, formData)
                .then((response) => {
                    console.log('rs:', response);
                    fetchUsers(currentPage, searchFilters)
                    closeModal()
                    notify(`Update ${editUser.name} thành công!`, 'success')
                })
                .catch((error) => {
                    console.log('er:', error);
                    setError(error.response.data.errors)
                    if (error.response.data.message != 'messages.errors.input') {
                        notify(error.response.data.message, 'error')
                    }
                });
        } catch (error) {
            console.error(error);
        }
    };

    const closeModal = () => {
        setShowModalEdit(false);
        reset({})
    };

    useEffect(() => {
        if (editUser) {
            delete editUser.password
            setData(editUser)
        }
    }, [editUser]);

    return (
        <span className={`space-y-6 ${className}`}>

            <Modal show={showModalEdit} onClose={closeModal}>
                <form onSubmit={handleFormUser} className="p-6">
                    {<h2 className="text-xl mb-2 font-semibold">Edit User</h2>}

                    <div className="flex">
                        <InputLabel className="mt-4 label-form" htmlFor="name" value="Tên" />
                        <div className="w-full">
                            <TextInput
                                id="name"
                                name="name"
                                value={data.name}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.name ? 'red' : '' }}
                                autoComplete="name"
                                isFocused={true}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                            />

                            <InputError message={errors.name} className="mt-2" />
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
                                autoComplete="username"
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />

                            <InputError message={errors.email} className="mt-2" />
                        </div>

                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="password" value="Mật khẩu" />
                        <div className="w-full">
                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.password ? 'red' : '' }}
                                autoComplete="new-password"
                                onChange={(e) => setData('password', e.target.value)}
                            />

                            <InputError message={errors.password} className="mt-2" />
                        </div>

                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="password_confirmation" value="Xác nhận" />
                        <div className="w-full">
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                value={data.password_confirmation}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.password_confirmation ? 'red' : '' }}
                                autoComplete="new-password"
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                            />

                            <InputError message={errors.password_confirmation} className="mt-2" />
                        </div>
                    </div>

                    <div className="mt-4 flex">
                        <InputLabel className="mt-4 label-form" htmlFor="GroupRole" value="Nhóm" />
                        <div className="w-full">
                            <select
                                id="group_role"
                                name="group_role"
                                value={data.group_role}
                                className="mt-1 block w-full"
                                style={{ borderColor: errors.group_role ? 'red' : '' }}
                                autoComplete="group_role"
                                onChange={(e) => setData('group_role', e.target.value)}
                                required
                            >
                                <option value="Admin">Admin</option>
                                <option value="Reviewer">Reviewer</option>
                                <option value="Editor">Editor</option>
                            </select>

                            <InputError message={errors.group_role} className="mt-2" />
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

                        <PrimaryButton className="ml-4 ml-4 bg-red-500" disabled={processing}>
                            Lưu
                        </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </span>
    );
}
