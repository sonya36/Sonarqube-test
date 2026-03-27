<script setup lang="ts">
import { cn } from '@/lib/utils'

defineProps<{
  variant?: 'outline' | 'secondary' | 'published'
  class?: string
}>()
</script>

<template>
  <span :class="cn(
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold border transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
    variant === 'published' ? 'border-transparent bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : '',
    variant === 'secondary' ? 'border-transparent bg-gray-100 text-gray-900' : '',
    variant === 'outline' ? 'text-gray-950 border-gray-200' : '',
    $props.class
  )">
    <slot />
  </span>
</template>
