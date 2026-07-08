<template>
    <nav v-if="totalPages > 1" class="flex items-center justify-between gap-2" aria-label="Pagination">
        <!-- Info teks (desktop) -->
        <p class="hidden sm:block text-xs text-muted-foreground font-medium">
            {{ infoText }}
        </p>

        <!-- Controls -->
        <div class="flex items-center gap-1 sm:gap-1.5">
            <!-- First -->
            <button
                :disabled="modelValue <= 1"
                class="hidden sm:inline-flex items-center justify-center w-8 h-8 rounded-lg text-muted-foreground hover:bg-muted hover:text-foreground disabled:opacity-30 disabled:pointer-events-none transition-colors cursor-pointer"
                @click="goTo(1)"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                </svg>
            </button>

            <!-- Previous -->
            <button
                :disabled="modelValue <= 1"
                class="inline-flex items-center justify-center h-8 px-2 sm:px-3 rounded-lg text-xs sm:text-sm font-semibold text-muted-foreground hover:bg-muted hover:text-foreground disabled:opacity-30 disabled:pointer-events-none transition-colors cursor-pointer gap-1"
                @click="goTo(modelValue - 1)"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="hidden sm:inline">{{ t('ui.components.pagination.previous') }}</span>
            </button>

            <!-- Page Numbers (desktop) -->
            <div class="hidden sm:flex items-center gap-1">
                <template v-for="page in visiblePages" :key="page">
                    <!-- Ellipsis -->
                    <span v-if="page === '...'" class="w-8 h-8 flex items-center justify-center text-xs text-muted-foreground">
                        …
                    </span>
                    <!-- Page button -->
                    <button
                        v-else
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors cursor-pointer"
                        :class="page === modelValue
                            ? 'bg-primary text-primary-foreground shadow-sm'
                            : 'text-muted-foreground hover:bg-muted hover:text-foreground'"
                        @click="goTo(page)"
                    >
                        {{ page }}
                    </button>
                </template>
            </div>

            <!-- Mobile: current page indicator -->
            <span class="sm:hidden inline-flex items-center h-8 px-2.5 rounded-lg bg-muted text-xs font-bold text-foreground">
                {{ modelValue }} / {{ totalPages }}
            </span>

            <!-- Next -->
            <button
                :disabled="modelValue >= totalPages"
                class="inline-flex items-center justify-center h-8 px-2 sm:px-3 rounded-lg text-xs sm:text-sm font-semibold text-muted-foreground hover:bg-muted hover:text-foreground disabled:opacity-30 disabled:pointer-events-none transition-colors cursor-pointer gap-1"
                @click="goTo(modelValue + 1)"
            >
                <span class="hidden sm:inline">{{ t('ui.components.pagination.next') }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <!-- Last -->
            <button
                :disabled="modelValue >= totalPages"
                class="hidden sm:inline-flex items-center justify-center w-8 h-8 rounded-lg text-muted-foreground hover:bg-muted hover:text-foreground disabled:opacity-30 disabled:pointer-events-none transition-colors cursor-pointer"
                @click="goTo(totalPages)"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m6-15l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();

const props = defineProps({
    modelValue: { type: Number, required: true },
    totalItems: { type: Number, default: 0 },
    perPage: { type: Number, default: 10 },
    visibleCount: { type: Number, default: 5 },
});

const emit = defineEmits(['update:modelValue']);

const totalPages = computed(() => Math.max(1, Math.ceil(props.totalItems / props.perPage)));

const infoText = computed(() => {
    const start = (props.modelValue - 1) * props.perPage + 1;
    const end = Math.min(props.modelValue * props.perPage, props.totalItems);
    return `${start}–${end} dari ${props.totalItems}`;
});

const visiblePages = computed(() => {
    const total = totalPages.value;
    const current = props.modelValue;
    const count = props.visibleCount;

    if (total <= count) {
        return Array.from({ length: total }, (_, i) => i + 1);
    }

    const pages = [];
    const half = Math.floor(count / 2);
    let start = current - half;
    let end = current + half;

    if (start < 1) {
        start = 1;
        end = count;
    }
    if (end > total) {
        end = total;
        start = total - count + 1;
    }

    if (start > 1) {
        pages.push(1);
        if (start > 2) pages.push('...');
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    if (end < total) {
        if (end < total - 1) pages.push('...');
        pages.push(total);
    }

    return pages;
});

const goTo = (page) => {
    if (page < 1 || page > totalPages.value || page === props.modelValue) return;
    emit('update:modelValue', page);
};
</script>