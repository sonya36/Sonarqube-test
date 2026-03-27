<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'

interface AppItem {
  name: string
  color: string
  active?: boolean
}

defineProps<{
  applications: AppItem[]
  currentApp?: string
}>()
</script>

<template>
  <div class="p-6 space-y-8">
    <!-- Applications Section -->
    <section>
      <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">Applications</h3>
      <nav class="space-y-1">
        <Link 
          v-for="app in applications" 
          :key="app.name"
          href="#"
          :class="cn(
            'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all group relative',
            app.active ? 'bg-indigo-500/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'
          )"
        >
          <div :class="cn('w-2 h-2 rounded-full shrink-0', app.color)" />
          {{ app.name }}
          <div v-if="app.active" class="absolute right-0 top-1/2 -translate-y-1/2 w-0.5 h-4 bg-indigo-500 rounded-full" />
        </Link>
      </nav>
    </section>

    <!-- Document Navigation Section -->
    <section>
       <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">HR DOCUMENTS</h3>
       <nav class="space-y-1">
          <slot name="doc-nav" />
       </nav>
    </section>
  </div>
</template>
