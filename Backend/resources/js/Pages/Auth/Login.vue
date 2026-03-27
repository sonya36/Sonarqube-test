<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 p-6">
        <div class="w-full max-w-sm space-y-8 bg-white dark:bg-gray-800 p-8 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-2xl transition-all duration-300">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gray-900 dark:bg-gray-100 mb-4">
                    <svg class="w-6 h-6 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-4.514A9.01 9.01 0 0012 3c7.27 0 9 4.477 9 9 0 4.523-1.73 9-9 9a9.01 9.01 0 01-6.813-3.086M12 11a4 4 0 114-4c0 1.451-.71 2.736-1.8 3.514" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100 italic">SILVER Docs</h2>
                <p class="mt-2 text-sm text-gray-500 font-medium">Please enter your details to sign in</p>
            </div>

            <div v-if="status" class="p-3 text-sm font-medium text-green-600 bg-green-50 rounded-lg dark:bg-green-900/10 mb-4">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <!-- Email Label & Input (Shadcn style) -->
                    <div class="group">
                        <label class="block text-sm font-semibold mb-1.5 text-gray-700 dark:text-gray-300 group-focus-within:text-indigo-600 transition-colors">Email address</label>
                        <input 
                            v-model="form.email" 
                            type="email" 
                            required 
                            autofocus
                            class="w-full h-11 px-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-gray-100"
                            placeholder="m@example.com"
                        >
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password -->
                    <div class="group">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 group-focus-within:text-indigo-600 transition-colors">Password</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs text-indigo-600 hover:text-indigo-500 font-bold transition-colors">
                                Forgot password?
                            </Link>
                        </div>
                        <input 
                            v-model="form.password" 
                            type="password" 
                            required 
                            class="w-full h-11 px-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all dark:text-gray-100"
                        >
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                </div>

                <div class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" class="rounded-md border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <span class="ms-2 text-sm font-medium text-gray-600 dark:text-gray-400">Remember me</span>
                </div>

                <!-- Submit Button -->
                <button 
                    :disabled="form.processing"
                    class="w-full h-11 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-lg text-sm font-bold hover:bg-gray-800 dark:hover:bg-gray-200 shadow-lg shadow-gray-200 dark:shadow-none transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign in</span>
                </button>

                <div class="text-center">
                   <p class="text-xs text-gray-400">
                       Don't have an account? 
                       <Link :href="route('register')" class="text-indigo-600 font-bold hover:underline">Register</Link>
                   </p>
                </div>
            </form>
        </div>
    </div>
</template>
