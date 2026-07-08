<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 z-50 flex items-start justify-center pt-[10vh] sm:pt-[15vh] px-4">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]" @click="close"></div>

                <!-- Content -->
                <div class="relative z-10 w-full max-w-lg bg-card border border-border rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Search Input -->
                    <div class="flex items-center gap-3 px-4 border-b border-border">
                        <svg class="w-5 h-5 text-muted-foreground shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input 
                            ref="searchInput"
                            v-model="query" 
                            @keydown.down.prevent="moveDown" 
                            @keydown.up.prevent="moveUp" 
                            @keydown.enter.prevent="selectCurrent" 
                            @keydown.escape.prevent="close" 
                            type="text" 
                            class="w-full py-4 bg-transparent text-foreground placeholder:text-muted-foreground outline-none text-sm" 
                            :placeholder="t('ui.command_palette.placeholder')" 
                        />
                        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-mono text-muted-foreground bg-muted rounded border border-border">ESC</kbd>
                    </div>

                    <!-- Results -->
                    <div v-if="filteredItems.length > 0" class="max-h-72 overflow-y-auto custom-scrollbar p-2">
                        <div 
                            v-for="(item, index) in filteredItems" 
                            :key="item.url" 
                            @click="navigate(item)" 
                            @mouseenter="activeIndex = index" 
                            :class="[
                                'flex items-center gap-3 px-3 py-2.5 rounded-lg cursor-pointer transition-colors',
                                activeIndex === index ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-muted/50'
                            ]"
                        >
                            <div class="w-8 h-8 rounded-lg bg-muted/60 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium truncate">{{ item.label }}</p>
                                <p v-if="item.group" class="text-[10px] text-muted-foreground truncate">{{ item.group }}</p>
                            </div>
                            <svg class="w-4 h-4 opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </div>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-else class="py-12 text-center">
                        <p class="text-sm text-muted-foreground">{{ t('ui.command_palette.empty') }} "<span class="font-semibold text-foreground">{{ query }}</span>"</p>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch, nextTick } from 'vue';
import { useMagicKeys, whenever } from '@vueuse/core';
import { router, usePage } from '@inertiajs/vue3';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();
const page = usePage();
const searchInput = ref(null);
const query = ref('');
const activeIndex = ref(0);
const isOpen = ref(false);

const { ctrl_k, cmd_k, escape } = useMagicKeys();

whenever(ctrl_k, (e) => { e.preventDefault(); toggle(); });
whenever(cmd_k, (e) => { e.preventDefault(); toggle(); });
whenever(escape, () => { if (isOpen.value) close(); });

// Flatten menus dari Inertia
const flatMenus = computed(() => {
    const menus = page.props.menus || [];
    const result = [];
    menus.forEach(menu => {
        if (menu.url && menu.url !== '#') result.push({ label: menu.label, url: menu.url, group: 'Menu' });
        if (menu.children) {
            menu.children.forEach(child => {
                if (child.url && child.url !== '#') result.push({ label: child.label, url: child.url, group: menu.label });
            });
        }
    });
    return result;
});

const filteredItems = computed(() => {
    if (!query.value) return flatMenus.value;
    const q = query.value.toLowerCase();
    return flatMenus.value.filter(item => item.label.toLowerCase().includes(q));
});

const toggle = () => { isOpen.value ? close() : open(); };

const open = () => {
    isOpen.value = true;
    query.value = '';
    activeIndex.value = 0;
    nextTick(() => searchInput.value?.focus());
};

const close = () => { isOpen.value = false; };

const moveDown = () => {
    if (activeIndex.value < filteredItems.value.length - 1) activeIndex.value++;
    else activeIndex.value = 0;
};

const moveUp = () => {
    if (activeIndex.value > 0) activeIndex.value--;
    else activeIndex.value = filteredItems.value.length - 1;
};

const selectCurrent = () => {
    if (filteredItems.value[activeIndex.value]) {
        navigate(filteredItems.value[activeIndex.value]);
    }
};

const navigate = (item) => {
    close();
    router.visit(item.url);
};

watch(isOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : null;
});
</script>