<template>
    <div class="w-full flex items-center">
        <template v-for="(step, index) in steps" :key="step.key">
            <!-- Step Node -->
            <div class="flex items-center flex-1 last:flex-none">
                <!-- Circle & Label -->
                <div class="flex flex-col items-center gap-1.5">
                    <div 
                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border-2 flex items-center justify-center transition-colors"
                        :class="stepClass(index)"
                    >
                        <!-- Completed Check -->
                        <svg v-if="isCompleted(index)" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <!-- Active Dot -->
                        <div v-else-if="isActive(index)" class="w-2.5 h-2.5 rounded-full bg-primary-foreground animate-pulse" />
                        <!-- Pending Number -->
                        <span v-else class="text-xs font-bold text-muted-foreground">{{ index + 1 }}</span>
                    </div>
                    
                    <!-- Label Text -->
                    <span 
                        class="text-[10px] sm:text-xs font-semibold text-center whitespace-nowrap transition-colors hidden sm:block"
                        :class="isCompleted(index) || isActive(index) ? 'text-foreground' : 'text-muted-foreground'"
                    >
                        {{ step.label }}
                    </span>
                </div>

                <!-- Connector Line -->
                <div 
                    v-if="index < steps.length - 1"
                    class="flex-1 h-0.5 mx-2 sm:mx-3 rounded-full transition-colors"
                    :class="isCompleted(index) ? 'bg-primary' : 'bg-border'"
                />
            </div>
        </template>
    </div>
</template>

<script setup>
const props = defineProps({
    steps: {
        type: Array,
        required: true,
        // Format: [{ key: String, label: String }]
    },
    currentStep: {
        type: [String, Number],
        required: true,
    }
});

const getCurrentIndex = () => {
    return props.steps.findIndex(s => s.key === props.currentStep);
};

const isCompleted = (index) => index < getCurrentIndex();
const isActive = (index) => index === getCurrentIndex();

const stepClass = (index) => {
    if (isCompleted(index)) return 'border-primary bg-primary text-primary-foreground';
    if (isActive(index)) return 'border-primary bg-background text-primary ring-2 ring-primary/20';
    return 'border-border bg-background text-muted-foreground';
};
</script>