<template>
    <div class="w-full">
        <!-- Header: Label & Percentage -->
        <div v-if="showLabel" class="flex items-center justify-between mb-1.5">
            <span class="text-xs font-semibold text-foreground truncate">{{ label }}</span>
            <span class="text-xs font-bold tabular-nums" :class="percentage > 0 ? 'text-foreground' : 'text-muted-foreground'">
                {{ percentage }}%
            </span>
        </div>

        <!-- Track -->
        <div 
            class="w-full h-2 bg-muted rounded-full overflow-hidden"
            :class="size === 'lg' ? 'h-3' : size === 'sm' ? 'h-1.5' : 'h-2'"
        >
            <!-- Fill -->
            <div
                class="h-full rounded-full transition-all duration-500 ease-out"
                :class="[variantClass, animated ? 'animate-pulse' : '']"
                :style="{ width: `${Math.min(100, Math.max(0, percentage))}%` }"
            />
        </div>
        
        <!-- Helper Text -->
        <p v-if="description" class="text-[11px] text-muted-foreground mt-1.5 truncate">
            {{ description }}
        </p>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    percentage: { type: Number, default: 0 },
    label: { type: String, default: '' },
    description: { type: String, default: '' },
    variant: {
        type: String,
        default: 'primary',
        validator: v => ['primary', 'secondary', 'tertiary', 'destructive', 'success', 'warning'].includes(v),
    },
    size: {
        type: String,
        default: 'md',
        validator: v => ['sm', 'md', 'lg'].includes(v),
    },
    showLabel: { type: Boolean, default: true },
    animated: { type: Boolean, default: false }, // true jika sedang loading/proses
});

const variantClass = computed(() => {
    const map = {
        primary: 'bg-primary',
        secondary: 'bg-secondary',
        tertiary: 'bg-tertiary',
        destructive: 'bg-destructive',
        success: 'bg-green-500',
        warning: 'bg-yellow-500',
    };
    return map[props.variant] || map.primary;
});
</script>