import Modal from '@/Components/Modal';
import { useForm } from '@inertiajs/react';

export default function ImageProductModal({  className = '', showModalImage, setShowModalImage, currProductImage = ''}) {

    const closeModal = () => {
        setShowModalImage(false);
    };

    return (
        <span className={`space-y-6 ${className}`}>
            <Modal show={showModalImage} onClose={closeModal}>
                {currProductImage && <img className="product_image" src={currProductImage} alt="Product" />}
            </Modal>
        </span>
    );
}
