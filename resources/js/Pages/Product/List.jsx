import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import { useState, useEffect } from 'react';
import DeleteProductForm from './Partials/DeleteProductForm';
import ImageProductModal from './Partials/ImageProductModal';
import SearchProductForm from './Partials/SearchProductForm';
import { Head } from '@inertiajs/react';
import PrimaryButton from '@/Components/PrimaryButton';
import { canCreate, canDelete, canEdit, canLock } from '@/Commons/RolePermission';
import { formatNumber } from '@/Commons/Number';
import { notify } from '@/Commons/Notify';

export default function List({ auth, mgs_product }) {
    const [currProductImage, setCurrProductImage] = useState(null);
    const [products, setProducts] = useState([]);
    const [total, setTotal] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);
    const [searchFilters, setSearchFilters] = useState({});
    const [showModalDelete, setShowModalDelete] = useState(false);
    const [showModalImage, setShowModalImage] = useState(false);
    const [currentProduct, setCurrentProduct] = useState({});

    useEffect(() => {
        fetchProducts();

        if (mgs_product) {
            notify(mgs_product, 'success')
        }
    }, []);

    const fetchProducts = async (page = 1, filters = {}) => {
        try {
            let params_fetch = {
                page: page,
                ...filters
            };
            const response = await axios.get(`/products?axios=1`, { params: params_fetch });
            console.log(response.data);
            setProducts(response.data.data);
            setTotal(response.data.total);
        } catch (error) {
            console.error(error);
        }
    };

    const handleDelete = async (product) => {
        setCurrentProduct(product)
        setShowModalDelete(true)
    };

    const reSetCurrentPage = async (page) => {
        setCurrentPage(page)
        fetchProducts(page, searchFilters)
    };

    const reSetSearchFilters = async (filters) => {
        console.log('reSetSearchFilters:', filters)
        setSearchFilters(filters)
        fetchProducts(currentPage, filters)
    };

    const handleMouseEnter = (image) => {
        if (image) {
            setCurrProductImage(image)
            setShowModalImage(true)
        }
    };

    const handleEdit = (product) => {
        window.location.href = `/products/${product.product_id}/edit`;
    };



    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">List Products</h2>}
        >
            <Head title="Profile" />

            <div className="py-3">
                <div className="space-y-6 div-main">
                    <SearchProductForm
                        className="max-w-xl"
                        reSetSearchFilters={reSetSearchFilters}
                        setCurrentPage={setCurrentPage}
                        fetchProducts={fetchProducts}
                    />

                    {canCreate(auth.user.group_role) &&
                        <PrimaryButton onClick={() => { window.location.href = route('products.create') }} className="create-user btn-add"> <i className="fa fa-plus mr-2" aria-hidden="true"></i> Thêm mới</PrimaryButton>
                    }

                    <DeleteProductForm
                        className="max-w-xl"
                        fetchProducts={fetchProducts}
                        deleteProduct={currentProduct}
                        showModalDelete={showModalDelete}
                        setShowModalDelete={setShowModalDelete}
                    />

                    <ImageProductModal
                        className="max-w-xl"
                        currProductImage={currProductImage}
                        showModalImage={showModalImage}
                        setShowModalImage={setShowModalImage}
                    />

                    { products.length > 0 &&
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
                                <th scope="col" className="px-6 py-3">Mô tả</th>
                                <th scope="col" className="px-6 py-3">Giá</th>
                                <th scope="col" className="px-6 py-3">Tình trạng</th>
                                <th scope="col" className="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {products.map((product, index) => (
                                <tr key={product.product_id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                    style={{ backgroundColor: (index % 2 == 1) ? '#f0f0f0' : '' }}
                                >
                                    <td className="px-6 py-4">
                                        {(currentPage - 1) * 20 + index + 1}
                                    </td>
                                    <td
                                        className="px-6 py-4"
                                    >
                                        <span
                                            onMouseEnter={() => handleMouseEnter(product.product_image_url)}
                                            className="">
                                            {product.product_name}
                                        </span>

                                    </td>
                                    <td className="px-6 py-4">
                                        <div dangerouslySetInnerHTML={{ __html: product.description }} />
                                    </td>
                                    <td className="px-6 py-4">
                                        ${formatNumber(product.product_price)}
                                    </td>
                                    <td className="px-6 py-4">
                                        {product.is_sales ? (<span className="active"> Đang bán </span>) : (<span className="in-active">Ngừng bán</span>)}
                                    </td>
                                    <td className="px-6 py-4">
                                        {canEdit(auth.user.group_role) &&
                                            <span className="edit-product"
                                                onClick={() => { handleEdit(product) }}
                                            >  <i className="fas fa-edit mr-3 edit-icon"></i> </span>
                                        }

                                        {canDelete(auth.user.group_role) &&
                                            <span className="edit-product"
                                                onClick={() => { handleDelete(product) }}
                                            >  <i className="fas fa-trash-alt mr-3 delete-icon"></i> </span>
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
