<template>
    <AuthenticatedLayout>
        <div class="space-y-4 sm:space-y-6">
            <SmartBreadcrumb :items="[
                { label: t('ui.breadcrumb.home'), path: '/dashboard' },
                { label: t('ui.breadcrumb.admin') },
                { label: t('menus.breadcrumb.title') }
            ]" />

            <div class="app-card-container !gap-0 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-primary/5 blur-2xl" />
                <div class="flex items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="app-icon-wrapper">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" /></svg>
                        </div>
                        <div class="min-w-0">
                            <h1 class="app-title !text-lg sm:!text-xl">{{ t('menus.title') }}</h1>
                            <p class="app-subtitle !text-xs sm:!text-sm">{{ t('menus.subtitle') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <SmartTooltip :text="t('menus.tooltip.add_root')" position="bottom">
                                <button @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    {{ t('menus.add_root_button') }}
                                </button>
                            </SmartTooltip>
                        </div>

                        <div class="flex sm:hidden items-center gap-2 w-full">
                            <button @click="openCreateModal" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                {{ t('menus.add_root_button') }}
                            </button>
                            <SmartDropdown ref="mobileMenuRef" align="right">
                                <template #trigger="{ isOpen }">
                                    <button class="p-2.5 rounded-xl border border-border hover:bg-muted transition-colors cursor-pointer shrink-0">
                                        <svg class="w-5 h-5 text-muted-foreground transition-transform duration-200" :class="{ 'rotate-90': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                    </button>
                                </template>
                                <div class="px-3 py-2">
                                    <p class="text-xs text-muted-foreground leading-relaxed">
                                        <svg class="w-3.5 h-3.5 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                                        {{ t('menus.mobile_drag_hint') }}
                                    </p>
                                </div>
                                <div class="border-t border-border" />
                                <button v-if="isOrderChanged" @click="handleMobileSaveOrder" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-primary hover:bg-muted cursor-pointer transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                    {{ t('menus.mobile_save_order') }}
                                </button>
                            </SmartDropdown>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-card-container">
                <SmartAlert variant="info" :title="t('menus.alert.drag_title')" :description="t('menus.alert.drag_desc')" dismissible class="!mb-4" />

                <SmartEmpty v-if="localMenus.length === 0" variant="muted" :title="t('menus.empty.title')" :description="t('menus.empty.desc')" />

                <div v-else class="space-y-3">
                    <div v-for="(menu, index) in localMenus" :key="menu.id" draggable="true" @dragstart="onRootDragStart($event, index)" @dragend="onRootDragEnd" @dragover.prevent="onRootDragOver($event, index)" @dragleave="onRootDragLeave(index)" @drop="onRootDrop($event, index)" class="border border-border rounded-xl p-4 bg-background hover:bg-muted/30 transition-colors cursor-move group" :class="[draggedRootIndex === index ? 'ring-2 ring-primary/30 border-primary/40' : '', dragOverRootIndex === index && draggedRootIndex !== index ? 'border-t-2 border-t-primary' : '']">
                        <div class="flex justify-between items-center gap-3">
                            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                <div class="text-muted-foreground/40 group-hover:text-muted-foreground transition-colors shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-8a2 2 0 10-.001-4.001A2 2 0 0013 6zm0 2a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                                </div>
                                <span class="text-[10px] font-mono text-muted-foreground shrink-0 tabular-nums">#{{ menu.order }}</span>
                                <SmartBadge variant="primary" size="xs" :pill="false">{{ menu.icon }}</SmartBadge>
                                <strong class="text-sm font-semibold text-foreground truncate">{{ menu.label }}</strong>
                                <span class="text-[11px] text-muted-foreground truncate hidden md:inline">({{ menu.route || '#' }})</span>
                                <SmartBadge v-if="menu.children && menu.children.length > 0" variant="secondary" size="xs" :pill="true">
                                    {{ menu.children.length }} {{ t('menus.sub_label') }}
                                </SmartBadge>
                            </div>

                            <div class="flex items-center gap-0.5 shrink-0 opacity-60 group-hover:opacity-100 transition-opacity">
                                <SmartTooltip :text="t('menus.tooltip.add_child')" position="top">
                                    <button @click.stop="openCreateChildModal(menu.id)" class="p-1.5 rounded-md text-muted-foreground hover:text-primary hover:bg-primary/10 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </button>
                                </SmartTooltip>
                                <SmartTooltip :text="t('menus.tooltip.edit')" position="top">
                                    <button @click.stop="openEditModal(menu)" class="p-1.5 rounded-md text-muted-foreground hover:text-secondary hover:bg-secondary/10 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </button>
                                </SmartTooltip>
                                <SmartTooltip :text="t('menus.tooltip.delete')" position="top">
                                    <button @click.stop="deleteMenu(menu)" class="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </SmartTooltip>
                            </div>
                        </div>

                        <div v-if="menu.children && menu.children.length > 0" class="ml-6 sm:ml-10 mt-3 pl-3 sm:pl-4 border-l-2 border-dashed border-border space-y-2">
                            <div v-for="(child, childIndex) in menu.children" :key="child.id" draggable="true" @dragstart.stop="onChildDragStart($event, menu.id, childIndex)" @dragend.stop="onChildDragEnd" @dragover.stop.prevent="onChildDragOver($event, menu.id, childIndex)" @dragleave.stop="onChildDragLeave(menu.id, childIndex)" @drop.stop="onChildDrop($event, menu.id, childIndex)" class="bg-background p-2.5 sm:p-3 border border-border rounded-lg flex justify-between items-center gap-3 group/child hover:bg-muted/20 transition-colors cursor-move" :class="[draggedChildParentId === menu.id && draggedChildIndex === childIndex ? 'ring-2 ring-primary/30 border-primary/40' : '', dragOverChildParentId === menu.id && dragOverChildIndex === childIndex && !(draggedChildParentId === menu.id && draggedChildIndex === childIndex) ? 'border-t-2 border-t-primary' : '']">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="text-muted-foreground/30 group-hover/child:text-muted-foreground transition-colors shrink-0">
                                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M7 2a2 2 0 10.001 4.001A2 2 0 007 2zm0 6a2 2 0 10.001 4.001A2 2 0 007 8zm0 6a2 2 0 10.001 4.001A2 2 0 007 14zm6-8a2 2 0 10-.001-4.001A2 2 0 0013 6zm0 2a2 2 0 10.001 4.001A2 2 0 0013 8zm0 6a2 2 0 10.001 4.001A2 2 0 0013 14z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-mono text-muted-foreground shrink-0 tabular-nums">#{{ child.order }}</span>
                                    <span class="text-sm font-medium text-foreground truncate">{{ child.label }}</span>
                                    <span class="text-[11px] text-muted-foreground truncate hidden sm:inline">({{ child.route || '#' }})</span>
                                </div>
                                <div class="flex items-center gap-0.5 shrink-0 opacity-50 group-hover/child:opacity-100 transition-opacity">
                                    <SmartTooltip :text="t('ui.actions.edit')" position="top">
                                        <button @click.stop="openEditModal(child)" class="p-1.5 rounded-md text-muted-foreground hover:text-secondary hover:bg-secondary/10 transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </button>
                                    </SmartTooltip>
                                    <SmartTooltip :text="t('ui.actions.delete')" position="top">
                                        <button @click.stop="deleteMenu(child)" class="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </SmartTooltip>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="isOrderChanged" class="mt-4 pt-4 border-t border-border flex justify-end">
                    <SmartTooltip :text="t('menus.tooltip.save_order')" position="top">
                        <button @click="saveNewOrder" :disabled="isSaving" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-primary-foreground rounded-xl text-sm font-semibold hover:opacity-90 transition-opacity cursor-pointer shadow-sm disabled:opacity-50">
                            <svg class="w-4 h-4" :class="{ 'animate-spin': isSaving }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            {{ isSaving ? t('ui.actions.saving') : t('menus.save_order_button') }}
                        </button>
                    </SmartTooltip>
                </div>
            </div>
        </div>

        <Modal :show="isModalOpen" @close="isModalOpen = false">
            <template #title>{{ form.id ? t('menus.modal.edit_title') : t('menus.modal.create_title') }}</template>
            <template #body>
                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- ═══ INPUT LABELS DINAMIS BERDASARKAN BAHASA YANG TERDAFTAR ═══ -->
                    <div class="space-y-4">
                        <div v-for="(name, code) in availableLocales" :key="code">
                            <InputField
                                v-model="form.labels[code]"
                                :label="`Label (${name})`"
                                :placeholder="`e.g. My Menu`"
                                :error="form.errors[`labels.${code}`]"
                                required
                            />
                        </div>
                    </div>
                    <InputField v-model="form.route" :label="t('menus.modal.route_label')" :placeholder="t('menus.modal.route_placeholder')" :error="form.errors.route" />
                    <InputField v-model="form.icon" :label="t('menus.modal.icon_label')" :placeholder="t('menus.modal.icon_placeholder')" :error="form.errors.icon" />
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">
                            {{ t('menus.modal.role_label') }}
                            <span class="font-normal normal-case tracking-normal text-muted-foreground/60 ml-1">{{ t('menus.modal.role_hint') }}</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2 p-3 border border-border bg-background/50 rounded-xl max-h-32 overflow-y-auto custom-scrollbar">
                            <label v-for="role in rolesList" :key="role" class="flex items-center gap-2.5 text-xs font-semibold text-foreground cursor-pointer select-none group py-0.5">
                                <input type="checkbox" :value="role" v-model="form.roles" class="w-4 h-4 rounded border-border text-tertiary focus:ring-tertiary cursor-pointer" />
                                <span class="group-hover:text-tertiary transition-colors">{{ role }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.roles" class="text-xs text-destructive font-medium mt-1.5">{{ form.errors.roles }}</p>
                    </div>
                </form>
            </template>
            <template #footer>
                <button @click="isModalOpen = false" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-medium text-foreground bg-muted rounded-lg hover:bg-muted/80 transition-colors cursor-pointer order-2 sm:order-1">
                    {{ t('ui.actions.cancel') }}
                </button>
                <button @click="submitForm" :disabled="form.processing" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-sm font-medium text-primary-foreground bg-primary rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50 cursor-pointer order-1 sm:order-2">
                    <span v-if="form.processing" class="inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        {{ t('ui.actions.saving') }}
                    </span>
                    <span v-else>{{ form.id ? t('ui.actions.save') : t('menus.modal.create_button') }}</span>
                </button>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/ui/Modal.vue';
import InputField from '@/Components/ui/InputField.vue';
import ConfirmDialog from '@/Components/ui/ConfirmDialog.vue';
import SmartBreadcrumb from '@/Components/smart/SmartBreadcrumb.vue';
import SmartAlert from '@/Components/smart/SmartAlert.vue';
import SmartBadge from '@/Components/smart/SmartBadge.vue';
import SmartTooltip from '@/Components/smart/SmartTooltip.vue';
import SmartEmpty from '@/Components/smart/SmartEmpty.vue';
import SmartDropdown from '@/Components/smart/SmartDropdown.vue';
import { useI18n } from '@/Composables/useI18n';
import { useConfirm } from '@/Composables/useConfirm';
const { open: openConfirm, setLoading: setConfirmLoading } = useConfirm();
const { t, availableLocales } = useI18n();

// ════════════════════════════════════════════════════════════════
// URL KONSTANTA (TANPA ZIGGY)
// ════════════════════════════════════════════════════════════════
const URL_MENU_STORE = '/admin/menus';
const URL_MENU_REORDER = '/admin/menus/reorder';

const props = defineProps({
    menusList: { type: Array, default: () => [] },
    rolesList: { type: Array, default: () => [] },
});

// ── State ──
const isModalOpen = ref(false);
const isOrderChanged = ref(false);
const isSaving = ref(false);
const localMenus = ref(JSON.parse(JSON.stringify(props.menusList)));
const mobileMenuRef = ref(null);

watch(() => props.menusList, (newVal) => {
    localMenus.value = JSON.parse(JSON.stringify(newVal));
    isOrderChanged.value = false;
}, { deep: true });

const form = useForm({
    id: null,
    parent_id: null,
    label: '',
    route: '',
    icon: '',
    roles: [],
});

// =========================================================================
// HTML5 DRAG & DROP - ROOT MENU
// =========================================================================
const draggedRootIndex = ref(null);
const dragOverRootIndex = ref(null);

const onRootDragStart = (event, index) => {
    draggedRootIndex.value = index;
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', String(index));
};

const onRootDragEnd = () => {
    draggedRootIndex.value = null;
    dragOverRootIndex.value = null;
};

const onRootDragOver = (event, index) => {
    event.dataTransfer.dropEffect = 'move';
    dragOverRootIndex.value = index;
};

const onRootDragLeave = (index) => {
    if (dragOverRootIndex.value === index) {
        dragOverRootIndex.value = null;
    }
};

const onRootDrop = (event, targetIndex) => {
    event.preventDefault();
    dragOverRootIndex.value = null;

    if (draggedRootIndex.value === null || draggedRootIndex.value === targetIndex) {
        draggedRootIndex.value = null;
        return;
    }

    const item = localMenus.value[draggedRootIndex.value];
    localMenus.value.splice(draggedRootIndex.value, 1);
    localMenus.value.splice(targetIndex, 0, item);

    // Update order untuk semua root
    localMenus.value.forEach((menu, idx) => {
        menu.order = idx + 1;
    });

    isOrderChanged.value = true;
    draggedRootIndex.value = null;
};

// =========================================================================
// HTML5 DRAG & DROP - CHILD MENU (SUB-MENU)
// =========================================================================
const draggedChildIndex = ref(null);
const draggedChildParentId = ref(null);
const dragOverChildIndex = ref(null);
const dragOverChildParentId = ref(null);

const onChildDragStart = (event, parentId, childIndex) => {
    draggedChildIndex.value = childIndex;
    draggedChildParentId.value = parentId;
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', `child-${parentId}-${childIndex}`);
};

const onChildDragEnd = () => {
    draggedChildIndex.value = null;
    draggedChildParentId.value = null;
    dragOverChildIndex.value = null;
    dragOverChildParentId.value = null;
};

const onChildDragOver = (event, parentId, childIndex) => {
    event.dataTransfer.dropEffect = 'move';
    dragOverChildIndex.value = childIndex;
    dragOverChildParentId.value = parentId;
};

const onChildDragLeave = (parentId, childIndex) => {
    if (dragOverChildIndex.value === childIndex && dragOverChildParentId.value === parentId) {
        dragOverChildIndex.value = null;
        dragOverChildParentId.value = null;
    }
};

const onChildDrop = (event, targetParentId, targetChildIndex) => {
    event.preventDefault();
    dragOverChildIndex.value = null;
    dragOverChildParentId.value = null;

    // Hanya proses jika drag dari parent yang SAMA
    if (draggedChildParentId.value !== targetParentId) {
        onChildDragEnd();
        return;
    }

    if (draggedChildIndex.value === null || draggedChildIndex.value === targetChildIndex) {
        onChildDragEnd();
        return;
    }

    // Cari parent menu
    const parentMenu = localMenus.value.find(m => m.id === targetParentId);
    if (!parentMenu || !parentMenu.children) {
        onChildDragEnd();
        return;
    }

    const child = parentMenu.children[draggedChildIndex.value];
    parentMenu.children.splice(draggedChildIndex.value, 1);
    parentMenu.children.splice(targetChildIndex, 0, child);

    // Update order untuk semua children
    parentMenu.children.forEach((ch, idx) => {
        ch.order = idx + 1;
    });

    isOrderChanged.value = true;
    onChildDragEnd();
};

// =========================================================================
// SAVE ORDER - KIRIM NESTED DATA KE BACKEND
// =========================================================================
const buildPayload = () => {
    return localMenus.value.map((menu, index) => ({
        id: menu.id,
        order: index + 1,
        children: (menu.children || []).map((child, childIndex) => ({
            id: child.id,
            order: childIndex + 1,
        })),
    }));
};

const saveNewOrder = () => {
    if (isSaving.value) return;
    isSaving.value = true;

    const payload = buildPayload();

    router.post(URL_MENU_REORDER, { items: payload }, {
        preserveScroll: true,
        // preserveState dihilangkan — biarkan Inertia handle secara default
        onFinish: () => {
            isSaving.value = false;
            isOrderChanged.value = false;
        },
    });
};

// Handler untuk mobile dropdown
const handleMobileSaveOrder = () => {
    saveNewOrder();
    if (mobileMenuRef.value && typeof mobileMenuRef.value.close === 'function') {
        mobileMenuRef.value.close();
    }
};

// =========================================================================
// CRUD ACTIONS
// =========================================================================
function openCreateModal(parentId = null) {
    // Inisialisasi labels kosong sesuai jumlah bahasa yang ada
    const initialLabels = {};
    for (const code in availableLocales.value) {
        initialLabels[code] = '';
    }

    form.defaults({
        id: null,
        parent_id: parentId,
        label: '',
        labels: initialLabels, // <-- PAKAI INI
        route: '',
        icon: '',
        roles: [],
    }).reset();
    isModalOpen.value = true;
}

const openCreateChildModal = (parentId) => {
    openCreateModal();
    form.parent_id = parentId;
};

function openEditModal(menu) {
    const currentLabels = menu.labels || {};
    
    // Pastikan setiap bahasa yang terdaftar punya placeholder kosong 
    // jika di database belum ada terjemahan untuk bahasa tertentu
    for (const code in availableLocales.value) {
        if (!currentLabels[code]) {
            currentLabels[code] = '';
        }
    }

    form.defaults({
        id: menu.id,
        parent_id: menu.parent_id,
        label: menu.label,
        labels: currentLabels, // <-- PAKAI INI
        route: menu.route,
        icon: menu.icon,
        roles: menu.roles || [],
    }).reset();
    isModalOpen.value = true;
}

const submitForm = () => {
    // Gunakan URL langsung TANPA Ziggy
    form.post(URL_MENU_STORE, {
        preserveScroll: true,
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        },
    });
};

// ── Delete Logic ──
const deleteMenu = async (menu) => {
    const isConfirmed = await openConfirm({
        title: t('menus.confirm_delete.title'),
        message: t('menus.confirm_delete.message', { label: menu.label || '' }),
        confirmText: t('menus.confirm_delete.button'),
        variant: 'destructive',
    });

    if (isConfirmed) {
        setConfirmLoading(true); // ← Sekarang ini terlihat!

        const deleteUrl = `/admin/menus/${menu.id}`;

        router.delete(deleteUrl, {
            onSuccess: () => {
                closeAfterSuccess(); // ← Tutup dialog setelah sukses
            },
            onError: () => {
                setConfirmLoading(false); // ← Hapus loading, tapi dialog tetap buka
            },
        });
    }
};

</script>