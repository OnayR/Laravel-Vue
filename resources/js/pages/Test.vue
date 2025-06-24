<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Test',
        href: '/dashboard',
    },
];
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const vacationHomes = ref([]);

const snackbar = ref('');

onMounted(async () => {
    const response = await axios.get('/api/vacation-homes');
    vacationHomes.value = response.data;

    const flashSuccess = document.querySelector('meta[name="flash-success"]')?.getAttribute('content');
    if (flashSuccess) {
      showSnackbar(flashSuccess);
    }

});

const showSnackbar = (msg: string) => {
  snackbar.value = msg;
  const snackbarElement = document.createElement('div');
  snackbarElement.className = 'fixed bottom-4 right-4 bg-gray-800 text-white p-3 rounded shadow-lg';
  snackbarElement.textContent = msg;
  document.body.appendChild(snackbarElement);
  setTimeout(() => {
    snackbarElement.remove();
  }, 3000);
};
// console.log('Test page loaded');
// showSnackbar('Welcome to the Test Page!');
</script>

<template>
    <Head title="Test" />

    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="w-1/2 mx-auto mt-10">
        <h2 class="pl-2 mb-4">Create Vacation Home</h2>
        <form action="/create-vacation-home" method="POST" enctype="multipart/form-data" class="">
          <input type="hidden" name="_token" :value="csrfToken" />
            
            <label for="Name" class="relative">
              <input type="text" id="Name" name="name" placeholder="" class="p-2 peer mt-0.5 w-full mb-3 pl-2 rounded border-gray-300 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"/>
              <span class="pointer-events-none absolute inset-y-0 start-3 -translate-y-5 bg-white px-0.5 text-sm font-medium text-gray-700 transition-transform peer-placeholder-shown:translate-y-0 peer-focus:-translate-y-5 dark:bg-gray-900 dark:text-white">Name</span>
            </label>
            <label for="Price" class="relative">
              <input type="text" id="Price" name="price" placeholder="" class="p-2 peer mt-0.5 w-full mb-3 pl-2 rounded border-gray-300 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"/>
              <span class="pointer-events-none absolute inset-y-0 start-3 -translate-y-5 bg-white px-0.5 text-sm font-medium text-gray-700 transition-transform peer-placeholder-shown:translate-y-0 peer-focus:-translate-y-5 dark:bg-gray-900 dark:text-white">Price (per night)</span>
            </label>

            <!-- Description Field -->
            <label for="Description" class="relative">
              <span class="text-sm font-medium text-gray-700 dark:text-white pl-3.5">Description</span>
              <textarea id="Description" name="description" rows="3" placeholder="" class="p-2 peer mt-0.5 w-full mb-3 pl-2 rounded border-gray-300 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
            </label>

            <!-- Image Field -->
            <label for="Images" class="block mb-3">
              <span class="text-sm font-medium text-gray-700 dark:text-white pl-3.5">Images</span>
              <input type="file" id="Images" name="images[]" accept="image/*" multiple class="block w-full text-sm text-gray-700 dark:text-white bg-white dark:border-gray-600 dark:bg-gray-900 p-2 rounded"/>
            </label>

            <button type="submit" class="pl-2 btn btn-primary bg-white dark:border-gray-600 dark:bg-gray-900 p-2 rounded float-right">Submit</button>
        </form>
        </div>

         <div class="w-2/3 mx-auto mt-16">
        <h2 class="mb-4 text-lg font-bold">Vacation Homes</h2>
        <ul v-if="vacationHomes.length">
          <li v-for="home in vacationHomes" :key="home.id" class="mb-6 p-4 border rounded shadow-sm dark:border-gray-700 bg-white dark:bg-gray-900">
            <div class="font-semibold text-lg">{{ home.name }}</div>
            <div class="text-gray-600 dark:text-gray-300">Price: ${{ home.price }}</div>
            <div class="mb-2 text-gray-700 dark:text-gray-200">Description: {{ home.description }}</div>
            <div v-if="home.images && home.images.length">
              <div class="flex flex-wrap gap-2">
                <img v-for="(img, idx) in home.images" :key="idx" :src="`/storage/${img}`" alt="Vacation Home Image" class="w-24 h-24 object-cover rounded" />
              </div>
            </div>
          </li>
        </ul>
        <div v-else class="text-gray-500 dark:text-gray-400">No vacation homes found.</div>
      </div>
    </AppLayout>
</template>