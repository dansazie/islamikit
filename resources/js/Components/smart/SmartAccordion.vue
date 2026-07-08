<template>
    <div class="space-y-2">
        <div 
            v-for="(item, index) in items" 
            :key="item.id || index" 
            class="border border-border rounded-xl overflow-hidden transition-colors"
            :class="isActive(item.id) ? 'bg-muted/30' : 'bg-card'"
        >
            <!-- Trigger -->
            <button 
                @click="toggle(item.id)" 
                :disabled="item.disabled"
                class="w-full flex items-center justify-between gap-3 px-4 py-3.5 text-left cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed transition-colors hover:bg-muted/50"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <div v-if="item.icon" v-html="item.icon" class="w-5 h-5 shrink-0 text-muted-foreground"></div>
                    <span class="text-sm font-semibold text-foreground truncate">{{ item.title }}</span>
                </div>
                <svg 
                    class="w-4 h-4 shrink-0 text-muted-foreground transition-transform duration-300" 
                    :class="{ '-rotate-180': isActive(item.id) }" 
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Content -->
            <Transition
                enter-active-class="transition ease-out duration-200 overflow-hidden"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-96 opacity-100"
                leave-active-class="transition ease-in duration-150 overflow-hidden"
                leave-from-class="max-h-96 opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div v-show="isActive(item.id)" class="px-4 pb-4 text-sm text-muted-foreground leading-relaxed">
                    <slot :name="`content-${item.id}`" :item="item">
                        {{ item.content }}
                    </slot>
                </div>
            </Transition>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    items: { 
        type: Array, 
        required: true,
        default: () => []
    },
    multiple: { type: Boolean, default: false },
    defaultActive: { type: [String, Array], default: null }
});

const activeIds = ref(props.defaultActive 
    ? (Array.isArray(props.defaultActive) ? props.defaultActive : [props.defaultActive]) 
    : []
);

const isActive = (id) => activeIds.value.includes(id);

const toggle = (id) => {
    if (props.multiple) {
        const index = activeIds.value.indexOf(id);
        if (index > -1) activeIds.value.splice(index, 1);
        else activeIds.value.push(id);
    } else {
        activeIds.value = isActive(id) ? [] : [id];
    }
};
</script>