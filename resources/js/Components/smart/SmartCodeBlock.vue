<template>
    <div class="relative group rounded-xl border border-border bg-muted/30 overflow-hidden">
        <!-- Header Bar -->
        <div class="flex items-center justify-between px-3 py-1.5 border-b border-border bg-muted/50">
            <span v-if="filename" class="text-[11px] font-bold text-muted-foreground uppercase tracking-wider truncate">
                {{ filename }}
            </span>
            <div v-else />
            
            <button
                @click="copyToClipboard"
                class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[11px] font-semibold transition-colors cursor-pointer"
                :class="copied ? 'text-primary bg-primary/10' : 'text-muted-foreground hover:text-foreground hover:bg-muted'"
            >
                <svg v-if="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                </svg>
                <svg v-else class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                {{ copied ? t('ui.components.code_block.copied') : t('ui.components.code_block.copy') }}
            </button>
        </div>

        <!-- Code Content -->
        <div class="p-3 sm:p-4 overflow-x-auto custom-scrollbar">
            <code class="block text-xs sm:text-sm font-mono text-foreground whitespace-pre leading-relaxed">{{ code }}</code>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();

const props = defineProps({
    code: { type: String, required: true },
    filename: { type: String, default: '' },
});

const copied = ref(false);
let timeout = null;

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(props.code);
        copied.value = true;
        clearTimeout(timeout);
        timeout = setTimeout(() => { copied.value = false; }, 2000);
    } catch (err) {
        // Fallback for older browsers
        const textArea = document.createElement("textarea");
        textArea.value = props.code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        copied.value = true;
        clearTimeout(timeout);
        timeout = setTimeout(() => { copied.value = false; }, 2000);
    }
};
</script>