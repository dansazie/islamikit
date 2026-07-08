<template>
    <div class="min-h-screen bg-background flex items-center justify-center p-4">
        <div class="text-center max-w-md">
            <div class="mx-auto w-16 h-16 rounded-full bg-destructive/10 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-destructive" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            </div>
            <p class="text-6xl sm:text-7xl font-black text-destructive/20 select-none">500</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-foreground">{{ t('errors.500.title') }}</h1>
            <p class="mt-3 text-sm text-muted-foreground leading-relaxed">{{ t('errors.500.description') }}</p>
            <div class="mt-8">
                <button
                    @click="router.visit('/dashboard')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    {{ t('errors.go_dashboard') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();
</script>