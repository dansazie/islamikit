<template>
    <Teleport to="body">
        <!-- Backdrop -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm"
                @click="close"
            />
        </Transition>

        <!-- Drawer Panel -->
        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <div
                v-if="modelValue"
                class="fixed top-0 right-0 z-50 h-full w-full sm:w-96 bg-card border-l border-border shadow-2xl flex flex-col"
                role="dialog"
                aria-modal="true"
            >
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-border shrink-0">
                    <h2 class="text-base font-bold text-foreground truncate">
                        <slot name="title">{{ title }}</slot>
                    </h2>
                    <button
                        @click="close"
                        class="p-1.5 rounded-lg text-muted-foreground hover:bg-muted hover:text-foreground transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto custom-scrollbar p-4">
                    <slot />
                </div>

                <!-- Footer (Optional) -->
                <div v-if="$slots.footer" class="p-4 border-t border-border shrink-0">
                    <slot name="footer" />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';

// PERBAIKAN: Mengubah 'show' menjadi 'modelValue' agar kompatibel dengan v-model
const props = defineProps({
    modelValue: { type: Boolean, default: false },
    title: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue', 'close']);

const close = () => {
    emit('update:modelValue', false);
    emit('close');
};

const handleEscape = (e) => {
    if (e.key === 'Escape' && props.modelValue) close();
};

onMounted(() => document.addEventListener('keydown', handleEscape));
onUnmounted(() => document.removeEventListener('keydown', handleEscape));
</script>