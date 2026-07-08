<template>
    <div class="fixed top-0 left-0 right-0 z-50 bg-amber-500 dark:bg-amber-600 text-amber-950 dark:text-amber-50">
        <div class="flex items-center justify-center gap-3 px-4 py-2 text-sm font-semibold">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            <span>{{ t('ui.impersonate.banner_text', { name: impersonating.name }) }}</span>
            <button
                @click="stopImpersonate"
                :disabled="isStopping"
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-950/20 hover:bg-amber-950/30 dark:bg-amber-50/20 dark:hover:bg-amber-50/30 rounded-md text-xs font-bold transition-colors cursor-pointer"
            >
                <svg v-if="isStopping" class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                {{ t('ui.impersonate.stop_button') }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    impersonating: { type: Object, required: true },
});

const isStopping = ref(false);

const stopImpersonate = () => {
    isStopping.value = true;
    router.post('/impersonate/stop', {}, {
        onFinish: () => { isStopping.value = false; },
    });
};
</script>