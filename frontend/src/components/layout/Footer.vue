<template>
  <!-- <footer class="mt-auto py-8 border-t border-slate-200/60 bg-white" >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
      
      <div class="text-xs font-medium text-slate-400">
        &copy; {{ currentYear }} <span class="text-slate-500 font-bold">Shabakat WOMS System</span>.
        <span class="hidden sm:inline text-slate-300 mx-1">|</span>
        <span class="hidden sm:inline text-orange-400">Abdalrhman Hamed</span>
      </div>

      <div class="flex items-center gap-6">
        <a href="#" class="text-xs font-medium text-slate-400 hover:text-blue-600 transition-colors">Help Center</a>
        <a href="#" class="text-xs font-medium text-slate-400 hover:text-blue-600 transition-colors">Privacy</a>
      </div>

    </div>
  </footer> -->
</template>

<script setup>
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>