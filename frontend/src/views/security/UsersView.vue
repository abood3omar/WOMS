<template>
  <AppLayout>
    <div class="flex flex-col h-auto lg:h-[calc(100vh-9rem)]">
      
      <div class="flex-none mb-6 animate-fade-in-up px-1">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
          <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Users Management 👥</h1>
            <p class="text-sm md:text-base text-slate-500 mt-2">Manage system users and access roles.</p>
          </div>
          
          <div class="flex flex-wrap gap-3 w-full md:w-auto">
             <div class="relative flex-1 md:w-64">
                <MagnifyingGlassIcon class="absolute left-3 top-3.5 w-5 h-5 text-slate-400" />
                <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Search users..." class="pl-10 modern-input">
             </div>

             <button @click="openModal('add')" class="btn-primary flex-shrink-0">
                <PlusIcon class="w-5 h-5" />
                <span class="hidden sm:inline">Add User</span>
             </button>
          </div>
        </div>
      </div>

      <div class="flex-1 bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col animate-fade-in-up animation-delay-100">
         
         <div class="hidden md:grid grid-cols-12 gap-4 p-5 border-b border-slate-100 bg-slate-50/50 text-xs font-bold text-slate-500 uppercase tracking-wider">
            <div class="col-span-6">User Details</div>
            <div class="col-span-4">Role</div>
            <div class="col-span-2 text-right">Actions</div>
         </div>

         <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-2">
            
            <div v-if="loading" class="py-20 text-center text-slate-400 flex flex-col items-center gap-2">
               <div class="animate-spin w-8 h-8 border-4 border-slate-200 border-t-blue-600 rounded-full"></div>
               Loading users...
            </div>

            <div v-else-if="users.length === 0" class="py-20 text-center text-slate-400 flex flex-col items-center gap-3">
               <UserGroupIcon class="w-16 h-16 text-slate-300" />
               <p>No users found.</p>
            </div>

            <div v-else v-for="user in users" :key="user.id" 
                 class="group bg-white border border-transparent hover:border-blue-200 hover:shadow-md rounded-xl p-4 transition-all duration-200">
               
               <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                  
                  <div class="col-span-1 md:col-span-6 flex items-center gap-4">
                     <div class="w-12 h-12 flex-shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md ring-2 ring-white">
                        {{ getInitials(user.name) }}
                     </div>
                     <div class="min-w-0">
                        <h3 class="font-bold text-slate-800 text-base truncate">{{ user.name }}</h3>
                        <p class="text-sm text-slate-500 truncate">{{ user.email }}</p>
                     </div>
                  </div>

                  <div class="col-span-1 md:col-span-4 flex md:block">
                     <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border"
                           :class="user.role ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 'bg-slate-100 text-slate-500 border-slate-200'">
                        <ShieldCheckIcon v-if="user.role" class="w-3 h-3" />
                        {{ user.role?.RoleName || 'No Role' }}
                     </span>
                  </div>

                  <div class="col-span-1 md:col-span-2 flex justify-end gap-2 border-t md:border-t-0 pt-3 md:pt-0 border-slate-50">
                     <button @click="openPasswordModal(user)" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Change Password">
                        <KeyIcon class="w-5 h-5" />
                     </button>
                     <button @click="openModal('edit', user)" class="p-2 text-slate-400 hover:text-yellow-500 hover:bg-yellow-50 rounded-lg transition" title="Edit User">
                        <PencilSquareIcon class="w-5 h-5" />
                     </button>
                     <button @click="deleteUser(user)" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete User">
                        <TrashIcon class="w-5 h-5" />
                     </button>
                  </div>
               </div>
            </div>
         </div>

         <div class="flex-none p-4 border-t border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-sm text-slate-500">
                Showing <span class="font-bold">{{ pagination.from || 0 }}</span> to <span class="font-bold">{{ pagination.to || 0 }}</span> of <span class="font-bold">{{ pagination.total || 0 }}</span> results
            </span>
            <div class="flex gap-2">
               <button @click="changePage(pagination.prev_page_url)" :disabled="!pagination.prev_page_url" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-50 text-sm font-bold transition-colors">Previous</button>
               <button @click="changePage(pagination.next_page_url)" :disabled="!pagination.next_page_url" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-50 text-sm font-bold transition-colors">Next</button>
            </div>
         </div>
      </div>

      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
         <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-6">{{ modalMode === 'add' ? 'Add New User' : 'Edit User' }}</h3>
            
            <form @submit.prevent="saveUser">
               <div class="space-y-4">
                  <div>
                     <label class="label">Full Name</label>
                     <input v-model="form.name" type="text" class="modern-input" required>
                  </div>
                  
                  <div>
                     <label class="label">Email Address</label>
                     <input v-model="form.email" type="email" class="modern-input" required>
                  </div>

                  <div>
                     <label class="label">Assign Role</label>
                     <div class="relative">
                        <select v-model="form.RoleID" class="modern-input appearance-none" required>
                            <option value="" disabled>Select a Role</option>
                            <option v-for="role in roles" :key="role.RoleID" :value="role.RoleID">{{ role.RoleName }}</option>
                        </select>
                        <div class="absolute right-4 top-3.5 text-slate-400 pointer-events-none">
                            <ChevronDownIcon class="w-5 h-5"/>
                        </div>
                     </div>
                  </div>

                  <div v-if="modalMode === 'add'">
                     <label class="label">Password</label>
                     <input v-model="form.password" type="password" class="modern-input" required placeholder="••••••••">
                  </div>
               </div>

               <div class="flex justify-end gap-3 mt-8">
                  <button type="button" @click="showModal = false" class="btn-secondary">Cancel</button>
                  <button type="submit" :disabled="saving" class="btn-primary">
                     <span v-if="saving" class="loader mr-2"></span> {{ modalMode === 'add' ? 'Create' : 'Update' }}
                  </button>
               </div>
            </form>
         </div>
      </div>

      <div v-if="showPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
         <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Change Password</h3>
            <p class="text-sm text-slate-500 mb-6">For user: <strong>{{ currentUser?.name }}</strong></p>
            
            <form @submit.prevent="changePassword">
               <div class="space-y-4">
                  <div>
                     <label class="label">New Password</label>
                     <input v-model="passwordForm.new_password" type="password" class="modern-input" required>
                  </div>
                  <div>
                     <label class="label">Confirm Password</label>
                     <input v-model="passwordForm.new_password_confirmation" type="password" class="modern-input" required>
                  </div>
               </div>

               <div class="flex justify-end gap-3 mt-8">
                  <button type="button" @click="showPasswordModal = false" class="btn-secondary">Cancel</button>
                  <button type="submit" :disabled="saving" class="btn-primary bg-teal-600 hover:bg-teal-700">
                     <span v-if="saving" class="loader mr-2"></span> Update
                  </button>
               </div>
            </form>
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
    MagnifyingGlassIcon, PlusIcon, PencilSquareIcon, TrashIcon, 
    ShieldCheckIcon, KeyIcon, UserGroupIcon, UserIcon, 
    ChevronDownIcon, ClockIcon 
} from '@heroicons/vue/24/outline';

