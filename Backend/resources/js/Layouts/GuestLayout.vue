<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import ToastHandler from '@/Components/Common/ToastHandler.vue';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-[#0a0a0f] pt-6 sm:justify-center sm:pt-0 relative overflow-hidden"
    >
        <ToastHandler />
        <!-- Background Glows -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full filter blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-violet-500/5 rounded-full filter blur-[120px] pointer-events-none"></div>

        <div class="relative z-10">
            <Link href="/">
                <ApplicationLogo size="lg" show-name />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-[#16161a] px-6 py-4 border border-white/5 shadow-2xl sm:max-w-md sm:rounded-2xl relative z-10"
        >
            <slot />
        </div>
    </div>
</template>
