<script setup>

import SidebarAdminContent from '@/Components/SidebarAdminContent.vue' 
import FooterContent from '@/Components/FooterContent.vue' 

</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <SidebarAdminContent />
    <div class="flex-1 overflow-auto">
      <main class="p-6 w-full">
        <slot></slot>
      </main>
      <FooterContent />
    </div>
  </div>
</template>