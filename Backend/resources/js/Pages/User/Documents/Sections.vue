<script setup lang="ts">
import DocsLayout from '@/Layouts/DocsLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ArrowLeft, Plus, Trash2, GripVertical, Save, Loader2, ChevronDown, ChevronUp, Pencil } from 'lucide-vue-next'
import { ref } from 'vue'

const props = defineProps<{
  document: { id: number; title: string; slug: string };
  sections: Array<{ id: number; sub_title: string; content: string; sort_order: number }>;
}>()

// Add section form
const addForm = useForm({
    sub_title: '',
    content: '',
})

const submitAdd = () => {
    addForm.post(route('user.sections.store', props.document.id), {
        preserveScroll: true,
        onSuccess: () => addForm.reset(),
    })
}

// Edit section
const editingId = ref<number | null>(null)
const editForm = useForm({ sub_title: '', content: '' })

const startEdit = (s: any) => {
    editingId.value = s.id
    editForm.sub_title = s.sub_title
    editForm.content = s.content
}

const submitEdit = (sectionId: number) => {
    editForm.put(route('user.sections.update', { document: props.document.id, section: sectionId }), {
        preserveScroll: true,
        onSuccess: () => { editingId.value = null },
    })
}

const deleteSection = (sectionId: number) => {
    if (confirm('Delete this section? This cannot be undone.')) {
        router.delete(route('user.sections.destroy', { document: props.document.id, section: sectionId }), {
            preserveScroll: true,
        })
    }
}
</script>

<template>
  <DocsLayout wide>
    <Head :title="`Sections – ${document.title}`" />

    <template #left-sidebar>
      <div class="p-6 space-y-4">
        <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest px-2">Navigation</h3>
        <nav class="space-y-1">
          <Link :href="route('user.documents.index')" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-all">
            <ArrowLeft class="w-4 h-4 shrink-0" />
            Back to Documents
          </Link>
        </nav>

        <!-- Section list preview -->
        <div v-if="sections.length > 0" class="mt-6">
          <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-2">Sections</h3>
          <nav class="space-y-1">
            <div v-for="s in sections" :key="s.id" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm text-gray-400">
              <GripVertical class="w-3 h-3 text-gray-600 shrink-0" />
              <span class="truncate">{{ s.sub_title }}</span>
            </div>
          </nav>
        </div>
      </div>
    </template>

    <div class="space-y-8 max-w-4xl mx-auto">
      <!-- Header -->
      <div>
        <h1 class="text-3xl font-bold tracking-tight text-white mb-1">Manage Sections</h1>
        <p class="text-gray-500 text-sm">Document: <span class="text-indigo-400 font-medium">{{ document.title }}</span></p>
      </div>

      <!-- Existing sections -->
      <div class="space-y-4">
        <div v-if="sections.length === 0" class="bg-[#161616] border border-dashed border-[#262626] rounded-2xl p-10 text-center text-gray-500">
          <p class="text-sm">No sections yet. Add your first section below.</p>
        </div>

        <div v-for="(s, idx) in sections" :key="s.id" class="bg-[#161616] border border-[#262626] rounded-2xl overflow-hidden">
          <!-- Section header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-[#262626]">
            <div class="flex items-center gap-3">
              <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest bg-[#1a1a1a] border border-[#262626] rounded px-2 py-0.5">#{{ idx + 1 }}</span>
              <h3 class="text-white font-bold">{{ s.sub_title }}</h3>
            </div>
            <div class="flex items-center gap-1">
              <button @click="startEdit(s)" class="p-2 text-gray-500 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-all" title="Edit">
                <Pencil class="w-4 h-4" />
              </button>
              <button @click="deleteSection(s.id)" class="p-2 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Delete">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>

          <!-- Edit form -->
          <div v-if="editingId === s.id" class="p-6 space-y-4 bg-[#1a1a1a]">
            <div class="space-y-2">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sub Title</label>
              <input v-model="editForm.sub_title" type="text" required class="w-full bg-[#161616] border-[#262626] rounded-xl px-4 py-2.5 text-white focus:border-indigo-500 focus:ring-indigo-500 transition-all" />
              <div v-if="editForm.errors.sub_title" class="text-red-400 text-xs">{{ editForm.errors.sub_title }}</div>
            </div>
            <div class="space-y-2">
              <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Content <span class="text-gray-600 font-normal">(Markdown)</span></label>
              <textarea v-model="editForm.content" rows="8" required class="w-full bg-[#161616] border-[#262626] rounded-xl px-4 py-3 text-sm text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono transition-all"></textarea>
              <div v-if="editForm.errors.content" class="text-red-400 text-xs">{{ editForm.errors.content }}</div>
            </div>
            <div class="flex items-center gap-2 justify-end">
              <button @click="editingId = null" class="px-4 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">Cancel</button>
              <button @click="submitEdit(s.id)" :disabled="editForm.processing" class="flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all disabled:opacity-50">
                <Loader2 v-if="editForm.processing" class="w-4 h-4 animate-spin" />
                <Save v-else class="w-4 h-4" />
                Save
              </button>
            </div>
          </div>

          <!-- Preview content snippet -->
          <div v-else class="px-6 py-3 text-xs text-gray-600 font-mono line-clamp-2">
            {{ s.content.substring(0, 120) }}{{ s.content.length > 120 ? '...' : '' }}
          </div>
        </div>
      </div>

      <!-- Add new section form -->
      <div class="bg-[#161616] border border-indigo-500/20 rounded-2xl p-6 space-y-5">
        <div class="flex items-center gap-2 mb-2">
          <Plus class="w-4 h-4 text-indigo-400" />
          <h2 class="text-base font-bold text-white">Add New Section</h2>
        </div>

        <form @submit.prevent="submitAdd" class="space-y-4">
          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-300">Sub Title</label>
            <input 
              v-model="addForm.sub_title" 
              type="text" 
              required 
              placeholder="e.g. Getting Started"
              class="w-full bg-[#1a1a1a] border-[#262626] rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium"
            >
            <div v-if="addForm.errors.sub_title" class="text-red-400 text-xs">{{ addForm.errors.sub_title }}</div>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-300 flex items-center justify-between">
              Content
              <span class="text-xs text-gray-500 font-normal border border-[#262626] bg-[#1a1a1a] px-2 py-0.5 rounded">Supports Markdown</span>
            </label>
            <textarea 
              v-model="addForm.content" 
              rows="10" 
              required
              placeholder="Write the section content using Markdown..."
              class="w-full bg-[#1a1a1a] border-[#262626] rounded-xl px-4 py-3 text-sm text-gray-300 placeholder-gray-600 focus:border-indigo-500 focus:ring-indigo-500 font-mono transition-all"
            ></textarea>
            <div v-if="addForm.errors.content" class="text-red-400 text-xs">{{ addForm.errors.content }}</div>
          </div>

          <div class="flex justify-end">
            <button 
              type="submit" 
              :disabled="addForm.processing"
              class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-500/20 disabled:opacity-50"
            >
              <Loader2 v-if="addForm.processing" class="w-4 h-4 animate-spin" />
              <Plus v-else class="w-4 h-4" />
              Add Section
            </button>
          </div>
        </form>
      </div>
    </div>
  </DocsLayout>
</template>
