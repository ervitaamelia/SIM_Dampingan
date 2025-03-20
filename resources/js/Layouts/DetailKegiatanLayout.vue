<script setup>
import FooterContent from '@/components/FooterContent.vue'
</script>

<template>
  <div class="flex h-screen bg-gray-100 overflow-auto">
    <main>
      <slot></slot>
    </main>
  </div>
  <FooterContent />
</template>
