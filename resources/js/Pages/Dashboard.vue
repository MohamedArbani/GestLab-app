<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>

    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">Dashboard</h2>
        </template>
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <!-- Search Box -->
                    <div class="input-group input-group-flat mb-3">
                        <span class="input-group-text">
                            <a title="Search labs" data-bs-toggle="tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </a>
                        </span>
                        <input type="text" v-model="searchQuery" placeholder="Search labs..." class="form-control">
                    </div>
                    <div id="table-default" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <button class="table-sort" data-sort="sort-name">Name</button>
                                    </th>
                                    <th>
                                        <button class="table-sort" data-sort="sort-address">Address</button>
                                    </th>
                                    <th>
                                        <button class="table-sort" data-sort="sort-phone">Phone</button>
                                    </th>
                                    <th>
                                        <button class="table-sort" data-sort="sort-latitude">Latitude</button>
                                    </th>
                                    <th>
                                        <button class="table-sort" data-sort="sort-longitude">Longitude</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-tbody">
                                <tr v-for="laboratory in filteredLaboratories" :key="laboratory.id">
                                    <td class="sort-name">{{ laboratory.name }}</td>
                                    <td class="sort-address">{{ laboratory.address }}</td>
                                    <td class="sort-phone">{{ laboratory.phone }}</td>
                                    <td class="sort-latitude">{{ laboratory.latitude }}</td>
                                    <td class="sort-longitude">{{ laboratory.longitude }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p v-if="error" class="error">{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    data() {
        return {
            laboratories: [],
            error: null,
            searchQuery: '',
        };
    },
    mounted() {
        this.fetchLaboratories();
    },
    computed: {
        filteredLaboratories() {
            return this.laboratories.filter(lab => {
                return lab.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    lab.address.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    lab.phone.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        }
    },
    methods: {
        async fetchLaboratories() {
            try {
                const response = await fetch('/laboratories');
                if (!response.ok) {
                    throw new Error('Failed to fetch laboratories');
                }
                const data = await response.json();
                this.laboratories = data.laboratories;
                console.log(data);
            } catch (error) {
                this.error = error.message;
            }
        },
    },
};
</script>

<style>
.search-box {
    margin-bottom: 20px;
    padding: 10px;
    width: 100%;
    box-sizing: border-box;
}
</style>
