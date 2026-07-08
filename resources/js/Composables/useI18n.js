import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useI18n() {
    const page = usePage();

    // ✅ TAMBAHKAN INI
    const locale = computed(() => page.props.locale || 'en');
    const availableLocales = computed(() => page.props.availableLocales || { en: 'English' });

    function t(key, replace = {}) {
        const translations = page.props.translations || {};
        let value = translations[key] || key;

        Object.keys(replace).forEach(param => {
            value = value.replace(`:${param}`, replace[param]);
        });

        return value;
    }

    const __ = t;

    // ✅ RETURN SEMUANYA
    return { t, __, locale, availableLocales };
}