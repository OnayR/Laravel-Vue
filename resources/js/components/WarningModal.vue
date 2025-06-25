<script setup lang="ts">
const props = defineProps<{
  homeToDelete: any;
  showModal: boolean;
  onDelete: (homeId: number) => void;
  onCancel: () => void;
}>();
</script>

<template>
  <div v-if="props.showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-lg max-w-sm w-full">
      <h3 class="text-lg font-bold mb-4 text-red-600">Are you sure?</h3>
      <p class="mb-6">
        Do you really want to delete
        <span class="font-semibold">{{ props.homeToDelete?.name }}</span>? This action cannot be undone.
      </p>
      <div class="flex justify-end gap-2">
        <button @click="props.onCancel" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800">Cancel</button>
        <button @click="props.onDelete(props.homeToDelete?.id)" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">Delete</button>
      </div>
    </div>
  </div>
</template>