<template>
    <nav aria-label="Breadcrumb" class="flex items-center">
        <ol class="flex items-center gap-1.5 text-xs sm:text-sm font-medium overflow-hidden">
            <template v-for="(item, index) in items" :key="item.path">
                <!-- Separator -->
                <li v-if="index > 0 && !(isMobile && isHiddenMiddle(index))" class="shrink-0 text-muted-foreground">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </li>

                <!-- Mobile Ellipsis -->
                <li v-if="isMobile && isHiddenMiddle(index)" class="shrink-0 text-muted-foreground">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                </li>

                <!-- Item -->
                <li 
                    v-else-if="!(isMobile && isHiddenMiddle(index))"
                    :class="[isLast(index) ? 'text-foreground font-semibold' : 'text-muted-foreground hover:text-foreground']"
                >
                    <InertiaLink 
                        v-if="!isLast(index) && item.path" 
                        :href="item.path"
                        class="transition-colors truncate max-w-[120px] sm:max-w-none block"
                    >
                        {{ item.label }}
                    </InertiaLink>
                    <span v-else class="truncate max-w-[150px] sm:max-w-none block" :aria-current="isLast(index) ? 'page' : undefined">
                        {{ item.label }}
                    </span>
                </li>
            </template>
        </ol>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link as InertiaLink } from '@inertiajs/vue3';

const props = defineProps({
    items: { type: Array, required: true },
});

const isMobile = ref(false);
let mql = null;

onMounted(() => {
    mql = window.matchMedia('(max-width: 639px)');
    isMobile.value = mql.matches;
    mql.addEventListener('change', (e) => { isMobile.value = e.matches; });
});

onUnmounted(() => { 
    if (mql) mql.removeEventListener('change', () => {}); 
});

const isLast = (index) => index === props.items.length - 1;

const isHiddenMiddle = (index) => {
    const length = props.items.length;
    return length > 2 && index !== 0 && index !== length - 1;
};
</script>