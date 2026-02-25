import { useToast } from 'vue-toast-notification';

export function useAlert() {
  const toast = useToast();

  const options = {
    position: 'top-right',
    duration: 3000,
  };

  const error = (message) => {
    toast.error(message, options);
  };

  const warning = (message) => {
    toast.warning(message, options);
  };

  const success = (message) => {
    toast.success(message, options);
  };

  const info = (message) => {
    toast.info(message, options);
  };

  return { error, success, warning, info };
}