const users = ref([]);
const roles = ref([]);
const pagination = ref({});
const searchQuery = ref('');
const loading = ref(false);
const saving = ref(false);

const showModal = ref(false);
const showPasswordModal = ref(false);
const modalMode = ref('add');
const currentUser = ref(null);
const form = ref({ id: null, name: '', email: '', RoleID: '', password: '' });
const passwordForm = ref({ new_password: '', new_password_confirmation: '' });

const fetchUsers = async (url = '/security/users') => {
    loading.value = true;
    try {
        const res = await axios.get(url, { params: { search: searchQuery.value } });
        users.value = res.data.data;
        pagination.value = res.data;
    } catch (error) {
        console.error('Fetch users error:', error);
    } finally {
        loading.value = false;
    }
};

const fetchRoles = async () => {
    try {
        const res = await axios.get('/security/roles');
        roles.value = res.data.roles || res.data; 
    } catch (e) { console.error(e); }
};

let debounceTimer;
const handleSearch = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => { fetchUsers(); }, 500);
};

const changePage = (url) => { if (url) fetchUsers(url); };

const openModal = (mode, user = null) => {
    modalMode.value = mode;
    if (mode === 'edit' && user) {
        form.value = { 
            id: user.id, 
            name: user.name, 
            email: user.email, 
            RoleID: user.RoleID, 
            password: '' 
        };
    } else {
        form.value = { id: null, name: '', email: '', RoleID: '', password: '' };
    }
    showModal.value = true;
};

const saveUser = async () => {
    saving.value = true;
    try {
        if (modalMode.value === 'add') {
            await axios.post('/security/users', form.value);
        } else {
            await axios.put(`/security/users/${form.value.id}`, form.value);
        }
        await fetchUsers();
        showModal.value = false;
    } catch (error) {
        const msg = error.response?.data?.message || 'Error saving user';
        alert(msg);
    } finally {
        saving.value = false;
    }
};

const deleteUser = async (user) => {

    try {
        await axios.delete(`/security/users/${user.id}`);
        await fetchUsers();
    } catch (error) {
        alert('Delete failed');
    }
};

const openPasswordModal = (user) => {
    currentUser.value = user;
    passwordForm.value = { new_password: '', new_password_confirmation: '' };
    showPasswordModal.value = true;
};

const changePassword = async () => {
    saving.value = true;
    try {
        await axios.put(`/security/users/${currentUser.value.id}/password`, passwordForm.value);
        showPasswordModal.value = false;
    } catch (error) {
        alert(error.response?.data?.message || 'Error updating password');
    } finally {
        saving.value = false;
    }
};

const getInitials = (name) => name ? name.substring(0, 2).toUpperCase() : 'U';

onMounted(() => {
    fetchUsers();
    fetchRoles();
});
</script>

<style scoped>
.modern-input {
    @apply block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-700 font-medium focus:border-slate-800 focus:ring-4 focus:ring-slate-800/10 transition-all outline-none bg-slate-50 focus:bg-white;
}
.label {
    @apply block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider;
}
.btn-primary {
    @apply px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold shadow-lg shadow-slate-900/20 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-70 disabled:cursor-not-allowed;
}
.btn-secondary {
    @apply px-5 py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all disabled:opacity-70;
}
.loader {
    @apply animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full;
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 9999px; }
</style>