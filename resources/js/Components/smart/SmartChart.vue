<template>
    <div class="inline-flex flex-col items-center gap-2">
        <div class="relative" :class="sizeClass">
            <!-- Background Track -->
            <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                <path
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                    fill="none"
                    :stroke="trackColor"
                    :stroke-width="thickness"
                    stroke-linecap="round"
                />
                <!-- Progress Fill -->
                <path
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                    fill="none"
                    :stroke="computedColor"
                    :stroke-width="thickness"
                    stroke-linecap="round"
                    :stroke-dasharray="`${percentage}, 100`"
                    class="transition-all duration-700 ease-out"
                />
            </svg>
            
            <!-- Center Text -->
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="font-black tabular-nums text-foreground leading-none" :class="centerSizeClass">
                    {{ displayValue }}
                </span>
                <span v-if="label" class="text-[10px] font-semibold text-muted-foreground mt-0.5 uppercase tracking-wider">
                    {{ label }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    percentage: { type: Number, default: 0 },
    label: { type: String, default: '' },
    showPercent: { type: Boolean, default: true },
    size: { type: String, default: 'md', validator: v => ['sm', 'md', 'lg', 'xl'].includes(v) },
    thickness: { type: String, default: '3', validator: v => ['2', '3', '4'].includes(v) },
    color: { type: String, default: 'primary' }, // 'primary', 'secondary', 'tertiary', 'destructive', 'success', 'warning'
});

const sizeClass = computed(() => {
    const map = { sm: 'w-16 h-16', md: 'w-24 h-24', lg: 'w-32 h-32', xl: 'w-40 h-40' };
    return map[props.size];
});

const centerSizeClass = computed(() => {
    const map = { sm: 'text-sm', md: 'text-xl', lg: 'text-3xl', xl: 'text-4xl' };
    return map[props.size];
});

const displayValue = computed(() => props.showPercent ? `${Math.round(props.percentage)}%` : '');

const trackColor = computed(() => 'var(--color-muted)');

const computedColor = computed(() => {
    const map = {
        primary: 'var(--color-primary)',
        secondary: 'var(--color-secondary)',
        tertiary: 'var(--color-tertiary)',
        destructive: 'var(--color-destructive)',
        success: 'rgb(34 197 94)',
        warning: 'rgb(234 179 8)',
    };
    return map[props.color] || map.primary;
});
</script>