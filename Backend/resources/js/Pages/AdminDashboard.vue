<script setup lang="ts">
import DocsLayout from '@/Layouts/DocsLayout.vue'
import SidebarNav from '@/Components/Docs/SidebarNav.vue'
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { Users, LayoutGrid, ShieldCheck, Activity } from 'lucide-vue-next'

const adminMenu = [
  { name: 'User Management', icon: Users, active: true },
  { name: 'Application Settings', icon: LayoutGrid },
  { name: 'Security & RBAC', icon: ShieldCheck },
  { name: 'System Logs', icon: Activity }
]

const applications = [
  { name: 'Human Resources', color: 'bg-indigo-500' },
  { name: 'Finance', color: 'bg-emerald-500' },
  { name: 'CRM', color: 'bg-orange-500' },
  { name: 'IT Support', color: 'bg-blue-500' }
]
</script>

<template>
  <DocsLayout>
    <!-- Left Sidebar Content -->
    <template #left-sidebar>
      <div class="p-6 space-y-8">
        <section>
          <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">Admin Portal</h3>
          <nav class="space-y-1">
            <Link 
              v-for="item in adminMenu" 
              :key="item.name"
              href="#"
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

        <section>
          <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-2">Quick Overview</h3>
          <SidebarNav :applications="applications" />
        </section>
      </div>
    </template>

    <!-- Main Content -->
    <div class="space-y-8">
      <header>
        <h1 class="text-3xl font-extrabold tracking-tight text-white mb-2">System Administration</h1>
        <p class="text-gray-500">Manage users, applications, and system-wide documentation policies.</p>
      </header>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-[#1a1a1a] border border-[#262626] p-6 rounded-2xl space-y-4 hover:border-indigo-500/30 transition-colors">
              <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">User Control Center</h4>
              <p class="text-gray-500 text-sm">Create, activate, or deactivate users. Assign roles and application permissions.</p>
              <button class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-900/20">Manage Users</button>
          </div>
          <div class="bg-[#1a1a1a] border border-[#262626] p-6 rounded-2xl space-y-4 hover:border-emerald-500/30 transition-colors">
              <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Application Manager</h4>
              <p class="text-gray-500 text-sm">Configure modules like HR, Finance, or CRM. Define icons and color systems.</p>
              <button class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20">Manage Apps</button>
          </div>
      </div>
    </div>
  </DocsLayout>
</template>
