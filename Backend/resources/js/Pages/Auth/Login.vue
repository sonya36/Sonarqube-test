<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
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

    <div class="min-h-screen flex items-center justify-center bg-[#0a0a0f] p-6 relative overflow-hidden">
        <!-- Optional: Background Glows to match Welcome Page -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full filter blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-violet-500/5 rounded-full filter blur-[120px] pointer-events-none"></div>
        
        <div class="w-full max-w-sm space-y-8 bg-[#16161a] p-8 rounded-2xl border border-white/5 shadow-2xl relative z-10 transition-all duration-300">
            <div class="text-center">
                <div class="mb-4">
                    <ApplicationLogo size="lg" show-name />
                </div>
                <p class="mt-2 text-sm text-gray-500/80 font-medium tracking-tight">Please enter your details to sign in</p>
            </div>

            <div v-if="status" class="p-3 text-sm font-medium text-green-600 bg-green-50 rounded-lg dark:bg-green-900/10 mb-4">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <!-- Email Label & Input (Shadcn style) -->
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

                    <!-- Password -->
                    <div class="group">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-semibold text-gray-400 group-focus-within:text-indigo-400 transition-colors">Password</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs text-indigo-400 hover:text-indigo-300 font-bold transition-colors">
                                Forgot password?
                            </Link>
                        </div>
                        <input 
                            v-model="form.password" 
                            type="password" 
                            required 
                            class="w-full h-11 px-3 rounded-lg border border-white/10 bg-white/5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-white"
                        >
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                </div>

                <div class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" class="rounded-md border-white/10 bg-white/5 text-indigo-500 shadow-sm focus:ring-indigo-500" />
                    <span class="ms-2 text-sm font-medium text-gray-500">Remember me</span>
                </div>

                <!-- Submit Button -->
                <button 
                    :disabled="form.processing"
                    class="w-full h-11 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-500 shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] disabled:opacity-50"
                >
                    <span v-if="form.processing">Signing in...</span>
                    <span v-else>Sign in</span>
                </button>

                <div class="text-center">
                   <p class="text-xs text-gray-600">
                       Don't have an account? 
                       <Link :href="route('register')" class="text-indigo-400 font-bold hover:underline">Register</Link>
                   </p>
                </div>
            </form>
        </div>
    </div>
</template>

