
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';

export default function SearchProductForm({ reSetSearchFilters, fetchProducts, setCurrentPage }) {
    const { data, setData, processing, errors, reset } = useForm({
        product_name: '',
        is_sales: '',
        product_min_price: '',
        product_max_price: ''
    });

    const searchProduct = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const filters = {
            product_name: formData.get("product_name"),
            is_sales: formData.get("is_sales"),
            product_min_price: formData.get("product_min_price"),
            product_max_price: formData.get("product_max_price")
        }
        console.log(filters)
        reSetSearchFilters(filters)
    };

    const deleteSearch = async (e) => {
        e.preventDefault();
        reSetSearchFilters({})
        setCurrentPage(1)
        reset()
        fetchProducts()
    };

    return (
        <section className={`space-y-2`}>
            <form onSubmit={searchProduct} className="px-6 py-3">
                <div className="grid grid-cols-4 gap-4 w-full">
                    <div className="mt-4">
                        <InputLabel htmlFor="product_name" value="Tên sản phẩm" />

                        <TextInput
                            id="product_name"
                            name="product_name"
                            placeholder="Nhập tên sản phẩm"
                            value={data.product_name}
                            className="mt-1 block w-full"
                            autoComplete="product_name"
                            isFocused={true}
                            onChange={(e) => setData('product_name', e.target.value)}
                        />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="is_sales" value="Trạng thái" />

                        <select
                            id="is_sales"
                            name="is_sales"
                            value={data.is_sales}
                            className="mt-1 block w-full"
                            autoComplete="is_sales"
                            onChange={(e) => setData('is_sales', e.target.value)}
                        >
                            <option value="">Chọn trạng thái</option>
                            <option value="1">Đang bán</option>
                            <option value="0">Ngừng bán</option>
                        </select>

                        <InputError message={errors.is_sales} className="mt-2" />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="product_min_price" value="Giá bán từ" />

                        <TextInput
                            id="product_min_price"
                            name="product_min_price"
                            value={data.product_min_price}
                            className="mt-1 block w-full"
                            autoComplete="product_min_price"
                            onChange={(e) => setData('product_min_price', e.target.value)}
                        />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="product_max_price" value="Giá bán đến" />

                        <TextInput
                            id="product_max_price"
                            name="product_max_price"
                            value={data.product_max_price}
                            className="mt-1 block w-full"
                            autoComplete="product_max_price"
                            onChange={(e) => setData('product_max_price', e.target.value)}
                        />
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

