<template>
  <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-6 sm:px-10 fixed top-0 right-0 left-0 md:left-72 z-30 transition-all duration-300">
    
    <button @click.stop="$emit('toggle-menu')" class="md:hidden p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-xl mr-2 transition-colors">
      <Bars3Icon class="w-6 h-6" />
    </button>

    <div class="flex-1 max-w-lg hidden sm:block"  v-if="route.name === 'work-orders'">
      <div class="relative group">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
          <MagnifyingGlassIcon class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" />
        </span>
        <input 
            v-model="searchQuery" 
             @keyup.enter="performSearch"
            type="text" 
            placeholder="Search orders..." 
            class="w-full pl-11 pr-4 py-2.5 bg-gray-50/50 border border-gray-100 rounded-xl text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-200 transition-all"
        >
      </div>
    </div>

    <div class="flex items-center gap-5 ml-auto">
      
<div class="relative">
          <button @click="showNotifications = !showNotifications" class="relative group p-2 rounded-xl hover:bg-orange-50 transition-colors">
            <BellIcon class="w-6 h-6 text-gray-400 group-hover:text-orange-500 transition-colors" />
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white group-hover:scale-110 transition-transform"></span>
          </button>

          <div v-if="showNotifications" 
               class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in-up">
              <div class="px-4 py-2 border-b border-slate-50">
                  <h3 class="font-bold text-slate-800 text-sm">Notifications</h3>
              </div>
              <div class="p-4 text-center">
                  <div class="bg-orange-50 text-orange-600 p-3 rounded-lg mb-2 inline-flex">
                      <WrenchScrewdriverIcon class="w-6 h-6" />
                  </div>
                  <p class="text-sm font-bold text-slate-700">Under Development</p>
                  <p class="text-xs text-slate-400 mt-1">This feature will be available soon.</p>
              </div>
          </div>
          
          <div v-if="showNotifications" @click="showNotifications = false" class="fixed inset-0 z-40" style="background: transparent;"></div>
      </div>

      <div class="h-8 w-px bg-gray-100"></div>

      <div class="relative" ref="dropdownRef">
        <button @click="isDropdownOpen = !isDropdownOpen" class="flex items-center gap-3 focus:outline-none group">
          
          <div class="text-right hidden md:block transition-opacity duration-200">
            <p class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">
              {{ authStore.user?.name }}
            </p>
            <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wide">
              {{ authStore.user?.role }}
            </p>
          </div>
          
          <div class="relative">
             <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-blue-100 to-blue-50 text-blue-600 flex items-center justify-center font-bold border-2 border-white shadow-md group-hover:shadow-blue-200 transition-all duration-300">
                {{ getUserInitials() }}
             </div>
             <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
          </div>

          <ChevronDownIcon 
            class="w-4 h-4 text-gray-400 transition-transform duration-300 group-hover:text-blue-500 hidden sm:block"
            :class="isDropdownOpen ? 'rotate-180' : ''"
          />

        </button>

        <transition 
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95 -translate-y-2"
            enter-to-class="transform opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100 translate-y-0"
            leave-to-class="transform opacity-0 scale-95 -translate-y-2"
        >
            <div v-if="isDropdownOpen" class="absolute right-0 mt-4 w-56 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] border border-gray-100 py-2 z-50 overflow-hidden">
            
            <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/30 md:hidden">
                <p class="text-sm font-bold text-gray-800">{{ authStore.user?.name }}</p>
                <p class="text-xs text-gray-500">{{ authStore.user?.role }}</p>
            </div>
            
            <div class="p-2">
                <router-link to="/account" @click="isDropdownOpen = false" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <UserCircleIcon class="w-5 h-5" />
                    My Profile
                </router-link>
            </div>

            <div class="h-px bg-gray-50 mx-2 my-1"></div>
            
            <div class="p-2">
                <button @click="handleLogout" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-red-600 rounded-xl hover:bg-red-50 transition-colors text-left group">
                    <ArrowRightOnRectangleIcon class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                    Sign Out
                </button>
            </div>
            </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { 
  MagnifyingGlassIcon, BellIcon, Bars3Icon, 
  UserCircleIcon, ArrowRightOnRectangleIcon, Cog6ToothIcon, 
  ChevronDownIcon 
} from '@heroicons/vue/24/outline';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const isDropdownOpen = ref(false);
const dropdownRef = ref(null);
const showNotifications = ref(false);
const searchQuery = ref('');

defineEmits(['toggle-menu']);

const getUserInitials = () => {
  const name = authStore.user?.name;
  if (!name) return 'U';
  return name.substring(0, 2).toUpperCase();
};

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
};

const closeDropdown = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isDropdownOpen.value = false;
  }
};

const performSearch = () => {
    router.push({ 
        query: { 
            ...route.query, 
            search: searchQuery.value 
        } 
    });
};

onMounted(() => document.addEventListener('click', closeDropdown));
onUnmounted(() => document.removeEventListener('click', closeDropdown));
</script>