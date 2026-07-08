<template>
    <div class="min-h-screen bg-background flex items-center justify-center p-4">
        <div class="w-full max-w-sm">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-primary-foreground mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <h1 class="text-xl font-bold text-foreground">{{ t('auth.forgot_password.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('auth.forgot_password.subtitle') }}</p>
            </div>

            <!-- Status Message -->
            <div v-if="status" class="mb-4 p-3 bg-primary/10 border border-primary/20 rounded-xl text-sm text-primary font-medium">
                {{ status }}
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <InputField
                    v-model="form.email"
                    type="email"
                    :label="t('auth.fields.email')"
                    :placeholder="t('auth.placeholders.email')"
                    :error="form.errors.email"
                    required
                    autofocus
                />

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    {{ t('auth.forgot_password.button') }}
                </button>
            </form>

            <div class="mt-6 text-center">
                <Link href="/login" class="text-sm font-medium text-primary hover:underline">
                    &larr; {{ t('auth.forgot_password.back_to_login') }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import InputField from '@/Components/ui/InputField.vue';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    status: { type: String, default: null },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password', {
        onFinish: () => form.reset('email'),
    });
};
</script>