<script setup lang="ts">
import DocsLayout from '@/Layouts/DocsLayout.vue'
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { ref, onMounted } from 'vue'

const props = defineProps<{
    application: { id: number; name: string; slug: string; color?: string },
    documents: Array<{ id: number; title: string; slug: string; status: string }>,
    currentDocument: {
        id: number; title: string; content: string; updated_at: string; status: string;
    } | null,
    sections: Array<{ id: string; sub_title: string; content: string }>,
    toc: Array<{ id: string; title: string; level: number }>,
}>()

const activeId = ref<string | null>(null)

const formatDate = (d: string) => new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })

const scrollTo = (id: string) => {
    const el = document.getElementById(id)
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

onMounted(() => {
    if (props.toc.length > 0) activeId.value = props.toc[0].id

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) activeId.value = e.target.id })
    }, { rootMargin: '-20% 0% -60% 0%', threshold: 0 })

    props.toc.forEach(item => {
        const el = document.getElementById(item.id)
        if (el) observer.observe(el)
    })
})
</script>

<template>
  <DocsLayout>
    <!-- Left sidebar: document list -->
    <template #left-sidebar>
      <div class="p-6">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">
          {{ application.name }}
        </h3>
        <nav class="space-y-0.5" v-if="documents.length > 0">
          <Link
            v-for="doc in documents"
            :key="doc.id"
            :href="route('app.show.doc', { appSlug: application.slug, docSlug: doc.slug })"
            :class="cn(
              'block px-3 py-2 rounded-lg text-sm font-medium transition-all',
              currentDocument && doc.id === currentDocument.id
                ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500'
                : 'text-gray-400 hover:text-white hover:bg-white/5'
            )"
          >
            {{ doc.title }}
          </Link>
        </nav>
        <div v-else class="text-xs text-gray-500 px-3 italic">No published documents yet.</div>
      </div>
    </template>

    <!-- Main content -->
    <div class="space-y-10" v-if="currentDocument">
      <!-- Document header -->
      <header class="space-y-4 pb-8 border-b border-[#262626]">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
          {{ currentDocument.title }}
        </h1>
        <div class="flex flex-wrap items-center gap-6 text-sm">
          <div class="flex flex-col gap-0.5">
            <span class="text-gray-500 font-bold uppercase tracking-wider text-[10px]">Application</span>
            <span class="text-white font-medium flex items-center gap-2">
              <div :class="cn('w-2 h-2 rounded-full', application.color || 'bg-indigo-500')" />
              {{ application.name }}
            </span>
          </div>
          <div class="h-8 w-px bg-[#262626]" />
          <div class="flex flex-col gap-0.5">
            <span class="text-gray-500 font-bold uppercase tracking-wider text-[10px]">Updated</span>
            <span class="text-white font-medium">{{ formatDate(currentDocument.updated_at) }}</span>
          </div>
          <div class="h-8 w-px bg-[#262626]" />
          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" />
            Published
          </span>
        </div>
      </header>

      <!-- Main intro content -->
      <div v-if="currentDocument.content" class="markdown-body" v-html="currentDocument.content" />

      <!-- Sections -->
      <div v-for="section in sections" :key="section.id" class="space-y-4 scroll-mt-20" >
        <div :id="section.id" class="space-y-4 pt-8 border-t border-[#1a1a1a] scroll-mt-24">
          <h2 class="text-2xl font-bold text-white">{{ section.sub_title }}</h2>
          <div class="markdown-body" v-html="section.content" />
        </div>
      </div>

      <!-- Empty sections state -->
      <div v-if="sections.length === 0" class="py-10 text-center text-gray-600 border border-dashed border-[#262626] rounded-2xl">
        <p class="text-sm">No sections have been added to this document yet.</p>
      </div>
    </div>

    <!-- No document selected -->
    <div v-else class="flex flex-col items-center justify-center py-24 text-gray-500">
      <h2 class="text-xl font-medium text-white mb-2">No document selected</h2>
      <p>Select a document from the left navigation.</p>
    </div>

    <!-- Right sidebar: TOC -->
    <template #right-sidebar v-if="toc && toc.length > 0">
      <div class="space-y-3">
        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">On this page</p>
        <nav class="space-y-1">
          <button
            v-for="item in toc"
            :key="item.id"
            @click="scrollTo(item.id)"
            :class="cn(
              'block w-full text-left px-0 py-1 text-sm transition-all border-l-2',
              activeId === item.id
                ? 'text-indigo-400 border-indigo-500 pl-3'
                : 'text-gray-500 hover:text-gray-300 border-transparent pl-3'
            )"
          >
            {{ item.title }}
          </button>
        </nav>
      </div>
    </template>
  </DocsLayout>
</template>

<style scoped>
.markdown-body :deep(h2) {
  font-size: 1.4rem; font-weight: 700; color: white;
  margin-top: 1.75rem; margin-bottom: 0.75rem;
}
.markdown-body :deep(h3) {
  font-size: 1.15rem; font-weight: 600; color: #e5e5e5;
  margin-top: 1.25rem; margin-bottom: 0.5rem;
}
.markdown-body :deep(p) { color: #9ca3af; line-height: 1.8; margin-bottom: 1rem; }
.markdown-body :deep(ul) { list-style-type: disc; padding-left: 1.5rem; color: #9ca3af; margin-bottom: 1rem; }
.markdown-body :deep(ol) { list-style-type: decimal; padding-left: 1.5rem; color: #9ca3af; margin-bottom: 1rem; }
.markdown-body :deep(li) { margin-bottom: 0.3rem; }
.markdown-body :deep(a) { color: #818cf8; text-decoration: underline; }
.markdown-body :deep(strong) { color: #e5e7eb; font-weight: 700; }
.markdown-body :deep(blockquote) {
  border-left: 3px solid #4f46e5; padding: 0.75rem 1rem;
  font-style: italic; color: #d1d5db;
  background: rgba(79,70,229,0.05); border-radius: 0.5rem; margin-bottom: 1rem;
}
.markdown-body :deep(pre) {
  background: #161616; border: 1px solid #262626;
  padding: 1rem; border-radius: 0.75rem; overflow-x: auto; margin-bottom: 1rem;
}
.markdown-body :deep(code) {
  font-family: monospace; font-size: 0.875rem;
  background: #262626; padding: 0.125rem 0.3rem; border-radius: 0.25rem;
}
.markdown-body :deep(pre code) { background: transparent; padding: 0; }
.markdown-body :deep(hr) { border-color: #262626; margin: 2rem 0; }
</style>
