<template>
  <AppLayout>
 <div class="flex flex-col h-auto lg:h-[calc(100vh-16rem)] min-h-[500px] pb-8 ">
      
      <div class="flex-none mb-6 animate-fade-in-up px-1">
        <div class="flex justify-between items-end">
          <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Work Orders 🔧</h1>
            <p class="text-sm text-slate-500 mt-2">Manage maintenance tasks efficiently.</p>
          </div>
          <button v-if="authStore.can('work_orders.create')" @click="openModal('create')" class="btn-primary shadow-blue-900/20">
            <PlusIcon class="w-5 h-5" />
            <span>New Order</span>
          </button>
        </div>

        <div class="flex gap-3 mt-6 overflow-x-auto pb-2 custom-scrollbar">
           <button v-for="status in ['pending', 'approved', 'in_progress', 'completed', 'rejected']" :key="status"
                   @click="changeFilter(status)" 
                   :class="filterStatus === status ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'" 
                   class="filter-chip capitalize">
              {{ formatStatus(status) }}
           </button>
           <button @click="changeFilter('')" :class="filterStatus === '' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 border-slate-200'" class="filter-chip">All</button>
        </div>
      </div>

      <div class="flex-1 bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col animate-fade-in-up animation-delay-100 min-h-[650px]">
        
        <div class="hidden md:grid grid-cols-12 gap-4 p-5 border-b border-slate-100 bg-slate-50/80 text-xs font-bold text-slate-500 uppercase tracking-wider backdrop-blur-sm sticky top-0 z-10">
           <div class="col-span-4">Task Details</div>
           <div class="col-span-2">Priority</div>
           <div class="col-span-2">Assigned To</div>
           <div class="col-span-2">Status</div>
           <div class="col-span-2 text-right">Actions</div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-3 relative">
           
           <div v-if="loading" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 z-20">
               <div class="animate-spin w-10 h-10 border-4 border-slate-200 border-t-blue-600 rounded-full mb-3"></div>
               <span class="text-slate-500 font-medium">Loading orders...</span>
           </div>

           <div v-else-if="workOrders.length === 0" class="h-full flex flex-col items-center justify-center text-slate-400 gap-3 min-h-[400px]">
               <ClipboardDocumentListIcon class="w-20 h-20 text-slate-200" />
               <p class="font-medium">No work orders found.</p>
           </div>

           <div v-else v-for="order in workOrders" :key="order.id" 
                class="group bg-white border border-slate-100 hover:border-blue-300 hover:shadow-lg rounded-2xl p-4 transition-all duration-200 cursor-pointer relative"
                @click="openDetailModal(order)">
              
              <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                 <div class="col-span-1 md:col-span-4">
                    <div class="flex items-start gap-4">
                       <div class="p-3 rounded-xl bg-slate-50 text-slate-600 font-mono text-sm font-bold border border-slate-200 h-12 w-16 flex items-center justify-center">
                          #{{ order.id }}
                       </div>
                       <div>
                          <h3 class="font-extrabold text-slate-900 text-lg leading-tight mb-1 line-clamp-1">{{ order.title }}</h3>
                          <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-medium text-slate-500">
                             <span class="flex items-center gap-1.5"><CalendarDaysIcon class="w-4 h-4 text-slate-400"/> {{ formatDate(order.created_at) }}</span>
                             <span v-if="order.due_date" class="flex items-center gap-1.5" :class="isOverdue(order.due_date, order.status) ? 'text-red-600 font-bold' : ''">
                                <ClockIcon class="w-4 h-4"/> {{ formatDate(order.due_date) }}
                             </span>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 text-sm font-bold" :class="priorityColor(order.priority)">
                       <span class="w-3 h-3 rounded-full bg-current shadow-sm"></span>
                       {{ capitalize(order.priority) }}
                    </div>
                 </div>

                 <div class="col-span-1 md:col-span-2">
                    <div v-if="order.assignee" class="flex items-center gap-3">
                       <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-200">
                          {{ getInitials(order.assignee.name) }}
                       </div>
                       <span class="text-sm font-bold text-slate-700 truncate">{{ order.assignee.name }}</span>
                    </div>
                    <span v-else class="text-sm text-slate-400 italic flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg w-fit">
                       <UserIcon class="w-4 h-4"/> Unassigned
                    </span>
                 </div>

                 <div class="col-span-1 md:col-span-2">
                    <span :class="statusStyle(order.status)" class="px-4 py-1.5 rounded-full text-xs font-extrabold border uppercase tracking-wide inline-block text-center min-w-[110px] shadow-sm">
                       {{ formatStatus(order.status) }}
                    </span>
                 </div>

                 <div class="col-span-1 md:col-span-2 flex justify-end gap-2" @click.stop>
                    <button @click="openModal('edit', order)" class="action-btn text-slate-400 hover:text-blue-600 hover:bg-blue-50" title="Edit">
                       <PencilSquareIcon class="w-5 h-5" />
                    </button>
                    <button @click="deleteOrder(order)" class="action-btn text-slate-400 hover:text-red-600 hover:bg-red-50" title="Delete">
                       <TrashIcon class="w-5 h-5" />
                    </button>
                 </div>
              </div>
           </div>
        </div>

        <div class="flex-none p-4 border-t border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-4 ">
            <span class="text-sm text-slate-500 font-medium">
                Showing <span class="font-bold text-slate-900">{{ pagination.from || 0 }}</span> to <span class="font-bold text-slate-900">{{ pagination.to || 0 }}</span> of <span class="font-bold text-slate-900">{{ pagination.total || 0 }}</span> results
            </span>
            
            <div class="flex items-center gap-2">
               <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="page-btn">
                  <ChevronLeftIcon class="w-4 h-4" />
               </button>

               <div class="hidden sm:flex gap-1">
                   <button v-for="page in pages" :key="page" 
                           @click="changePage(page)"
                           :class="pagination.current_page === page ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                           class="w-9 h-9 rounded-lg text-sm font-bold border transition-all flex items-center justify-center">
                       {{ page }}
                   </button>
               </div>

               <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="page-btn">
                  <ChevronRightIcon class="w-4 h-4" />
               </button>
            </div>
         </div>
      </div>

      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
         <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl"><ClipboardDocumentListIcon class="w-6 h-6"/></div>
                {{ modalMode === 'create' ? 'New Work Order' : 'Edit Work Order' }}
            </h3>
            <form @submit.prevent="saveOrder">
               <div class="space-y-5">
                  <div>
                     <label class="label">Title</label>
                     <input v-model="form.title" type="text" class="modern-input" required>
                  </div>
                  <div>
                     <label class="label">Description</label>
                     <textarea v-model="form.description" rows="3" class="modern-input"></textarea>
                  </div>
                  <div class="grid grid-cols-2 gap-5">
                     <div>
                        <label class="label">Priority</label>
                        <select v-model="form.priority" class="modern-input">
                           <option value="low">Low</option>
                           <option value="medium">Medium</option>
                           <option value="high">High</option>
                        </select>
                     </div>
                     <div>
                        <label class="label">Due Date</label>
                        <input v-model="form.due_date" type="date" class="modern-input">
                     </div>
                  </div>
                  <div>
                     <label class="label">Assign To</label>
                     <div class="relative">
                        <select v-model="form.assigned_to" class="modern-input appearance-none">
                            <option :value="null">Unassigned</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                        <div class="absolute right-4 top-3.5 text-slate-400 pointer-events-none"><UserIcon class="w-5 h-5"/></div>
                     </div>
                  </div>
               </div>
               <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                  <button type="button" @click="showModal = false" class="btn-secondary">Cancel</button>
                  <button type="submit" :disabled="saving" class="btn-primary">
                     <span v-if="saving" class="loader mr-2"></span> Save
                  </button>
               </div>
            </form>
         </div>
      </div>

      <div v-if="showDetailModal && selectedOrder" class="fixed inset-0 z-50 flex justify-end bg-slate-900/60 backdrop-blur-sm animate-fade-in" @click.self="closeDetailModal">
         <div class="w-full max-w-2xl bg-white h-full shadow-2xl p-0 flex flex-col transform transition-transform duration-300 animate-slide-in-right">
            <div class="p-6 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
               <div>
                  <div class="flex items-center gap-3 mb-2">
                     <span class="text-sm font-mono font-bold text-slate-400">#{{ selectedOrder.id }}</span>
                     <span :class="statusStyle(selectedOrder.status)" class="px-2.5 py-0.5 rounded text-[10px] font-bold border uppercase">{{ formatStatus(selectedOrder.status) }}</span>
                  </div>
                  <h2 class="text-2xl font-extrabold text-slate-900">{{ selectedOrder.title }}</h2>
               </div>
               <button @click="closeDetailModal" class="p-2 bg-white rounded-full border border-slate-200 text-slate-400 hover:text-slate-600 hover:shadow-sm transition">
                  <XMarkIcon class="w-5 h-5" />
               </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
               <div class="p-6 space-y-8">
                  <div class="grid grid-cols-2 gap-6 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                     <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Created By</span>
                        <div class="flex items-center gap-2">
                           <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold">{{ getInitials(selectedOrder.creator?.name) }}</div>
                           <span class="text-sm font-bold text-slate-700">{{ selectedOrder.creator?.name }}</span>
                        </div>
                     </div>
                     <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Created At</span>
                        <span class="text-sm font-bold text-slate-700">{{ formatDateTime(selectedOrder.created_at) }}</span>
                     </div>
                     <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Due Date</span>
                        <span class="text-sm font-bold text-slate-700">{{ formatDate(selectedOrder.due_date) }}</span>
                     </div>
                     <div class="col-span-2 pt-2 border-t border-slate-200/60 mt-1">
                        <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Description</span>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ selectedOrder.description || 'No description provided.' }}</p>
                     </div>
                  </div>

                  <div>
                     <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b pb-2">Actions</h3>
                     <div class="flex flex-col gap-4">
                        <div class="flex flex-wrap gap-2">
                           <span class="text-xs font-bold text-slate-400 mr-2 w-full mb-1">Set Status:</span>
                           <button v-for="status in ['pending', 'approved', 'in_progress', 'completed', 'rejected']" :key="status"
                                   @click="updateStatus(status)"
                                   :disabled="selectedOrder.status === status"
                                   class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                   :class="selectedOrder.status === status ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-400'">
                              {{ formatStatus(status) }}
                           </button>
                        </div>
                        
                        <div class="w-full mt-2">
                           <label class="text-xs font-bold text-slate-400 mr-2 uppercase">Re-Assign To:</label>
                           <div class="flex gap-2 mt-2">
                              <select v-model="assignToId" class="modern-input py-2 text-sm">
                                 <option :value="null">Unassigned</option>
                                 <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                              </select>
                              <button @click="assignOrder" :disabled="!assignToId || assignToId === selectedOrder.assigned_to" class="btn-primary py-2 px-4 text-xs">Assign</button>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div>
                     <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6 border-b pb-2">Activity History</h3>
                     <div class="relative pl-4 border-l-2 border-slate-100 space-y-6">
                        <div v-for="log in selectedOrder.logs" :key="log.id" class="relative group">
                           <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full border-2 border-white bg-slate-300 group-hover:bg-blue-500 transition-colors shadow-sm"></div>
                           <div>
                              <p class="text-sm font-bold text-slate-800">{{ log.action }}</p>
                              <p class="text-xs text-slate-500 mt-0.5">by <span class="font-bold text-slate-600">{{ log.performer?.name }}</span></p>
                              <p class="text-[10px] text-slate-400 mt-1">{{ formatDateTime(log.created_at) }}</p>
                           </div>
                        </div>
                        <div v-if="!selectedOrder.logs || selectedOrder.logs.length === 0" class="text-sm text-slate-400 italic">No activity recorded yet.</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
    PlusIcon, EyeIcon, XMarkIcon, UserIcon, 
    ClipboardDocumentListIcon, CalendarDaysIcon, ClockIcon,
    PencilSquareIcon, TrashIcon, ChevronLeftIcon, ChevronRightIcon
} from '@heroicons/vue/24/outline';

