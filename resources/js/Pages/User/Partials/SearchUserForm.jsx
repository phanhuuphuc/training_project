
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';

export default function SearchUserForm({ reSetSearchFilters, fetchUsers, setCurrentPage }) {
    const { data, setData, processing, errors, reset } = useForm({
        name: '',
        email: '',
        group_role: '',
        is_active: true
    });

    const searchUser = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const filters = {
            name: formData.get("name"),
            email: formData.get("email"),
            group_role: formData.get("group_role"),
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
        fetchUsers()
    };

    return (
        <section className={`space-y-2`}>
            <form onSubmit={searchUser} className="px-6 py-3">
                <div className="grid grid-cols-4 gap-4 w-full">
                    <div className="mt-4">
                        <InputLabel htmlFor="name" value="Tên" />

                        <TextInput
                            id="name"
                            name="name"
                            value={data.name}
                            placeholder="Nhập họ tên"
                            className="mt-1 block w-full"
                            autoComplete="name"
                            isFocused={true}
                            onChange={(e) => setData('name', e.target.value)}
                        />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="email" className="font-bold" value="Email" />

                        <TextInput
                            id="email"
                            name="email"
                            placeholder="Nhập email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="username"
                            onChange={(e) => setData('email', e.target.value)}
                        />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="GroupRole" value="Nhóm" />

                        <select
                            id="group_role"
                            name="group_role"
                            value={data.group_role}
                            className="mt-1 block w-full"
                            autoComplete="group_role"
                            onChange={(e) => setData('group_role', e.target.value)}
                        >
                            <option value="">Chọn nhóm</option>
                            <option value="Admin">Admin</option>
                            <option value="Reviewer">Reviewer</option>
                            <option value="Editor">Editor</option>
                        </select>

                        <InputError message={errors.group_role} className="mt-2" />
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

                        <InputError message={errors.group_role} className="mt-2" />
                    </div>
                </div>
                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton className="ml-4 btn-search" disabled={processing}>
                    <i className="fa fa-search mr-2"></i>  Tìm kiếm
                    </PrimaryButton>
                    <PrimaryButton className="ml-4 btn-delete-search" onClick={deleteSearch} disabled={processing}>
                    <i className='far fa-trash-alt mr-2'></i> Xóa Tìm
                    </PrimaryButton>
                </div>
            </form>
        </section>
    );
}
