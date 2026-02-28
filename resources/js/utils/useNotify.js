import { showNotify, closeNotify } from 'vant';

export function useNotify() {
  
  const baseConfig = (message, type, background, duration) => ({
    type,
    message,
    background,
    duration,
    className: 'custom-notify cursor-pointer', // Tambah cursor-pointer agar user tahu bisa diklik
    onClick: () => closeNotify(), // Klik untuk menutup
  });

  const success = (message = 'Berhasil disimpan') => {
    showNotify(baseConfig(message, 'success', '#0d9488', 2000));
  };

  const error = (message = 'Terjadi kesalahan') => {
    showNotify(baseConfig(message, 'danger', '#e11d48', 3000));
  };

  const warn = (message) => {
    showNotify(baseConfig(message, 'warning', '#f59e0b', 2500));
  };

  const info = (message) => {
    showNotify(baseConfig(message, 'primary', '#2563eb', 2500));
  };

  return { info, success, error, warn };
}