import { toast } from 'react-toastify';

export const notify = (mgs = '', type = 'default') => {
    switch(type) {
        case 'error':
            toast.error(mgs);
            break;
        case 'warn':
            toast.warn(mgs);
            break;
        case 'success':
            toast.success(mgs);
            break;
        default:
            toast(mgs);
            break;
      }
}
