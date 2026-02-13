<template>
  <div class="min-h-screen bg-slate-50 font-sans relative overflow-scroll">
    
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none" 
         style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 30px 30px;">
    </div>
    
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob pointer-events-none"></div>
    <div class="absolute bottom-0 left-64 w-[500px] h-[500px] bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000 pointer-events-none"></div>
    <div class="relative z-10 flex min-h-screen">
      
      <Sidebar :is-open="isMobileMenuOpen" @close="isMobileMenuOpen = false" />
      
      <div class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300 md:pl-72">
        
        <Navbar @toggle-menu="isMobileMenuOpen = !isMobileMenuOpen" />
        
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 mt-20 w-full max-w-[1600px] mx-auto">
          <slot></slot>
        </main>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '../components/layout/Sidebar.vue';
import Navbar from '../components/layout/Navbar.vue';

const isMobileMenuOpen = ref(false);
</script>

<style>

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