<template>
    <div class="min-h-screen bg-background flex items-center justify-center p-4">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary text-primary-foreground mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                </div>
                <h1 class="text-xl font-bold text-foreground">{{ t('auth.reset_password.title') }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ t('auth.reset_password.subtitle') }}</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <InputField
                    v-model="form.email"
                    type="email"
                    :label="t('auth.fields.email')"
                    :placeholder="t('auth.placeholders.email')"
                    :error="form.errors.email"
                    required
                />

                <InputField
                    v-model="form.password"
                    type="password"
                    :label="t('auth.fields.password')"
                    :placeholder="t('auth.placeholders.password')"
                    :error="form.errors.password"
                    required
                />

                <InputField
                    v-model="form.password_confirmation"
                    type="password"
                    :label="t('auth.fields.password_confirmation')"
                    :placeholder="t('auth.placeholders.password_confirmation')"
                    :error="form.errors.password_confirmation"
                    required
                />

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer"
                >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    {{ t('auth.reset_password.button') }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import InputField from '@/Components/ui/InputField.vue';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();

const props = defineProps({
    token: { type: String, required: true },
    email: { type: String, required: true },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>