<template>
    <!-- ══════════════════════════════════════════════════
         DESKTOP (md+): Compact Table
         ══════════════════════════════════════════════════ -->
    <div v-if="isDesktop" class="overflow-x-auto border border-border rounded-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-muted/70 text-muted-foreground text-[11px] font-bold uppercase tracking-wider border-b border-border">
                    <th
                        v-for="header in desktopHeaders"
                        :key="header.key"
                        class="px-3.5 py-3 whitespace-nowrap"
                        :class="alignClass(header)"
                    >
                        <slot :name="`header-${header.key}`" :header="header">
                            {{ header.label }}
                        </slot>
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-border">
                <tr
                    v-for="row in rows"
                    :key="row[rowKey]"
                    class="hover:bg-muted/30 transition-colors"
                >
                    <td
                        v-for="header in desktopHeaders"
                        :key="header.key"
                        class="px-3.5 py-2.5 align-middle"
                        :class="alignClass(header)"
                    >
                        <slot :name="`cell-${header.key}`" :row="row" :value="row[header.key]">
                            <span :class="header.muted ? 'text-muted-foreground text-xs' : 'text-foreground'">
                                {{ row[header.key] }}
                            </span>
                        </slot>
                    </td>
                </tr>

                <!-- Empty State Desktop -->
                <tr v-if="!rows || rows.length === 0">
                    <td :colspan="desktopHeaders.length" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-full bg-muted flex items-center justify-center">
                                <svg class="w-7 h-7 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                            </div>
                            <p class="text-sm text-muted-foreground max-w-xs">{{ emptyText }}</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ══════════════════════════════════════════════════
         MOBILE (<md): Accordion Cards
         ══════════════════════════════════════════════════ -->
    <div v-else class="space-y-2.5">
        <div
            v-for="row in rows"
            :key="row[rowKey]"
            class="bg-card border border-border rounded-xl overflow-hidden transition-shadow hover:shadow-sm"
        >
            <!-- ── Accordion Trigger ── -->
            <button
                @click="toggle(row[rowKey])"
                class="w-full flex items-center gap-3 p-3 text-left cursor-pointer"
            >
                <!-- Avatar Inisial -->
                <div class="w-10 h-10 rounded-full bg-accent text-accent-foreground flex items-center justify-center shrink-0 text-sm font-bold">
                    {{ getInitial(row[primaryColumn]) }}
                </div>

                <!-- Teks Utama -->
                <div class="flex-1 min-w-0">
                    <slot :name="`cell-${primaryColumn}`" :row="row" :value="row[primaryColumn]">
                        <p class="text-sm font-semibold text-foreground truncate">
                            {{ row[primaryColumn] }}
                        </p>
                    </slot>
                    <!-- Subteks: kolom sekunder -->
                    <p
                        v-if="secondaryColumn && row[secondaryColumn]"
                        class="text-xs text-muted-foreground truncate mt-0.5"
                    >
                        {{ row[secondaryColumn] }}
                    </p>
                </div>

                <!-- Chevron -->
                <svg
                    class="w-4 h-4 text-muted-foreground shrink-0 transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen(row[rowKey]) }"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- ── Accordion Body: 2-Column Grid ── -->
            <Transition
                enter-active-class="transition-all duration-250 ease-out overflow-hidden"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-[600px] opacity-100"
                leave-active-class="transition-all duration-200 ease-in overflow-hidden"
                leave-from-class="max-h-[600px] opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div v-if="isOpen(row[rowKey])">
                    <div class="px-3 pb-3 pt-2.5 border-t border-border">
                        <!-- 
                            Grid 2 kolom untuk distribusi kiri-kanan.
                            Item dengan span: 2 akan memenuhi lebar penuh.
                        -->
                        <div
                            class="grid gap-x-4 gap-y-3"
                            :class="
                                mobileHeaders.length <= 1
                                    ? 'grid-cols-1'
                                    : 'grid-cols-2'
                            "
                        >
                            <div
                                v-for="header in mobileHeaders"
                                :key="header.key"
                                :class="header.span === 2 ? 'col-span-2' : ''"
                            >
                                <p class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-1">
                                    {{ header.label }}
                                </p>
                                <slot :name="`cell-${header.key}`" :row="row" :value="row[header.key]">
                                    <p class="text-sm text-foreground break-words">{{ row[header.key] }}</p>
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>

        <!-- Empty State Mobile -->
        <div v-if="!rows || rows.length === 0" class="bg-card border border-border rounded-xl p-8 text-center">
            <div class="flex flex-col items-center gap-3">
                <div class="w-14 h-14 rounded-full bg-muted flex items-center justify-center">
                    <svg class="w-7 h-7 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <p class="text-sm text-muted-foreground">{{ emptyText }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    headers: {
        type: Array,
        required: true,
        // Format: { key, label, align?, muted?, hideOnMobile?, span? }
        // span: 2 → full width di mobile accordion
    },
    rows: {
        type: Array,
        default: () => [],
    },
    rowKey: {
        type: String,
        default: 'id',
    },
    primaryColumn: {
        type: String,
        default: '',
    },
    secondaryColumn: {
        type: String,
        default: '',
    },
    emptyText: {
        type: String,
        default: 'Tidak ada data untuk ditampilkan.',
    },
});

// ─── Breakpoint reaktif ───
const isDesktop = ref(false);
let mql = null;

onMounted(() => {
    mql = window.matchMedia('(min-width: 768px)');
    isDesktop.value = mql.matches;
    mql.addEventListener('change', (e) => { isDesktop.value = e.matches; });
});

onUnmounted(() => {
    if (mql) mql.removeEventListener('change', () => {});
});

// ─── Kolom desktop: semuanya ───
const desktopHeaders = computed(() => props.headers);

// ─── Kolom mobile: kecuali primary, secondary, dan hideOnMobile ───
const mobileHeaders = computed(() => {
    const primary = props.primaryColumn || props.headers[0]?.key;
    return props.headers.filter((h) => {
        if (h.key === primary) return false;
        if (h.key === props.secondaryColumn) return false;
        if (h.hideOnMobile) return false;
        return true;
    });
});

// ─── Accordion state ───
const openRows = ref({});

const isOpen = (key) => !!openRows.value[key];

const toggle = (key) => {
    const next = { ...openRows.value };
    if (next[key]) {
        delete next[key];
    } else {
        next[key] = true;
    }
    openRows.value = next;
};

// ─── Helper: inisial ───
const getInitial = (text) => {
    if (!text) return '?';
    const str = String(text).trim();
    if (!str) return '?';
    return str.charAt(0).toUpperCase();
};

// ─── Helper: alignment ───
const alignClass = (header) => {
    if (header.align === 'center') return 'text-center';
    if (header.align === 'right') return 'text-right';
    return '';
};
</script>