<template>
    <div
        class="relative"
        @mouseenter="onMouseEnter"
        @mouseleave="onMouseLeave"
    >

        <!-- ══════ TANPA ANAK ══════ -->
        <template v-if="!item.children || item.children.length === 0">
            <Link
                :href="item.url"
                class="flex items-center rounded-lg text-sm font-medium transition-all duration-200 h-10"
                :class="[
                    isActive
                        ? 'bg-sidebar-accent text-sidebar-primary'
                        : 'text-sidebar-foreground hover:bg-sidebar-accent/60',
                    collapsed
                        ? 'justify-center w-10 mx-auto'
                        : 'gap-3 px-3 w-full'
                ]"
                @click="$emit('navigate')"
            >
                <MenuIcon
                    :name="item.icon"
                    class="w-5 h-5 shrink-0 transition-colors duration-200"
                    :class="isActive ? 'text-sidebar-primary' : 'text-muted-foreground'"
                />
                <span v-if="!collapsed" class="truncate whitespace-nowrap">{{ item.label }}</span>
            </Link>

            <!-- Tooltip: HANYA saat benar-benar collapsed (bukan hover-expand) -->
            <div
                v-if="collapsed && hovered"
                class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-popover text-popover-foreground text-xs font-medium rounded-lg shadow-xl border border-border whitespace-nowrap z-[60] pointer-events-none"
            >
                {{ item.label }}
                <div class="absolute right-full top-1/2 -translate-y-1/2">
                    <div class="w-0 h-0 border-t-[5px] border-b-[5px] border-r-[6px] border-t-transparent border-b-transparent border-r-popover"></div>
                </div>
            </div>
        </template>


        <!-- ══════ DENGAN ANAK ══════ -->
        <template v-else>
            <!-- Tombol parent -->
            <button
                @click="!collapsed && (open = !open)"
                class="flex items-center rounded-lg text-sm font-medium transition-all duration-200 w-full h-10"
                :class="[
                    hasActiveChild
                        ? 'text-sidebar-primary'
                        : 'text-sidebar-foreground hover:bg-sidebar-accent/60',
                    collapsed
                        ? 'justify-center w-10 mx-auto'
                        : 'justify-between gap-3 px-3'
                ]"
            >
                <div class="flex items-center" :class="collapsed ? '' : 'gap-3'">
                    <MenuIcon
                        :name="item.icon"
                        class="w-5 h-5 shrink-0 transition-colors duration-200"
                        :class="hasActiveChild ? 'text-sidebar-primary' : 'text-muted-foreground'"
                    />
                    <span v-if="!collapsed" class="truncate whitespace-nowrap">{{ item.label }}</span>
                </div>

                <svg
                    v-if="!collapsed"
                    class="w-4 h-4 shrink-0 transition-transform duration-200"
                    :class="[
                        open ? 'rotate-180 text-sidebar-primary' : 'text-muted-foreground'
                    ]"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- ── Dropdown inline: HANYA saat tidak collapsed ── -->
            <Transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-[500px] opacity-100"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="max-h-[500px] opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div v-if="!collapsed && open" class="overflow-hidden">
                    <div class="pl-9 pr-1 py-1.5 space-y-0.5">
                        <Link
                            v-for="child in item.children"
                            :key="child.id"
                            :href="child.url"
                            class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-md transition-colors duration-200"
                            :class="[
                                isChildActive(child)
                                    ? 'text-sidebar-primary font-semibold bg-sidebar-accent'
                                    : 'text-muted-foreground hover:text-sidebar-foreground hover:bg-sidebar-accent/60'
                            ]"
                            @click="$emit('navigate')"
                        >
                            <span
                                class="w-1.5 h-1.5 rounded-full shrink-0"
                                :class="isChildActive(child) ? 'bg-sidebar-primary' : 'bg-border'"
                            ></span>
                            <span class="truncate">{{ child.label }}</span>
                        </Link>
                    </div>
                </div>
            </Transition>

            <!-- ── Flyout + Bridge + Tooltip: HANYA saat benar-benar collapsed ── -->
            <template v-if="collapsed">
                <!-- Jembatan hover -->
                <div
                    class="absolute top-0 left-full w-3 h-full"
                    @mouseenter="bridgeEnter"
                    @mouseleave="bridgeLeave"
                ></div>

                <!-- Flyout dropdown -->
                <div
                    v-if="open"
                    class="absolute left-full top-0 ml-2 w-56 bg-popover border border-border rounded-xl shadow-2xl py-2 z-[60]"
                    @mouseenter="flyoutEnter"
                    @mouseleave="flyoutLeave"
                >
                    <div class="px-4 py-2 border-b border-border mb-1">
                        <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{{ item.label }}</p>
                    </div>
                    <Link
                        v-for="child in item.children"
                        :key="child.id"
                        :href="child.url"
                        class="flex items-center gap-2.5 px-4 py-2 text-sm transition-colors duration-200"
                        :class="[
                            isChildActive(child)
                                ? 'text-sidebar-primary font-semibold bg-sidebar-accent/50'
                                : 'text-popover-foreground hover:bg-muted'
                        ]"
                        @click="$emit('navigate')"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full shrink-0"
                            :class="isChildActive(child) ? 'bg-sidebar-primary' : 'bg-border'"
                        ></span>
                        <span class="truncate">{{ child.label }}</span>
                    </Link>
                </div>

                <!-- Tooltip (saat flyout tertutup) -->
                <div
                    v-if="!open && hovered"
                    class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-popover text-popover-foreground text-xs font-medium rounded-lg shadow-xl border border-border whitespace-nowrap z-[60] pointer-events-none"
                >
                    {{ item.label }}
                    <div class="absolute right-full top-1/2 -translate-y-1/2">
                        <div class="w-0 h-0 border-t-[5px] border-b-[5px] border-r-[6px] border-t-transparent border-b-transparent border-r-popover"></div>
                    </div>
                </div>
            </template>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, watch, watchEffect } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import MenuIcon from '@/Components/ui/MenuIcon.vue';

