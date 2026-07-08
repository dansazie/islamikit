// resources/js/app-starterkit.js
// Entry point for IslamiKit Starterkit

// ============================================
// UI Components - Global Registration
// ============================================
import ConfirmDialog from './Components/ui/ConfirmDialog.vue';
import DarkModeToggle from './Components/ui/DarkModeToggle.vue';
import Dropdown from './Components/ui/Dropdown.vue';
import ImpersonateBanner from './Components/ui/ImpersonateBanner.vue';
import InputField from './Components/ui/InputField.vue';
import LanguageSwitcher from './Components/ui/LanguageSwitcher.vue';
import MenuIcon from './Components/ui/MenuIcon.vue';
import Modal from './Components/ui/Modal.vue';
import SidebarItem from './Components/ui/SidebarItem.vue';
import Toast from './Components/ui/Toast.vue';

// ============================================
// Smart Components - Global Registration
// ============================================
import SmartAccordion from './Components/smart/SmartAccordion.vue';
import SmartAlert from './Components/smart/SmartAlert.vue';
import SmartAvatar from './Components/smart/SmartAvatar.vue';
import SmartAvatarGroup from './Components/smart/SmartAvatarGroup.vue';
import SmartBadge from './Components/smart/SmartBadge.vue';
import SmartBreadcrumb from './Components/smart/SmartBreadcrumb.vue';
import SmartButton from './Components/smart/SmartButton.vue';
import SmartChart from './Components/smart/SmartChart.vue';
import SmartCodeBlock from './Components/smart/SmartCodeBlock.vue';
import SmartCommandPalette from './Components/smart/SmartCommandPalette.vue';
import SmartDatePicker from './Components/smart/SmartDatePicker.vue';
import SmartDrawer from './Components/smart/SmartDrawer.vue';
import SmartDropdown from './Components/smart/SmartDropdown.vue';
import SmartEmpty from './Components/smart/SmartEmpty.vue';
import SmartFeed from './Components/smart/SmartFeed.vue';
import SmartFileUpload from './Components/smart/SmartFileUpload.vue';
import SmartGridStat from './Components/smart/SmartGridStat.vue';
import SmartNotificationBell from './Components/smart/SmartNotificationBell.vue';
import SmartPagination from './Components/smart/SmartPagination.vue';
import SmartPipeline from './Components/smart/SmartPipeline.vue';
import SmartProgress from './Components/smart/SmartProgress.vue';
import SmartSearch from './Components/smart/SmartSearch.vue';
import SmartSelect from './Components/smart/SmartSelect.vue';
import SmartSkeleton from './Components/smart/SmartSkeleton.vue';
import SmartStat from './Components/smart/SmartStat.vue';
import SmartSwitch from './Components/smart/SmartSwitch.vue';
import SmartTable from './Components/smart/SmartTable.vue';
import SmartTabs from './Components/smart/SmartTabs.vue';
import SmartTextarea from './Components/smart/SmartTextarea.vue';
import SmartTimeline from './Components/smart/SmartTimeline.vue';
import SmartTooltip from './Components/smart/SmartTooltip.vue';

// ============================================
// Export all for on-demand import
// ============================================
export {
    // UI Components
    ConfirmDialog,
    DarkModeToggle,
    Dropdown,
    ImpersonateBanner,
    InputField,
    LanguageSwitcher,
    MenuIcon,
    Modal,
    SidebarItem,
    Toast,
    
    // Smart Components
    SmartAccordion,
    SmartAlert,
    SmartAvatar,
    SmartAvatarGroup,
    SmartBadge,
    SmartBreadcrumb,
    SmartButton,
    SmartChart,
    SmartCodeBlock,
    SmartCommandPalette,
    SmartDatePicker,
    SmartDrawer,
    SmartDropdown,
    SmartEmpty,
    SmartFeed,
    SmartFileUpload,
    SmartGridStat,
    SmartNotificationBell,
    SmartPagination,
    SmartPipeline,
    SmartProgress,
    SmartSearch,
    SmartSelect,
    SmartSkeleton,
    SmartStat,
    SmartSwitch,
    SmartTable,
    SmartTabs,
    SmartTextarea,
    SmartTimeline,
    SmartTooltip,
};

// ============================================
// Auto-register components globally
// ============================================
export function registerStarterkitComponents(app) {
    // UI Components
    app.component('ui-confirm-dialog', ConfirmDialog);
    app.component('ui-dark-mode-toggle', DarkModeToggle);
    app.component('ui-dropdown', Dropdown);
    app.component('ui-impersonate-banner', ImpersonateBanner);
    app.component('ui-input-field', InputField);
    app.component('ui-language-switcher', LanguageSwitcher);
    app.component('ui-menu-icon', MenuIcon);
    app.component('ui-modal', Modal);
    app.component('ui-sidebar-item', SidebarItem);
    app.component('ui-toast', Toast);

    // Smart Components
    app.component('smart-accordion', SmartAccordion);
    app.component('smart-alert', SmartAlert);
    app.component('smart-avatar', SmartAvatar);
    app.component('smart-avatar-group', SmartAvatarGroup);
    app.component('smart-badge', SmartBadge);
    app.component('smart-breadcrumb', SmartBreadcrumb);
    app.component('smart-button', SmartButton);
    app.component('smart-chart', SmartChart);
    app.component('smart-code-block', SmartCodeBlock);
    app.component('smart-command-palette', SmartCommandPalette);
    app.component('smart-date-picker', SmartDatePicker);
    app.component('smart-drawer', SmartDrawer);
    app.component('smart-dropdown', SmartDropdown);
    app.component('smart-empty', SmartEmpty);
    app.component('smart-feed', SmartFeed);
    app.component('smart-file-upload', SmartFileUpload);
    app.component('smart-grid-stat', SmartGridStat);
    app.component('smart-notification-bell', SmartNotificationBell);
    app.component('smart-pagination', SmartPagination);
    app.component('smart-pipeline', SmartPipeline);
    app.component('smart-progress', SmartProgress);
    app.component('smart-search', SmartSearch);
    app.component('smart-select', SmartSelect);
    app.component('smart-skeleton', SmartSkeleton);
    app.component('smart-stat', SmartStat);
    app.component('smart-switch', SmartSwitch);
    app.component('smart-table', SmartTable);
    app.component('smart-tabs', SmartTabs);
    app.component('smart-textarea', SmartTextarea);
    app.component('smart-timeline', SmartTimeline);
    app.component('smart-tooltip', SmartTooltip);
}

// Auto-register if Vue app exists
if (typeof window !== 'undefined') {
    window.registerStarterkitComponents = registerStarterkitComponents;
}