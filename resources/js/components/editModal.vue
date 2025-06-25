<script setup lang="ts">
const props = defineProps<{
  show: boolean,
  form: { name: string, price: string, description: string },
  home: any
}>();
const emit = defineEmits(['cancel', 'save']);
</script>

<template>
  <div v-if="props.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-lg max-w-3/10 w-full">
      <h3 class="text-lg font-bold mb-4 text-blue-600">Edit Vacation Home</h3>
      <form @submit.prevent="emit('save')">
        <div class="mb-4">
          <label class="block mb-1 font-medium">Name</label>
          <input v-model="props.form.name" type="text" class="w-full border rounded p-2" required />
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-medium">Price</label>
          <input v-model="props.form.price" type="number" min="0" class="w-full border rounded p-2" required />
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-medium">Description</label>
          <textarea v-model="props.form.description" class="w-full border rounded p-2" rows="3"></textarea>
        </div>
        <div class="mb-4">
          <label class="block mb-1 font-medium">Images</label>
          <input type="file" accept="image/*" multiple class="w-full border rounded p-2" />
          <p class="text-sm text-gray-500 mt-1">You can upload multiple images.</p>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" @click="emit('cancel')" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>