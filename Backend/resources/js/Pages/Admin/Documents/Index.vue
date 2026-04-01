<script setup lang="ts">
import DocsLayout from '@/Layouts/DocsLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { 
  Users, Layers, LayoutGrid, Activity, FileText,
  Plus, Edit2, Trash2, CheckCircle2, Clock, Eye,
  AlertCircle, Archive
} from 'lucide-vue-next'
import { ref, watch } from 'vue'

const props = defineProps<{
  documents: {
    data: Array<any>;
    links: Array<any>;
    current_page: number;
    last_page: number;
    total: number;
  };
  stats: {
    total: number;
    published: number;
    draft: number;
    archived: number;
  };
  filters: {
    search?: string;
  };
}>()

const adminMenu = [
  { name: 'Dashboard', icon: LayoutGrid, active: false, href: route('admin.dashboard') },
  { name: 'User Management', icon: Users, active: false, href: route('admin.users.index') },
  { name: 'Application Management', icon: Layers, active: false, href: route('admin.applications.index') },
  { name: 'Documentation Management', icon: FileText, active: true, href: route('admin.documents.index') },
  { name: 'My Documents', icon: FileText, active: false, href: route('user.documents.index') }
]

const search = ref(props.filters.search || '')

watch(search, (value) => {
    router.get(route('admin.documents.index'), { search: value }, {
        preserveState: true,
        replace: true
    })
})

const deleteDocument = (doc: any) => {
    if (confirm(`Are you sure you want to delete "${doc.title}"? This will move it to trash.`)) {
        router.delete(route('admin.documents.destroy', doc.id), {
            preserveScroll: true,
        })
    }
}

const statsItems = [
    { name: 'Total Documents', value: props.stats.total, icon: FileText, color: 'text-indigo-400', bg: 'bg-indigo-500/10' },
    { name: 'Published', value: props.stats.published, icon: CheckCircle2, color: 'text-emerald-400', bg: 'bg-emerald-500/10' },
    { name: 'Drafts', value: props.stats.draft, icon: Clock, color: 'text-orange-400', bg: 'bg-orange-500/10' },
    { name: 'Archived', value: props.stats.archived, icon: Archive, color: 'text-gray-400', bg: 'bg-gray-500/10' },
]
</script>

