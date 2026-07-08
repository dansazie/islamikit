<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="modal-portal fixed inset-0 z-50">
                <!-- Backdrop -->
                <div class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-[2px]" @click="close"></div>

                <!-- Content wrapper -->
                <div class="modal-wrapper relative z-10 flex min-h-full items-center justify-center p-4 sm:p-6">
                    <Transition
                        enter-active-class="ease-out duration-300"
                        enter-from-class="modal-content-enter opacity-0 sm:scale-95"
                        enter-to-class="modal-content-enter-to opacity-100 sm:scale-100"
                        leave-active-class="ease-in duration-200"
                        leave-from-class="opacity-100 sm:scale-100"
                        leave-to-class="modal-content-leave-to opacity-0 sm:scale-95"
                    >
                        <div
                            v-if="show"
                            class="modal-content bg-card rounded-xl shadow-2xl border border-border w-full sm:max-w-md md:max-w-lg transform"
                        >
                            <!-- Header -->
                            <div class="flex justify-between items-center px-4 sm:px-6 py-3.5 sm:py-4 border-b border-border">
                                <h3 class="text-base sm:text-lg font-bold text-foreground pr-4">
                                    <slot name="title" />
                                </h3>
                                <button
                                    @click="close"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer shrink-0"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <div class="px-4 sm:px-6 py-4 sm:py-5 text-sm text-muted-foreground max-h-[60vh] sm:max-h-[70vh] overflow-y-auto custom-scrollbar leading-relaxed">
                                <slot name="body" />
                            </div>

                            <!-- Footer -->
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 px-4 sm:px-6 py-3.5 sm:py-4 border-t border-border bg-muted/30 rounded-b-xl">
                                <slot name="footer" />
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({ show: { type: Boolean, default: false } });
const emit = defineEmits(['close']);
const close = () => emit('close');

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) close();
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

watch(() => props.show, (value) => {
    document.body.style.overflow = value ? 'hidden' : null;
});
</script>