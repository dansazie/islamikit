<template>
    <component
        :is="href ? 'a' : 'button'"
        :href="href"
        :type="href ? undefined : nativeType"
        :disabled="disabled || loading"
        :class="[
            'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-200 cursor-pointer select-none whitespace-nowrap focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:pointer-events-none disabled:opacity-50',
            variantClasses,
            sizeClasses,
            roundedClasses,
        ]"
    >
        <!-- Slot Icon Kiri -->
        <span v-if="$slots.icon && iconPosition === 'left'" :class="['shrink-0', iconSizeClasses]">
            <slot name="icon" />
        </span>

        <!-- Spinner Loading -->
        <svg v-if="loading" :class="['animate-spin shrink-0', iconSizeClasses]" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>

        <!-- Label -->
        <slot />
        
        <!-- Slot Icon Kanan -->
        <span v-if="$slots.icon && iconPosition === 'right'" :class="['shrink-0', iconSizeClasses]">
            <slot name="icon" />
        </span>
    </component>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: v => ['primary', 'secondary', 'destructive', 'outline', 'ghost', 'link'].includes(v)
    },
    size: {
        type: String,
        default: 'md',
        validator: v => ['xs', 'sm', 'md', 'lg', 'xl'].includes(v)
    },
    rounded: {
        type: String,
        default: 'lg',
        validator: v => ['none', 'sm', 'md', 'lg', 'xl', 'full'].includes(v)
    },
    iconPosition: {
        type: String,
        default: 'left',
        validator: v => ['left', 'right'].includes(v)
    },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    href: { type: String, default: null },
    nativeType: { type: String, default: 'button' },
});

const variantClasses = computed(() => {
    const map = {
        primary: 'bg-primary text-primary-foreground hover:opacity-90 shadow-sm',
        secondary: 'bg-secondary text-secondary-foreground hover:opacity-90 shadow-sm',
        destructive: 'bg-destructive text-destructive-foreground hover:opacity-90 shadow-sm',
        outline: 'border border-border bg-transparent text-foreground hover:bg-muted',
        ghost: 'bg-transparent text-foreground hover:bg-muted',
        link: 'text-primary underline-offset-4 hover:underline bg-transparent shadow-none p-0 h-auto',
    };
    return map[props.variant];
});

const sizeClasses = computed(() => {
    if (props.variant === 'link') return '';
    const map = {
        xs: 'px-2.5 py-1 text-[11px]',
        sm: 'px-3 py-1.5 text-xs',
        md: 'px-4 py-2 text-sm',
        lg: 'px-5 py-2.5 text-base',
        xl: 'px-6 py-3 text-lg',
    };
    return map[props.size];
});

const roundedClasses = computed(() => {
    if (props.variant === 'link') return '';
    const map = {
        none: 'rounded-none',
        sm: 'rounded-sm',
        md: 'rounded-md',
        lg: 'rounded-lg',
        xl: 'rounded-xl',
        full: 'rounded-full',
    };
    return map[props.rounded];
});

const iconSizeClasses = computed(() => {
    const map = {
        xs: 'w-3 h-3',
        sm: 'w-3.5 h-3.5',
        md: 'w-4 h-4',
        lg: 'w-5 h-5',
        xl: 'w-5 h-5',
    };
    return map[props.size];
});
</script>