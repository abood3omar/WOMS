<template>
  <AppLayout>
    
    <div class="flex flex-col h-auto lg:h-[calc(100vh-9rem)] min-h-[600px]">
      
      <div class="flex-none mb-6 lg:mb-8 animate-fade-in-up px-1">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Roles & Permissions 🛡️</h1>
        <p class="text-sm md:text-base text-slate-500 mt-2">Manage user roles and assign access rights across the system.</p>
      </div>

      <div class="flex-1 flex flex-col lg:flex-row gap-6 lg:overflow-hidden animate-fade-in-up animation-delay-100 pb-2">
        
        <div class="flex-none w-full lg:w-1/3 xl:w-1/4 h-[500px] lg:h-full flex flex-col gap-4">
          
          <button @click="showAddModal = true" class="flex-none w-full py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold shadow-lg shadow-slate-900/20 flex items-center justify-center gap-3 transition-all hover:-translate-y-1 active:scale-95 text-base">
             <PlusIcon class="w-6 h-6" />
             Create New Role
          </button>

          <div class="flex-1 bg-white rounded-[1.5rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col">
             <div class="flex-none p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Roles List</h3>
                <span class="text-xs bg-slate-200 text-slate-600 px-2.5 py-1 rounded-full font-bold">{{ roles.length }}</span>
             </div>
             
             <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-2.5">
                <div v-if="loading" class="text-center py-10 text-slate-400 flex flex-col items-center gap-2">
                    <div class="animate-spin w-5 h-5 border-2 border-slate-300 border-t-blue-600 rounded-full"></div>
                    <span class="text-xs">Loading...</span>
                </div>
                
                <div v-for="role in roles" :key="role.RoleID" 
                     @click="selectRole(role)"
                     class="group relative p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 flex justify-between items-center select-none"
                     :class="selectedRole?.RoleID === role.RoleID 
                        ? 'border-blue-600 bg-blue-50 ring-1 ring-blue-200' 
                        : 'border-transparent bg-slate-50 hover:bg-white hover:border-slate-200 hover:shadow-sm'">
                   
                   <div class="flex items-center gap-4 min-w-0">
                      <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors shadow-sm ring-2 ring-offset-2"
                           :class="selectedRole?.RoleID === role.RoleID ? 'bg-blue-600 text-white ring-blue-100' : 'bg-white text-slate-500 ring-slate-100 group-hover:ring-slate-200'">
                         {{ role.RoleName.substring(0,2).toUpperCase() }}
                      </div>
                      <div class="flex flex-col min-w-0">
                          <span class="font-bold text-slate-800 text-base truncate" :class="selectedRole?.RoleID === role.RoleID ? 'text-blue-700' : ''">
                              {{ role.RoleName }}
                          </span>
                          <span class="text-xs text-slate-400 truncate">{{ role.permissions.length }} active permissions</span>
                      </div>
                   </div>

                   <button @click.stop="deleteRole(role)" 
                           class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100 focus:opacity-100">
                      <TrashIcon class="w-5 h-5" />
                   </button>
                </div>
             </div>
          </div>
        </div>

        <div class="flex-1 flex flex-col h-auto lg:h-full min-h-0">
           
           <div v-if="selectedRole" class="flex-1 bg-white rounded-[1.5rem] shadow-sm border border-slate-200 flex flex-col overflow-hidden relative animate-fade-in min-h-[500px]">
              
              <div class="flex-none px-6 py-5 border-b border-slate-100 bg-white sticky top-0 z-30 shadow-sm flex flex-wrap gap-4 justify-between items-center">
                 <div class="flex items-center gap-4 overflow-hidden">
                    <div class="hidden sm:flex p-3 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 flex-shrink-0">
                        <ShieldCheckIcon class="w-7 h-7" />
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <input v-model="selectedRole.RoleName" type="text" class="text-xl md:text-2xl font-black text-slate-900 bg-transparent border-b-2 border-transparent hover:border-slate-300 focus:border-blue-500 focus:outline-none transition-colors px-1 w-full max-w-[200px] truncate" />
                            <PencilIcon class="w-5 h-5 text-slate-400 flex-shrink-0" />
                        </div>
                        <p class="text-sm text-slate-500 font-medium truncate">Configuring permissions for #{{ selectedRole.RoleID }}</p>
                    </div>
                 </div>
                 
                 <button @click="savePermissions" :disabled="saving" class="flex-shrink-0 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold shadow-lg shadow-slate-900/10 transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed hover:-translate-y-0.5 active:translate-y-0 text-sm">
                    <span v-if="saving" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    <span v-else>Save Changes</span>
                 </button>
              </div>

              <div class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-slate-50 relative">
                 
                 <div v-for="module in systemStructure" :key="module.ModuleID" class="mb-10 last:mb-0 relative">
                    
                    <div class=" top-0 z-20 bg-slate-50 py-4 -mt-4 mb-2 flex items-center gap-4">
                       <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest bg-white px-4 py-1.5 rounded-lg border border-slate-200 shadow-sm whitespace-nowrap">
                          {{ module.ModuleName }}
                       </h3>
                       <div class="h-px flex-1 bg-slate-200"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-3 gap-5">
                       <div v-for="entity in module.entities" :key="entity.EntityID" class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition-all duration-200 group flex flex-col">
                          
                          <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-50">
                             <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                 <CubeIcon class="w-5 h-5" />
                             </div>
                             <h4 class="font-bold text-slate-800 text-base leading-tight">{{ entity.EntityName }}</h4>
                          </div>
                          
                          <div class="space-y-3 flex-1">
                             <div v-if="!entity.actions || entity.actions.length === 0" class="text-xs text-slate-400 italic text-center py-3 bg-slate-50 rounded-lg">
                                 No actions available
                             </div>
                             
                             <label v-for="action in entity.actions" :key="action.ActionID" 
                                    class="flex items-center justify-between cursor-pointer group/item select-none p-2.5 rounded-lg hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                
                                <span class="text-sm font-bold text-slate-600 group-hover/item:text-slate-900 transition-colors">
                                   {{ action.ActionName }}
                                </span>

                                <div class="relative flex-shrink-0">
                                   <input type="checkbox" 
                                          :checked="hasPermission(entity.EntityID, action.ActionID)"
                                          @change="togglePermission(entity.EntityID, action.ActionID)"
                                          class="peer sr-only">
                                   <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </div>
                             </label>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="h-12"></div>
              </div>
           </div>

           <div v-else class="flex-1 bg-slate-50 rounded-[1.5rem] border-2 border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400 p-10 gap-6 select-none min-h-[400px]">
              <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm animate-pulse">
                  <ShieldCheckIcon class="w-12 h-12 text-slate-300" />
              </div>
              <div class="text-center max-w-xs">
                  <p class="text-xl font-bold text-slate-600 mb-1">Select a Role</p>
                  <p class="text-sm text-slate-400">Choose a role from the left sidebar to view and manage its permissions.</p>
              </div>
           </div>

        </div>
      </div>

      <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
          <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm p-8 transform transition-all scale-100">
              <h3 class="text-2xl font-bold text-slate-800 mb-6">Create New Role</h3>
              <form @submit.prevent="createRole">
                  <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Role Name</label>
                  <input v-model="newRoleName" type="text" class="modern-input mb-8" placeholder="e.g. Supervisor" required>
                  <div class="grid grid-cols-2 gap-4">
                      <button type="button" @click="showAddModal = false" class="py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 text-sm">Cancel</button>
                      <button type="submit" class="py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 shadow-lg text-sm">Create</button>
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
    PlusIcon, TrashIcon, PencilIcon, CubeIcon, ShieldCheckIcon 
} from '@heroicons/vue/24/outline';