<template>
  <DocsLayout wide>
    <Head title="Documentation Management - Admin Portal" />

    <template #left-sidebar>
      <div class="p-6 space-y-8">
        <section>
          <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">Admin Portal</h3>
          <nav class="space-y-1">
            <Link 
              v-for="item in adminMenu" 
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
          </nav>
        </section>
      </div>
    </template>

    <div class="space-y-8 max-w-6xl">
      <!-- Header -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-white mb-1">Documentation Management</h1>
          <p class="text-gray-500 text-sm">Manage, audit, and organize all system documentations across applications.</p>
        </div>
        <div class="flex items-center gap-2">
            <Link :href="route('user.documents.create')" class="flex items-center gap-2 px-3 py-1.5 h-9 bg-white text-black rounded-lg text-xs font-bold hover:bg-gray-200 transition-all shadow-lg shadow-white/5">
                <Plus class="w-3.5 h-3.5" />
                New Document
            </Link>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div 
          v-for="stat in statsItems" 
          :key="stat.name"
          class="bg-[#161616] border border-[#262626] p-5 rounded-2xl hover:border-indigo-500/30 transition-all group"
        >
          <div class="flex items-center justify-between mb-3">
            <div :class="cn('w-8 h-8 rounded-lg flex items-center justify-center', stat.bg)">
                <component :is="stat.icon" :class="cn('w-4 h-4', stat.color)" />
            </div>
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ stat.name }}</span>
          </div>
          <div class="text-2xl font-extrabold text-white">{{ stat.value }}</div>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="flex items-center justify-between gap-4">
          <div class="relative flex-1 max-w-sm">
            <Activity class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" />
            <input 
                v-model="search"
                type="text" 
                placeholder="Search documents by title or slug..." 
                class="w-full bg-[#161616] border-[#262626] rounded-xl pl-10 pr-4 py-2 text-sm text-white placeholder-gray-600 focus:border-indigo-500 focus:ring-indigo-500 transition-all"
            >
          </div>
      </div>

      <!-- Documents Table -->
      <div class="bg-[#161616] border border-[#262626] rounded-3xl overflow-hidden shadow-2xl">
         <div class="overflow-x-auto">
             <table class="w-full text-left text-sm text-gray-400">
                 <thead class="bg-[#1a1a1a] text-xs uppercase text-gray-500 border-b border-[#262626]">
                     <tr>
                         <th class="px-6 py-4 font-bold tracking-wider">Document Title</th>
                         <th class="px-6 py-4 font-bold tracking-wider">Application</th>
                         <th class="px-6 py-4 font-bold tracking-wider">Author / Status</th>
                         <th class="px-6 py-4 font-bold tracking-wider">Version</th>
                         <th class="px-6 py-4 font-bold tracking-wider text-right">Actions</th>
                     </tr>
                 </thead>
                 <tbody class="divide-y divide-[#262626]">
                     <tr 
                         v-for="doc in props.documents.data" 
                         :key="doc.id" 
                         class="hover:bg-[#1f1f1f] transition-all group relative border-l-4 border-transparent"
                         :class="doc.application ? `border-l-${doc.application.color || 'indigo'}-500 hover:bg-${doc.application.color || 'indigo'}-500/[0.03]` : ''"
                     >
                         <td class="px-6 py-4">
                             <div class="flex items-center gap-3">
                                <div 
                                    class="w-9 h-9 rounded-xl bg-[#1a1a1a] border border-[#262626] flex flex-shrink-0 items-center justify-center transition-all shadow-inner group-hover:scale-110"
                                    :class="doc.application ? `text-${doc.application.color}-400 group-hover:border-${doc.application.color}-500/50` : 'text-indigo-400 group-hover:border-indigo-500/50'"
                                >
                                    <FileText class="w-4 h-4" />
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-white font-bold group-hover:text-indigo-300 transition-colors">{{ doc.title }}</span>
                                    <span class="text-[10px] text-gray-500 font-mono">{{ doc.slug }}</span>
                                </div>
                             </div>
                         </td>
                         <td class="px-6 py-4">
                             <div v-if="doc.application" class="flex items-center gap-2">
                                 <div 
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold border transition-all"
                                    :class="`bg-${doc.application.color || 'indigo'}-500/10 text-${doc.application.color || 'indigo'}-400 border-${doc.application.color || 'indigo'}-500/20`"
                                 >
                                     {{ doc.application.name }}
                                 </div>
                             </div>
                             <span v-else class="text-xs text-gray-600 italic">No Application</span>
                         </td>
                         <td class="px-6 py-4">
                             <div class="flex flex-col gap-1.5">
                                 <span class="text-[11px] font-medium text-gray-300 flex items-center gap-1">
                                     <Users class="w-3 h-3 text-gray-500" />
                                     {{ doc.author }}
                                 </span>
                                 <div :class="cn(
                                     'inline-flex items-center gap-1.5 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-widest w-fit',
                                     doc.status === 'published' ? 'bg-emerald-500/10 text-emerald-400' : 
                                     doc.status === 'draft' ? 'bg-orange-500/10 text-orange-400' : 'bg-gray-800 text-gray-400'
                                 )">
                                     <CheckCircle2 v-if="doc.status === 'published'" class="w-2.5 h-2.5" />
                                     <Clock v-if="doc.status === 'draft'" class="w-2.5 h-2.5" />
                                     <Archive v-if="doc.status === 'archived'" class="w-2.5 h-2.5" />
                                     {{ doc.status }}
                                 </div>
                             </div>
                         </td>
                         <td class="px-6 py-4">
                             <div class="flex flex-col">
                                 <span class="text-xs font-bold text-white">v{{ doc.version }}</span>
                                 <span class="text-[10px] text-gray-500">{{ doc.view_count }} views</span>
                             </div>
                         </td>
                         <td class="px-6 py-4 text-right">
                             <div class="flex items-center justify-end gap-1">
                                 <Link :href="route('app.show.doc', { appSlug: doc.application ? doc.application.slug : 'unassigned', docSlug: doc.slug })" class="p-2 text-gray-500 hover:text-white hover:bg-white/5 rounded-lg transition-all" title="View Document">
                                     <Eye class="w-4 h-4" />
                                 </Link>
                                 <Link :href="route('user.documents.edit', doc.id)" class="p-2 text-gray-500 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-all" title="Edit Document">
                                     <Edit2 class="w-4 h-4" />
                                 </Link>
                                 <button @click="deleteDocument(doc)" class="p-2 text-gray-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all" title="Delete Document">
                                     <Trash2 class="w-4 h-4" />
                                 </button>
                             </div>
                         </td>
                     </tr>
                     <tr v-if="props.documents.data.length === 0">
                         <td colspan="5" class="px-6 py-12 text-center">
                             <div class="flex flex-col items-center justify-center text-gray-500">
                                 <FileText class="w-12 h-12 mb-3 text-gray-700" />
                                 <p class="text-sm font-medium">No documents found.</p>
                                 <p class="text-xs">Try adjusting your search or create a new document.</p>
                             </div>
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>

         <!-- Pagination -->
         <div v-if="props.documents.last_page > 1" class="px-6 py-4 bg-[#1a1a1a] border-t border-[#262626] flex items-center justify-between">
             <span class="text-[11px] text-gray-500 font-bold uppercase tracking-widest">
                 Showing {{ props.documents.data.length }} of {{ props.documents.total }} documents
             </span>
             <div class="flex items-center gap-1">
                 <Link 
                     v-for="link in props.documents.links" 
                     :key="link.label"
                     :href="link.url || '#'"
                     v-html="link.label"
                     :class="cn(
                         'px-3 py-1 rounded text-xs font-bold transition-all',
                         link.active ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 
                         !link.url ? 'text-gray-700 cursor-not-allowed' : 'text-gray-500 hover:text-white hover:bg-white/5'
                     )"
                 />
             </div>
         </div>
      </div>
    </div>
  </DocsLayout>
</template>
