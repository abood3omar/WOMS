<template>
  <AppLayout>
    
    <div class="mb-10 animate-fade-in-up">
      <h1 class="text-3xl font-extrabold text-slate-800">System Structure ⚙️</h1>
      <p class="text-slate-500 mt-2">Manage Modules, Entities, and link Actions dynamically.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-250px)] min-h-[600px] animate-fade-in-up animation-delay-100">
      
      <div class="column-card border-t-4 border-t-blue-500">
        <div class="column-header">
           <h2 class="text-lg font-bold text-slate-700 flex items-center gap-2">
              <Squares2X2Icon class="w-5 h-5 text-blue-500" /> Modules
           </h2>
           <button @click="openModal('module', 'add')" class="btn-icon bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white">
              <PlusIcon class="w-5 h-5" />
           </button>
        </div>
        
        <div class="column-body custom-scrollbar">
           <div v-for="mod in modules" :key="mod.ModuleID" class="item-card group hover:border-blue-200">
              <span class="font-bold text-slate-700">{{ mod.ModuleName }}</span>
              <div class="action-buttons">
                 <button @click="openModal('module', 'edit', mod)" class="text-yellow-500 hover:bg-yellow-50 p-1.5 rounded-lg transition"><PencilSquareIcon class="w-4 h-4"/></button>
                 <button @click="deleteItem('modules', mod.ModuleID)" class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition"><TrashIcon class="w-4 h-4"/></button>
              </div>
           </div>
        </div>
      </div>

      <div class="column-card border-t-4 border-t-purple-500">
        <div class="column-header">
           <h2 class="text-lg font-bold text-slate-700 flex items-center gap-2">
              <CubeIcon class="w-5 h-5 text-purple-500" /> Entities
           </h2>
           <button @click="openModal('entity', 'add')" class="btn-icon bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white">
              <PlusIcon class="w-5 h-5" />
           </button>
        </div>
        
        <div class="column-body custom-scrollbar">
           <div v-for="ent in entities" :key="ent.EntityID" class="item-card group hover:border-purple-200 flex-col !items-start gap-2">
              <div class="flex justify-between w-full items-center">
                  <span class="font-bold text-slate-700">{{ ent.EntityName }}</span>
                  
                  <div class="action-buttons bg-slate-50 border border-slate-100 px-1 py-0.5 rounded-lg">
                     <button @click="openLinkActions(ent)" class="text-green-600 hover:bg-green-50 p-1.5 rounded-lg transition" title="Link Actions">
                        <BoltIcon class="w-4 h-4"/>
                     </button>
                     <div class="w-px h-4 bg-slate-200 mx-1"></div>
                     <button @click="openModal('entity', 'edit', ent)" class="text-yellow-500 hover:bg-yellow-50 p-1.5 rounded-lg transition"><PencilSquareIcon class="w-4 h-4"/></button>
                     <button @click="deleteItem('entities', ent.EntityID)" class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition"><TrashIcon class="w-4 h-4"/></button>
                  </div>
              </div>
              
              <div class="w-full flex justify-between items-center mt-1">
                  <span class="text-[10px] text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                      {{ ent.module?.ModuleName || 'No Module' }}
                  </span>
                  </div>
           </div>
        </div>
      </div>

      <div class="column-card border-t-4 border-t-pink-500">
        <div class="column-header">
           <h2 class="text-lg font-bold text-slate-700 flex items-center gap-2">
              <BoltIcon class="w-5 h-5 text-pink-500" /> Actions
           </h2>
           <button @click="openModal('action', 'add')" class="btn-icon bg-pink-50 text-pink-600 hover:bg-pink-600 hover:text-white">
              <PlusIcon class="w-5 h-5" />
           </button>
        </div>
        
        <div class="column-body custom-scrollbar">
           <div v-for="act in actions" :key="act.ActionID" class="item-card group hover:border-pink-200">
              <span class="font-bold text-slate-700">{{ act.ActionName }}</span>
              <div class="action-buttons">
                 <button @click="openModal('action', 'edit', act)" class="text-yellow-500 hover:bg-yellow-50 p-1.5 rounded-lg transition"><PencilSquareIcon class="w-4 h-4"/></button>
                 <button @click="deleteItem('actions', act.ActionID)" class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition"><TrashIcon class="w-4 h-4"/></button>
              </div>
           </div>
        </div>
      </div>

    </div>

    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 transform transition-all scale-100">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 capitalize">
                {{ modalMode }} {{ modalType }}
            </h3>
            
            <form @submit.prevent="saveItem">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Name</label>
                        <input v-model="form.name" type="text" class="modern-input" placeholder="Enter name..." required>
                    </div>

                    <div v-if="modalType === 'entity'">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Module</label>
                        <select v-model="form.module_id" class="modern-input" required>
                            <option value="" disabled>Select Module</option>
                            <option v-for="mod in modules" :key="mod.ModuleID" :value="mod.ModuleID">
                                {{ mod.ModuleName }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-8">
                    <button type="button" @click="showModal = false" class="py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="py-3 rounded-xl bg-slate-800 text-white font-bold hover:bg-slate-900 shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div v-if="showLinkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 transform transition-all scale-100 flex flex-col max-h-[80vh]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <BoltIcon class="w-6 h-6 text-green-500" /> Link Actions: <span class="text-green-600">{{ currentEntity?.EntityName }}</span>
                </h3>
            </div>
            
            <div class="grid grid-cols-2 gap-3 overflow-y-auto custom-scrollbar pr-2 mb-6 flex-1">
                <label v-for="act in actions" :key="act.ActionID" 
                       class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all duration-200"
                       :class="selectedActions.includes(act.ActionID) ? 'bg-green-50 border-green-500' : 'bg-slate-50 border-slate-100 hover:border-slate-300'">
                    
                    <div class="relative flex items-center">
                        <input type="checkbox" :value="act.ActionID" v-model="selectedActions" class="peer sr-only">
                        <div class="w-5 h-5 border-2 border-slate-400 rounded peer-checked:bg-green-500 peer-checked:border-green-500 transition-colors flex items-center justify-center">
                            <CheckIcon class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" />
                        </div>
                    </div>
                    <span class="text-sm font-bold" :class="selectedActions.includes(act.ActionID) ? 'text-green-700' : 'text-slate-600'">{{ act.ActionName }}</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button @click="showLinkModal = false" class="px-5 py-2.5 rounded-xl text-slate-500 font-bold hover:bg-slate-50">Close</button>
                <button id="saveLinkBtn" @click="saveLinkedActions" class="px-6 py-2.5 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg">Save Links</button>
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
    Squares2X2Icon, CubeIcon, BoltIcon, PlusIcon, 
    PencilSquareIcon, TrashIcon, CheckIcon 
} from '@heroicons/vue/24/outline';


