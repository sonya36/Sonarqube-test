<script setup lang="ts">
import DocsLayout from '@/Layouts/DocsLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { FileText, Save, ArrowLeft, Loader2, LayoutGrid, Users, Layers } from 'lucide-vue-next'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'

const page = usePage()
const user = computed(() => (page.props.auth as any).user)

const props = defineProps<{
  assignableApplications: Array<{ id: number; name: string }>;
}>()

const form = useForm({
    title: '',
    application_id: '',
    status: 'draft',
    content: ''
})

const userMenu = computed(() => {
  if (user.value?.role === 'admin') {
    return [
      { name: 'Dashboard', icon: LayoutGrid, active: false, href: route('admin.dashboard') },
      { name: 'User Management', icon: Users, active: false, href: route('admin.users.index') },
      { name: 'Application Management', icon: Layers, active: false, href: route('admin.applications.index') },
      { name: 'Documentation Management', icon: FileText, active: false, href: route('admin.documents.index') },
      { name: 'My Documents', icon: FileText, active: true, href: route('user.documents.index') }
    ]
  }

  return [
    { name: 'My Documents', icon: FileText, active: true, href: route('user.documents.index') }
  ]
})

const submit = () => {
    form.post(route('user.documents.store'))
}
</script>

<template>
  <DocsLayout wide>
    <Head title="Create Document" />

    <template #left-sidebar>
      <div class="p-6 space-y-8">
        <section>
          <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">
              {{ user?.role === 'admin' ? 'Admin Portal' : 'User Portal' }}
          </h3>
          <nav class="space-y-1">
            <Link 
              v-for="item in userMenu" 
              :key="item.name"
              :href="item.href || '#'"
              :class="cn(
                'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all group',
                item.active ? 'bg-indigo-500/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'
              )"
            >
              <component :is="item.icon" class="w-4 h-4 shrink-0" />
              {{ item.name }}
            </Link>
            
            <div class="pt-4 mt-4 border-t border-[#262626]">
                <Link 
                  :href="route('user.documents.index')"
                  class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-all group"
                >
                  <ArrowLeft class="w-4 h-4 shrink-0" />
                  Back to Documents
                </Link>
            </div>
          </nav>
        </section>
      </div>
    </template>

    <div class="space-y-8 max-w-4xl mx-auto">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-white mb-1">Create Document</h1>
          <p class="text-gray-500 text-sm">Create a new documentation article. You can add sections after publishing.</p>
        </div>
      </div>

      <form @submit.prevent="submit" class="bg-[#161616] border border-[#262626] rounded-3xl overflow-hidden shadow-2xl p-8 space-y-6">
          <!-- Title -->
          <div class="space-y-2">
              <label class="text-sm font-bold text-gray-300">Title</label>
              <input 
                  v-model="form.title" 
                  type="text" 
                  required
                  class="w-full bg-[#1a1a1a] border-[#262626] rounded-xl px-4 py-2.5 text-white placeholder-gray-600 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium"
                  placeholder="e.g. Onboarding Guide"
              >
              <div v-if="form.errors.title" class="text-red-400 text-xs mt-1">{{ form.errors.title }}</div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Application -->
              <div class="space-y-2">
                  <label class="text-sm font-bold text-gray-300">Application</label>
                  <select 
                      v-model="form.application_id" 
                      required
                      class="w-full bg-[#1a1a1a] border-[#262626] rounded-xl px-4 py-2.5 text-white focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium"
                  >
                      <option value="" disabled>Select an Application...</option>
                      <option v-for="app in assignableApplications" :key="app.id" :value="app.id">{{ app.name }}</option>
                  </select>
                  <div v-if="form.errors.application_id" class="text-red-400 text-xs mt-1">{{ form.errors.application_id }}</div>
              </div>

              <!-- Status -->
              <div class="space-y-2">
                  <label class="text-sm font-bold text-gray-300">Status</label>
                  <select 
                      v-model="form.status" 
                      required
                      class="w-full bg-[#1a1a1a] border-[#262626] rounded-xl px-4 py-2.5 text-white focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium"
                  >
                      <option value="draft">Draft</option>
                      <option value="published">Published</option>
                  </select>
                  <div v-if="form.errors.status" class="text-red-400 text-xs mt-1">{{ form.errors.status }}</div>
              </div>
          </div>

          <!-- Content -->
          <div class="space-y-2">
              <label class="text-sm font-bold text-gray-300 flex items-center justify-between">
                  Introduction / Overview
                  <span class="text-xs text-gray-500 font-normal border border-[#262626] bg-[#1a1a1a] px-2 py-0.5 rounded">Supports Markdown</span>
              </label>
              <textarea 
                  v-model="form.content" 
                  rows="10" 
                  required
                  class="w-full bg-[#1a1a1a] border-[#262626] rounded-xl px-4 py-3 text-sm text-gray-300 placeholder-gray-600 focus:border-indigo-500 focus:ring-indigo-500 font-mono transition-all"
                  placeholder="Write a brief introduction for this document..."
              ></textarea>
              <div v-if="form.errors.content" class="text-red-400 text-xs mt-1">{{ form.errors.content }}</div>
          </div>

          <div class="pt-4 border-t border-[#262626] flex items-center justify-between gap-3">
              <p class="text-xs text-gray-500">After creating, you can add sections with sub-titles from the document management page.</p>
              <div class="flex items-center gap-3">
                  <Link :href="route('user.documents.index')" class="px-4 py-2 text-sm font-bold text-gray-400 hover:text-white transition-colors">
                      Cancel
                  </Link>
                  <button 
                      type="submit" 
                      :disabled="form.processing"
                      class="flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-500/20 disabled:opacity-50"
                  >
                      <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                      <Save v-else class="w-4 h-4" />
                      Create Document
                  </button>
              </div>
          </div>
      </form>
    </div>
  </DocsLayout>
</template>
