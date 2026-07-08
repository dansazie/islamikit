<template>
    <Teleport to="body">
        <div class="toast-portal fixed bottom-4 right-4 sm:bottom-5 sm:right-5 z-50 flex flex-col space-y-2 max-w-sm w-full sm:w-auto">
            <TransitionGroup
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <!-- Success -->
                <div v-if="message" class="toast-item bg-card border border-border shadow-lg rounded-xl p-3 sm:p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-foreground">{{ t('ui.toast.success_title') }}</p>
                        <p class="text-xs text-muted-foreground mt-0.5 break-words leading-relaxed">{{ message }}</p>
                    </div>
                    <button @click="closeMessage" class="text-muted-foreground hover:text-foreground transition-colors cursor-pointer shrink-0 p-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Error -->
                <div v-if="error" class="toast-item bg-card border border-border shadow-lg rounded-xl p-3 sm:p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-destructive/10 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-destructive" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-foreground">{{ t('ui.toast.error_title') }}</p>
                        <p class="text-xs text-muted-foreground mt-0.5 break-words leading-relaxed">{{ error }}</p>
                    </div>
                    <button @click="closeError" class="text-muted-foreground hover:text-foreground transition-colors cursor-pointer shrink-0 p-0.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();

const page = usePage();
const message = ref('');
const error = ref('');
let messageTimeout = null;
let errorTimeout = null;

const closeMessage = () => { message.value = ''; };
const closeError = () => { error.value = ''; };

watch(() => page.props.flash, (newFlash) => {
    if (newFlash?.message) {
        message.value = newFlash.message;
        if (messageTimeout) clearTimeout(messageTimeout);
        messageTimeout = setTimeout(() => { message.value = ''; }, 4000);
    }
    if (newFlash?.error) {
        error.value = newFlash.error;
        if (errorTimeout) clearTimeout(errorTimeout);
        errorTimeout = setTimeout(() => { error.value = ''; }, 5000);
    }
}, { deep: true, immediate: true });
</script>