const route = useRoute();
const authStore = useAuthStore();
const workOrders = ref([]);
const users = ref([]);
const pagination = ref({});
const loading = ref(true);
const saving = ref(false);
const filterStatus = ref('');
const showModal = ref(false);
const modalMode = ref('create'); 
const showDetailModal = ref(false);
const selectedOrder = ref(null);
const assignToId = ref(null);

const form = ref({ 
    id: null, title: '', description: '', priority: 'medium', due_date: '', assigned_to: null 
});


const pages = computed(() => {
    if (!pagination.value.last_page) return [];
    let p = [];
    for (let i = 1; i <= pagination.value.last_page; i++) {
        p.push(i);
    }

    return p;
});

const fetchOrders = async (url = '/work-orders') => {
    loading.value = true;
    try {
        const params = {
        status: filterStatus.value,
        search: route.query.search 
        };
        if (!params.status) delete params.status;
        if (!params.search) delete params.search;
        if (filterStatus.value) params.status = filterStatus.value;
        const response = await axios.get(url, { params });
        workOrders.value = response.data.data;
        pagination.value = response.data;
    } catch (error) { console.error(error); } finally { loading.value = false; }
};

const fetchUsers = async () => {
    try {
        const res = await axios.get('/security/users'); 
        users.value = res.data.data || res.data;
    } catch (e) { console.error(e); }
};

