<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-background px-4 py-12 sm:px-6 lg:px-8 transition-colors duration-300 relative overflow-hidden">
        
        <!-- Background decorative blurs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-primary/10 rounded-full filter blur-3xl opacity-70 animate-pulse"></div>
        <div class="absolute bottom-0 -right-4 w-72 h-72 bg-secondary/10 rounded-full filter blur-3xl opacity-70 animate-pulse delay-700"></div>

        <!-- Subtle geometric pattern overlay -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle, var(--color-foreground) 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="w-full max-w-md z-10">
            <div class="absolute top-4 right-4 z-20">
                <LanguageSwitcher />
            </div>
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-primary/10 text-primary rounded-2xl mb-3 shadow-sm transition-transform duration-300 hover:scale-105">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3c-4 3-7 7.5-7 12h14c0-4.5-3-9-7-12z" />
                        <path d="M12 3v4" />
                        <path d="M8 7h8" />
                        <rect x="5" y="15" width="14" height="6" rx="0.5" />
                        <path d="M9 15v-2a1 1 0 011-1h4a1 1 0 011 1v2" />
                        <line x1="12" y1="18" x2="12" y2="19.5" />
                        <circle cx="12" cy="18.5" r="0.5" fill="currentColor" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black tracking-tight text-foreground">{{ t('auth.login.greeting') }}</h2>
                <p class="text-xs font-medium text-muted-foreground mt-1.5">{{ t('auth.login.subtitle') }}</p>
            </div>

            <!-- Card -->
            <div class="bg-card border border-border p-8 rounded-2xl shadow-md transition-all duration-300 hover:shadow-lg relative overflow-hidden">
                
                <!-- Dekorasi garis hijau tipis di atas card -->
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-primary to-transparent"></div>

                <!-- Status message -->
                <div v-if="status" class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-xs font-medium rounded-lg">
                    {{ status }}
                </div>

                <!-- General error banner -->
                <div v-if="generalError" class="mb-4 p-3 bg-destructive/10 border border-destructive/20 text-destructive text-xs font-medium rounded-lg animate-shake">
                    {{ generalError }}
                </div>

                <form @submit.prevent="submit">
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">{{ t('auth.login.email') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground/60 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </span>
                            <input 
                                id="email"
                                type="email" 
                                v-model="form.email"
                                class="w-full bg-background/50 border text-sm rounded-xl pl-10 pr-4 py-3 text-foreground placeholder-muted-foreground/50 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-card transition-colors duration-200"
                                :class="emailError ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-border'"
                                placeholder="name@company.com"
                                required 
                                autofocus
                            />
                        </div>
                        <p v-if="emailError" class="text-xs text-destructive font-medium mt-1">{{ emailError }}</p>
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground">{{ t('auth.login.password') }}</label>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground/60 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input 
                                id="password"
                                :type="showPassword ? 'text' : 'password'" 
                                v-model="form.password"
                                class="w-full bg-background/50 border text-sm rounded-xl pl-10 pr-11 py-3 text-foreground placeholder-muted-foreground/50 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-card transition-colors duration-200"
                                :class="form.errors.password ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-border'"
                                placeholder="••••••••"
                                required 
                            />
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted-foreground/60 hover:text-muted-foreground transition-colors duration-200 cursor-pointer"
                                :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                            >
                                <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-destructive font-medium mt-1">{{ form.errors.password }}</p>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center group cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                v-model="form.remember"
                                class="rounded border-border text-primary shadow-sm focus:ring-primary focus:ring-offset-background cursor-pointer"
                            />
                            <span class="ml-2.5 text-xs font-semibold text-muted-foreground group-hover:text-foreground transition-colors duration-200">
                                {{ t('auth.login.remember') }}
                            </span>
                        </label>
                    </div>

                    <!-- Submit button -->
                    <div>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-primary text-primary-foreground py-3 px-4 rounded-xl shadow-sm text-sm font-bold tracking-wide transition-all duration-200 flex items-center justify-center space-x-2 active:scale-[0.98] disabled:opacity-50 cursor-pointer hover:opacity-90"
                        >
                            <span v-if="form.processing" class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
                            <span>{{ form.processing ? t('auth.login.submitting') : t('auth.login.submit') }}</span>
                        </button>
                    </div>
                </form>

                <!-- ── Link ke Register ── -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-muted-foreground">
                        {{ t('auth.login.no_account') }}
                        <a href="/register" class="font-bold text-primary hover:text-primary/80 transition-colors duration-200">
                            {{ t('auth.login.register_link') }}
                        </a>
                    </p>
                </div>
            </div>

            <p class="text-center text-[10px] font-medium text-muted-foreground/60 mt-8 tracking-wider uppercase">
                &copy; {{ new Date().getFullYear() }} IslamicKit Management System &bull; All rights reserved.
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import LanguageSwitcher from '@/Components/ui/LanguageSwitcher.vue';
import { useI18n } from '@/Composables/useI18n';

const { t } = useI18n();

const page = usePage();
const showPassword = ref(false);

const status = computed(() => page.props.status);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function isCredentialError(message) {
    if (!message) return false;
    const lower = message.toLowerCase();
    return (
        lower.includes('credentials') ||
        lower.includes('do not match') ||
        lower.includes('tidak cocok') ||
        lower.includes('salah')
    );
}

const generalError = computed(() => {
    if (form.errors.failed) return form.errors.failed;
    const emailErr = form.errors.email;
    if (emailErr && isCredentialError(emailErr)) return emailErr;
    return null;
});

const emailError = computed(() => {
    const emailErr = form.errors.email;
    if (emailErr && !isCredentialError(emailErr)) return emailErr;
    return null;
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
.animate-shake {
    animation: shake 0.4s ease-in-out;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}
</style>