const page = usePage();

const props = defineProps({
    item: { type: Object, required: true },
    collapsed: { type: Boolean, default: false },
});

defineEmits(['navigate']);

const open = ref(false);
const hovered = ref(false);

// ══════════════════════════════════════════════════════════
// ACTIVE STATE — Dihitung di client berdasarkan URL saat ini
// ══════════════════════════════════════════════════════════

/**
 * Cek apakah URL menu cocok dengan URL halaman saat ini.
 * Mendukung exact match dan prefix match (untuk child routes).
 */
const isUrlActive = (menuUrl) => {
    if (!menuUrl || menuUrl === '#') return false;

    const currentUrl = page.url; // e.g. "http://localhost:8000/admin/users"
    const menuUrlPath = new URL(menuUrl, window.location.origin).pathname; // e.g. "/admin/users"

    const currentPath = new URL(currentUrl, window.location.origin).pathname;

    // Exact match
    if (currentPath === menuUrlPath) return true;

    // Prefix match: /admin/users cocok dengan /admin/users/1/edit
    if (currentPath.startsWith(menuUrlPath + '/')) return true;

    return false;
};

/**
 * Apakah menu item ini aktif (tanpa anak).
 */
const isActive = computed(() => isUrlActive(props.item.url));

/**
 * Apakah salah satu anak menu ini aktif.
 */
const hasActiveChild = computed(() =>
    props.item.children?.some(child => isUrlActive(child.url)) || false
);

/**
 * Cek apakah child tertentu aktif.
 * Dipakai di template untuk styling individual child item.
 */
const isChildActive = (child) => isUrlActive(child.url);

// ─── Auto-buka dropdown jika ada anak aktif (hanya saat tidak collapsed) ───
watchEffect(() => {
    if (!props.collapsed && hasActiveChild.value) {
        open.value = true;
    }
});

// ─── Saat sidebar di-collapse, tutup semua dropdown ───
watch(() => props.collapsed, (val) => {
    if (val) open.value = false;
});

// ─── Hover state ───
const onMouseEnter = () => {
    hovered.value = true;
    if (props.collapsed) {
        open.value = true;
    }
};

const onMouseLeave = () => {
    hovered.value = false;
    if (props.collapsed) {
        setTimeout(() => {
            if (!hovered.value) open.value = false;
        }, 150);
    }
};

// ─── Bridge hover ───
const bridgeEnter = () => { hovered.value = true; };
const bridgeLeave = () => {
    hovered.value = false;
    setTimeout(() => {
        if (!hovered.value) open.value = false;
    }, 150);
};

// ─── Flyout hover ───
const flyoutEnter = () => { hovered.value = true; };
const flyoutLeave = () => {
    hovered.value = false;
    setTimeout(() => {
        if (!hovered.value) open.value = false;
    }, 100);
};
</script>