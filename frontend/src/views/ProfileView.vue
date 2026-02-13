<template>
  <AppLayout>
    <div class="max-w-5xl mx-auto mb-10 text-center animate-fade-in-up">
      <h1 class="text-3xl md:text-4xl font-extrabold text-orange-400 tracking-tight">Account Settings</h1>
      <p class="text-slate-500 mt-3 text-lg">Manage your profile details & security preferences</p>
    </div>

    <div class="max-w-5xl mx-auto grid grid-cols-1 gap-8 animate-fade-in-up animation-delay-200">
      
      <div class="bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
        
        <div class="h-2 w-full bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-500"></div>

        <div class="p-8 md:p-10">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 border-b border-slate-50 pb-8">
             <div>
                <h3 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                        <UserCircleIcon class="w-8 h-8" /> 
                    </div>
                    Personal Information
                </h3>
                <p class="text-slate-500 mt-2 ml-1">Update your photo and personal details here.</p>
             </div>
             
             <button 
                v-if="!isEditing" 
                @click="startEditing" 
                class="px-6 py-2.5 rounded-xl text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5"
             >
                <PencilIcon class="w-4 h-4" /> Edit Details
             </button>
             <button 
                v-else 
                @click="cancelEdit" 
                class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-500 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all duration-300"
             >
                Cancel
             </button>
          </div>

          <form @submit.prevent="updateProfile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              
              <div class="group/input">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1 transition-colors group-hover/input:text-blue-500">Full Name</label>
                <div class="relative">
                   <UserIcon class="absolute left-5 top-4 w-5 h-5 text-slate-400 group-focus-within/input:text-blue-500 transition-colors" />
                   <input 
                      v-model="profileForm.name" 
                      :disabled="!isEditing" 
                      type="text" 
                      class="modern-input pl-14"
                      :class="!isEditing ? 'bg-slate-50/50 text-slate-500' : 'bg-white text-slate-800 shadow-sm'"
                   >
                </div>
              </div>

              <div class="group/input">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1 transition-colors group-hover/input:text-blue-500">Email Address</label>
                <div class="relative">
                   <EnvelopeIcon class="absolute left-5 top-4 w-5 h-5 text-slate-400 group-focus-within/input:text-blue-500 transition-colors" />
                   <input 
                      v-model="profileForm.email" 
                      :disabled="!isEditing" 
                      type="email" 
                      class="modern-input pl-14"
                      :class="!isEditing ? 'bg-slate-50/50 text-slate-500' : 'bg-white text-slate-800 shadow-sm'"
                   >
                </div>
              </div>

              <div class="group/input">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1">
                  Current Role
                </label>
                <div class="relative">
                   <ShieldCheckIcon class="absolute left-5 top-4 w-5 h-5 text-indigo-400" />
                   
                   <input 
                      :value="authStore.user?.role || 'Staff Member'" 
                      disabled 
                      type="text" 
                      class="modern-input pl-14 bg-slate-100 text-slate-500 border-slate-200 cursor-not-allowed font-semibold select-none"
                   >
                   
                   <LockClosedIcon class="absolute right-5 top-4 w-4 h-4 text-slate-400/70" />
                </div>
              </div>

            </div>

            <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0">
                <div v-if="successMessage" class="mt-8 p-4 bg-emerald-50 text-emerald-700 rounded-2xl flex items-center gap-3 border border-emerald-100 font-medium shadow-sm">
                    <CheckCircleIcon class="w-6 h-6 text-emerald-500" /> {{ successMessage }}
                </div>
            </transition>

            <div v-if="isEditing" class="mt-10 flex justify-end border-t border-slate-50 pt-6">
               <button type="submit" :disabled="loading" class="btn-primary">
                  <span v-if="loading" class="animate-spin mr-2 h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                  Save Changes
               </button>
            </div>
          </form>
        </div>
      </div>

      <div class="bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
         
         <div class="h-2 w-full bg-gradient-to-r from-orange-400 via-red-400 to-orange-400"></div>

         <div class="p-8 md:p-10">
            <h3 class="text-2xl font-bold text-slate-800 mb-2 flex items-center gap-3">
              <div class="p-2.5 bg-orange-50 text-orange-500 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                <LockClosedIcon class="w-8 h-8" />
              </div> 
              Security & Password
            </h3>
            <p class="text-slate-500 mb-10 ml-1">Ensure your account is using a long, random password to stay secure.</p>
            
            <form @submit.prevent="changePassword">
              <div class="space-y-6 max-w-3xl"> <div class="group/input">
                   <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1 group-hover/input:text-orange-500 transition-colors">Current Password</label>
                   <input v-model="passwordForm.current_password" type="password" class="modern-input focus:border-orange-500 focus:ring-orange-500/10" placeholder="••••••••">
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                   <div class="group/input">
                     <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1 group-hover/input:text-orange-500 transition-colors">New Password</label>
                     <input v-model="passwordForm.password" type="password" class="modern-input focus:border-orange-500 focus:ring-orange-500/10" placeholder="New strong password">
                   </div>
                   <div class="group/input">
                     <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 ml-1 group-hover/input:text-orange-500 transition-colors">Confirm Password</label>
                     <input v-model="passwordForm.password_confirmation" type="password" class="modern-input focus:border-orange-500 focus:ring-orange-500/10" placeholder="Repeat password">
                   </div>
                 </div>
                 
                 <div v-if="passwordMessage" class="p-4 bg-orange-50 text-orange-700 text-sm rounded-2xl border border-orange-100 font-medium">
                     {{ passwordMessage }}
                 </div>

                 <div class="flex justify-end pt-4">
                     <button type="submit" :disabled="loadingPass" class="px-8 py-3.5 bg-orange-400 hover:bg-orange-500 text-white rounded-2xl font-bold shadow-lg shadow-slate-800/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                         <span v-if="loadingPass" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                         Update Password
                     </button>
                 </div>
              </div>
            </form>
         </div>
      </div>

      <div class="bg-red-50/50 rounded-[2rem] border border-red-100 p-8 md:p-10 hover:border-red-200 transition-colors duration-300">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
              <div>
                  <h3 class="text-xl font-bold text-red-700 flex items-center gap-3">
                      <div class="p-2 bg-red-100 rounded-xl">
                          <ExclamationTriangleIcon class="w-6 h-6 text-red-600" /> 
                      </div>
                      Danger Zone
                  </h3>
                  <p class="text-slate-500 mt-2 max-w-xl leading-relaxed">
                      Permanently delete your account and all of your content. This action is not reversible, so please continue with caution.
                  </p>
              </div>
              <button @click="showDeleteModal = true" class="bg-white text-red-600 border border-red-200 px-8 py-3.5 rounded-2xl font-bold hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-lg hover:shadow-red-600/20 transition-all duration-300 whitespace-nowrap">
                  Delete Account
              </button>
          </div>
      </div>

    </div>

    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-10 transform transition-all scale-100">
            <div class="text-center">
                <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 border-[6px] border-red-50">
                    <ExclamationTriangleIcon class="w-10 h-10 text-red-500" />
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-3">Delete Account?</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">This action cannot be undone. All your data will be lost. Please enter your password to confirm.</p>
                
                <input v-model="deletePassword" type="password" class="modern-input mb-8 text-center text-lg" placeholder="Enter your password">
                
                <div class="grid grid-cols-2 gap-4">
                    <button @click="showDeleteModal = false" class="py-3.5 rounded-2xl border-2 border-slate-100 text-slate-600 font-bold hover:bg-slate-50 hover:border-slate-200 transition-colors">Cancel</button>
                    <button @click="deleteAccount" class="py-3.5 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-xl shadow-red-600/30 hover:-translate-y-1 transition-all">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import AppLayout from '@/layouts/AppLayout.vue';
