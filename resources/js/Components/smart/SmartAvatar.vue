<template>
    <div class="inline-flex shrink-0" :class="sizeClass">
        <div
            class="relative rounded-full overflow-hidden bg-muted flex items-center justify-center"
            :class="[sizeClass, borderClass]"
        >
            <!-- Gambar -->
            <img
                v-if="src && !imgError"
                :src="src"
                :alt="name || 'Avatar'"
                class="w-full h-full object-cover"
                @error="imgError = true"
                loading="lazy"
            />

            <!-- Fallback Inisial -->
            <span
                v-else
                class="font-bold select-none"
                :class="[initialClass, size === 'xs' ? 'text-[9px]' : size === 'sm' ? 'text-[11px]' : size === 'md' ? 'text-sm' : size === 'lg' ? 'text-lg' : 'text-2xl']"
            >
                {{ initials }}
            </span>

            <!-- Status indicator -->
            <span
                v-if="status"
                class="absolute rounded-full border-2 border-card ring-1 ring-background"
                :class="[statusPosClass, statusSizeClass, statusColorClass]"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    src: { type: String, default: null },
    name: { type: String, default: '' },
    size: {
        type: String,
        default: 'md',
        validator: v => ['xs', 'sm', 'md', 'lg', 'xl'].includes(v),
    },
    status: {
        type: String,
        default: null,
        validator: v => ['online', 'offline', 'away', 'busy'].includes(v),
    },
    bordered: { type: Boolean, default: false },
});

const imgError = ref(false);

const initials = computed(() => {
    if (!props.name) return '?';
    const parts = props.name.trim().split(/\s+/);
    if (parts.length >= 2) {
        return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
    }
    return props.name.charAt(0).toUpperCase();
});

const sizeClass = computed(() => {
    const map = { xs: 'w-6 h-6', sm: 'w-8 h-8', md: 'w-10 h-10', lg: 'w-12 h-12', xl: 'w-16 h-16' };
    return map[props.size];
});

const borderClass = computed(() => {
    return props.bordered ? 'ring-2 ring-border ring-offset-2 ring-offset-card' : '';
});

const initialClass = computed(() => {
    return 'text-muted-foreground bg-muted';
});

const statusPosClass = computed(() => {
    const map = { xs: '-bottom-px -right-px', sm: '-bottom-px -right-px', md: 'bottom-0 right-0', lg: 'bottom-0.5 right-0.5', xl: 'bottom-1 right-1' };
    return map[props.size];
});

const statusSizeClass = computed(() => {
    const map = { xs: 'w-1.5 h-1.5', sm: 'w-2 h-2', md: 'w-2.5 h-2.5', lg: 'w-3 h-3', xl: 'w-4 h-4' };
    return map[props.size];
});

const statusColorClass = computed(() => {
    const map = {
        online: 'bg-green-500',
        offline: 'bg-muted-foreground',
        away: 'bg-yellow-500',
        busy: 'bg-destructive',
    };
    return map[props.status] || '';
});
</script>