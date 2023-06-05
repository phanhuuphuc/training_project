import InputError from '@/Components/InputError';
import { useRef } from 'react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import { useForm } from '@inertiajs/react';
import SecondaryButton from '@/Components/SecondaryButton';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import CkeditorTextarea from '@/Components/CkeditorTextarea';

export default function EditProductForm({auth, className = '',  editProduct}) {
    let editProductData = editProduct.data;
    const fileInputRef = useRef(null);
    const [fileName, setFileName] = useState('tên file upload');
    const [previewImage, setPreviewImage] = useState('');

    const { data, setData, processing, errors, setError, clearErrors, reset } = useForm({
        product_name: '',
        product_image: '',
        product_price: '',
        is_sales: '',
        description: ''
    });

    const handleFormProduct = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('description', data.description)
        formData.append("_method", 'PUT');
        try {
            clearErrors()
            axios.post( `/products/${editProductData.product_id}`, formData)
                .then((response) => {
                    console.log('success update product')
                    window.location.href = '/products';
                })
                .catch((error) => {
                    console.log('er:', error.response.data.errors);
                    setError(error.response.data.errors)
                });
        } catch (error) {
            console.error(error);
        }
    };

    const handleImageChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            setFileName(file.name);
            const reader = new FileReader();
            reader.onload = () => {
                setPreviewImage(reader.result);
            };
            reader.readAsDataURL(file);
        }
      };

    const unSelectFile = () => {
        setData('product_image', '');
        setPreviewImage('/image/thumb.png');
        setFileName('tên file upload');
    }

    function getBaseName(url) {
        const parsedUrl = new URL(url);
        const path = parsedUrl.pathname;
        const parts = path.split('/');
        const baseName = parts[parts.length - 1];
        return baseName;
      }

    useEffect(() => {
        if (editProductData.product_image) {
            setFileName(getBaseName(editProductData.product_image_url))
            setPreviewImage(editProductData.product_image_url);
        }
        else {
            setPreviewImage('/image/thumb.png');
        }
        editProductData.product_image = '';
        setData(editProductData)

    }, [editProductData]);

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Edit Products</h2>}
        >
            <Head title="Profile" />

            <div className="py-3">
                <div className="space-y-6 div-main">
                    <span className={`space-y-6 ${className}`}>
                        <form onSubmit={handleFormProduct} className="p-6">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="p-4">
                                    <div>
                                        <InputLabel htmlFor="product_name" value="Tên" />

                                        <TextInput
                                            id="product_name"
                                            name="product_name"
                                            value={data.product_name}
                                            className="mt-1 block w-full"
                                            style={{ borderColor: errors.product_name ? 'red' : '' }}
                                            autoComplete="product_name"
                                            isFocused={true}
                                            onChange={(e) => setData('product_name', e.target.value)}
                                            required
                                        />

                                        <InputError message={errors.product_name} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="product_price" value="Giá bán" />

                                        <TextInput
                                            id="product_price"
                                            name="product_price"
                                            type="number"
                                            value={data.product_price}
                                            className="mt-1 block w-full"
                                            style={{ borderColor: errors.product_price ? 'red' : '' }}
                                            autoComplete="product_price"
                                            onChange={(e) => setData('product_price', e.target.value)}
                                            required
                                        />

                                        <InputError message={errors.product_price} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="description" value="Mô tả" />
                                        <CkeditorTextarea
                                            id="description"
                                            name="description"
                                            content={data.description}
                                            className="mt-1 block w-full"
                                            style={{ borderColor: errors.description ? 'red' : '' }}
                                            autoComplete="description"
                                            setData={setData}
                                            required
                                        />

                                        <InputError message={errors.description} className="mt-2" />
                                    </div>


                                    <div className="mt-4">
                                        <InputLabel htmlFor="is_sales" value="Trạng thái" />

                                        <select
                                            id="is_sales"
                                            name="is_sales"
                                            value={data.is_sales}
                                            className="mt-1 block w-full"
                                            style={{ borderColor: errors.is_sales ? 'red' : '' }}
                                            autoComplete="is_sales"
                                            onChange={(e) => setData('is_sales', e.target.value)}
                                            required
                                        >
                                            <option value="1">Đang bán</option>
                                            <option value="0">Ngừng bán</option>
                                        </select>

                                        <InputError message={errors.is_sales} className="mt-2" />
                                    </div>
                                </div>
                                <div className="p-4">
                                    <div className="mt-4">
                                        <InputLabel htmlFor="product_image" className="mb-3" value="Hình ảnh" />
                                        {previewImage && <img className="thumb_temp" src={previewImage} alt="Preview Image" />}

                                        <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3 " onClick={(e) => {
                                            e.preventDefault();
                                            fileInputRef.current.click()
                                        }}>Upload</button>

                                        <button className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-3 mt-3 " onClick={(e) => {
                                            e.preventDefault();
                                            unSelectFile()
                                         }}>Xóa file</button>

                                        <span> { fileName } </span>

                                        <TextInput
                                            id="product_image"
                                            ref={fileInputRef}
                                            type="file"
                                            name="product_image"
                                            value={data.product_image}
                                            className="mt-1 block w-full hidden"
                                            autoComplete="product_image"
                                            onChange={(e) => {
                                                setData('product_image', e.target.value);
                                                handleImageChange(e)
                                            }}
                                        />

                                        <InputError message={errors.product_image} className="mt-2" />
                                    </div>
                                </div>
                            </div>
                            <div className="flex items-center justify-end mt-4">
                                <SecondaryButton onClick={(e)=>{  window.location.replace('/products'); }}>Hủy</SecondaryButton>

                                <PrimaryButton className="ml-4 bg-red-500" disabled={processing}>
                                    Lưu
                                </PrimaryButton>
                            </div>
                        </form>
                    </span>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