const roles = ref([]);
const systemStructure = ref([]); 
const selectedRole = ref(null);
const checkedPermissions = ref(new Set()); 

const loading = ref(false);
const saving = ref(false);
const showAddModal = ref(false);
const newRoleName = ref('');

const fetchData = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/security/roles');
        roles.value = res.data.roles;
        systemStructure.value = res.data.modules;
    } catch (error) {
        console.error("Error fetching data", error);
    } finally {
        loading.value = false;
    }
};

const selectRole = (role) => {
    selectedRole.value = JSON.parse(JSON.stringify(role));
    checkedPermissions.value.clear();
    
    if (role.permissions && role.permissions.length > 0) {
        role.permissions.forEach(entity => {
            const entityId = entity.EntityID;
            const actionId = entity.pivot ? entity.pivot.action_id : null;
            if (entityId && actionId) {
                checkedPermissions.value.add(`${entityId}_${actionId}`);
            }
        });
    }
};

const hasPermission = (entityId, actionId) => {
    return checkedPermissions.value.has(`${entityId}_${actionId}`);
};

const togglePermission = (entityId, actionId) => {
    const key = `${entityId}_${actionId}`;
    if (checkedPermissions.value.has(key)) {
        checkedPermissions.value.delete(key);
    } else {
        checkedPermissions.value.add(key);
    }
};

const savePermissions = async () => {
    if (!selectedRole.value) return;
    saving.value = true;

    const permissionsPayload = Array.from(checkedPermissions.value).map(key => {
        const [entity_id, action_id] = key.split('_');
        return { entity_id, action_id };
    });

    try {
        await axios.put(`/security/roles/${selectedRole.value.RoleID}`, {
            name: selectedRole.value.RoleName,
            permissions: permissionsPayload
        });
        
        await fetchData(); 
        const updatedRole = roles.value.find(r => r.RoleID === selectedRole.value.RoleID);
        if(updatedRole) selectRole(updatedRole);

    } catch (error) {
        alert('Error saving permissions.');
        console.error(error);
    } finally {
        saving.value = false;
    }
};

const createRole = async () => {
    if (!newRoleName.value) return;
    try {
        await axios.post('/security/roles', { name: newRoleName.value });
        await fetchData();
        showAddModal.value = false;
        newRoleName.value = '';
    } catch (error) {
        alert('Error creating role.');
    }
};

const deleteRole = async (role) => {
    try {
        await axios.delete(`/security/roles/${role.RoleID}`);
        if (selectedRole.value?.RoleID === role.RoleID) selectedRole.value = null;
        await fetchData();
    } catch (error) {
        alert('Error deleting role.');
    }
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.modern-input {
    @apply block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-700 font-medium focus:border-slate-800 focus:ring-4 focus:ring-slate-800/10 transition-all;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 9999px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #94a3b8;
}
</style>