const changeFilter = (status) => {
    filterStatus.value = status;
    fetchOrders('/work-orders'); 
};

const changePage = (page) => {
    if (page < 1 || page > pagination.value.last_page) return;
    fetchOrders(`/work-orders?page=${page}`);
};

const openModal = (mode, order = null) => {
    modalMode.value = mode;
    if (mode === 'edit' && order) {
        form.value = { 
            id: order.id,
            title: order.title, 
            description: order.description, 
            priority: order.priority, 
            due_date: order.due_date, 
            assigned_to: order.assigned_to 
        };
    } else {
        form.value = { title: '', description: '', priority: 'medium', due_date: '', assigned_to: null };
    }
    showModal.value = true;
};

const saveOrder = async () => {
    saving.value = true;
    try {
        const payload = { ...form.value };
        if (!payload.due_date) payload.due_date = null;

        if (modalMode.value === 'create') {
            await axios.post('/work-orders', payload);
        } else {
            await axios.put(`/work-orders/${form.value.id}`, payload);
        }

        showModal.value = false;
        await fetchOrders();
    } catch (error) { 
        console.error(error);
        alert(error.response?.data?.message || 'Error processing request');
    } 
    finally { saving.value = false; }
};

const deleteOrder = async (order) => {
 
    try {
        await axios.delete(`/work-orders/${order.id}`);
        await fetchOrders();
    } catch (e) { alert('Failed to delete order'); }
};

