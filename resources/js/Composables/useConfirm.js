import { reactive } from 'vue';

const state = reactive({
    show: false,
    title: '',
    message: '',
    confirmText: '',
    variant: 'destructive',
    loading: false,
    resolvePromise: null,
});

export function useConfirm() {
    const open = (options = {}) => {
        state.title = options.title || '';
        state.message = options.message || '';
        state.confirmText = options.confirmText || '';
        state.variant = options.variant || 'destructive';
        state.loading = false;
        state.show = true;

        return new Promise((resolve) => {
            state.resolvePromise = resolve;
        });
    };

    /**
     * Dipanggil oleh tombol "Ya" di ConfirmDialog.
     * TIDAK menutup dialog — biarkan caller yang menutup
     * setelah operasi async selesai.
     */
    const confirm = () => {
        if (state.resolvePromise) {
            state.resolvePromise(true);
        }
    };

    /**
     * Dipanggil oleh tombol "Batal" atau klik backdrop.
     * Langsung menutup dialog dan resolve false.
     */
    const close = () => {
        state.show = false;
        state.loading = false;
        if (state.resolvePromise) {
            state.resolvePromise(false);
            state.resolvePromise = null;
        }
    };

    const setLoading = (status) => {
        state.loading = status;
    };

    /**
     * Wajib dipanggil oleh caller setelah operasi BERHASIL.
     * Ini yang menutup dialog.
     */
    const closeAfterSuccess = () => {
        state.show = false;
        state.loading = false;
        state.resolvePromise = null;
    };

    return { state, open, confirm, close, closeAfterSuccess, setLoading };
}