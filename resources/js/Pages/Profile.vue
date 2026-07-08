<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('profile.breadcrumb.profile') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-primary/5 blur-2xl" />
                <div class="absolute -left-10 -bottom-10 w-32 h-32 rounded-full bg-secondary/5 blur-2xl" />
                
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 relative z-10">
                    <div class="relative group shrink-0">
                        <SmartAvatar 
                            :src="avatarPreview || $page.props?.auth?.user?.avatar" 
                            :name="form.name" 
                            size="xl" 
                            bordered 
                        />
                        <label 
                            class="absolute inset-0 rounded-full bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                            :class="{ 'opacity-100': avatarPreview !== null }"
                        >
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                            </svg>
                            <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp" @change="handleAvatarChange" />
                        </label>
                    </div>

                    <div class="text-center sm:text-left flex-1 min-w-0">
                        <h1 class="app-title !text-lg sm:!text-xl">{{ form.name || $page.props?.auth?.user?.name }}</h1>
                        <p class="app-subtitle !text-xs sm:!text-sm">{{ form.email || $page.props?.auth?.user?.email }}</p>
                        <p v-if="$page.props?.auth?.user?.phone" class="text-xs text-muted-foreground mt-0.5">
                            {{ $page.props?.auth?.user?.phone }}
                        </p>
                        <div class="flex flex-wrap justify-center sm:justify-start gap-1.5 mt-2">
                            <SmartBadge v-for="role in $page.props?.auth?.user?.roles" :key="role" variant="primary" size="xs">
                                {{ role }}
                            </SmartBadge>
                        </div>
                    </div>

                    <div class="sm:ml-auto shrink-0 hidden sm:block">
                        <div v-if="avatarPreview" class="flex items-center gap-2 px-3 py-1.5 bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 rounded-lg text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            {{ t('profile.avatar.not_saved') }}
                        </div>
                    </div>
                </div>
            </div>

            <SmartTabs v-model="activeTab" :items="tabs">
                <template #profil>
                    <div class="app-card-container">
                        <div class="app-card-header">
                            <div class="flex items-center gap-3">
                                <div class="app-icon-wrapper">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="app-title">{{ t('profile.info.title') }}</h2>
                                    <p class="app-subtitle">{{ t('profile.info.subtitle') }}</p>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="updateProfile" class="space-y-4 sm:space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                <InputField v-model="form.name" :label="t('profile.info.name_label')" :placeholder="t('profile.info.name_placeholder')" :error="form.errors.name" required />
                                <InputField v-model="form.email" type="email" :label="t('profile.info.email_label')" :placeholder="t('profile.info.email_placeholder')" :error="form.errors.email" required />
                            </div>

                            <InputField v-model="form.phone" type="tel" :label="t('profile.info.phone_label')" :placeholder="t('profile.info.phone_placeholder')" :error="form.errors.phone" />

                            <div v-if="avatarPreview" class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground">
                                    {{ t('profile.info.new_avatar_label') }}
                                </label>
                                <div class="flex items-center gap-3 p-3 bg-muted/30 rounded-xl border border-border">
                                    <img :src="avatarPreview" class="w-12 h-12 rounded-lg object-cover" alt="Preview" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-foreground truncate">{{ avatarFile?.name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ t('profile.info.resize_note') }}</p>
                                    </div>
                                    <SmartTooltip :text="t('ui.actions.delete')" position="top">
                                        <button type="button" @click="removeAvatarPreview" class="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </SmartTooltip>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2 border-t border-border">
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer shadow-sm">
                                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    {{ t('ui.actions.save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

                <template #password>
                    <div class="app-card-container">
                        <div class="app-card-header">
                            <div class="flex items-center gap-3">
                                <div class="app-icon-wrapper">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="app-title">{{ t('profile.password.title') }}</h2>
                                    <p class="app-subtitle">{{ t('profile.password.subtitle') }}</p>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="updatePassword" class="space-y-4 sm:space-y-5">
                            <InputField v-model="passwordForm.current_password" type="password" :label="t('profile.password.current_label')" :placeholder="t('profile.password.current_placeholder')" :error="passwordForm.errors.current_password" required />
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                <InputField v-model="passwordForm.password" type="password" :label="t('profile.password.new_label')" :placeholder="t('profile.password.new_placeholder')" :error="passwordForm.errors.password" required />
                                <InputField v-model="passwordForm.password_confirmation" type="password" :label="t('profile.password.confirm_label')" :placeholder="t('profile.password.confirm_placeholder')" :error="passwordForm.errors.password_confirmation" required />
                            </div>

                            <SmartAlert variant="muted" :description="t('profile.password.requirement_note')" :bordered="false" />

                            <div class="flex justify-end pt-2 border-t border-border">
                                <button type="submit" :disabled="passwordForm.processing" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer shadow-sm">
                                    <svg v-if="passwordForm.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    {{ t('profile.password.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

                <template #bahaya>
                    <div class="app-card-container !border-destructive/30">
                        <div class="app-card-header !border-b-destructive/20">
                            <div class="flex items-center gap-3">
                                <div class="app-icon-wrapper !bg-destructive/10 !text-destructive">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="app-title !text-destructive">{{ t('profile.danger.title') }}</h2>
                                    <p class="app-subtitle">{{ t('profile.danger.subtitle') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <SmartAlert variant="destructive" :title="t('profile.danger.warning_title')" :description="t('profile.danger.warning_desc')" :bordered="false" />

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 p-4 bg-destructive/5 rounded-xl border border-destructive/20">
                                <div>
                                    <p class="text-sm font-semibold text-foreground">{{ t('profile.danger.delete_button_label') }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">{{ t('profile.danger.delete_button_desc') }}</p>
                                </div>
                                <button @click="triggerDeleteAccount" :disabled="isDeleting" class="inline-flex items-center gap-2 px-4 py-2.5 bg-destructive text-destructive-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shrink-0 disabled:opacity-50">
                                    <svg v-if="isDeleting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    {{ isDeleting ? t('ui.actions.processing') : t('profile.danger.delete_button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </SmartTabs>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
// ← DIHAPUS: import ConfirmDialog (sudah global di AuthenticatedLayout)
import InputField from '@/Components/ui/InputField.vue';
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import SmartAvatar from '@/Components/smart/SmartAvatar.vue';
import SmartBadge from '@/Components/smart/SmartBadge.vue';
import SmartAlert from '@/Components/smart/SmartAlert.vue';
import SmartTabs from '@/Components/smart/SmartTabs.vue';
import SmartTooltip from '@/Components/smart/SmartTooltip.vue';
import { useI18n } from '@/Composables/useI18n';
import { useConfirm } from '@/Composables/useConfirm';

const { open: openConfirm, setLoading: setConfirmLoading, closeAfterSuccess } = useConfirm();
const { t } = useI18n();

const activeTab = ref('profil');
const avatarPreview = ref(null);
const avatarFile = ref(null);
const isDeleting = ref(false); // ← DITAMBAHKAN

// ← DIPERBAIKI: Tab labels pakai t()
const tabs = computed(() => [
    { key: 'profil', label: t('profile.tabs.profile') },
    { key: 'password', label: t('profile.tabs.password') },
    { key: 'bahaya', label: t('profile.tabs.danger') },
]);

const { props } = usePage();
const currentUser = props.auth?.user || {};

const form = useForm({
    name: currentUser.name || '',
    email: currentUser.email || '',
    phone: currentUser.phone || '',
    avatar: null,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const handleAvatarChange = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        form.setError('avatar', t('profile.avatar.error_size'));
        return;
    }

    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        form.setError('avatar', t('profile.avatar.error_type'));
        return;
    }

    form.clearErrors('avatar');
    avatarFile.value = file;
    form.avatar = file;

    const reader = new FileReader();
    reader.onload = (e) => { avatarPreview.value = e.target.result; };
    reader.readAsDataURL(file);
};

const removeAvatarPreview = () => {
    avatarPreview.value = null;
    avatarFile.value = null;
    form.avatar = null;
    form.clearErrors('avatar');
};

const updateProfile = () => {
    const data = new FormData();
    data.append('name', form.name);
    data.append('email', form.email);
    data.append('phone', form.phone);

    if (form.avatar instanceof File) {
        data.append('avatar', form.avatar);
    }

    router.put('/profile', data, {
        preserveScroll: true,
        onSuccess: () => {
            avatarPreview.value = null;
            avatarFile.value = null;
            form.avatar = null;
        },
        // ← DIHAPUS: onError manual setError — Inertia sudah otomatis
    });
};

const updatePassword = () => {
    router.put('/profile/password', {
        current_password: passwordForm.current_password,
        password: passwordForm.password,
        password_confirmation: passwordForm.password_confirmation,
    }, {
        preserveScroll: true,
        onSuccess: () => { passwordForm.reset(); },
        // ← DIHAPUS: onError manual
    });
};

// ← DIPERBAIKI: Hapus variabel yang tidak ada
const triggerDeleteAccount = async () => {
    const isConfirmed = await openConfirm({
        title: t('profile.danger.confirm_title'),
        message: t('profile.danger.confirm_message'),
        confirmText: t('profile.danger.confirm_button'),
        variant: 'destructive',
    });

    if (isConfirmed) {
        isDeleting.value = true;
        setConfirmLoading(true);

        router.delete('/profile', {
            preserveScroll: false,
            preserveState: false,
            onFinish: () => {
                isDeleting.value = false;
            },
        });
    }
};
</script>