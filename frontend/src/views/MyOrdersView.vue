<template>
  <AppLayout>
    <div class="flex flex-col h-[calc(100vh-9rem)] items-center justify-center text-center p-6 animate-fade-in-up">
      
      <div class="bg-white p-10 rounded-[2rem] shadow-sm border border-slate-200 max-w-lg w-full relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-10 -mt-10"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-orange-50 rounded-full blur-3xl -ml-10 -mb-10"></div>

        <div class="relative z-10 flex flex-col items-center">
            <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-slate-100">
                <BriefcaseIcon class="w-10 h-10 text-slate-400" />
            </div>
            
            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">My Orders</h1>
            <p class="text-slate-500 mb-6">
                This page is dedicated for technicians to view, accept, or reject their assigned tasks.
            </p>

            <span class="px-4 py-2 rounded-full bg-orange-100 text-orange-600 text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                <WrenchScrewdriverIcon class="w-4 h-4" />
                Under Development
            </span>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { BriefcaseIcon, WrenchScrewdriverIcon } from '@heroicons/vue/24/outline';
</script>