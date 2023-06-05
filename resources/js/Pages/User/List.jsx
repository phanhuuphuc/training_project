import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { useState, useEffect } from 'react';
import CreateUserForm from './Partials/CreateUserForm';
import EditUserForm from './Partials/EditUserForm';
import DeleteUserForm from './Partials/DeleteUserForm';
import BlockUserForm from './Partials/BlockUserForm';
import SearchUserForm from './Partials/SearchUserForm';
import { Head } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';
import {canCreate, canDelete, canEdit, canLock} from '@/Commons/RolePermission';

export default function Edit({ auth }) {
    const [users, setUsers] = useState([]);
    const [total, setTotal] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchFilters, setSearchFilters] = useState({});
    const [showModalCreate, setShowModalCreate] = useState(false);
    const [showModalEdit, setShowModalEdit] = useState(false);
    const [showModalDelete, setShowModalDelete] = useState(false);
    const [showModalBlock, setShowModalBlock] = useState(false);
    const [currentUser, setCurrentUser] = useState({});

    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = async (page = 1, filters = {}) => {
        try {
            let params_fetch = {
                page: page,
                ... filters
            };
            const response = await axios.get(`/users?axios=1`, { params: params_fetch });
            console.log(response.data);
            setUsers(response.data.data);
            setTotal(response.data.total);
        } catch (error) {
            console.error(error);
        }
    };

    const handleEdit = async (user) => {
        setCurrentUser(user)
        setShowModalEdit(true)
    };

    const handleDelete = async (user) => {
        setCurrentUser(user)
        setShowModalDelete(true)
    };

    const handleBlock = async (user) => {
        setCurrentUser(user)
        setShowModalBlock(true)
    };

    const reSetCurrentPage = async (page) => {
        setCurrentPage(page)
        fetchUsers(page, searchFilters)
    };

    const reSetSearchFilters = async (filters) => {
        console.log('reSetSearchFilters:', filters)
        setSearchFilters(filters)
        fetchUsers(currentPage, filters)
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">List Users</h2>}
        >
            <Head title="Profile" />

            <div className="py-3">
                <div className="space-y-6 div-main">

                    <SearchUserForm
                        className="max-w-xl"
                        reSetSearchFilters={reSetSearchFilters}
                        setCurrentPage={setCurrentPage}
                        fetchUsers={fetchUsers}
                    />

                    { canCreate(auth.user.group_role) &&
                     <PrimaryButton onClick={()=>{setShowModalCreate(true)}} className="create-user btn-add"> <i className="fa fa-plus mr-2" aria-hidden="true"></i> Thêm mới</PrimaryButton>
                    }

                    <EditUserForm
                        className="max-w-xl"
                        fetchUsers={fetchUsers}
                        editUser={currentUser}
                        currentPage={currentPage}
                        searchFilters={searchFilters}
                        showModalEdit={showModalEdit}
                        setCurrentPage={setCurrentPage}
                        setShowModalEdit={setShowModalEdit}
                    />

                    <CreateUserForm
                        className="max-w-xl"
                        fetchUsers={fetchUsers}
                        showModalCreate={showModalCreate}
                        setCurrentPage={setCurrentPage}
                        setShowModalCreate={setShowModalCreate}
                    />

                    <DeleteUserForm
                        className="max-w-xl"
                        fetchUsers={fetchUsers}
                        deleteUser={currentUser}
                        showModalDelete={showModalDelete}
                        setShowModalDelete={setShowModalDelete}
                    />
                    <BlockUserForm
                        className="max-w-xl"
                        fetchUsers={fetchUsers}
                        currentPage={currentPage}
                        searchFilters={searchFilters}
                        blockUser={currentUser}
                        showModalBlock={showModalBlock}
                        setShowModalBlock={setShowModalBlock}
                    />

                    { users.length > 0 &&
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
                            <th scope="col" className="px-6 py-3">Nhóm</th>
                            <th scope="col" className="px-6 py-3">Trạng Thái</th>
                            <th scope="col" className="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        {users.map((user, index) => (
                            <tr key={user.id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            style={{ backgroundColor: (index % 2 == 1) ? '#f0f0f0' : '' }}
                            >
                                <td className="px-6 py-4">
                                    {(currentPage-1)*20 + index + 1}
                                </td>
                                <td className="px-6 py-4">
                                    {user.name}
                                </td>
                                <td className="px-6 py-4">
                                    {user.email}
                                </td>
                                <td className="px-6 py-4">
                                    {user.group_role}
                                </td>
                                <td className="px-6 py-4">
                                    {user.is_active ? (<span className="active"> Đang hoạt động </span>) : (<span className="in-active">Tạm khóa</span>)}
                                </td>
                                <td className="px-6 py-4">
                                    { canEdit(auth.user.group_role) &&
                                    <span className="edit-user"
                                        onClick={()=>{handleEdit(user)}}
                                    >  <i className="fas fa-edit mr-3 edit-icon"></i> </span>
                                    }

                                    { canDelete(auth.user.group_role) &&
                                    <span className="edit-user"
                                        onClick={()=>{handleDelete(user)}}
                                    >  <i className="fas fa-trash-alt mr-3 delete-icon"></i> </span>
                                    }

                                    { canLock(auth.user.group_role) &&
                                    <span className="edit-user"
                                        onClick={()=>{handleBlock(user)}}
                                    > {user.is_active ?  <i className="fas fa-lock"></i>  : <i className="fas fa-lock-open"></i>}
                                    </span>
                                    }
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
