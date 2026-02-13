<template>
  <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none" 
         style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 30px 30px;">
    </div>
    
    <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 animate-fade-in-up">
      
      <div class="flex flex-col items-center mb-8 transform transition-all hover:scale-105 duration-500">
        <div class="bg-white p-4 rounded-2xl shadow-lg shadow-blue-100 border border-blue-50 mb-4">
           <img src="../assets/shabakat.png" alt="Shabakat Logo" class="h-10 w-auto">
        </div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Shabakat <span class="text-orange-500">WOMS</span></h2>
        <p class="mt-2 text-sm text-slate-500 font-medium">Internal Work Order Management System</p>
      </div>

      <div class="bg-white/80 backdrop-blur-lg py-8 px-6 shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] sm:rounded-3xl sm:px-10 border border-white">
        
        <form class="space-y-6" @submit.prevent="handleLogin">
          
          <div class="group">
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-blue-600">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                  <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
              </div>
              <input 
                v-model="email" 
                id="email" 
                name="email" 
                type="email" 
                autocomplete="email" 
                required 
                class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 sm:text-sm shadow-sm hover:border-blue-300" 
                placeholder="name@shabakat.com"
              >
            </div>
          </div>

          <div class="group">
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1 ml-1 transition-colors group-focus-within:text-blue-600">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-blue-500 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
              </div>
              <input 
                v-model="password" 
                id="password" 
                name="password" 
                type="password" 
                autocomplete="current-password" 
                required 
                class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 sm:text-sm shadow-sm hover:border-blue-300" 
                placeholder="••••••••"
              >
            </div>
          </div>

          <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0">
            <div v-if="errorMessage" class="rounded-xl bg-red-50 p-4 border border-red-100 flex items-center gap-3">
              <svg class="h-5 w-5 text-red-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              <h3 class="text-sm font-medium text-red-800">{{ errorMessage }}</h3>
            </div>
          </transition>

          <div>
            <button 
              type="submit" 
              :disabled="loading" 
              class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-xl disabled:opacity-70 disabled:cursor-not-allowed"
            >
              <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ loading ? 'Signing in...' : 'Sign In' }}
            </button>
          </div>
        </form>
      </div>

      <div class="mt-8 text-center animate-fade-in-up animation-delay-500">
        <p class="text-xs text-slate-400">
          &copy; 2026 Shabakat Tech Solutions Inc. All rights reserved. <br/>
          <a href="#" class="hover:text-blue-600 transition-colors">Privacy Policy</a> | <a href="#" class="hover:text-blue-600 transition-colors">Contact Support</a>
        </p>
        <p class="mt-4 text-xs font-mono text-slate-300">
          Built by <span class="font-bold text-slate-400">Abdalrhman Hamed</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const loading = ref(false);

const handleLogin = async () => {
  loading.value = true;
  errorMessage.value = '';
  
  try {
    await authStore.login({
      email: email.value,
      password: password.value
    });
   if (authStore.user.role === 'Operator') {
      router.push('/my-orders');
  } else {
      router.push('/');
  }
  } catch (error) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message;
    } else {
      errorMessage.value = 'Something went wrong. Please try again.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out forwards;
}

.animation-delay-500 {
  animation-delay: 0.3s;
}

@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}
</style>