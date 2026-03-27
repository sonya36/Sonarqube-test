<script setup lang="ts">
import { cn } from '@/lib/utils'

interface TOCItem {
  id: string
  title: string
  active?: boolean
}

defineProps<{
  items: TOCItem[]
}>()
</script>

<template>
  <div class="space-y-4">
    <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">On This Page</h3>
    <nav class="space-y-0 relative border-l border-[#262626]">
       <a 
          v-for="item in items" 
          :key="item.id"
          :href="`#${item.id}`"
          :class="cn(
            'block py-2 pl-4 text-sm transition-all border-l-2 -ml-[2px]',
            item.active 
              ? 'border-indigo-500 text-indigo-400 font-medium' 
              : 'border-transparent text-gray-500 hover:text-gray-300'
          )"
       >
         {{ item.title }}
       </a>
    </nav>
  </div>
</template>
