<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6 max-w-4xl">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('ui.breadcrumb.admin') },
                { label: t('settings.breadcrumb.title') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-chart-5/5 blur-2xl" />
                <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                    <div class="app-icon-wrapper">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="app-title !text-lg sm:!text-xl">{{ t('settings.title') }}</h1>
                        <p class="app-subtitle !text-xs sm:!text-sm">{{ t('settings.subtitle') }}</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitForm" class="space-y-4 sm:space-y-6">
                <div class="app-card-container">
                    <div class="app-card-header">
                        <div>
                            <h2 class="app-title">{{ t('settings.general.title') }}</h2>
                            <p class="app-subtitle">{{ t('settings.general.desc') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <InputField v-model="form.app_name" :label="t('settings.general.app_name_label')" :placeholder="t('settings.general.app_name_placeholder')" :error="form.errors.app_name" />
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ t('settings.general.app_desc_label') }}</label>
                            <SmartTextarea v-model="form.app_description" :placeholder="t('settings.general.app_desc_placeholder')" :max-length="500" />
                            <p v-if="form.errors.app_description" class="text-xs text-destructive font-medium mt-1.5">{{ form.errors.app_description }}</p>
                        </div>
                        <SmartSelect v-model="form.date_format" :options="dateOptions" :label="t('settings.general.date_format_label')" :error="form.errors.date_format" />
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer shadow-sm">
                        <svg v-if="!form.processing" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        {{ form.processing ? t('ui.actions.processing') : t('ui.actions.save_data') }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputField from '@/Components/ui/InputField.vue';
import SmartTextarea from '@/Components/smart/SmartTextarea.vue';
import SmartSelect from '@/Components/smart/SmartSelect.vue';
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
    urls: { type: Object, default: () => ({}) },
});

const dateOptions = [
    { label: 'DD/MM/YYYY (01/12/2023)', value: 'd/m/Y' },
    { label: 'MM/DD/YYYY (12/01/2023)', value: 'm/d/Y' },
    { label: 'YYYY-MM-DD (2023-12-01)', value: 'Y-m-d' },
];

const form = useForm({
    app_name: props.settings.app_name || '',
    app_description: props.settings.app_description || '',
    date_format: props.settings.date_format || 'd/m/Y',
});

const submitForm = () => {
    form.put(props.urls.update, {
        preserveScroll: true,
    });
};
</script>