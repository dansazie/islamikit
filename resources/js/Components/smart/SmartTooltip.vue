<template>
    <div class="relative inline-flex">
        <!-- Trigger -->
        <div class="group/trig relative">
            <slot />
        </div>

        <!-- Tooltip Content -->
        <div
            class="absolute z-50 pointer-events-none opacity-0 group-hover/trig:opacity-100 transition-opacity duration-150 whitespace-nowrap"
            :class="positionClass"
        >
            <div class="px-2.5 py-1.5 text-[11px] font-semibold rounded-lg shadow-lg"
                 :class="themeClass">
                <slot name="content">
                    {{ text }}
                </slot>
            </div>
            <!-- Arrow -->
            <div class="absolute inset-x-0 mx-auto h-0 w-0 overflow-hidden" :class="arrowClass">
                <div class="w-2 h-2 origin-center rotate-45 -translate-x-1/2" :class="[themeClass, arrowPosClass]" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    text: { type: String, default: '' },
    position: {
        type: String,
        default: 'top',
        validator: v => ['top', 'bottom', 'left', 'right'].includes(v),
    },
    theme: {
        type: String,
        default: 'dark',
        validator: v => ['dark', 'light'].includes(v),
    },
});

const themeClass = computed(() => {
    return props.theme === 'dark' 
        ? 'bg-foreground text-background' 
        : 'bg-popover text-popover-foreground border border-border';
});

const positionClass = computed(() => {
    const map = {
        top: 'bottom-full left-1/2 -translate-x-1/2 mb-2',
        bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
        left: 'right-full top-1/2 -translate-y-1/2 mr-2',
        right: 'left-full top-1/2 -translate-y-1/2 ml-2',
    };
    return map[props.position];
});

const arrowClass = computed(() => {
    const map = {
        top: 'top-full -mt-1',
        bottom: 'bottom-full -mb-1',
        left: 'left-full -ml-1 top-1/2 -translate-y-1/2',
        right: 'right-full -mr-1 top-1/2 -translate-y-1/2',
    };
    return map[props.position];
});

const arrowPosClass = computed(() => {
    const map = {
        top: '-translate-y-1/2 top-0',
        bottom: 'translate-y-1/2 bottom-0',
        left: '-translate-x-1/2 left-0',
        right: 'translate-x-1/2 right-0',
    };
    return map[props.position];
});
</script>