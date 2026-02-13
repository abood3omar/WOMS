<template>
  <AppLayout>
    <div class="space-y-6 animate-fade-in-up">
      
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-extrabold text-slate-900">Dashboard Overview </h1>
          <p class="text-slate-500 mt-1">Welcome back! Here's what's happening today.</p>
        </div>
        <div class="text-right hidden sm:block">
           <p class="text-2xl font-bold text-slate-700">{{ currentTime }}</p>
           <p class="text-xs text-slate-400 font-medium">{{ currentDate }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
         <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
               <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders</p>
               <h3 class="text-3xl font-black text-slate-800 mt-1">{{ stats.counts?.total || 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
               <ClipboardDocumentListIcon class="w-6 h-6" />
            </div>
         </div>

         <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
               <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending</p>
               <h3 class="text-3xl font-black text-yellow-500 mt-1">{{ stats.counts?.pending || 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center group-hover:scale-110 transition-transform">
               <ClockIcon class="w-6 h-6" />
            </div>
         </div>

         <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
               <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">In Progress</p>
               <h3 class="text-3xl font-black text-indigo-600 mt-1">{{ stats.counts?.in_progress || 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
               <WrenchScrewdriverIcon class="w-6 h-6" />
            </div>
         </div>

         <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all">
            <div>
               <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Completed</p>
               <h3 class="text-3xl font-black text-green-500 mt-1">{{ stats.counts?.completed || 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
               <CheckBadgeIcon class="w-6 h-6" />
            </div>
         </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
         
         <div class="lg:col-span-2 bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
               <h3 class="font-bold text-slate-800 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Recent Activity
               </h3>
               <router-link to="/logs" class="text-xs font-bold text-blue-600 hover:underline">View All</router-link>
            </div>
            
            <div class="p-0">
               <div v-if="!stats.recent_activity || stats.recent_activity.length === 0" class="p-8 text-center text-slate-400 text-sm">
                  No recent activity found.
               </div>
               <div v-else class="divide-y divide-slate-50">
                  <div v-for="log in stats.recent_activity" :key="log.id" class="p-4 hover:bg-slate-50 transition-colors flex items-start gap-4">
                     <div class="mt-1 p-2 rounded-lg bg-slate-100 text-slate-500">
                        <component :is="getActionIcon(log.action)" class="w-4 h-4" />
                     </div>
                     <div>
                        <p class="text-sm font-bold text-slate-800">
                           <span class="text-blue-600">{{ log.user?.name || 'System' }}</span> {{ formatAction(log.action) }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ log.details }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ timeAgo(log.created_at) }}</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="bg-orange-400 rounded-[1.5rem] shadow-lg p-6 text-white flex flex-col relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl -mr-10 -mt-10"></div>
            
            <h3 class="text-lg font-bold mb-1 relative z-10">System Status</h3>
            <p class="text-white text-sm mb-6 relative z-10">Everything is running smoothly.</p>

            <div class="space-y-4 flex-1 relative z-10">
               <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/10">
                  <div class="flex items-center gap-3">
                     <UserGroupIcon class="w-5 h-5 text-blue-400" />
                     <span class="text-sm font-bold">Active Users</span>
                  </div>
                  <span class="font-mono font-bold">{{ stats.counts?.users || 0 }}</span>
               </div>
               
               <router-link to="/work-orders" class="flex items-center justify-between p-3 rounded-xl bg-blue-600 hover:bg-blue-500 transition border border-blue-500/50 cursor-pointer group">
                  <div class="flex items-center gap-3">
                     <PlusIcon class="w-5 h-5 text-white" />
                     <span class="text-sm font-bold">Create Order</span>
                  </div>
                  <ChevronRightIcon class="w-4 h-4 text-white/70 group-hover:translate-x-1 transition-transform"/>
               </router-link>
            </div>
         </div>

      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
    ClipboardDocumentListIcon, ClockIcon, CheckBadgeIcon, WrenchScrewdriverIcon,
    UserGroupIcon, PlusIcon, ChevronRightIcon,
    PencilSquareIcon, TrashIcon, ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline';

const stats = ref({});
const currentTime = ref('');
const currentDate = ref('');
let timer;

const fetchStats = async () => {
    try {
        const res = await axios.get('/dashboard/stats');
        if(res.data) {
        stats.value = res.data;
        }
    } catch (e) { console.error(e); }
};

const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    currentDate.value = now.toLocaleDateString([], { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};

const getActionIcon = (action) => {
    if (action.includes('Create')) return PlusIcon;
    if (action.includes('Update') || action.includes('Status')) return PencilSquareIcon;
    if (action.includes('Delete')) return TrashIcon;
    return ArrowRightOnRectangleIcon;
};

const formatAction = (action) => {
    return action.replace('Create Order', 'created a new order')
                 .replace('Update Status', 'updated status')
                 .replace('Login', 'logged in');
};

const timeAgo = (date) => {
    const seconds = Math.floor((new Date() - new Date(date)) / 1000);
    let interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + " hours ago";
    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + " minutes ago";
    return "Just now";
};

onMounted(() => {
    fetchStats();
    updateTime();
    timer = setInterval(updateTime, 1000);
});

onUnmounted(() => clearInterval(timer));
</script>