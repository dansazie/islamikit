<template>
    <component :is="currentIcon" class="w-5 h-5 transition-colors duration-200" aria-hidden="true" />
</template>

<script setup>
import { computed, h } from 'vue';

const props = defineProps({
    name: {
        type: String,
        default: 'Layers'
    }
});

// Pemetaan string database ke bentuk SVG Path (Standar Lucide)
const currentIcon = computed(() => {
    const icons = {
        'Dashboard': h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
            h('rect', { width: '7', height: '9', x: '3', y: '3', rx: '1' }),
            h('rect', { width: '7', height: '5', x: '14', y: '3', rx: '1' }),
            h('rect', { width: '7', height: '9', x: '14', y: '10', rx: '1' }),
            h('rect', { width: '7', height: '5', x: '3', y: '14', rx: '1' }),
        ]),
        'Users': h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
            h('path', { d: 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2' }),
            h('circle', { cx: '9', cy: '7', r: '4' }),
            h('path', { d: 'M22 21v-2a4 4 0 0 0-3-3.87' }),
            h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' }),
        ]),
        'Settings': h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
            h('path', { d: 'M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z' }),
            h('circle', { cx: '12', cy: '12', r: '3' }),
        ]),
        // Fallback Icon (Layers)
        'Layers': h('svg', { xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, [
            h('path', { d: 'm12 3-10 5 10 5 10-5-10-5Z' }),
            h('path', { d: 'm2 17 10 5 10-5' }),
            h('path', { d: 'm2 12 10 5 10-5' }),
        ])
    };

    return icons[props.name] || icons['Layers'];
});
</script>