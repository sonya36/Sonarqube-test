<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-center">
            <h1 class="text-xl font-bold text-white mb-2">Forgot Password?</h1>
            <p class="text-sm text-gray-500/80 leading-relaxed max-w-xs mx-auto">
                No problem. Enter your email and we'll send you a password reset link to choose a new one.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-6 text-sm font-bold text-emerald-400 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-300"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="group">
                <label class="block text-sm font-semibold mb-1.5 text-gray-400 group-focus-within:text-indigo-400 transition-colors">Email address</label>
                <input 
                    v-model="form.email" 
                    type="email" 
                    required 
                    autofocus
                    class="w-full h-11 px-3 rounded-lg border border-white/10 bg-white/5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-white placeholder:text-gray-600"
                    placeholder="m@example.com"
                >
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex flex-col gap-4">
                <button 
                    :disabled="form.processing"
                    class="w-full h-11 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-500 shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    <span v-if="form.processing">Sending link...</span>
                    <span v-else>Email Password Reset Link</span>
                </button>

                <div class="text-center">
                    <Link :href="route('login')" class="text-xs text-gray-500 hover:text-indigo-400 font-medium transition-colors inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to login
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
