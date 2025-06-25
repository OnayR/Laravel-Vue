<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import axios from 'axios';
import WarningModal from '../components/WarningModal.vue';
import EditModal from '../components/editModal.vue';
import { Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Vacation Homes',
        href: '/dashboard',
    },
];
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const vacationHomes = ref([]);
const filter = ref('');

onMounted(async () => {
    // Fetch only vacation homes connected to the current user
    const response = await axios.get('/api/my-vacation-homes');
    vacationHomes.value = response.data;
});

const filteredHomes = computed(() =>
  vacationHomes.value.filter(home =>
    home.name.toLowerCase().includes(filter.value.toLowerCase()) ||
    (home.description && home.description.toLowerCase().includes(filter.value.toLowerCase())) 
  )
);


</script>

<template>
    <Head title="My Vacation Homes" />

    <AppLayout :breadcrumbs="breadcrumbs">

      <div class="w-2/3 mx-auto mt-16">
          <input
            v-model="filter"
            type="text"
            placeholder="Filter by name or description..."
            class="mb-6 p-2 w-full border rounded shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
          />

        <h2 class="mb-4 text-lg font-bold">My Vacation Homes</h2>
        <ul v-if="vacationHomes.length">
          <li v-for="home in vacationHomes" :key="home.id" class="mb-6 p-4 border rounded shadow-sm dark:border-gray-700 bg-white dark:bg-gray-900">
            <div class="flex">
              <div class="mr-4 max-w-1/2 w-1/2">
                <div class="font-semibold text-lg">{{ home.vacation_house.name }}</div>
                <div class="text-gray-600 dark:text-gray-300">Price: ${{ home.vacation_house.price }}</div>
                <div class="mb-2 text-gray-700 dark:text-gray-200">Description: {{ home.vacation_house.description }}</div>
                <div class="mb-2 text-gray-700 dark:text-gray-200">{{ home.start_date }} until {{ home.end_date }}</div>
              </div>
              <div class="flex-1 w-1/2 flex justify-end items-start">
                <div v-if="home.vacation_house.images && home.vacation_house.images.length" class="flex flex-wrap gap-2 justify-end max-w-xs max-h-48 overflow-y-auto">
                  <img v-for="(img, index) in home.vacation_house.images" :key="index" :src="`/storage/${img}`" alt="Vacation Home Image" class="w-24 h-24 object-cover rounded"/>
                </div>
                <div v-else class="text-gray-500 dark:text-gray-400 self-center">No images available</div>
              </div>
            </div>
          </li>
        </ul>
        <div v-else class="text-gray-500 dark:text-gray-400">No vacation homes found.</div>
      </div>
    </AppLayout>
</template>