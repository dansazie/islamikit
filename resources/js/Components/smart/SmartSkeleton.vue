<template>
    <div 
        class="animate-pulse rounded-md bg-muted"
        :class="[baseClass, shapeClass]"
        :style="{ width: width, height: height }"
    />
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'text',
        validator: v => ['text', 'circle', 'rect', 'card'].includes(v),
    },
    width: { type: String, default: '100%' },
    height: { type: String, default: '' },
    lines: { type: Number, default: 3 },
});

const shapeClass = computed(() => {
    switch (props.type) {
        case 'circle': return 'rounded-full';
        case 'rect': return 'rounded-xl';
        case 'card': return 'rounded-xl p-4';
        default: return 'rounded-md';
    }
});

const baseClass = computed(() => {
    if (props.type === 'text') return 'h-4';
    if (props.type === 'circle') return 'aspect-square';
    if (props.type === 'card') return 'flex flex-col gap-3';
    return '';
});
</script>