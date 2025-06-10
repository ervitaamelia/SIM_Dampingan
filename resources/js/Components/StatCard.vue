<template>
  <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
    <span class="text-3xl mr-3">{{ icon }}</span>
    <div>
      <h3 class="text-gray-500 text-sm">{{ label }}</h3>
      <p class="text-xl font-bold">{{ value }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    icon: String,
    label: String,
    value: [String, Number]
  }
}
</script>