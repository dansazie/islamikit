<template>
    <div class="min-h-screen bg-background flex">
        <!-- --- Impersonate Banner --- -->
        <ImpersonateBanner v-if="isImpersonating" :impersonating="impersonating" />

        <!-- --- Overlay Mobile --- -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 md:hidden"
                @click="sidebarOpen = false"
            ></div>
        </Transition>

        <!-- --- Sidebar --- -->
        <aside
            @mouseenter="onSidebarEnter"
            @mouseleave="onSidebarLeave"
            class="no-print fixed inset-y-0 left-0 z-40 bg-sidebar border-r border-sidebar-border flex flex-col transition-[width,box-shadow] duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] md:translate-x-0"
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                { 'shadow-xl shadow-black/[0.08] dark:shadow-black/30': isHoverExpanded }
            ]"
            :style="{ width: sidebarWidth + 'px' }"
        >
            <!-- Header Logo -->
            <div
                class="h-14 sm:h-16 flex items-center border-b border-sidebar-border shrink-0 overflow-hidden transition-all duration-300"
                :class="isVisuallyExpanded ? 'px-4 sm:px-5' : 'justify-center px-2 sm:px-3'"
            >
                <!-- Ikon selalu tampil -->
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-sidebar-primary flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sidebar-primary-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>

                <!-- Nama aplikasi: slide masuk saat expand -->
                <div
                    class="overflow-hidden transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
                    :class="isVisuallyExpanded ? 'max-w-[180px] sm:max-w-[220px] opacity-100 ml-2.5 sm:ml-3' : 'max-w-0 opacity-0 ml-0'"
                >
                    <span class="font-bold text-base sm:text-lg text-sidebar-foreground tracking-tight whitespace-nowrap block">{{ appName }}</span>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav
                class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar transition-[padding] duration-300"
                :class="isVisuallyExpanded ? 'py-3 sm:py-4 px-2.5 sm:px-3' : 'py-2 sm:py-3 px-2 sm:px-2.5'"
            >
                <SidebarItem
                    v-for="menu in menus"
                    :key="menu.id"
                    :item="menu"
                    :collapsed="!isVisuallyExpanded && isDesktop"
                    @navigate="sidebarOpen = false"
                />
            </nav>

            <!-- Tombol Collapse/Expand -->
            <div class="hidden md:block border-t border-sidebar-border shrink-0">
                <button
                    @click="collapsed = !collapsed"
                    class="w-full flex items-center transition-all duration-200 text-muted-foreground hover:text-sidebar-foreground hover:bg-sidebar-accent/60 cursor-pointer"
                    :class="collapsed ? 'justify-center py-2.5 sm:py-3 px-2' : 'gap-3 px-3 sm:px-4 py-2.5 sm:py-3'"
                    :title="collapsed ? t('ui.sidebar.expand_tooltip') : t('ui.sidebar.collapse_tooltip')"
                >
                    <svg
                        class="w-4 h-4 sm:w-[18px] sm:h-[18px] shrink-0 transition-transform duration-300"
                        :class="{ 'rotate-180': collapsed }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span
                        v-if="!collapsed"
                        class="text-sm font-medium truncate"
                    >{{ t('ui.sidebar.collapse_btn') }}</span>
                </button>
            </div>

            <!-- Footer Sidebar -->
            <div
                class="border-t border-sidebar-border shrink-0 overflow-hidden transition-all duration-300 py-2 px-2 text-center"
            >
                <p class="text-[9px] sm:text-[10px] text-muted-foreground leading-tight">{{ new Date().getFullYear() }}</p>
            </div>
        </aside>

        <!-- --- Area Konten Utama --- -->
        <div
            class="flex-1 flex flex-col min-h-screen transition-[padding-left] duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
            :style="{ paddingLeft: isDesktop ? sidebarWidth + 'px' : '0' }"
        >
            <!-- Header -->
            <header class="no-print h-14 sm:h-16 bg-card border-b border-border flex items-center justify-between px-3 sm:px-4 md:px-6 sticky top-0 z-20">
                <div class="flex items-center gap-2 sm:gap-3">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="md:hidden p-2 rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-xs sm:text-sm font-semibold text-muted-foreground uppercase tracking-wider hidden sm:block truncate">
                        {{ t('ui.header.title') }}
                    </h2>
                </div>

                <div class="flex items-center gap-1 sm:gap-1.5">
                    <LanguageSwitcher />
                    <SmartNotificationBell :urls="$page.props?.notificationUrls || {}" />
                    <DarkModeToggle />
                    <Dropdown width="56">
                        <template #trigger>
                            <button class="inline-flex items-center gap-1.5 sm:gap-2 px-2 sm:px-3 py-1.5 sm:py-2 text-sm font-medium rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer">
                                
                                <!-- ? PERBAIKAN 1: Tambahkan ?. di sini -->
                                <div v-if="$page.props?.auth?.user?.avatar" class="w-7 h-7 rounded-full overflow-hidden shrink-0">
                                    <img :src="$page?.props?.auth?.user?.avatar" :alt="userName" class="w-full h-full object-cover" />
                                </div>
                                <div v-else class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-bold text-primary">{{ userInitial }}</span>
                                </div>
                                
                                <span class="hidden sm:inline max-w-[100px] md:max-w-[120px] truncate">{{ userName }}</span>
                                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <div class="px-3 sm:px-4 py-2 sm:py-2.5 border-b border-border">
                                <p class="text-sm font-medium text-foreground truncate">{{ userName }}</p>
                                <p class="text-xs text-muted-foreground truncate">{{ userEmail }}</p>
                            </div>
                            <Link href="/profile" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2 text-left text-sm text-popover-foreground hover:bg-muted transition-colors">
                                <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                {{ t('ui.profile.manage') }}
                            </Link>
                            <div class="border-t border-border"></div>
                            <Link href="/logout" method="post" as="button" class="flex items-center gap-2.5 w-full px-3 sm:px-4 py-2 text-left text-sm text-destructive hover:bg-muted transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                {{ t('ui.auth.logout') }}
                            </Link>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-3 sm:p-4 md:p-6 lg:p-8">
                <Transition name="page-fade" mode="out-in">
                    <div :key="$page.url" class="app-content-max">
                        <slot />
                    </div>
                </Transition>
            </main>
        </div>

        <Toast />
        <ConfirmDialog />
        <SmartCommandPalette />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import SidebarItem from '@/Components/ui/SidebarItem.vue';
