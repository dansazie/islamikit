<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('dashboard.breadcrumb.overview') }
            ]" />

            <SmartAlert variant="info" :title="t('dashboard.alert.maintenance_title')" :description="t('dashboard.alert.maintenance_desc')" dismissible />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-primary/5 blur-2xl" />
                
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <SmartAvatar :src="$page.props?.auth?.user?.avatar" :name="$page.props?.auth?.user?.name || ''" size="lg" status="online" bordered />
                        <div class="min-w-0">
                            <h1 class="app-title !text-lg sm:!text-xl">{{ t('dashboard.welcome', { name: ($page.props?.auth?.user?.name || '').split(' ')[0] }) }}</h1>
                            <p class="app-subtitle !text-xs sm:!text-sm">{{ t('dashboard.welcome_subtitle') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <SmartTooltip :text="t('dashboard.tooltip.open_filter')" position="bottom">
                                <button @click="isDrawerOpen = true" class="p-2.5 rounded-xl border border-border hover:bg-muted transition-colors cursor-pointer">
                                    <svg class="w-5 h-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                                </button>
                            </SmartTooltip>

                            <div class="flex items-center gap-0.5 p-1 bg-muted/50 rounded-lg border border-border">
                                <button @click="triggerToast('success')" class="px-2.5 py-1.5 text-[11px] font-bold text-green-600 dark:text-green-400 hover:bg-green-500/10 rounded-md cursor-pointer transition-colors">
                                    {{ t('dashboard.test_ok') }}
                                </button>
                                <button @click="triggerToast('error')" class="px-2.5 py-1.5 text-[11px] font-bold text-destructive hover:bg-destructive/10 rounded-md cursor-pointer transition-colors">
                                    {{ t('dashboard.test_err') }}
                                </button>
                            </div>
                            
                            <SmartTooltip :text="t('dashboard.tooltip.open_modal')" position="bottom">
                                <button @click="isModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                    {{ t('dashboard.demo_form') }}
                                </button>
                            </SmartTooltip>
                        </div>

                        <div class="flex sm:hidden items-center gap-2 w-full">
                            <button @click="isModalOpen = true" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                {{ t('dashboard.demo_form') }}
                            </button>

                            <SmartDropdown ref="mobileMenu" align="right">
                                <template #trigger="{ isOpen }">
                                    <button class="p-2.5 rounded-xl border border-border hover:bg-muted transition-colors cursor-pointer shrink-0">
                                        <svg class="w-5 h-5 text-muted-foreground transition-transform duration-200" :class="{ 'rotate-90': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                </template>
                                <button @click="handleMobileFilter" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm text-foreground hover:bg-muted cursor-pointer transition-colors">
                                    <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" /></svg>
                                    {{ t('dashboard.filter_advanced') }}
                                </button>
                                <div class="my-1 border-t border-border" />
                                <button @click="handleMobileToast('success')" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-green-600 dark:text-green-400 hover:bg-muted cursor-pointer transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ t('dashboard.test_toast_success') }}
                                </button>
                                <button @click="handleMobileToast('error')" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-destructive hover:bg-muted cursor-pointer transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                                    {{ t('dashboard.test_toast_error') }}
                                </button>
                            </SmartDropdown>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-grid-cards">
                <SmartGridStat :label="t('dashboard.stats.revenue')" :value="124500000" :trend="14.2" :sparkline="[20, 40, 35, 60, 45, 80, 70]" />
                <SmartGridStat :label="t('dashboard.stats.active_users')" :value="8540" :trend="-3.1" :sparkline="[80, 60, 75, 50, 65, 40, 90]" />
                <div class="app-card-container !gap-3 flex flex-col items-center justify-center">
                    <SmartChart :percentage="78" :label="t('dashboard.stats.target')" color="secondary" size="lg" />
                    <p class="text-[11px] font-semibold text-muted-foreground text-center">{{ t('dashboard.stats.achievement') }}</p>
                </div>
                <SmartStat :label="t('dashboard.stats.support_tickets')" :value="24" variant="tertiary" :trend="5.4" :description="t('dashboard.stats.unhandled')" />
                <SmartAvatarGroup :users="[
                    { name: 'Budi Santoso', avatar: null },
                    { name: 'Andi Pratama', avatar: null },
                    { name: 'Siti Rahayu', avatar: null },
                    { name: 'John Doe', avatar: null },
                    { name: 'Jane Doe', avatar: null }
                ]" :max="3" size="lg" />
            </div>

            <div class="app-grid-2">
                <div class="app-card-container">
                    <div class="app-card-header !pb-0 !border-b-0 !mb-4">
                        <h2 class="app-title">{{ t('dashboard.pipeline.title') }}</h2>
                        <SmartBadge variant="outline" size="xs">{{ t('dashboard.pipeline.today') }}</SmartBadge>
                    </div>
                    <SmartPipeline 
                        :steps="[
                            { key: 'order', label: t('dashboard.pipeline.order') },
                            { key: 'process', label: t('dashboard.pipeline.process') },
                            { key: 'ship', label: t('dashboard.pipeline.ship') },
                            { key: 'done', label: t('dashboard.pipeline.done') }
                        ]" current-step="ship" />
                    <div class="grid grid-cols-4 gap-2 mt-4 pt-4 border-t border-border">
                        <div class="text-center">
                            <p class="text-sm font-bold text-foreground">12</p>
                            <p class="text-[10px] text-muted-foreground">{{ t('dashboard.pipeline.order') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-foreground">5</p>
                            <p class="text-[10px] text-muted-foreground">{{ t('dashboard.pipeline.process') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-primary">8</p>
                            <p class="text-[10px] text-muted-foreground">{{ t('dashboard.pipeline.ship') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-bold text-foreground">45</p>
                            <p class="text-[10px] text-muted-foreground">{{ t('dashboard.pipeline.done') }}</p>
                        </div>
                    </div>
                </div>

                <div class="app-card-container">
                    <div class="app-card-header !pb-0 !border-b-0 !mb-4">
                        <h2 class="app-title">{{ t('dashboard.activity.title') }}</h2>
                        <button class="text-xs font-semibold text-primary hover:underline cursor-pointer">{{ t('dashboard.activity.see_all') }}</button>
                    </div>
                    <div class="divide-y divide-border max-h-[280px] overflow-y-auto custom-scrollbar">
                        <SmartFeed title="Budi Santoso" :message="t('dashboard.activity.upload_msg')" time="2 mnt lalu" :unread="true" :expandable="true">
                            <template #detail>
                                <p class="text-xs text-muted-foreground">{{ t('dashboard.activity.file_label') }} <span class="font-mono text-foreground">Laporan_Maret.pdf</span></p>
                            </template>
                            <template #action>
                                <SmartTooltip :text="t('ui.actions.delete')" position="top">
                                    <button class="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </SmartTooltip>
                            </template>
                        </SmartFeed>
                        <SmartFeed title="Sistem" :message="t('dashboard.activity.backup_msg')" time="1 jam lalu">
                            <template #avatar>
                                <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center text-sm font-bold shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                                </div>
                            </template>
                        </SmartFeed>
                        <SmartFeed title="Andi Pratama" :message="t('dashboard.activity.ticket_msg', { id: '1042' })" time="3 jam lalu" />
                    </div>
                </div>
            </div>

            <!-- SmartSkeleton Demo Section -->
            <div class="app-card-container">
                <div class="app-card-header">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z" /></svg>
                        </div>
                        <div>
                            <h2 class="app-title">{{ t('dashboard.skeleton.title') }}</h2>
                            <p class="app-subtitle">{{ t('dashboard.skeleton.subtitle') }}</p>
                        </div>
                    </div>
                    <SmartButton 
                        :variant="showSkeleton ? 'outline' : 'secondary'" 
                        size="sm" 
                        @click="showSkeleton = !showSkeleton"
                    >
                        {{ showSkeleton ? t('dashboard.skeleton.hide_button') : t('dashboard.skeleton.toggle_button') }}
                    </SmartButton>
                </div>
                
                <div v-if="showSkeleton" class="space-y-4">
                    <div class="flex items-center gap-4">
                        <SmartSkeleton variant="circle" size="lg" />
                        <div class="flex-1 space-y-2">
                            <SmartSkeleton variant="text" width="40%" />
                            <SmartSkeleton variant="text" width="60%" size="sm" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <SmartSkeleton variant="card" height="100px" />
                        <SmartSkeleton variant="card" height="100px" />
                        <SmartSkeleton variant="card" height="100px" />
                    </div>
                    <SmartSkeleton variant="table" :rows="3" :columns="4" />
                </div>
                <SmartEmpty 
                    v-else 
                    variant="muted" 
                    :title="t('dashboard.skeleton.empty_title')" 
                    :description="t('dashboard.skeleton.empty_desc')" 
                    size="sm" 
                />
            </div>

            <div class="app-card-container">
                <div class="app-card-header flex-col !items-start sm:!items-center gap-3">
                    <div class="flex items-center gap-3 min-w-0 w-full sm:w-auto">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0118 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0118 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 016 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 12 6 12.504 6 13.125" /></svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="app-title">{{ t('dashboard.table.title') }}</h2>
                            <p class="app-subtitle">{{ t('dashboard.table.subtitle') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                        <SmartSearch v-model="searchQuery" @search="handleSearch" :placeholder="t('dashboard.table.search_placeholder')" shortcut="Ctrl+K" class="sm:w-64" />
                        <SmartSelect v-model="statusFilter" :options="statusOptions" :placeholder="t('dashboard.table.status')" size="sm" class="sm:w-36" />
                    </div>
                </div>

                <SmartTabs v-model="activeTab" :items="tabs">
                    <template #semua>
                        <SmartTable :headers="tableHeaders" :rows="dummyOrders" primary-column="order_id" secondary-column="customer" :empty-text="t('dashboard.table.empty')">
                            <template #cell-status="{ value }">
                                <SmartBadge :variant="getVariantStatus(value)" size="xs" dot>{{ value }}</SmartBadge>
                            </template>
                            <template #cell-total="{ value }">
                                <span class="font-semibold tabular-nums">{{ formatRupiah(value) }}</span>
                            </template>
                            <template #cell-actions>
                                <div class="flex items-center gap-1.5">
                                    <SmartTooltip :text="t('ui.actions.detail')" position="top">
                                        <button class="p-1.5 rounded-md text-muted-foreground hover:bg-muted hover:text-foreground transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </button>
                                    </SmartTooltip>
                                </div>
                            </template>
                        </SmartTable>
                        <div class="mt-4 flex justify-end">
                            <SmartPagination v-model="currentPage" :total-items="150" :per-page="10" />
                        </div>
                    </template>
                    <template #selesai>
                        <SmartEmpty variant="muted" :title="t('dashboard.table.empty_done_title')" :description="t('dashboard.table.empty_done_desc')" size="sm" />
                    </template>
                </SmartTabs>
            </div>

            <div class="app-grid-2">
                <div class="app-card-container">
                    <div class="app-card-header !pb-0 !border-b-0 !mb-4">
                        <h2 class="app-title">{{ t('dashboard.server.title') }}</h2>
                        <SmartBadge variant="success" size="xs" dot>{{ t('dashboard.server.online') }}</SmartBadge>
                    </div>
                    <SmartProgress :percentage="75" label="CPU Usage" variant="warning" class="mb-3" />
                    <SmartProgress :percentage="45" label="RAM Usage" variant="primary" class="mb-4" />
                    <SmartTimeline :items="serverLogs" />
                </div>
                <div class="space-y-4 sm:space-y-6">
                    <div class="app-card-container">
                        <h2 class="app-title mb-4">{{ t('dashboard.settings.title') }}</h2>
                        <div class="space-y-4">
                            <SmartSwitch v-model="settings.notif" :label="t('dashboard.settings.notif_email')" :description="t('dashboard.settings.notif_email_desc')" />
                            <SmartSwitch v-model="settings.twoFa" :label="t('dashboard.settings.two_fa')" :description="t('dashboard.settings.two_fa_desc')" />
                        </div>
                    </div>
                    <div class="app-card-container">
                        <h2 class="app-title mb-4">{{ t('dashboard.api.title') }}</h2>
                        <SmartCodeBlock filename=".env" code="VITE_APP_KEY=base64:xR9kL2...qW8pM5=" />
                    </div>
                </div>
            </div>

            <!-- SmartAccordion FAQ Section -->
            <div class="app-card-container">
                <div class="flex items-center gap-2.5 sm:gap-3 mb-4">
                    <div class="app-icon-wrapper">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                    </div>
                    <div>
                        <h2 class="app-title">{{ t('dashboard.faq.title') }}</h2>
                        <p class="app-subtitle">{{ t('dashboard.faq.subtitle') }}</p>
                    </div>
                </div>
                <SmartAccordion :items="faqItems" />
            </div>

            <div class="app-card-container">
                <div class="flex items-center gap-2.5 sm:gap-3 mb-4">
                    <div class="app-icon-wrapper">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <div>
                        <h2 class="app-title">{{ t('dashboard.roles.title') }}</h2>
                        <p class="app-subtitle">{{ t('dashboard.roles.subtitle') }}</p>
                    </div>
                    <SmartButton variant="destructive" size="sm" @click="testDelete">
                        <template #icon><IconTrash2 /></template>
                        Test Hapus (Composable)
                    </SmartButton>
                </div>
                <div class="flex flex-wrap gap-2 mb-3">
                    <SmartBadge v-for="role in ($page.props?.auth?.user?.roles || [])" :key="role" variant="primary" size="md">{{ role }}</SmartBadge>
                </div>
                <div v-if="($page.props?.auth?.user?.permissions || []).length" class="p-3 bg-muted/30 rounded-xl border border-border">
                    <p class="text-[10px] sm:text-xs text-muted-foreground mb-2 font-bold uppercase tracking-wider">{{ t('dashboard.roles.permissions_label') }}</p>
                    <div class="flex flex-wrap gap-1.5">
                        <SmartBadge v-for="perm in ($page.props?.auth?.user?.permissions || [])" :key="perm" variant="neutral" size="xs" :pill="false">{{ perm }}</SmartBadge>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="isModalOpen" @close="isModalOpen = false">
            <template #title>{{ t('dashboard.modal.product_form_title') }}</template>
            <template #body>
                <form @submit.prevent="saveData" class="space-y-4">
                    <InputField v-model="form.productName" :label="t('dashboard.modal.product_name')" :placeholder="t('dashboard.modal.product_name_placeholder')" :error="form.errors.productName" />
                    <InputField v-model="form.price" type="number" :label="t('dashboard.modal.price_label')" :placeholder="t('dashboard.modal.price_placeholder')" :error="form.errors.price" />
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ t('dashboard.modal.desc_label') }}</label>
                        <SmartTextarea v-model="form.desc" :placeholder="t('dashboard.modal.desc_placeholder')" :max-length="150" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ t('dashboard.modal.photo_label') }}</label>
                        <SmartFileUpload v-model="form.photos" accept="image/*" :multiple="true" :max-size-mb="2" />
                    </div>
                </form>
            </template>
            <template #footer>
                <button @click="isModalOpen = false" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-medium text-foreground bg-muted rounded-lg hover:bg-muted/80 transition-colors cursor-pointer order-2 sm:order-1">
                    {{ t('ui.actions.cancel') }}
                </button>
                <button @click="saveData" :disabled="form.processing" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-medium text-primary-foreground bg-primary rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer order-1 sm:order-2">
                    {{ t('ui.actions.save_data') }}
                </button>
            </template>
        </Modal>

        <SmartDrawer v-model="isDrawerOpen" :title="t('dashboard.drawer.title')">
            <div class="space-y-5">
                <SmartAlert variant="muted" :description="t('dashboard.drawer.desc')" :bordered="false" />
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">{{ t('dashboard.drawer.date_range') }}</label>
                    <SmartDatePicker 
                        v-model="selectedDate" 
                        :range="true" 
                        :placeholder="t('dashboard.drawer.date_placeholder')"
                    />
                    <p v-if="selectedDate" class="mt-2 text-xs text-muted-foreground">
                        {{ t('dashboard.drawer.selected') }}: {{ Array.isArray(selectedDate) ? selectedDate.join(' — ') : selectedDate }}
                    </p>
                </div>
                <SmartSelect v-model="filters.payment" :options="paymentOptions" :label="t('dashboard.drawer.payment_method')" />
                <SmartSwitch v-model="filters.showCancelled" :label="t('dashboard.drawer.show_cancelled')" />
            </div>
            <template #footer>
                <div class="flex gap-2">
                    <button @click="resetFilters" class="flex-1 py-2.5 border border-border rounded-lg text-sm font-semibold hover:bg-muted transition-colors cursor-pointer">
                        {{ t('ui.actions.reset') }}
                    </button>
                    <button @click="isDrawerOpen = false" class="flex-1 py-2.5 bg-primary text-primary-foreground rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer">
                        {{ t('ui.actions.apply') }}
                    </button>
                </div>
            </template>
        </SmartDrawer>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/ui/Modal.vue';
import InputField from '@/Components/ui/InputField.vue';

// Import semua Smart Components
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import SmartAlert from '@/Components/smart/SmartAlert.vue';
import SmartAvatar from '@/Components/smart/SmartAvatar.vue';
import SmartTooltip from '@/Components/smart/SmartTooltip.vue';
import SmartStat from '@/Components/smart/SmartStat.vue';
import SmartGridStat from '@/Components/smart/SmartGridStat.vue';
import SmartChart from '@/Components/smart/SmartChart.vue';
import SmartBadge from '@/Components/smart/SmartBadge.vue';
import SmartPipeline from '@/Components/smart/SmartPipeline.vue';
import SmartFeed from '@/Components/smart/SmartFeed.vue';
import SmartSearch from '@/Components/smart/SmartSearch.vue';
import SmartSelect from '@/Components/smart/SmartSelect.vue';
import SmartTabs from '@/Components/smart/SmartTabs.vue';
import SmartTable from '@/Components/smart/SmartTable.vue';
import SmartPagination from '@/Components/smart/SmartPagination.vue';
import SmartEmpty from '@/Components/smart/SmartEmpty.vue';
import SmartProgress from '@/Components/smart/SmartProgress.vue';
import SmartTimeline from '@/Components/smart/SmartTimeline.vue';
import SmartSwitch from '@/Components/smart/SmartSwitch.vue';
import SmartCodeBlock from '@/Components/smart/SmartCodeBlock.vue';
import SmartTextarea from '@/Components/smart/SmartTextarea.vue';
import SmartFileUpload from '@/Components/smart/SmartFileUpload.vue';
import SmartDrawer from '@/Components/smart/SmartDrawer.vue';
// ════════════════════════════════════════════════════════════════
// YANG SEBELUMNYA HILANG: Import SmartDropdown
// ════════════════════════════════════════════════════════════════
import SmartDropdown from '@/Components/smart/SmartDropdown.vue';
import SmartAvatarGroup from '@/Components/smart/SmartAvatarGroup.vue';
import SmartAccordion from '@/Components/smart/SmartAccordion.vue';
import SmartSkeleton from '@/Components/smart/SmartSkeleton.vue';
import SmartDatePicker from '@/Components/smart/SmartDatePicker.vue';
import { useI18n } from '@/Composables/useI18n';
import SmartButton from '@/Components/smart/SmartButton.vue';
import { useConfirm } from '@/Composables/useConfirm';
import IconTrash2 from '~icons/lucide/trash-2';

const { open: openConfirm } = useConfirm();

async function testDelete() {
    const isConfirmed = await openConfirm({
        title: 'Hapus Data Demo?',
        message: 'Ini adalah percobaan fitur useConfirm(). Data tidak benar-benar terhapus.',
        confirmText: 'Ya, Hapus!',
        variant: 'destructive', // coba ganti ke 'warning' atau 'info'
    });

    if (isConfirmed) {
        console.log('User mengkonfirmasi penghapusan!');
        // Di sini nanti taruh: router.delete('/url/nya', ...)
    } else {
        console.log('User membatalkan.');
    }
}

const { t } = useI18n();

// --- STATE ---
const isModalOpen = ref(false);
const isDrawerOpen = ref(false);
const mobileMenu = ref(null);
const searchQuery = ref('');
const statusFilter = ref(null);
const activeTab = ref('semua');
const currentPage = ref(1);

const showSkeleton = ref(false);
const selectedDate = ref(null);

const faqItems = [
    { 
        key: 'faq1', 
        title: t('dashboard.faq.q1'), 
        content: t('dashboard.faq.a1') 
    },
    { 
        key: 'faq2', 
        title: t('dashboard.faq.q2'), 
        content: t('dashboard.faq.a2') 
    },
    { 
        key: 'faq3', 
        title: t('dashboard.faq.q3'), 
        content: t('dashboard.faq.a3') 
    },
];

const settings = reactive({ notif: true, twoFa: false });

const filters = reactive({
    dateFrom: '',
    dateTo: '',
    payment: null,
    showCancelled: false,
});

// Fungsi simulasi trigger toast via Inertia
const triggerToast = (type) => {
    if (type === 'success') {
        router.get('/test-toast-success', {}, { preserveScroll: true, preserveState: false });
    } else {
        router.get('/test-toast-error', {}, { preserveScroll: true, preserveState: false });
    }
};

// Handler mobile dropdown (menggunakan ref yang benar)
const handleMobileFilter = () => {
    isDrawerOpen.value = true;
    if (mobileMenu.value && typeof mobileMenu.value.close === 'function') {
        mobileMenu.value.close();
    }
};

const handleMobileToast = (type) => {
    triggerToast(type);
    if (mobileMenu.value && typeof mobileMenu.value.close === 'function') {
        mobileMenu.value.close();
    }
};

const resetFilters = () => {
    filters.dateFrom = '';
    filters.dateTo = '';
    filters.payment = null;
    filters.showCancelled = false;
};

// --- DUMMY DATA ---
const tabs = [
    { key: 'semua', label: 'Semua' },
    { key: 'selesai', label: 'Selesai' },
];

const statusOptions = [
    { label: 'Semua Status', value: null },
    { label: 'Diproses', value: 'Diproses' },
    { label: 'Dikirim', value: 'Dikirim' },
    { label: 'Selesai', value: 'Selesai' },
];

const paymentOptions = [
    { label: 'Semua Metode', value: null },
    { label: 'Bank Transfer', value: 'bank' },
    { label: 'E-Wallet', value: 'ewallet' },
    { label: 'COD', value: 'cod' },
];

const tableHeaders = [
    { key: 'order_id', label: 'ID Pesanan' },
    { key: 'customer', label: 'Pelanggan' },
    { key: 'status', label: 'Status' },
    { key: 'total', label: 'Total' },
    { key: 'actions', label: '', align: 'center', hideOnMobile: true },
];

const dummyOrders = [
    { id: 1, order_id: 'ORD-9021', customer: 'Joko Widodo', status: 'Dikirim', total: 250000 },
    { id: 2, order_id: 'ORD-9020', customer: 'Megawati S.', status: 'Diproses', total: 150000 },
    { id: 3, order_id: 'ORD-9019', customer: 'Prabowo Subianto', status: 'Selesai', total: 1200000 },
    { id: 4, order_id: 'ORD-9018', customer: 'Sri Mulyani', status: 'Dikirim', total: 50000 },
];

const serverLogs = [
    { title: 'Deploy v2.1.0', time: '09:00', description: 'Pembaruan fitur export PDF.', dotClass: 'bg-green-500' },
    { title: 'Maintenance DB', time: '02:00', description: 'Optimasi index tabel users.', dotClass: 'bg-yellow-500' },
    { title: 'Server Restart', time: 'Senin, 00:00', description: 'Penerapan patch keamanan kernel.', dotClass: 'bg-red-500' },
];

// --- FORM & ACTIONS ---
const form = useForm({
    productName: '',
    price: '',
    desc: '',
    photos: [],
});

const handleSearch = (val) => {
    console.log('Searching:', val);
};

const getVariantStatus = (status) => {
    const map = {
        'Diproses': 'warning',
        'Dikirim': 'primary',
        'Selesai': 'success',
        'Gagal': 'destructive',
    };
    return map[status] || 'neutral';
};

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(val);
};

const saveData = () => {
    form.clearErrors();
    
    // Simulasi validasi lokal
    let hasError = false;
    if (!form.productName) {
        form.setError('productName', 'Nama produk wajib diisi.');
        hasError = true;
    }
    if (!form.price || form.price <= 0) {
        form.setError('price', 'Harga harus berupa angka di atas 0.');
        hasError = true;
    }

    if (!hasError) {
        // Karena ini hanya demo di local, kita alert lalu tutup
        alert(`Data Berhasil Disimpan!\n\nProduk: ${form.productName}\nHarga: ${formatRupiah(form.price)}\nFoto: ${form.photos.length} file`);
        form.reset();
        isModalOpen.value = false;
    }
};
</script>