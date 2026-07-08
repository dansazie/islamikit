import { usePage } from '@inertiajs/vue3';

export function usePermission() {
    const page = usePage();

    // Fungsi untuk cek apakah user punya permission tertentu
    const can = (permission) => {
        const permissions = page.props.auth.user?.permissions || [];
        return permissions.includes(permission);
    };

    // Fungsi untuk cek apakah user punya role tertentu
    const hasRole = (role) => {
        const roles = page.props.auth.user?.roles || [];
        return roles.includes(role);
    };

    return { can, hasRole };
}