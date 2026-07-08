<template>
    <div class="relative">
        <div @click="open = !open" class="cursor-pointer">
            <slot name="trigger" />
        </div>

        <!-- Click-away overlay -->
        <div v-if="open" @click="open = false" class="fixed inset-0 z-40"></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="open"
                class="absolute right-0 mt-2 rounded-xl shadow-xl z-50 bg-popover border border-border focus:outline-none overflow-hidden"
                :class="widthClass"
                @click="open = false"
            >
                <slot name="content" />
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    width: { type: String, default: '48' },
});

const open = ref(false);

const widthClass = computed(() => ({
    '48': 'w-48',
    '52': 'w-52',
    '56': 'w-56',
    '64': 'w-64',
}[props.width] || 'w-48'));
</script>