import { 
    EnvelopeIcon, PhoneIcon, UserCircleIcon, 
    LockClosedIcon, CheckCircleIcon, ExclamationTriangleIcon, UserIcon, PencilIcon , ShieldCheckIcon, 
  
} from '@heroicons/vue/24/outline';

const authStore = useAuthStore();
const router = useRouter();

const isEditing = ref(false);
const loading = ref(false);
const loadingPass = ref(false);
const successMessage = ref('');
const passwordMessage = ref('');
const showDeleteModal = ref(false);
const deletePassword = ref('');

const profileForm = ref({ name: '', email: '' });
const passwordForm = ref({ current_password: '', password: '', password_confirmation: '' });

onMounted(() => {
    if (authStore.user) {
        profileForm.value = {
            name: authStore.user.name || '',
            email: authStore.user.email || '',
        };
    }
});

const startEditing = () => isEditing.value = true;
const cancelEdit = () => {
    isEditing.value = false;
 
    if (authStore.user) {
        profileForm.value = { ...authStore.user };
    }
};

const updateProfile = async () => {
    loading.value = true;
    successMessage.value = '';
    try {
        const response = await axios.put('/profile/info', profileForm.value);
        authStore.user = response.data.user;
        localStorage.setItem('user', JSON.stringify(response.data.user));
        successMessage.value = 'Profile updated successfully!';
        isEditing.value = false;
        setTimeout(() => successMessage.value = '', 4000);
    } catch (error) {
        alert(error.response?.data?.message || 'Failed to update profile.');
    } finally {
        loading.value = false;
    }
};

const changePassword = async () => {
    loadingPass.value = true;
    passwordMessage.value = '';
    try {
        await axios.put('/profile/password', passwordForm.value);
        passwordMessage.value = 'Password changed successfully!';
        passwordForm.value = { current_password: '', password: '', password_confirmation: '' };
    } catch (error) {
        passwordMessage.value = error.response?.data?.message || 'Error changing password.';
    } finally {
        loadingPass.value = false;
    }
};

const deleteAccount = async () => {
    if (!deletePassword.value) return alert('Please enter password');
    try {
        await axios.post('/profile/delete', { password: deletePassword.value });
        showDeleteModal.value = false;
        authStore.logout();
        router.push('/login');
    } catch (error) {
        alert('Incorrect password or delete failed.');
    }
};
</script>

<style scoped>

.modern-input {
    @apply block w-full rounded-2xl border-transparent bg-slate-50 py-3.5 px-5 text-slate-800 font-medium placeholder-slate-400
    focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all duration-300;
}

.btn-primary {
    @apply flex items-center justify-center bg-blue-600 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 disabled:opacity-70 disabled:hover:translate-y-0;
}
</style>