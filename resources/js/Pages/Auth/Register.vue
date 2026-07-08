<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-background px-4 py-12 sm:px-6 lg:px-8 transition-colors duration-300 relative overflow-hidden">
        
        <!-- Background decorative blurs -->
        <div class="absolute top-0 -right-4 w-72 h-72 bg-secondary/10 rounded-full filter blur-3xl opacity-70 animate-pulse"></div>
        <div class="absolute bottom-0 -left-4 w-72 h-72 bg-primary/10 rounded-full filter blur-3xl opacity-70 animate-pulse delay-700"></div>

        <!-- Subtle geometric pattern overlay -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle, var(--color-foreground) 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="w-full max-w-md z-10">
            <div class="absolute top-4 right-4 z-20">
                <LanguageSwitcher />
            </div>
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-3 bg-secondary/10 text-secondary rounded-2xl mb-3 shadow-sm transition-transform duration-300 hover:scale-105">
                    <!-- Ikon user-plus / pendaftaran -->
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black tracking-tight text-foreground">Buat Akun Baru</h2>
                <p class="text-xs font-medium text-muted-foreground mt-1.5">Isi data berikut untuk mendaftar ke dalam sistem.</p>
            </div>

            <!-- Card -->
            <div class="bg-card border border-border p-8 rounded-2xl shadow-md transition-all duration-300 hover:shadow-lg relative overflow-hidden">
                
                <!-- Dekorasi garis emas tipis di atas card -->
                <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-secondary to-transparent"></div>

                <!-- General error banner -->
                <div v-if="generalError" class="mb-4 p-3 bg-destructive/10 border border-destructive/20 text-destructive text-xs font-medium rounded-lg animate-shake">
                    {{ generalError }}
                </div>

                <form @submit.prevent="submit">
                    <!-- Full Name -->
                    <div class="mb-5">
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground/60 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input 
                                id="name"
                                type="text" 
                                v-model="form.name"
                                class="w-full bg-background/50 border text-sm rounded-xl pl-10 pr-4 py-3 text-foreground placeholder-muted-foreground/50 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-card transition-colors duration-200"
                                :class="form.errors.name ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-border'"
                                placeholder="Ahmad Fauzan"
                                required 
                                autofocus
                            />
                        </div>
                        <p v-if="form.errors.name" class="text-xs text-destructive font-medium mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">Email Address</label>
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
                                :class="form.errors.email ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-border'"
                                placeholder="name@company.com"
                                required 
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-destructive font-medium mt-1">{{ form.errors.email }}</p>
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">Password</label>
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
                                placeholder="Minimal 8 karakter"
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

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">Konfirmasi Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground/60 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <input 
                                id="password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'" 
                                v-model="form.password_confirmation"
                                class="w-full bg-background/50 border text-sm rounded-xl pl-10 pr-11 py-3 text-foreground placeholder-muted-foreground/50 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:bg-card transition-colors duration-200"
                                :class="form.errors.password_confirmation ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-border'"
                                placeholder="Ulangi password"
                                required 
                            />
                            <button 
                                type="button"
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted-foreground/60 hover:text-muted-foreground transition-colors duration-200 cursor-pointer"
                                :aria-label="showConfirmPassword ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password'"
                            >
                                <svg v-if="!showConfirmPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="form.errors.password_confirmation" class="text-xs text-destructive font-medium mt-1">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <!-- Submit button -->
                    <div>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-primary text-primary-foreground py-3 px-4 rounded-xl shadow-sm text-sm font-bold tracking-wide transition-all duration-200 flex items-center justify-center space-x-2 active:scale-[0.98] disabled:opacity-50 cursor-pointer hover:opacity-90"
                        >
                            <span v-if="form.processing" class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
                            <span>{{ form.processing ? 'Mendaftarkan...' : 'Daftar Akun' }}</span>
                        </button>
                    </div>
                </form>

                <!-- ── Link ke Login ── -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-muted-foreground">
                        Sudah punya akun?
                        <a href="/login" class="font-bold text-primary hover:text-primary/80 transition-colors duration-200">
                            Masuk di sini
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
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

/**
 * Deteksi error umum (misal email sudah terdaftar)
 * agar ditampilkan sebagai banner, bukan di bawah field.
 */
const generalError = computed(() => {
    if (form.errors.failed) return form.errors.failed;
    const emailErr = form.errors.email;
    if (emailErr) {
        const lower = emailErr.toLowerCase();
        if (
            lower.includes('already been taken') ||
            lower.includes('sudah terdaftar') ||
            lower.includes('already registered')
        ) {
            return emailErr;
        }
    }
    return null;
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
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