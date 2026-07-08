<template>
    <div class="flex items-center -space-x-2">
        <div 
            v-for="(user, index) in displayedUsers" 
            :key="user.email || index"
            :class="[
                'relative rounded-full border-2 border-card overflow-hidden transition-transform hover:z-10 hover:scale-110',
                sizeClasses
            ]"
        >
            <img v-if="user.avatar" :src="user.avatar" :alt="user.name" class="w-full h-full object-cover" />
            <div v-else :class="['w-full h-full flex items-center justify-center font-bold text-white', getBgColor(index)]">
                {{ getInitials(user.name) }}
            </div>
        </div>

        <div 
            v-if="remaining > 0"
            :class="[
                'relative rounded-full border-2 border-card bg-muted flex items-center justify-center font-bold text-muted-foreground',
                sizeClasses
            ]"
        >
            <span :class="textSizeClass">+{{ remaining }}</span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    users: { type: Array, default: () => [] },
    max: { type: Number, default: 4 },
    size: { type: String, default: 'md', validator: v => ['sm', 'md', 'lg'].includes(v) },
});

const bgColors = [
    'bg-primary', 'bg-secondary', 'bg-tertiary', 'bg-destructive', 'bg-chart-4', 'bg-chart-5'
];

const displayedUsers = computed(() => props.users.slice(0, props.max));
const remaining = computed(() => props.users.length - props.max);

const sizeClasses = computed(() => {
    const map = { sm: 'w-6 h-6', md: 'w-8 h-8', lg: 'w-10 h-10' };
    return map[props.size];
});

const textSizeClass = computed(() => {
    const map = { sm: 'text-[9px]', md: 'text-[10px]', lg: 'text-xs' };
    return map[props.size];
});

const getInitials = (name) => {
    if (!name) return '?';
    const parts = name.split(' ').filter(Boolean);
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
};

// Fix: Pisahkan operasi modulo ke function agar tidak error di template
const getBgColor = (index) => {
    return bgColors[index % bgColors.length];
};
</script>