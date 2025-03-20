<script setup>
import SidebarFasilitatorContent from '@/components/SidebarFasilitatorContent.vue'
import FooterContent from '@/components/FooterContent.vue'
</script>

<template>
  <div class="flex h-screen bg-gray-100 overflow-auto">
    <SidebarFasilitatorContent />
    <main>
      <slot></slot>
    </main>
  </div>
  <FooterContent />
</template>
