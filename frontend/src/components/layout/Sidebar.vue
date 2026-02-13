<template>
  <div v-if="isOpen" @click="$emit('close')" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 md:hidden transition-opacity"></div>

  <aside 
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 flex flex-col transition-transform duration-300 ease-in-out md:translate-x-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]"
    :class="isOpen ? 'translate-x-0' : '-translate-x-full'"
  >
    <div class="h-20 flex items-center px-8 border-b border-gray-50">
      <div class="flex items-center gap-3 group cursor-pointer">
        <div class="p-2 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors">
            <img src="@/assets/shabakat.png" alt="Logo" class="h-6 w-auto">
        </div>
        <div>
          <h1 class="text-lg font-bold text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors">Shabakat</h1>
          <p class="text-[10px] text-orange-500 font-bold tracking-widest uppercase">WOMS System</p>
        </div>
      </div>
      
      <button @click="$emit('close')" class="md:hidden ml-auto text-gray-400 hover:text-red-500 transition-colors">
        <XMarkIcon class="w-6 h-6" />
      </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
      
      <div class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</div>

      <router-link v-if="authStore.can('dashboard.view')" to="/" @click="$emit('close')" class="nav-item group" :class="$route.path === '/' ? 'active' : ''">
        <HomeIcon class="w-5 h-5 transition-colors group-hover:text-blue-600" :class="$route.path === '/'  ? 'text-blue-600' : 'text-gray-400'" />
        <span class="font-medium">Dashboard</span>
        <div class="active-indicator"></div>
      </router-link>

      <router-link v-if="authStore.can('work_orders.view')" to="/work-orders" @click="$emit('close')" class="nav-item group" :class="$route.path.includes('work-orders') ? 'active' : ''">
        <ClipboardDocumentListIcon class="w-5 h-5 transition-colors group-hover:text-blue-600" :class="$route.path.includes('work-orders') ? 'text-blue-600' : 'text-gray-400'" />
        <span class="font-medium">Work Orders</span>
        <div class="active-indicator"></div>
      </router-link>

      <router-link v-if="authStore.can('logs.view')" to="/logs" @click="$emit('close')" class="nav-item group" :class="$route.path.includes('logs') ? 'active' : ''">
        <ClockIcon class="w-5 h-5 transition-colors group-hover:text-blue-600" :class="$route.path.includes('logs') ? 'text-blue-600' : 'text-gray-400'" />
        <span class="font-medium">System Logs</span>
        <div class="active-indicator"></div>
      </router-link>

      <router-link v-if="authStore.can('my_orders.view')" to="/my-orders" @click="$emit('close')" class="nav-item group" :class="$route.path.includes('my-orders') ? 'active' : ''">
        <BriefcaseIcon class="w-5 h-5 transition-colors group-hover:text-blue-600" :class="$route.path.includes('my-orders') ? 'text-blue-600' : 'text-gray-400'" />
        <span class="font-medium">My Orders</span>
        <div class="active-indicator"></div>
      </router-link>

      <div class="my-6 border-t border-gray-50"></div>

      <div v-if="authStore.can('system_modules.view') || authStore.can('role_rights.view') || authStore.can('users.view')">
          <div class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administration</div>

          <div class="relative">
            <button @click="isSecurityOpen = !isSecurityOpen" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl text-gray-600 hover:bg-gray-50 transition-all duration-200 group">
              <div class="flex items-center gap-3">
                <ShieldCheckIcon class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition-colors" />
                <span class="group-hover:text-gray-900">Security</span>
              </div>
              <ChevronDownIcon class="w-4 h-4 text-gray-300 transition-transform duration-300 group-hover:text-orange-400" :class="isSecurityOpen ? 'rotate-180' : ''" />
            </button>

            <div v-show="isSecurityOpen" class="mt-1 space-y-1 relative">
                <div class="absolute left-6 top-0 bottom-0 w-px bg-gray-100"></div>

                <router-link v-if="authStore.can('system_modules.view')" to="/security/modules" @click="$emit('close')" class="sub-nav-item group" :class="$route.path.includes('/security/modules') ? 'active-sub' : ''">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-3 group-hover:bg-orange-400 transition-colors" :class="$route.path.includes('/security/modules') ? '!bg-blue-600' : ''"></span>
                    System Modules
                </router-link>

                <router-link v-if="authStore.can('role_rights.view')" to="/security/roles" @click="$emit('close')" class="sub-nav-item group" :class="$route.path.includes('/security/roles') ? 'active-sub' : ''">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-3 group-hover:bg-orange-400 transition-colors" :class="$route.path.includes('/security/roles') ? '!bg-blue-600' : ''"></span>
                    Role Rights
                </router-link>

                <router-link v-if="authStore.can('users.view')" to="/security/users" @click="$emit('close')" class="sub-nav-item group" :class="$route.path.includes('/security/users') ? 'active-sub' : ''">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-3 group-hover:bg-orange-400 transition-colors" :class="$route.path.includes('/security/users') ? '!bg-blue-600' : ''"></span>
                    Users
                </router-link>            
            </div>
          </div>
      </div>

    </nav>
    
    <div class="p-6 border-t border-gray-50 bg-white">
      <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
        <div class="relative flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-700">System Online</p>
            <p class="text-xs font-bold text-blue-600">{{ authStore.user?.role || 'User' }}</p>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth'; 
import { 
  HomeIcon, ClipboardDocumentListIcon, ClockIcon, ShieldCheckIcon, 
  XMarkIcon, ChevronDownIcon, BriefcaseIcon 
} from '@heroicons/vue/24/outline';

defineProps({ isOpen: Boolean });
defineEmits(['close']);

const authStore = useAuthStore(); 
const isSecurityOpen = ref(false);
</script>

<style scoped>
.nav-item {
  @apply relative flex items-center gap-3 px-4 py-3 text-sm text-gray-500 rounded-xl transition-all duration-300 overflow-hidden;
}

.nav-item:hover {
  @apply bg-blue-50/50 text-blue-600 translate-x-1;
}

.nav-item.active {
  @apply bg-blue-50 text-blue-700 font-semibold shadow-sm;
}

.active-indicator {
  @apply absolute left-0 top-2 bottom-2 w-1 bg-orange-500 rounded-r-full opacity-0 transition-opacity duration-300;
}

.nav-item.active .active-indicator {
  @apply opacity-100;
}

.sub-nav-item {
  @apply flex items-center pl-9 py-2.5 text-sm text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-50 transition-colors ml-2;
}
.active-sub {
  @apply text-blue-600 font-medium bg-blue-50/30;
}
</style>