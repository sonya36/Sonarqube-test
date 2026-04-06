<script setup lang="ts">
import { watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { toast, type ToastOptions } from 'vue3-toastify';

const page = usePage();

const notify = () => {
    const flash = page.props.flash as { success?: string; error?: string };
    
    if (flash.success) {
        toast.success(flash.success, {
            transition: toast.TRANSITIONS.SLIDE,
            toastStyle: {
                backgroundColor: '#16161a',
                border: '1px border #262626',
                color: '#10b981', // Emerald for success
            },
            progressStyle: {
                background: '#10b981',
            }
        } as ToastOptions);
    }

    if (flash.error) {
        toast.error(flash.error, {
            transition: toast.TRANSITIONS.SLIDE,
            toastStyle: {
                backgroundColor: '#16161a',
                border: '1px border #262626',
                color: '#ef4444', // Red for error
            },
            progressStyle: {
                background: '#ef4444',
            }
        } as ToastOptions);
    }
};

// Check on initial mount (e.g. after a full page reload)
onMounted(() => {
    notify();
});

// Watch for changes in the flash prop (e.g. after XHR/Inertia visits)
watch(() => page.props.flash, () => {
    notify();
}, { deep: true });
</script>

<template>
    <!-- This component does not render any visible HTML -->
</template>
