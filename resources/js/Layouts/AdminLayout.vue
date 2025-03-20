<script setup>
import SidebarAdminContent from '@/components/SidebarAdminContent.vue'
import FooterContent from '@/components/FooterContent.vue'
</script>

<template>
  <div class="flex flex-col min-h-screen">
    <div class="flex h-screen bg-gray-100 overflow-auto">
      <div class="flex flex-1">
        <SidebarAdminContent />
        <main>
          <slot></slot>
        </main>
      </div>
    </div>
    <FooterContent />
  </div>
</template>