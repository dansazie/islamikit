<template>
    <div
        class="app-card-container !gap-0 relative overflow-hidden group"
        :class="clickable ? 'cursor-pointer hover:ring-2 hover:ring-ring/20' : ''"
        @click="clickable && $emit('click', $event)"
    >
        <!-- Background decoration -->
        <div
            v-if="icon"
            class="absolute -right-3 -top-3 w-24 h-24 rounded-full opacity-[0.06] group-hover:opacity-[0.1] transition-opacity"
            :class="variantBgClass"
        />

        <div class="flex items-start justify-between gap-3 relative z-10">
            <!-- Label & Value -->
            <div class="min-w-0 flex-1">
                <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">
                    {{ label }}
                </p>
                <p class="text-2xl sm:text-3xl font-black tracking-tight text-foreground leading-none">
                    <span v-if="prefix" class="text-lg sm:text-xl font-bold text-muted-foreground mr-0.5">{{ prefix }}</span>
                    <slot name="value">
                        {{ formattedValue }}
                    </slot>
                    <span v-if="suffix" class="text-lg sm:text-xl font-bold text-muted-foreground ml-0.5">{{ suffix }}</span>
                </p>
            </div>

            <!-- Icon -->
            <div
                v-if="icon"
                class="app-icon-wrapper !rounded-xl"
                :class="variantIconClass"
            >
                <component :is="icon" class="w-5 h-5 sm:w-6 sm:h-6" />
            </div>
        </div>

        <!-- Trend & Description -->
        <div class="flex items-center gap-2 mt-3 relative z-10 flex-wrap">
            <!-- Trend indicator -->
            <div
                v-if="trend !== null && trend !== undefined"
                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[11px] font-bold"
                :class="trendClass"
            >
                <svg
                    class="w-3 h-3"
                    :class="{ 'rotate-180': trend < 0 }"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                </svg>
                {{ Math.abs(trend) }}%
            </div>

            <span v-if="description" class="text-[11px] sm:text-xs text-muted-foreground">
                {{ description }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    value: { type: [Number, String], default: null },
    prefix: { type: String, default: '' },
    suffix: { type: String, default: '' },
    icon: { type: [Object, null], default: null },
    trend: { type: [Number, null], default: null },
    description: { type: String, default: '' },
    variant: {
        type: String,
        default: 'primary',
        validator: v => ['primary', 'secondary', 'tertiary', 'destructive', 'muted'].includes(v),
    },
    format: {
        type: String,
        default: 'number',
        validator: v => ['number', 'currency', 'percent', 'plain'].includes(v),
    },
    clickable: { type: Boolean, default: false },
});

defineEmits(['click']);

const formattedValue = computed(() => {
    if (props.value === null || props.value === undefined) return '—';
    const num = Number(props.value);
    if (isNaN(num)) return props.value;

    switch (props.format) {
        case 'currency':
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(num);
        case 'percent':
            return `${num}%`;
        case 'plain':
            return props.value;
        default:
            return new Intl.NumberFormat('id-ID').format(num);
    }
});

const variantBgClass = computed(() => {
    const map = {
        primary: 'bg-primary',
        secondary: 'bg-secondary',
        tertiary: 'bg-tertiary',
        destructive: 'bg-destructive',
        muted: 'bg-muted-foreground',
    };
    return map[props.variant] || map.primary;
});

const variantIconClass = computed(() => {
    const map = {
        primary: 'bg-primary/10 text-primary',
        secondary: 'bg-secondary/10 text-secondary',
        tertiary: 'bg-tertiary/10 text-tertiary',
        destructive: 'bg-destructive/10 text-destructive',
        muted: 'bg-muted text-muted-foreground',
    };
    return map[props.variant] || map.primary;
});

const trendClass = computed(() => {
    if (props.trend > 0) return 'bg-primary/10 text-primary';
    if (props.trend < 0) return 'bg-destructive/10 text-destructive';
    return 'bg-muted text-muted-foreground';
});
</script>