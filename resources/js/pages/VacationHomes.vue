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
        title: 'Test',
        href: '/dashboard',
    },
];
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const vacationHomes = ref([]);
const filter = ref('');
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

const filteredHomes = computed(() =>
  vacationHomes.value.filter(home =>
    home.name.toLowerCase().includes(filter.value.toLowerCase()) ||
    (home.description && home.description.toLowerCase().includes(filter.value.toLowerCase())) 
  )
);

const showModal = ref(false);
const homeToDelete = ref<any>(null);

function confirmDelete(home: any) {
  homeToDelete.value = home;
  showModal.value = true;
}

function cancelDelete() {
  showModal.value = false;
  homeToDelete.value = null;
}

function deleteHome() {
  axios.delete(`/api/vacation-homes/${homeToDelete.value.id}`, {
    headers: {
      'X-CSRF-TOKEN': csrfToken,
    },
  })
  if (!homeToDelete.value) return;
  showSnackbar(`Deleted "${homeToDelete.value.name}" successfully.`);
  showModal.value = false;
  homeToDelete.value = null;
}


const showEditModal = ref(false);
const homeToEdit = ref<any>(null);
const editForm = ref({ name: '', price: '', description: '' });

function openEditModal(home: any) {
  homeToEdit.value = home;
  editForm.value = {
    name: home.name,
    price: home.price,
    description: home.description,
  };
  showEditModal.value = true;
}

function cancelEdit() {
  showEditModal.value = false;
  homeToEdit.value = null;
}

async function saveEdit() {
  if (!homeToEdit.value) return;
  try {
    await axios.put(`/api/vacation-homes/${homeToEdit.value.id}`, editForm.value, {
      headers: {
        'X-CSRF-TOKEN': csrfToken,
      },
    });
    // Update the local list (optional: refetch from API for consistency)
    Object.assign(homeToEdit.value, editForm.value);
    showSnackbar(`Updated "${editForm.value.name}" successfully.`);
    showEditModal.value = false;
    homeToEdit.value = null;
  } catch (e) {
    showSnackbar('Failed to update vacation home.');
  }
}

function openVacationHome(home: any) {
  router.visit(`/vacation-home/${home.id}`);
}
</script>

<template>
    <Head title="Test" />

    <AppLayout :breadcrumbs="breadcrumbs">

      <div class="w-2/3 mx-auto mt-16">
          <input
            v-model="filter"
            type="text"
            placeholder="Filter by name or description..."
            class="mb-6 p-2 w-full border rounded shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"
          />

        <h2 class="mb-4 text-lg font-bold">Vacation Homes</h2>
        <ul v-if="filteredHomes.length">
          <li v-for="home in filteredHomes" :key="home.id" class="mb-6 p-4 border rounded shadow-sm dark:border-gray-700 bg-white dark:bg-gray-900">
            <div class="flex">
              <div class="mr-4 max-w-1/2 w-1/2">
                <div class="font-semibold text-lg">{{ home.name }}</div>
                <div class="text-gray-600 dark:text-gray-300">Price: ${{ home.price }}</div>
                <div class="mb-2 text-gray-700 dark:text-gray-200">Description: {{ home.description }}</div>
              </div>
              <div class="flex-1 w-1/2 flex justify-end items-start">
                <div v-if="home.images && home.images.length" class="flex flex-wrap gap-2 justify-end max-w-xs max-h-48 overflow-y-auto">
                  <img v-for="(img, index) in home.images" :key="index" :src="`/storage/${img}`" alt="Vacation Home Image" class="w-24 h-24 object-cover rounded"/>
                </div>
                <div v-else class="text-gray-500 dark:text-gray-400 self-center">No images available</div>
              </div>
            </div>
            <div class="flex flex-row gap-2 mt-4 justify-end w-full">
              <Link :href="route('vacation-home', home.id)" class="flex items-center">
                <button class="mt-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 cursor-pointer">View</button>
              </Link>
              <button @click="openEditModal(home)" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">Edit</button>
              <button @click="confirmDelete(home)" class="mt-2 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
            </div>
          </li>
        </ul>
        <div v-else class="text-gray-500 dark:text-gray-400">No vacation homes found.</div>
      </div>




      <WarningModal
        :showModal="showModal"
        :homeToDelete="homeToDelete"
        :onDelete="deleteHome"
        :onCancel="cancelDelete"
      />

      <EditModal
        :show="showEditModal"
        :form="editForm"
        :home="homeToEdit"
        @cancel="cancelEdit"
        @save="saveEdit"
      />
    </AppLayout>
</template>