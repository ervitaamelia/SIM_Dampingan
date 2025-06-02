<script setup>
import SidebarFasilitatorContent from '@/Components/SidebarFasilitatorContent.vue'
import FooterContent from '@/Components/FooterContent.vue'
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <div class="sticky top-0 h-screen">
      <SidebarFasilitatorContent />
    </div>
    
    <div class="flex-1 overflow-y-auto max-h-screen flex flex-col">
      <main class="p-6 flex-1 overflow-y-auto">
        <slot></slot>
      </main>
      <FooterContent />
    </div>
  </div>
</template>