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
            <div v-if="state.show" class="modal-portal fixed inset-0 z-[60]">
                <!-- Backdrop -->
                <div class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-[2px]" @click="close"></div>

                <!-- Content -->
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
                            v-if="state.show"
                            class="modal-content bg-card rounded-xl shadow-2xl border border-border w-full sm:max-w-sm transform"
                        >
                            <!-- Icon Variant -->
                            <div class="flex justify-center pt-6 sm:pt-7">
                                <div :class="['w-14 h-14 rounded-full flex items-center justify-center', iconWrapperClass]">
                                    <component :is="iconComponent" :class="['w-7 h-7', iconClass]" />
                                </div>
                            </div>

                            <!-- Judul & Pesan -->
                            <div class="px-5 sm:px-6 pt-4 sm:pt-5 pb-2 text-center">
                                <h3 class="text-base sm:text-lg font-bold text-foreground">{{ state.title }}</h3>
                                <p class="text-sm text-muted-foreground mt-2 leading-relaxed">{{ state.message }}</p>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-center gap-2 sm:gap-3 px-5 sm:px-6 py-4 sm:py-5 border-t border-border bg-muted/30 rounded-b-xl">
                                <button
                                    @click="close"
                                    class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-semibold text-foreground bg-muted rounded-lg hover:bg-muted/80 transition-colors cursor-pointer order-2 sm:order-1"
                                >
                                    {{ t('ui.actions.cancel') }}
                                </button>
                                <button
                                    @click="confirm"
                                    :disabled="state.loading"
                                    :class="[
                                        'w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-semibold rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer order-1 sm:order-2',
                                        buttonClass
                                    ]"
                                >
                                    <span v-if="state.loading" class="inline-flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        {{ t('ui.actions.processing') }}
                                    </span>
                                    <span v-else>{{ state.confirmText }}</span>
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, watch } from 'vue';
import { useI18n } from '@/Composables/useI18n';
import { useConfirm } from '@/Composables/useConfirm';
import IconTriangleAlert from '~icons/lucide/triangle-alert';
import IconInfo from '~icons/lucide/info';

const { t } = useI18n();
const { state, confirm, close } = useConfirm();

// Mapping objek component (bukan string)
const iconComponent = computed(() => {
    const icons = {
        destructive: IconTriangleAlert,
        warning: IconTriangleAlert,
        info: IconInfo,
    };
    return icons[state.variant] || IconTriangleAlert;
});

const iconWrapperClass = computed(() => {
    const classes = {
        destructive: 'bg-destructive/10',
        warning: 'bg-secondary/10',
        info: 'bg-primary/10',
    };
    return classes[state.variant] || classes.destructive;
});

const iconClass = computed(() => {
    const classes = {
        destructive: 'text-destructive',
        warning: 'text-secondary',
        info: 'text-primary',
    };
    return classes[state.variant] || classes.destructive;
});

const buttonClass = computed(() => {
    const classes = {
        destructive: 'bg-destructive text-destructive-foreground',
        warning: 'bg-secondary text-secondary-foreground',
        info: 'bg-primary text-primary-foreground',
    };
    return classes[state.variant] || classes.destructive;
});

watch(() => state.show, (value) => {
    document.body.style.overflow = value ? 'hidden' : null;
});
</script>