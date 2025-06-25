<script setup lang="ts">
import { ref } from 'vue';

const visible = ref(false);
const message = ref('');

function show(msg: string, timeout = 3000) {
  message.value = msg;
  visible.value = true;
  setTimeout(() => (visible.value = false), timeout);
}

// Expose show method globally
defineExpose({ show });
</script>

<template>
  <div
    v-if="visible"
    class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50"
  >
    {{ message }}
  </div>
</template>