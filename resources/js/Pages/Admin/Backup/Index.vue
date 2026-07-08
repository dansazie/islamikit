<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6 max-w-5xl">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('ui.breadcrumb.admin') },
                { label: t('backup.breadcrumb.title') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-chart-3/5 blur-2xl" />
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                        </div>
                        <div class="min-w-0">
                            <h1 class="app-title !text-lg sm:!text-xl">{{ t('backup.title') }}</h1>
                            <p class="app-subtitle !text-xs sm:!text-sm">{{ t('backup.subtitle') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <SmartTooltip :text="t('backup.tooltip.create')" position="bottom">
                            <button
                                @click="confirmCreate"
                                :disabled="isCreating"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm disabled:opacity-50 whitespace-nowrap"
                            >
                                <svg v-if="isCreating" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span class="hidden sm:inline">{{ isCreating ? t('backup.creating') : t('backup.create_button') }}</span>
                            </button>
                        </SmartTooltip>
                    </div>
                </div>
            </div>

            <SmartAlert variant="warning" :title="t('backup.alert.title')" :description="t('backup.alert.description')" dismissible class="!mb-0" />

            <div class="app-card-container">
                <SmartEmpty
                    v-if="backups.length === 0"
                    variant="muted"
                    :title="t('backup.empty.title')"
                    :description="t('backup.empty.description')"
                >
                    <template #icon>
                        <svg class="w-12 h-12 text-muted-foreground/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" /></svg>
                    </template>
                </SmartEmpty>

                <div v-else class="space-y-2">
                    <div
                        v-for="backup in backups"
                        :key="backup.file_name"
                        class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 border border-border rounded-xl bg-background hover:bg-muted/20 transition-colors group"
                    >
                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-lg bg-chart-3/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-chart-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-foreground truncate">{{ backup.file_name }}</p>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span class="text-[11px] text-muted-foreground">{{ backup.size_human }}</span>
                                <span class="text-[11px] text-muted-foreground">{{ backup.last_modified_human }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-1 shrink-0 opacity-60 group-hover:opacity-100 transition-opacity">
                            <SmartTooltip :text="t('ui.actions.download')" position="top">
                                <a
                                    :href="`/admin/backups/${backup.file_name}/download`"
                                    class="p-2 rounded-lg text-muted-foreground hover:text-primary hover:bg-primary/10 transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                </a>
                            </SmartTooltip>
                            <SmartTooltip :text="t('ui.actions.delete')" position="top">
                                <button
                                    @click="confirmDelete(backup)"
                                    class="p-2 rounded-lg text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </SmartTooltip>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import SmartAlert from '@/Components/smart/SmartAlert.vue';
import SmartTooltip from '@/Components/smart/SmartTooltip.vue';
import SmartEmpty from '@/Components/smart/SmartEmpty.vue';
import { useI18n } from '@/Composables/useI18n';
import { useConfirm } from '@/Composables/useConfirm';

const { t } = useI18n();
const { open: openConfirm } = useConfirm();

const props = defineProps({
    backups: { type: Array, default: () => [] },
});

const isCreating = ref(false);

const confirmCreate = async () => {
    const confirmed = await openConfirm({
        title: t('backup.confirm_create.title'),
        message: t('backup.confirm_create.message'),
        confirmText: t('backup.confirm_create.button'),
        variant: 'warning',
    });

    if (confirmed) {
        isCreating.value = true;
        router.post('/admin/backups', {}, {
            onFinish: () => {
                isCreating.value = false;
            },
        });
    }
};

const confirmDelete = async (backup) => {
    const confirmed = await openConfirm({
        title: t('backup.confirm_delete.title'),
        message: t('backup.confirm_delete.message', { file: backup.file_name }),
        confirmText: t('backup.confirm_delete.button'),
        variant: 'destructive',
    });

    if (confirmed) {
        router.delete(`/admin/backups/${backup.file_name}`);
    }
};
</script>