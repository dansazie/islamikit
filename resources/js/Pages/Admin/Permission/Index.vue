<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('ui.breadcrumb.admin') },
                { label: t('permissions.breadcrumb.title') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-secondary/5 blur-2xl" />
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <h1 class="app-title !text-lg sm:!text-xl">{{ t('permissions.title') }}</h1>
                            <p class="app-subtitle !text-xs sm:!text-sm">{{ t('permissions.subtitle') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <SmartSearch v-model="searchQuery" @search="handleSearch" :placeholder="t('permissions.search_placeholder')" shortcut="Ctrl+K" class="sm:w-64" />
                            <SmartTooltip :text="t('permissions.tooltip.add')" position="bottom">
                                <button @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    {{ t('permissions.add_button') }}
                                </button>
                            </SmartTooltip>
                        </div>

                        <div class="flex sm:hidden items-center gap-2 w-full">
                            <button @click="openCreateModal" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                {{ t('permissions.add_button') }}
                            </button>
                            <SmartDropdown ref="mobileMenuRef" align="right">
                                <template #trigger="{ isOpen }">
                                    <button class="p-2.5 rounded-xl border border-border hover:bg-muted transition-colors cursor-pointer shrink-0">
                                        <svg class="w-5 h-5 text-muted-foreground transition-transform duration-200" :class="{ 'rotate-90': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                </template>
                                <div class="p-2 border-b border-border">
                                    <input v-model="mobileSearchQuery" @keyup.enter="handleMobileSearch" type="text" :placeholder="t('permissions.search_placeholder')" class="w-full px-2 py-1.5 text-sm bg-transparent border border-border rounded-md outline-none focus:ring-1 focus:ring-ring" />
                                </div>
                                <button @click="handleMobileSearch" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm text-primary hover:bg-muted cursor-pointer transition-colors">
                                    {{ t('ui.actions.search') }}
                                </button>
                            </SmartDropdown>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-card-container">
                <SmartTable :headers="tableHeaders" :rows="permissionsList" primary-column="name" secondary-column="group" :empty-text="t('permissions.table.empty')">
                    <template #cell-group="{ value }">
                        <SmartBadge variant="neutral" size="xs" pill>{{ value }}</SmartBadge>
                    </template>
                    <template #cell-actions="{ row }">
                        <div class="flex items-center gap-1">
                            <SmartTooltip :text="t('ui.actions.edit')" position="top">
                                <button @click="openEditModal(row)" class="p-1.5 rounded-md text-muted-foreground hover:text-secondary hover:bg-secondary/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </button>
                            </SmartTooltip>
                            <SmartTooltip :text="t('ui.actions.delete')" position="top">
                                <button @click="confirmDelete(row)" class="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </SmartTooltip>
                        </div>
                    </template>
                </SmartTable>
                <div v-if="totalPermissions > perPage" class="mt-4 flex justify-end">
                    <SmartPagination v-model="currentPage" :total-items="totalPermissions" :per-page="perPage" />
                </div>
            </div>
        </div>

        <Modal :show="isModalOpen" @close="isModalOpen = false">
            <template #title>{{ form.id ? t('permissions.modal.edit_title') : t('permissions.modal.create_title') }}</template>
            <template #body>
                <form @submit.prevent="submitForm" class="space-y-4">
                    <InputField v-model="form.name" :label="t('permissions.modal.name_label')" :placeholder="t('permissions.modal.name_placeholder')" :error="form.errors.name" />
                    <p class="text-xs text-muted-foreground leading-relaxed">{{ t('permissions.modal.name_hint') }}</p>
                </form>
            </template>
            <template #footer>
                <button @click="isModalOpen = false" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-medium text-foreground bg-muted rounded-lg hover:bg-muted/80 transition-colors cursor-pointer order-2 sm:order-1">
                    {{ t('ui.actions.cancel') }}
                </button>
                <button @click="submitForm" :disabled="form.processing" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-medium text-primary-foreground bg-tertiary rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer order-1 sm:order-2">
                    <span v-if="form.processing" class="inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        {{ t('ui.actions.processing') }}
                    </span>
                    <span v-else>{{ form.id ? t('ui.actions.save') : t('permissions.modal.create_button') }}</span>
                </button>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/ui/Modal.vue';
import InputField from '@/Components/ui/InputField.vue';
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import SmartTable from '@/Components/smart/SmartTable.vue';
import SmartBadge from '@/Components/smart/SmartBadge.vue';
import SmartSearch from '@/Components/smart/SmartSearch.vue';
import SmartPagination from '@/Components/smart/SmartPagination.vue';
import SmartTooltip from '@/Components/smart/SmartTooltip.vue';
import SmartDropdown from '@/Components/smart/SmartDropdown.vue';
import { useI18n } from '@/Composables/useI18n';
import { useConfirm } from '@/Composables/useConfirm';

const { open: openConfirm, setLoading: setConfirmLoading, closeAfterSuccess } = useConfirm();
const { t } = useI18n();

const props = defineProps({
    permissionsList: { type: Array, default: () => [] },
    urls: { type: Object, default: () => ({}) },
    totalPermissions: { type: Number, default: 0 },
    perPage: { type: Number, default: 10 },
});

const tableHeaders = computed(() => [
    { key: 'name', label: t('permissions.table.name') },
    { key: 'group', label: t('permissions.table.group') },
    { key: 'actions', label: '', align: 'right', hideOnMobile: false },
]);

const isModalOpen = ref(false);
const searchQuery = ref('');
const mobileSearchQuery = ref('');
const currentPage = ref(1);
const mobileMenuRef = ref(null);

const form = useForm({ id: null, name: '' });

const handleSearch = (val) => {
    router.get(props.urls.index, { search: val, page: 1 }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const handleMobileSearch = () => {
    mobileMenuRef.value?.close();
    if (mobileSearchQuery.value.trim()) {
        handleSearch(mobileSearchQuery.value.trim());
    }
};

const openCreateModal = () => {
    form.reset();
    form.clearErrors();
    form.id = null;
    isModalOpen.value = true;
};

const openEditModal = (permission) => {
    form.clearErrors();
    form.id = permission.id;
    form.name = permission.name;
    isModalOpen.value = true;
};

const submitForm = () => {
    const url = form.id ? props.urls.update + form.id : props.urls.store;
    const method = form.id ? 'put' : 'post';
    form[method](url, {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        },
    });
};

const confirmDelete = async (permission) => {
    const isConfirmed = await openConfirm({
        title: t('permissions.confirm_delete.title'),
        message: t('permissions.confirm_delete.message', { name: permission.name }),
        confirmText: t('permissions.confirm_delete.button'),
        variant: 'destructive',
    });

    if (isConfirmed) {
        setConfirmLoading(true);
        router.delete(props.urls.destroy + permission.id, {
            onSuccess: () => closeAfterSuccess(),
            onError: () => setConfirmLoading(false),
        });
    }
};
</script>