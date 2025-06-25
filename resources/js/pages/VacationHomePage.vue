<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps<{ id: number|string }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Test',
        href: '/dashboard',
    },
];

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const vacationHome = ref<any>(null);

const showRentModal = ref(false);

const startDate = ref(new Date().toISOString().split('T')[0]);
const endDate = ref(new Date().toISOString().split('T')[0]);
const numberOfGuests = ref(1);

onMounted(async () => {
    const response = await axios.get(`/api/vacation-home/${props.id}`);
    vacationHome.value = response.data;
});

function openRentModal() {
    showRentModal.value = true;
}

function closeRentModal() {
    showRentModal.value = false;
    startDate.value = new Date().toISOString().split('T')[0];
    endDate.value = new Date().toISOString().split('T')[0];
    numberOfGuests.value = 1;
}
</script>

<template>
    <Head title="Test" />

    <AppLayout :breadcrumbs="breadcrumbs">
      <div v-if="vacationHome" class="max-w-xl mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-2">{{ vacationHome.name }}</h2>
        <div class="mb-2 text-gray-700">Price: ${{ vacationHome.price }}</div>
        <div class="mb-4">{{ vacationHome.description }}</div>
        <div v-if="vacationHome.images && vacationHome.images.length" class="flex flex-wrap gap-2">
          <img v-for="(img, idx) in vacationHome.images" :key="idx" :src="`/storage/${img}`" class="w-32 h-32 object-cover rounded" />
        </div>
        <button
          class="mt-2 px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-800 cursor-pointer"
          @click="openRentModal"
        >
          Rent House
        </button>
      </div>
      <div v-else class="text-center mt-10 text-gray-500">Loading...</div>

      <!-- Rent Modal -->
      <div v-if="showRentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-lg max-w-sm w-full">
          <h3 class="text-lg font-bold mb-4 text-blue-600">Select Rental Date</h3>
          <form action="/api/vacation-home/rent" method="POST">
            <input type="hidden" name="_token" :value="csrfToken" />
            <input type="hidden" name="vacation_home_id" :value="props.id" />
            <label class="block mb-2 text-gray-700">Choose a start date:</label>
            <input
              type="date"
              v-model="startDate"
              name="start_date"
              class="w-full border rounded p-2 mb-4"
              required
            />
            <label class="block mb-2 text-gray-700">Choose an end date:</label>
            <input
              type="date"
              v-model="endDate"
              name="end_date"
              class="w-full border rounded p-2 mb-4"
              required
            />
            <input
              type="number"
              v-model="numberOfGuests"
              name="number_of_guests"
              class="w-full border rounded p-2 mb-4"
              placeholder="Enter number of guests"
              min="1"
              required
            />
            <div class="flex justify-end gap-2">
              <button @click="closeRentModal" type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800">Cancel</button>
              <button
                :disabled="!startDate && !endDate && !numberOfGuests"
                type="submit"
                class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white disabled:opacity-50"
              >
                Rent
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
    </template>