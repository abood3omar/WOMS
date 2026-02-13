<template>
  <AppLayout>
    <div class="flex flex-col h-auto lg:h-[calc(100vh-9rem)]">
      
      <div class="flex-none mb-6 animate-fade-in-up px-1">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
          <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Logs 📜</h1>
            <p class="text-sm text-slate-500 mt-2">Audit trail of all system activities and security events.</p>
          </div>
          
          <div class="relative w-full md:w-80">
             <MagnifyingGlassIcon class="absolute left-3 top-3.5 w-5 h-5 text-slate-400" />
             <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Search logs, users, IPs..." class="pl-10 modern-input">
          </div>
        </div>
      </div>

      <div class="flex-1 bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col animate-fade-in-up animation-delay-100">
         
         <div class="hidden md:grid grid-cols-12 gap-4 p-5 border-b border-slate-100 bg-slate-50/50 text-xs font-bold text-slate-500 uppercase tracking-wider sticky top-0 z-10 backdrop-blur-sm">
            <div class="col-span-3">User</div>
            <div class="col-span-5">Action & Details</div>
            <div class="col-span-2">IP Address</div>
            <div class="col-span-2 text-right">Time</div>
         </div>

         <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
            
            <div v-if="loading" class="py-20 text-center text-slate-400 flex flex-col items-center gap-2">
               <div class="animate-spin w-8 h-8 border-4 border-slate-200 border-t-indigo-600 rounded-full"></div>
               Loading activity logs...
            </div>

            <div v-else-if="logs.length === 0" class="py-20 text-center text-slate-400 flex flex-col items-center gap-3">
               <DocumentMagnifyingGlassIcon class="w-16 h-16 text-slate-300" />
               <p>No logs found.</p>
            </div>

            <div v-else class="divide-y divide-slate-50">
               <div v-for="log in logs" :key="log.id" class="group hover:bg-slate-50/80 transition-colors p-4 md:px-6 md:py-4">
                  <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                     
                     <div class="col-span-1 md:col-span-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-sm"
                             :class="log.user ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400'">
                           {{ log.user ? getInitials(log.user.name) : '?' }}
                        </div>
                        <div class="flex flex-col">
                           <span class="text-sm font-bold text-slate-700">{{ log.user?.name || 'System / Deleted' }}</span>
                           <span class="text-[10px] text-slate-400">ID: {{ log.user_id || 'N/A' }}</span>
                        </div>
                     </div>

                     <div class="col-span-1 md:col-span-5">
                        <div class="flex items-start gap-3">
                           <div class="mt-0.5 p-1.5 rounded-lg shrink-0" :class="getActionColor(log.action)">
                              <component :is="getActionIcon(log.action)" class="w-4 h-4" />
                           </div>
                           <div>
                              <p class="text-sm font-bold text-slate-800">{{ log.action }}</p>
                              <p class="text-xs text-slate-500 mt-0.5 line-clamp-1" :title="log.details">{{ log.details }}</p>
                           </div>
                        </div>
                     </div>

                     <div class="col-span-1 md:col-span-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-50 border border-slate-100 font-mono text-xs text-slate-500">
                           <GlobeAltIcon class="w-3 h-3 text-slate-400" />
                           {{ log.ip_address || 'Unknown' }}
                        </span>
                     </div>

                     <div class="col-span-1 md:col-span-2 text-right">
                        <p class="text-xs font-medium text-slate-600">{{ formatTime(log.created_at) }}</p>
                        <p class="text-[10px] text-slate-400">{{ formatDate(log.created_at) }}</p>
                     </div>

                  </div>
               </div>
            </div>
         </div>

         <div class="flex-none p-4 border-t border-slate-100 bg-white flex justify-between items-center">
            <span class="text-xs font-medium text-slate-500">
               Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <div class="flex gap-2">
               <button @click="changePage(pagination.prev_page_url)" :disabled="!pagination.prev_page_url" class="page-btn">
                  <ChevronLeftIcon class="w-4 h-4" />
               </button>
               <button @click="changePage(pagination.next_page_url)" :disabled="!pagination.next_page_url" class="page-btn">
                  <ChevronRightIcon class="w-4 h-4" />
               </button>
            </div>
         </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
    MagnifyingGlassIcon, DocumentMagnifyingGlassIcon, GlobeAltIcon, 
    ChevronLeftIcon, ChevronRightIcon,
    PlusCircleIcon, PencilSquareIcon, TrashIcon, ArrowRightOnRectangleIcon, 
    ShieldCheckIcon, ExclamationTriangleIcon
} from '@heroicons/vue/24/outline';

const logs = ref([]);
const pagination = ref({});
const loading = ref(true);
const searchQuery = ref('');

const fetchLogs = async (url = '/logs') => {
    loading.value = true;
    try {
        const res = await axios.get(url, { params: { search: searchQuery.value } });
        if (res.data.data) {
            logs.value = res.data.data;
            pagination.value = res.data;
        } else {
            logs.value = res.data.logs || res.data;
            pagination.value = {};
        }
    } catch (e) { console.error(e); } 
    finally { loading.value = false; }
};

let debounceTimer;
const handleSearch = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchLogs(), 500);
};

const changePage = (url) => { if(url) fetchLogs(url); };

const getActionIcon = (action) => {
    const a = (action || '').toLowerCase();
    if (a.includes('create')) return PlusCircleIcon;
    if (a.includes('update') || a.includes('edit')) return PencilSquareIcon;
    if (a.includes('delete') || a.includes('remove')) return TrashIcon;
    if (a.includes('login')) return ArrowRightOnRectangleIcon;
    if (a.includes('permission') || a.includes('role')) return ShieldCheckIcon;
    return ExclamationTriangleIcon;
};

const getActionColor = (action) => {
    const a = (action || '').toLowerCase();
    if (a.includes('create')) return 'bg-emerald-100 text-emerald-600';
    if (a.includes('update')) return 'bg-blue-100 text-blue-600';
    if (a.includes('delete')) return 'bg-red-100 text-red-600';
    if (a.includes('login')) return 'bg-purple-100 text-purple-600';
    return 'bg-slate-100 text-slate-600';
};

const getInitials = (n) => n ? n.substring(0, 2).toUpperCase() : '?';
const formatTime = (d) => new Date(d).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
const formatDate = (d) => new Date(d).toLocaleDateString();

onMounted(() => fetchLogs());
</script>

<style scoped>
.modern-input {
    @apply block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-700 font-medium focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none bg-slate-50 focus:bg-white;
}
.page-btn {
    @apply p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all;
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 9999px; }
</style>