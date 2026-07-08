<template>
    <div>
        <div
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop"
            @click="triggerInput"
            class="relative flex flex-col items-center justify-center gap-2 p-6 sm:p-8 border-2 border-dashed rounded-xl cursor-pointer transition-colors"
            :class="[
                isDragging ? 'border-primary bg-primary/5' : 'border-border hover:border-primary/50 hover:bg-muted/30',
                error ? 'border-destructive' : ''
            ]"
        >
            <input
                ref="inputRef"
                type="file"
                :multiple="multiple"
                :accept="accept"
                class="hidden"
                @change="handleInput"
            />

            <div class="w-10 h-10 rounded-full bg-muted flex items-center justify-center">
                <svg class="w-5 h-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
            </div>
            
            <div class="text-center">
                <p class="text-sm font-semibold text-foreground">
                    <span class="text-primary">{{ t('ui.components.file_upload.click_to_upload') }}</span> {{ t('ui.components.file_upload.or_drag') }}
                </p>
                <p class="text-xs text-muted-foreground mt-1">
                    {{ hint || t('ui.components.file_upload.max_size', { sizeMB: maxSizeMB }) }} 
                    <span v-if="accept">{{ t('ui.components.file_upload.format', { formats: accept }) }}</span>
                </p>
            </div>
        </div>

        <p v-if="error" class="text-xs text-destructive font-medium mt-1.5">{{ error }}</p>

        <!-- File List Preview -->
        <ul v-if="modelValue.length > 0" class="mt-3 space-y-2">
            <li
                v-for="(file, index) in modelValue"
                :key="index"
                class="flex items-center gap-3 p-2 bg-muted/50 border border-border rounded-lg"
            >
                <!-- Image Preview -->
                <img
                    v-if="isImage(file)"
                    :src="getPreviewUrl(file)"
                    class="w-10 h-10 object-cover rounded-md shrink-0 bg-muted"
                    alt="Preview"
                />
                <!-- File Icon -->
                <div v-else class="w-10 h-10 rounded-md bg-muted flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>

                <!-- File Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-foreground truncate">{{ file.name }}</p>
                    <p class="text-[10px] text-muted-foreground">{{ formatSize(file.size) }}</p>
                </div>

                <!-- Remove Button -->
                <button
                    type="button"
                    @click.stop="removeFile(index)"
                    class="p-1 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();

const props = defineProps({
    modelValue: { type: Array, default: () => [] },
    accept: { type: String, default: 'image/*,.pdf,.doc,.docx' },
    multiple: { type: Boolean, default: false },
    maxSizeMB: { type: Number, default: 5 },
    hint: { type: String, default: '' },
    error: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);
const isDragging = ref(false);
const previewUrls = ref({});

const triggerInput = () => inputRef.value?.click();

const isImage = (file) => file.type.startsWith('image/');

const getPreviewUrl = (file) => {
    if (previewUrls.value[file.name]) return previewUrls.value[file.name];
    const url = URL.createObjectURL(file);
    previewUrls.value[file.name] = url;
    return url;
};

const formatSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const validateAndEmit = (files) => {
    const validFiles = [];
    const maxSize = props.maxSizeMB * 1024 * 1024;

    Array.from(files).forEach(file => {
        if (file.size <= maxSize) {
            validFiles.push(file);
        } else {
            alert(`File "${file.name}" melebihi batas ${props.maxSizeMB}MB.`);
        }
    });

    const newFiles = props.multiple 
        ? [...props.modelValue, ...validFiles]
        : validFiles.length > 0 ? [validFiles[0]] : [];
        
    emit('update:modelValue', newFiles);
};

const handleInput = (e) => validateAndEmit(e.target.files);

const handleDrop = (e) => {
    isDragging.value = false;
    validateAndEmit(e.dataTransfer.files);
};

const removeFile = (index) => {
    const newFiles = [...props.modelValue];
    const removed = newFiles.splice(index, 1)[0];
    
    // Revoke object URL to prevent memory leaks
    if (previewUrls.value[removed.name]) {
        URL.revokeObjectURL(previewUrls.value[removed.name]);
        delete previewUrls.value[removed.name];
    }
    
    emit('update:modelValue', newFiles);
};
</script>