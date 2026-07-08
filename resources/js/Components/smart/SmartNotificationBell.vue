<template>
    <div class="relative" ref="bellRef">
        <button 
            @click="toggleDropdown" 
            class="relative p-2 rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer"
            :title="t('ui.notifications.title')"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            <!-- Unread Badge -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="scale-0 opacity-0"
                enter-to-class="scale-100 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="scale-100 opacity-100"
                leave-to-class="scale-0 opacity-0"
            >
                <span 
                    v-if="unreadCount > 0" 
                    class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-4.5 h-4.5 text-[9px] font-bold text-white bg-destructive rounded-full ring-2 ring-card"
                    style="width: 18px; height: 18px;"
                >
                    {{ unreadCount > 9 ? '9+' : unreadCount }}
                </span>
            </Transition>
        </button>

        <!-- Dropdown Panel -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div 
                v-if="isOpen" 
                class="absolute right-0 mt-2 w-80 sm:w-96 bg-popover border border-border rounded-xl shadow-2xl z-50 overflow-hidden"
            >
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-border bg-muted/30">
                    <h3 class="text-sm font-bold text-foreground">{{ t('ui.notifications.title') }}</h3>
                    <button 
                        v-if="unreadCount > 0" 
                        @click="markAllRead" 
                        class="text-[11px] font-semibold text-primary hover:underline cursor-pointer"
                    >
                        {{ t('ui.notifications.mark_all') }}
                    </button>
                </div>

                <!-- Content -->
                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                    <!-- Loading State -->
                    <div v-if="isLoading" class="flex items-center justify-center py-10">
                        <svg class="w-6 h-6 animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="notifications.length === 0" class="py-10 text-center">
                        <svg class="w-10 h-10 mx-auto text-muted-foreground/50 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" /></svg>
                        <p class="text-sm text-muted-foreground">{{ t('ui.notifications.empty') }}</p>
                    </div>

                    <!-- List Notif -->
                    <div v-else>
                        <button 
                            v-for="notif in notifications" 
                            :key="notif.id" 
                            @click="handleRead(notif)"
                            :class="[
                                'w-full text-left px-4 py-3 flex gap-3 hover:bg-muted/50 transition-colors border-b border-border/50 last:border-b-0 cursor-pointer',
                                !notif.read_at ? 'bg-primary/[0.03]' : ''
                            ]"
                        >
                            <div :class="['w-2 h-2 rounded-full mt-2 shrink-0', notif.read_at ? 'bg-transparent' : 'bg-primary']"></div>
                            <div class="min-w-0 flex-1">
                                <p :class="['text-sm leading-snug', notif.read_at ? 'text-muted-foreground' : 'text-foreground font-medium']">
                                    {{ notif.data.message || notif.data.title || 'Notifikasi baru' }}
                                </p>
                                <p class="text-[10px] text-muted-foreground mt-1">{{ notif.created_at }}</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { onClickOutside } from '@vueuse/core';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();
const page = usePage();

const props = defineProps({
    urls: { type: Object, required: true }
});

const bellRef = ref(null);
const isOpen = ref(false);
const isLoading = ref(false);
const notifications = ref([]);

const unreadCount = ref(page.props.unreadNotificationsCount || 0);

watch(() => page.props.unreadNotificationsCount, (val) => {
    unreadCount.value = val || 0;
});

onClickOutside(bellRef, () => { if (isOpen.value) isOpen.value = false; });

const toggleDropdown = async () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value && notifications.value.length === 0) {
        await fetchNotifications();
    }
};

const fetchNotifications = async () => {
    isLoading.value = true;
    try {
        const res = await fetch(props.urls.index, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        notifications.value = await res.json();
    } catch (e) {
        console.error('Failed to fetch notifications', e);
    } finally {
        isLoading.value = false;
    }
};

const handleRead = async (notif) => {
    if (!notif.read_at) {
        notif.read_at = new Date().toISOString();
        unreadCount.value = Math.max(0, unreadCount.value - 1);
        
        try {
            await fetch(props.urls.read + notif.id + '/read', { method: 'PATCH' });
        } catch (e) { console.error(e); }
    }
    
    if (notif.data.url) {
        isOpen.value = false;
        window.location.href = notif.data.url;
    }
};

const markAllRead = async () => {
    notifications.value.forEach(n => n.read_at = new Date().toISOString());
    unreadCount.value = 0;
    
    try {
        await fetch(props.urls.readAll, { method: 'POST' });
    } catch (e) { console.error(e); }
};
</script>