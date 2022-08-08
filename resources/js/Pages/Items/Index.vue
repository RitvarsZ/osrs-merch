<script setup lang="ts">
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import LoadingVue from '@/Components/Loading.vue';
import type { Item } from '@/Types/Item';
import type { Paginate } from '@/Types/Laravel/Paginate';
import { Inertia, type RequestPayload } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3';
import { watch, ref } from 'vue';
import type { Header as TableHeader, ServerOptions } from "vue3-easy-data-table";
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue';
import { FormatNumber } from '@/Helpers/Number';

const props = defineProps<{
  data: Paginate<Item>
}>();

const loading = ref(false)
const serverOptions = ref<ServerOptions>({
  page: props.data.current_page,
  rowsPerPage: props.data.per_page,
  sortBy: 'name',
  sortType: 'asc',
});
const search = ref('');
const justSearched = ref(false);

const headers: TableHeader[] = [
  { text: 'ICON', value: 'icon' },
  { text: 'NAME', value: 'name', sortable: true },
  { text: 'MEMBERS', value: 'members' },
  { text: 'HIGHALCH', value: 'highalch', sortable: true },
  { text: 'LIMIT', value: 'limit', sortable: true },
];

const fetchPage = async () => {
  loading.value = true;

  const url = route('items.index');
  const params: RequestPayload = {
    page: serverOptions.value.page,
    per_page: serverOptions.value.rowsPerPage,
    sortBy: serverOptions.value.sortBy || null,
    sortType: serverOptions.value.sortType || null,
    search: search.value,
  };

  Inertia.get(url, params, {
    replace: true,
    preserveScroll: true,
    preserveState: true,
    onFinish: () => {
      loading.value = false;
    }
  });
}

watch(serverOptions, (value) => { fetchPage() })
watch(search, (value) => { 
  if (justSearched.value || value.length < 4) return;

  justSearched.value = true;
  setTimeout(() => {
    justSearched.value = false;
    fetchPage();
  }, 500);
})

</script>

<template>

  <Head title="Items" />

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Browse Items
      </h2>
    </template>

    <div class="py-12 pb-32">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">

            <div class="mb-3 flex gap-2 items-center">
              <Label for="search" value="Search:" />
              <Input id="search" type="text" v-model="search" class="h-8"/>
            </div>

            <EasyDataTable
              v-model:server-options="serverOptions"
              :server-items-length="props.data.total"
              :headers="headers"
              :items="props.data.data"
              :loading="loading"
              must-sort>

              <template #item-icon="{ icon }">
                <img class="" :src="`/storage${icon}`">
              </template>
              <template #item-highalch="{ highalch }">
                <span class="text-gray-600">{{ FormatNumber(highalch) }}</span>
              </template>
              <template #item-value="{ value }">
                <span class="text-gray-600">{{ FormatNumber(value) }}</span>
              </template>
              <template #item-limit="{ limit }">
                <span class="text-gray-600">{{ FormatNumber(limit) }}</span>
              </template>

              <template #loading>
                <LoadingVue />
              </template>

            </EasyDataTable>

          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>
