<template>
    <span
        class="inline-flex items-center gap-1 font-semibold select-none whitespace-nowrap"
        :class="[sizeClass, variantClass, roundedClass]"
    >
        <!-- Dot indicator (optional) -->
        <span
            v-if="dot"
            class="rounded-full shrink-0"
            :class="dotSizeClass"
            :style="{ backgroundColor: dotColor }"
        />

        <slot>{{ label }}</slot>
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, default: '' },
    variant: {
        type: String,
        default: 'neutral',
        validator: v => ['primary', 'secondary', 'tertiary', 'destructive', 'success', 'warning', 'neutral', 'outline'].includes(v),
    },
    size: {
        type: String,
        default: 'sm',
        validator: v => ['xs', 'sm', 'md'].includes(v),
    },
    dot: { type: Boolean, default: false },
    pill: { type: Boolean, default: true },
});

const sizeClass = computed(() => {
    const map = {
        xs: 'text-[10px] px-1.5 py-px',
        sm: 'text-[11px] px-2 py-0.5',
        md: 'text-xs px-2.5 py-1',
    };
    return map[props.size];
});

const roundedClass = computed(() => {
    return props.pill ? 'rounded-full' : 'rounded-md';
});

const variantClass = computed(() => {
    const map = {
        primary: 'bg-primary/10 text-primary',
        secondary: 'bg-secondary/10 text-secondary',
        tertiary: 'bg-tertiary/10 text-tertiary',
        destructive: 'bg-destructive/10 text-destructive',
        success: 'bg-green-500/10 text-green-600 dark:text-green-400',
        warning: 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400',
        neutral: 'bg-muted text-muted-foreground',
        outline: 'bg-transparent border border-border text-foreground',
    };
    return map[props.variant] || map.neutral;
});

const dotColor = computed(() => {
    const map = {
        primary: 'var(--color-primary)',
        secondary: 'var(--color-secondary)',
        tertiary: 'var(--color-tertiary)',
        destructive: 'var(--color-destructive)',
        success: 'rgb(34 197 94)',
        warning: 'rgb(234 179 8)',
        neutral: 'var(--color-muted-foreground)',
        outline: 'var(--color-foreground)',
    };
    return map[props.variant] || map.neutral;
});

const dotSizeClass = computed(() => {
    const map = { xs: 'w-1 h-1', sm: 'w-1.5 h-1.5', md: 'w-2 h-2' };
    return map[props.size];
});
</script>