const openDetailModal = async (order) => {
    try {
        const res = await axios.get(`/work-orders/${order.id}`);
        selectedOrder.value = res.data;
        assignToId.value = res.data.assigned_to;
        showDetailModal.value = true;
    } catch (e) { alert('Error fetching details'); }
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedOrder.value = null;
    fetchOrders(); 
};

const updateStatus = async (status) => {
    try {
        await axios.put(`/work-orders/${selectedOrder.value.id}/status`, { status });
        const res = await axios.get(`/work-orders/${selectedOrder.value.id}`);
        selectedOrder.value = res.data;
    } catch (e) { alert('Failed to update status'); }
};

const assignOrder = async () => {
    try {
        await axios.put(`/work-orders/${selectedOrder.value.id}/assign`, { assigned_to: assignToId.value });
        const res = await axios.get(`/work-orders/${selectedOrder.value.id}`);
        selectedOrder.value = res.data;
    } catch (e) { alert('Failed to assign'); }
};

const formatDate = (date) => date ? new Date(date).toLocaleDateString() : 'No Date';
const formatDateTime = (date) => date ? new Date(date).toLocaleString() : '';
const getInitials = (n) => n ? n.substring(0, 2).toUpperCase() : '??';
const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const formatStatus = (s) => s.replace('_', ' ');

const isOverdue = (date, status) => {
    if (!date || status === 'completed' || status === 'rejected') return false;
    return new Date(date) < new Date();
};

const statusStyle = (s) => {
    const map = {
        'pending': 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'approved': 'bg-purple-100 text-purple-700 border-purple-200',
        'in_progress': 'bg-blue-100 text-blue-700 border-blue-200',
        'completed': 'bg-green-100 text-green-700 border-green-200',
        'rejected': 'bg-red-100 text-red-700 border-red-200'
    };
    return map[s] || 'bg-gray-100 text-gray-700';
};

const priorityColor = (p) => {
    if (p === 'high') return 'text-red-600';
    if (p === 'medium') return 'text-orange-500';
    return 'text-slate-500';
};

watch(
    () => route.query.search,
    (newSearch) => {
        fetchOrders();
    }
);

onMounted(() => {
    fetchOrders();
    fetchUsers();
});
</script>

<style scoped>
.modern-input {
    @apply block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-700 font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none bg-slate-50 focus:bg-white;
}
.label {
    @apply block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider;
}
.btn-primary {
    @apply px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold flex items-center justify-center gap-2 transition-all disabled:opacity-70;
}
.btn-secondary {
    @apply px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all;
}
.action-btn {
    @apply p-2.5 rounded-xl transition border border-transparent hover:border-slate-200 bg-slate-50 hover:bg-white;
}
.page-btn {
    @apply w-9 h-9 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-all disabled:opacity-50 disabled:cursor-not-allowed;
}
.filter-chip {
    @apply px-4 py-2 rounded-full text-xs font-bold border transition-all whitespace-nowrap hover:shadow-sm;
}
.loader {
    @apply animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full;
}
.animate-slide-in-right {
    animation: slide-in-right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes slide-in-right {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 9999px; }
</style>