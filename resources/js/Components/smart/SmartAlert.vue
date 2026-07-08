<template>
    <Transition
        enter-active-class="transition-all duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="show"
            class="flex gap-3 p-3 sm:p-4 rounded-xl border"
            :class="[variantWrapperClass, bordered ? '' : 'border-transparent']"
            role="alert"
        >
            <!-- Icon -->
            <div class="shrink-0 mt-0.5">
                <slot name="icon">
                    <svg class="w-5 h-5" :class="variantIconClass" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path v-if="variant === 'destructive'" stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </slot>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h4 v-if="title" class="text-sm font-bold" :class="variantTextClass">
                    {{ title }}
                </h4>
                <div v-if="$slots.default || description" class="mt-1 text-xs sm:text-sm leading-relaxed" :class="variantMutedTextClass">
                    <slot>
                        <p>{{ description }}</p>
                    </slot>
                </div>
            </div>

            <!-- Dismiss Button -->
            <button
                v-if="dismissible"
                @click="show = false"
                class="shrink-0 p-1 rounded-lg transition-colors cursor-pointer hover:bg-black/5 dark:hover:bg-white/5"
                :class="variantMutedTextClass"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'info',
        validator: v => ['info', 'success', 'warning', 'destructive', 'muted'].includes(v),
    },
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    dismissible: { type: Boolean, default: false },
    bordered: { type: Boolean, default: true },
});

const show = ref(true);

const variantWrapperClass = computed(() => {
    const map = {
        info: 'bg-primary/5 border-primary/20',
        success: 'bg-green-500/5 border-green-500/20',
        warning: 'bg-yellow-500/5 border-yellow-500/20',
        destructive: 'bg-destructive/5 border-destructive/20',
        muted: 'bg-muted border-border',
    };
    return map[props.variant];
});

const variantIconClass = computed(() => {
    const map = {
        info: 'text-primary',
        success: 'text-green-500',
        warning: 'text-yellow-500',
        destructive: 'text-destructive',
        muted: 'text-muted-foreground',
    };
    return map[props.variant];
});

const variantTextClass = computed(() => {
    const map = {
        info: 'text-primary',
        success: 'text-green-600 dark:text-green-400',
        warning: 'text-yellow-600 dark:text-yellow-400',
        destructive: 'text-destructive',
        muted: 'text-foreground',
    };
    return map[props.variant];
});

const variantMutedTextClass = computed(() => {
    const map = {
        info: 'text-primary/80',
        success: 'text-green-600/80 dark:text-green-400/80',
        warning: 'text-yellow-600/80 dark:text-yellow-400/80',
        destructive: 'text-destructive/80',
        muted: 'text-muted-foreground',
    };
    return map[props.variant];
});
</script>