const modules = ref([]);
const entities = ref([]);
const actions = ref([]);


const showModal = ref(false);
const showLinkModal = ref(false);
const modalType = ref(''); 
const modalMode = ref(''); 
const saving = ref(false);
const form = ref({ id: null, name: '', module_id: '' });
const currentEntity = ref(null);
const selectedActions = ref([]);


const fetchData = async () => {
    try {
        const res = await axios.get('/security/system-modules');
        modules.value = res.data.modules;
        actions.value = res.data.actions;
        entities.value = res.data.entities;
    } catch (error) {
        console.error("Failed to load data", error);
    }
};


const openModal = (type, mode, item = null) => {
    modalType.value = type;
    modalMode.value = mode;
    
    if (mode === 'edit' && item) {
        form.value = {
            id: item.ModuleID || item.EntityID || item.ActionID,
            name: item.ModuleName || item.EntityName || item.ActionName,
            module_id: item.ModuleID || ''
        };
    } else {
        form.value = { id: null, name: '', module_id: '' };
    }
    showModal.value = true;
};

const saveItem = async () => {
    saving.value = true;
    try {
        const id = form.value.id; 
        
        const payload = { 
            name: form.value.name,
            module_id: form.value.module_id 
        };
        
        let endpoint = '';
        if (modalType.value === 'module') endpoint = 'modules';
        else if (modalType.value === 'action') endpoint = 'actions';
        else if (modalType.value === 'entity') endpoint = 'entities';
        

        if (modalMode.value === 'edit') {
            await axios.put(`/security/${endpoint}/${id}`, payload);
        } else {

            await axios.post(`/security/${endpoint}`, payload);
        }
        
        await fetchData(); 
        showModal.value = false;

    } catch (error) {
        console.error("Save error:", error);
        const msg = error.response?.data?.message || 'Error occurred';
        alert(`Failed: ${msg}`);
    } finally {
        saving.value = false;
    }
};

const deleteItem = async (type, id) => {

    
    try {
        let resourceName = type;
        
        await axios.delete(`/security/${resourceName}/${id}`);
        await fetchData();
    } catch (error) {
        alert('Delete failed');
    }
};

const openLinkActions = (entity) => {
    currentEntity.value = entity;
    
    if (entity.actions && entity.actions.length > 0) {
        selectedActions.value = entity.actions.map(a => a.ActionID);
    } else {
        selectedActions.value = [];
    }
    
    showLinkModal.value = true;
};

const saveLinkedActions = async () => {
    if (!currentEntity.value) return;
    
    const originalBtnText = document.getElementById('saveLinkBtn').innerText;
    document.getElementById('saveLinkBtn').innerText = 'Saving...';
    
    try {
        await axios.post(`/security/entities/${currentEntity.value.EntityID}/actions`, {
            actions: selectedActions.value
        });
        await fetchData();
        
        showLinkModal.value = false;
    } catch (error) {
        alert(error.response?.data?.message || 'Failed to link actions.');
    } finally {
        if(document.getElementById('saveLinkBtn')) 
            document.getElementById('saveLinkBtn').innerText = originalBtnText;
    }
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.column-card {
    @apply bg-white rounded-3xl shadow-lg shadow-slate-200/50 border-x border-b border-slate-100 flex flex-col overflow-hidden h-full;
}

.column-header {
    @apply p-6 border-b border-slate-50 flex justify-between items-center bg-white sticky top-0 z-10;
}

.column-body {
    @apply flex-1 p-4 space-y-3 overflow-y-auto bg-slate-50/30;
}


.item-card {
    @apply bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex justify-between items-center transition-all duration-200 hover:shadow-md hover:-translate-y-0.5;
}

.action-buttons {
    @apply flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200;
}

.btn-icon {
    @apply w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-md;
}

.modern-input {
    @apply block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-700 font-medium focus:border-slate-800 focus:ring-4 focus:ring-slate-800/10 transition-all;
}


.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #cbd5e1;
  border-radius: 20px;
}
</style>