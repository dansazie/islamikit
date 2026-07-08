<template>
    <Dropdown width="40">
        <template #trigger>
            <button
                class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 py-1.5 sm:py-2 text-sm font-medium rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors cursor-pointer"
                :title="'Language'"
            >
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364V3M3 11.25h4.5m-4.5 0a48.667 48.667 0 017.5 0M3 17.25h4.5m-4.5 0a48.667 48.667 0 017.5 0M12 17.25V11.25m0 5.898c1.263.208 2.55.342 3.864.398m-.398-6.046a48.32 48.32 0 00-3.864-.398m3.864.398L12 11.25" />
                </svg>
                <span class="hidden sm:inline uppercase text-xs font-bold">{{ locale }}</span>
                <svg class="w-3.5 h-3.5 shrink-0 hidden sm:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </template>

        <template #content>
            <!-- ✅ PENAMBAHAN DIV WRAPPER DI SINI -->
            <div class="py-1">
                <form
                    v-for="(label, code) in availableLocales"
                    :key="code"
                    @submit.prevent="switchLocale(code)"
                >
                    <button
                        type="submit"
                        class="flex items-center justify-between w-full px-3 sm:px-4 py-2 text-left text-sm transition-colors cursor-pointer"
                        :class="code === locale
                            ? 'text-primary bg-primary/10 font-semibold'
                            : 'text-popover-foreground hover:bg-muted'"
                    >
                        <span>{{ label }}</span>
                        <svg
                            v-if="code === locale"
                            class="w-4 h-4 text-primary"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </button>
                </form>
            </div>
        </template>
    </Dropdown>
</template>

<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

// ✅ GANTI KE RELATIVE PATH (Karena Dropdown ada di folder yang sama)
import Dropdown from './Dropdown.vue'; 

// ✅ GANTI KE RELATIVE PATH (Naik 3 folder ke js/, lalu masuk Composables/)
import { useI18n } from '@/Composables/useI18n';

const { locale, availableLocales } = useI18n();

function switchLocale(code) {
    router.post('/locale', { locale: code }, {
        preserveScroll: true,
        preserveState: false,
    });
}
</script>