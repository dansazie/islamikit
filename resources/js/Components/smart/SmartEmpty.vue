<template>
    <div
        class="flex flex-col items-center justify-center text-center px-4"
        :class="[paddingClass, fullWidth ? 'w-full' : '']"
    >
        <!-- Icon -->
        <div
            class="rounded-full flex items-center justify-center mb-4"
            :class="[iconSizeClass, iconBgClass]"
        >
            <slot name="icon">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" :class="iconColorClass" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
            </slot>
        </div>

        <!-- Title -->
        <h3 v-if="title" class="text-sm sm:text-base font-bold text-foreground mb-1.5">
            {{ title }}
        </h3>

        <!-- Description -->
        <p v-if="description" class="text-xs sm:text-sm text-muted-foreground max-w-xs leading-relaxed">
            {{ description }}
        </p>

        <!-- Action -->
        <div v-if="$slots.action" class="mt-4">
            <slot name="action" />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    size: {
        type: String,
        default: 'md',
        validator: v => ['sm', 'md', 'lg'].includes(v),
    },
    variant: {
        type: String,
        default: 'muted',
        validator: v => ['muted', 'primary', 'secondary', 'destructive'].includes(v),
    },
    fullWidth: { type: Boolean, default: true },
});

const paddingClass = computed(() => {
    const map = { sm: 'py-6', md: 'py-10', lg: 'py-16' };
    return map[props.size];
});

const iconSizeClass = computed(() => {
    const map = { sm: 'w-10 h-10 sm:w-12 sm:h-12', md: 'w-14 h-14', lg: 'w-20 h-20' };
    return map[props.size];
});

const iconBgClass = computed(() => {
    const map = {
        muted: 'bg-muted',
        primary: 'bg-primary/10',
        secondary: 'bg-secondary/10',
        destructive: 'bg-destructive/10',
    };
    return map[props.variant];
});

const iconColorClass = computed(() => {
    const map = {
        muted: 'text-muted-foreground',
        primary: 'text-primary',
        secondary: 'text-secondary',
        destructive: 'text-destructive',
    };
    return map[props.variant];
});
</script>