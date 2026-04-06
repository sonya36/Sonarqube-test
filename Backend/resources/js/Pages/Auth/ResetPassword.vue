<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-8 text-center">
            <h1 class="text-xl font-bold text-white mb-2">Reset Password</h1>
            <p class="text-sm text-gray-500/80 leading-relaxed max-w-xs mx-auto">
                Set your new password to regain access to your account.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="group">
                <label class="block text-sm font-semibold mb-1.5 text-gray-400 group-focus-within:text-indigo-400 transition-colors">Email address</label>
                <input 
                    v-model="form.email" 
                    type="email" 
                    required 
                    readonly
                    class="w-full h-11 px-3 rounded-lg border border-white/10 bg-white/5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-white/50 cursor-not-allowed"
                >
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="group">
                <label class="block text-sm font-semibold mb-1.5 text-gray-400 group-focus-within:text-indigo-400 transition-colors">New Password</label>
                <input 
                    v-model="form.password" 
                    type="password" 
                    required 
                    autofocus
                    class="w-full h-11 px-3 rounded-lg border border-white/10 bg-white/5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-white"
                    placeholder="••••••••"
                >
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="group">
                <label class="block text-sm font-semibold mb-1.5 text-gray-400 group-focus-within:text-indigo-400 transition-colors">Confirm Password</label>
                <input 
                    v-model="form.password_confirmation" 
                    type="password" 
                    required 
                    class="w-full h-11 px-3 rounded-lg border border-white/10 bg-white/5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-white"
                    placeholder="••••••••"
                >
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-2">
                <button 
                    :disabled="form.processing"
                    class="w-full h-11 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-500 shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    <span v-if="form.processing">Resetting password...</span>
                    <span v-else>Reset Password</span>
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
