<script setup>
import SidebarAdminContent from '@/Components/SidebarAdminContent.vue'
import FooterContent from '@/Components/FooterContent.vue'
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar - dibuat fixed -->
    <div class="fixed h-full z-10">
      <SidebarAdminContent />
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 ml-0 lg:ml-72 min-h-screen flex flex-col">
      <!-- Scrollable Content -->
      <main class="p-6 w-full flex-grow overflow-auto">
        <slot></slot>
      </main>
      
      <!-- Footer - akan selalu di bawah -->
      <FooterContent />
    </div>
  </div>
</template>