import Dropdown from '@/Components/ui/Dropdown.vue';
import Toast from '@/Components/ui/Toast.vue';
import DarkModeToggle from '@/Components/ui/DarkModeToggle.vue';
import LanguageSwitcher from '@/Components/ui/LanguageSwitcher.vue';
import { useI18n } from '@/Composables/useI18n';
import ConfirmDialog from '@/Components/ui/ConfirmDialog.vue';
import SmartCommandPalette from '@/Components/smart/SmartCommandPalette.vue';
import SmartNotificationBell from '@/Components/smart/SmartNotificationBell.vue';
import ImpersonateBanner from '@/Components/ui/ImpersonateBanner.vue';

const page = usePage();
const { t } = useI18n();
const isImpersonating = computed(() => !!page.props?.impersonating);
const impersonating = computed(() => page.props?.impersonating || {});

const userName = computed(() => page.props?.auth?.user?.name || 'User');
const userEmail = computed(() => page.props?.auth?.user?.email || '');
const userInitial = computed(() => userName.value.charAt(0).toUpperCase() || '?');
const appName = computed(() => page.props?.appName || 'Laravel');
const appInitials = computed(() => {
    const name = appName.value.trim();
    if (!name) return 'L';
    const words = name.split(/[\s\-_]+/).filter(Boolean);
    if (words.length >= 2) return (words[0].charAt(0) + words[1].charAt(0)).toUpperCase();
    return name.substring(0, 2).toUpperCase();
});

const menus = computed(() => {
    const backendMenus = page.props?.menus;
    if (backendMenus && Array.isArray(backendMenus) && backendMenus.length > 0) {
        return backendMenus;
    }
    return [
        { id: '_default_dashboard', label: 'Dashboard', url: '/dashboard', icon: 'Dashboard', type: 'link', is_active: true, children: [] },
    ];
});

const sidebarOpen = ref(false);

// Default: collapsed di tablet (md), expanded di desktop (lg)
const getDefaultCollapsed = () => {
    const saved = localStorage.getItem('sidebar-collapsed');
    if (saved !== null) return saved === 'true';
    return window.innerWidth < 1024;
};

const collapsed = ref(getDefaultCollapsed());
watch(collapsed, (val) => localStorage.setItem('sidebar-collapsed', String(val)));

const isDesktop = ref(false);
let mqlMd = null;

const onMdChange = (e) => {
    isDesktop.value = e.matches;
    if (!e.matches) {
        sidebarOpen.value = false;
        sidebarHovered.value = false;
    }
};

onMounted(() => {
    mqlMd = window.matchMedia('(min-width: 768px)');
    isDesktop.value = mqlMd.matches;
    mqlMd.addEventListener('change', onMdChange);
});

onUnmounted(() => {
    if (mqlMd) mqlMd.removeEventListener('change', onMdChange);
});

const SIDEBAR_EXPANDED = 256;
const SIDEBAR_COLLAPSED = 72;

// ------ HOVER-TO-EXPAND STATE ------
const sidebarHovered = ref(false);

const onSidebarEnter = () => {
    if (collapsed.value && isDesktop.value) {
        sidebarHovered.value = true;
    }
};

const onSidebarLeave = () => {
    sidebarHovered.value = false;
};

// Apakah sedang dalam mode hover-expand?
const isHoverExpanded = computed(() => {
    return collapsed.value && sidebarHovered.value && isDesktop.value;
});

// Apakah sidebar secara visual menampilkan konten penuh?
const isVisuallyExpanded = computed(() => {
    if (!isDesktop.value) return true;
    return !collapsed.value || sidebarHovered.value;
});

// Lebar sidebar: mempertimbangkan hover-expand
const sidebarWidth = computed(() => {
    if (!isDesktop.value) return SIDEBAR_EXPANDED;
    if (collapsed.value && !sidebarHovered.value) return SIDEBAR_COLLAPSED;
    return SIDEBAR_EXPANDED;
});
</script>