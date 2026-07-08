<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('ui.breadcrumb.admin') },
                { label: t('activity_log.breadcrumb.title') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-chart-4/5 blur-2xl" />
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="min-w-0">
                            <h1 class="app-title !text-lg sm:!text-xl">{{ t('activity_log.title') }}</h1>
                            <p class="app-subtitle !text-xs sm:!text-sm">{{ t('activity_log.subtitle') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <SmartSearch v-model="searchQuery" @search="handleSearch" :placeholder="t('activity_log.search_placeholder')" class="sm:w-64" />
                            <SmartSelect v-model="subjectFilter" :options="filterOptions" @change="handleFilter" :placeholder="t('activity_log.filter_placeholder')" size="sm" class="sm:w-40" />
                        </div>
                        
                        <div class="flex sm:hidden items-center gap-2 w-full">
                            <SmartDropdown ref="mobileMenuRef" align="right">
                                <template #trigger="{ isOpen }">
                                    <button class="p-2.5 rounded-xl border border-border hover:bg-muted transition-colors cursor-pointer shrink-0">
                                        <svg class="w-5 h-5 text-muted-foreground transition-transform duration-200" :class="{ 'rotate-90': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                </template>
                                <div class="p-2 border-b border-border">
                                    <input v-model="searchQuery" @keyup.enter="handleMobileSearch" type="text" :placeholder="t('activity_log.search_placeholder')" class="w-full px-2 py-1.5 text-sm bg-transparent border border-border rounded-md outline-none focus:ring-1 focus:ring-ring" />
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
                <SmartTable :headers="tableHeaders" :rows="logsList" primary-column="description" secondary-column="causer" :empty-text="t('activity_log.table.empty')">
                    <template #cell-subject_type="{ value }">
                        <SmartBadge v-if="value" variant="neutral" size="xs" pill>{{ value }}</SmartBadge>
                        <span v-else class="text-xs text-muted-foreground">-</span>
                    </template>
                    <template #cell-created_at="{ value }">
                        <span class="text-xs text-muted-foreground whitespace-nowrap">{{ value }}</span>
                    </template>
                    <template #cell-actions="{ row }">
                        <SmartTooltip :text="t('activity_log.tooltip.details')" position="top">
                            <button 
                                @click="openDetails(row)" 
                                :disabled="!row.properties || row.properties.length === 0"
                                class="p-1.5 rounded-md text-muted-foreground hover:text-primary hover:bg-primary/10 transition-colors cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent disabled:hover:text-muted-foreground"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                        </SmartTooltip>
                    </template>
                </SmartTable>
                <div v-if="totalLogs > perPage" class="mt-4 flex justify-end">
                    <SmartPagination v-model="currentPage" :total-items="totalLogs" :per-page="perPage" />
                </div>
            </div>
        </div>

        <SmartDrawer v-model="isDrawerOpen" :title="t('activity_log.drawer.title')">
            <div v-if="selectedLog" class="space-y-4">
                <div class="p-3 bg-muted/30 rounded-xl border border-border space-y-1.5">
                    <p class="text-xs text-muted-foreground"><span class="font-bold text-foreground">{{ t('activity_log.drawer.action') }}:</span> {{ selectedLog.description }}</p>
                    <p class="text-xs text-muted-foreground"><span class="font-bold text-foreground">{{ t('activity_log.drawer.by') }}:</span> {{ selectedLog.causer }}</p>
                    <p class="text-xs text-muted-foreground"><span class="font-bold text-foreground">{{ t('activity_log.drawer.time') }}:</span> {{ selectedLog.created_at }}</p>
                </div>

                <div v-if="selectedLog.properties && selectedLog.properties.length > 0" class="border border-border rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="px-3 py-2 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ t('activity_log.drawer.field') }}</th>
                                <th class="px-3 py-2 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ t('activity_log.drawer.old') }}</th>
                                <th class="px-3 py-2 text-left text-[10px] font-bold text-muted-foreground uppercase tracking-wider">{{ t('activity_log.drawer.new') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="(prop, index) in selectedLog.properties" :key="index">
                                <td class="px-3 py-2 font-mono text-xs font-semibold text-foreground whitespace-nowrap">{{ prop.field }}</td>
                                <td class="px-3 py-2 text-xs text-destructive break-all max-w-[120px]">{{ prop.old ?? '-' }}</td>
                                <td class="px-3 py-2 text-xs text-primary break-all max-w-[120px]">{{ prop.new ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <SmartEmpty v-else variant="muted" :title="t('activity_log.drawer.no_changes')" size="sm" />
            </div>
        </SmartDrawer>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import SmartTable from '@/Components/smart/SmartTable.vue';
import SmartBadge from '@/Components/smart/SmartBadge.vue';
import SmartSearch from '@/Components/smart/SmartSearch.vue';
import SmartSelect from '@/Components/smart/SmartSelect.vue';
import SmartPagination from '@/Components/smart/SmartPagination.vue';
import SmartTooltip from '@/Components/smart/SmartTooltip.vue';
import SmartDropdown from '@/Components/smart/SmartDropdown.vue';
import SmartDrawer from '@/Components/smart/SmartDrawer.vue';
import SmartEmpty from '@/Components/smart/SmartEmpty.vue';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    logsList: { type: Array, default: () => [] },
    subjectTypes: { type: Array, default: () => [] },
    urls: { type: Object, default: () => ({}) },
    totalLogs: { type: Number, default: 0 },
    perPage: { type: Number, default: 10 },
});

// ← DIPERBAIKI: Pakai t() untuk semua label
const tableHeaders = computed(() => [
    { key: 'description', label: t('activity_log.table.description') },
    { key: 'subject_type', label: t('activity_log.table.type') },
    { key: 'causer', label: t('activity_log.table.causer') },
    { key: 'created_at', label: t('activity_log.table.time') },
    { key: 'actions', label: '', align: 'right', hideOnMobile: true },
]);

const searchQuery = ref('');
const subjectFilter = ref(null);
const currentPage = ref(1);
const mobileMenuRef = ref(null);
const isDrawerOpen = ref(false);
const selectedLog = ref(null);

const filterOptions = computed(() => [
    { label: t('activity_log.filter_all'), value: null },
    ...props.subjectTypes
]);

const handleSearch = (val) => {
    router.get(props.urls.index, { search: val, subject_type: subjectFilter.value, page: 1 }, { preserveState: true, preserveScroll: true });
};

const handleFilter = () => {
    router.get(props.urls.index, { search: searchQuery.value, subject_type: subjectFilter.value, page: 1 }, { preserveState: true, preserveScroll: true });
};

const handleMobileSearch = () => {
    mobileMenuRef.value?.close();
    handleSearch(searchQuery.value);
};

const openDetails = (log) => {
    selectedLog.value = log;
    isDrawerOpen.value = true;
};
</script>