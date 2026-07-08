<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('ui.breadcrumb.admin') },
                { label: t('users.breadcrumb.title') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-primary/5 blur-2xl" />
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <h1 class="app-title !text-lg sm:!text-xl">{{ t('users.title') }}</h1>
                            <p class="app-subtitle !text-xs sm:!text-sm">{{ t('users.subtitle') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <SmartSearch v-model="searchQuery" @search="handleSearch" :placeholder="t('users.search_placeholder')" shortcut="Ctrl+K" class="sm:w-64" />
                            <SmartTooltip :text="t('users.tooltip.add')" position="bottom">
                                <button @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    {{ t('users.add_button') }}
                                </button>
                            </SmartTooltip>
                        </div>

                        <div class="flex sm:hidden items-center gap-2 w-full">
                            <button @click="openCreateModal" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                {{ t('users.add_button') }}
                            </button>
                            <SmartDropdown ref="mobileMenuRef" align="right">
                                <template #trigger="{ isOpen }">
                                    <button class="p-2.5 rounded-xl border border-border hover:bg-muted transition-colors cursor-pointer shrink-0">
                                        <svg class="w-5 h-5 text-muted-foreground transition-transform duration-200" :class="{ 'rotate-90': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                </template>
                                <div class="p-2 border-b border-border">
                                    <input v-model="mobileSearchQuery" @keyup.enter="handleMobileSearch" type="text" :placeholder="t('users.search_placeholder')" class="w-full px-2 py-1.5 text-sm bg-transparent border border-border rounded-md outline-none focus:ring-1 focus:ring-ring" />
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
                <SmartTable :headers="tableHeaders" :rows="usersList" primary-column="name" secondary-column="email" :empty-text="t('users.table.empty')">
                    <template #cell-roles="{ row }">
                        <div class="flex flex-wrap gap-1 sm:gap-1.5">
                            <SmartBadge v-for="role in row.roles" :key="role" variant="tertiary" size="xs" :pill="false">{{ role }}</SmartBadge>
                        </div>
                    </template>
                    <template #cell-created_at="{ value }">
                        <span class="text-xs text-muted-foreground whitespace-nowrap">{{ value }}</span>
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
                            <!-- PERBAIKAN: canImpersonate(row) bukan canImpersonate(user) -->
                            <SmartTooltip v-if="canImpersonate(row)" :text="t('ui.impersonate.start_tooltip')" position="top">
                                <button
                                    @click.stop="startImpersonate(row)"
                                    class="p-1.5 rounded-md text-muted-foreground hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-500/10 transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </button>
                            </SmartTooltip>
                        </div>
                    </template>
                </SmartTable>
                <div v-if="totalUsers > perPage" class="mt-4 flex justify-end">
                    <SmartPagination v-model="currentPage" :total-items="totalUsers" :per-page="perPage" />
                </div>
            </div>
        </div>

        <Modal :show="isModalOpen" @close="isModalOpen = false">
            <template #title>{{ form.id ? t('users.modal.edit_title') : t('users.modal.create_title') }}</template>
            <template #body>
                <form @submit.prevent="submitForm" class="space-y-4">
                    <InputField v-model="form.name" :label="t('users.modal.name_label')" :placeholder="t('users.modal.name_placeholder')" :error="form.errors.name" />
                    <InputField v-model="form.email" type="email" :label="t('users.modal.email_label')" :placeholder="t('users.modal.email_placeholder')" :error="form.errors.email" />
                    <InputField v-model="form.password" type="password" :label="t('users.modal.password_label')" :placeholder="form.id ? t('users.modal.password_placeholder_edit') : t('users.modal.password_placeholder_create')" :error="form.errors.password" />
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ t('users.modal.role_label') }}</label>
                        <div class="grid grid-cols-2 gap-2 p-3 border border-border bg-background/50 rounded-xl max-h-32 overflow-y-auto custom-scrollbar">
                            <label v-for="role in rolesList" :key="role" class="flex items-center gap-2.5 text-xs font-semibold text-foreground cursor-pointer select-none group py-0.5">
                                <input type="checkbox" :value="role" v-model="form.roles" class="w-4 h-4 rounded border-border text-tertiary focus:ring-tertiary cursor-pointer" />
                                <span class="group-hover:text-tertiary transition-colors">{{ role }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.roles" class="text-xs text-destructive font-medium mt-1.5">{{ form.errors.roles }}</p>
                    </div>
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
                    <span v-else>{{ form.id ? t('ui.actions.save') : t('users.modal.create_button') }}</span>
                </button>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
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
const page = usePage(); // ← DITAMBAHKAN

const props = defineProps({
    usersList: { type: Array, default: () => [] },
    rolesList: { type: Array, default: () => [] },
    urls: { type: Object, default: () => ({}) },
    totalUsers: { type: Number, default: 0 },
    perPage: { type: Number, default: 10 },
});

// ← DIPERBAIKI: Pakai t()
const tableHeaders = computed(() => [
    { key: 'name', label: t('users.table.name') },
    { key: 'email', label: t('users.table.email') },
    { key: 'roles', label: t('users.table.roles') },
    { key: 'created_at', label: t('users.table.joined'), muted: true },
    { key: 'actions', label: '', align: 'right', hideOnMobile: false },
]);

const isModalOpen = ref(false);
const searchQuery = ref('');
const mobileSearchQuery = ref(''); // ← DITAMBAHKAN
const currentPage = ref(1);
const mobileMenuRef = ref(null);

const form = useForm({
    id: null,
    name: '',
    email: '',
    password: '',
    roles: [],
});

// ← DIPERBAIKI: Gunakan page yang sudah diimport
const currentUser = computed(() => page.props.auth.user);

const canImpersonate = (targetUser) => {
    if (!currentUser.value) return false;
    if (currentUser.value.roles.includes('super-admin')) {
        return targetUser.id !== currentUser.value.id;
    }
    return currentUser.value.permissions.includes('impersonate users') && targetUser.id !== currentUser.value.id;
};

const startImpersonate = (user) => {
    router.post(`/impersonate/${user.id}`, {}, {
        onSuccess: () => { /* redirect otomatis */ },
    });
};

const handleSearch = (val) => {
    router.get(props.urls.index, { search: val, page: 1 }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// ← DIPERBAIKI: Tidak pakai window.prompt
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

const openEditModal = (user) => {
    form.clearErrors();
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.roles = [...user.roles];
    isModalOpen.value = true;
};

const submitForm = () => {
    form.post(props.urls.store, {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        },
    });
};

const confirmDelete = async (user) => {
    const isConfirmed = await openConfirm({
        title: t('users.confirm_delete.title'),
        message: t('users.confirm_delete.message', { name: user.name }),
        confirmText: t('users.confirm_delete.button'),
        variant: 'destructive',
    });

    if (isConfirmed) {
        setConfirmLoading(true);
        router.delete(`/admin/users/${user.id}`, {
            onSuccess: () => closeAfterSuccess(), // ← DIPERBAIKI
            onError: () => setConfirmLoading(false),
        });
    }
};
</script>