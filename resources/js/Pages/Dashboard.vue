<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>

    <Head title="Dashboard" />
    <GuestLayout>
        <template #header>
            <h2 class="page-title">Dashboard</h2>
        </template>
        <div class="py-12" id="laboratories">
            <!-- Search Box -->
            <div class="input-group input-group-flat mb-3">
                <span class="input-group-text">
                    <a title="Search labs" data-bs-toggle="tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                    </a>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search labs..." class="form-control">
            </div>
            <div class="">
                <div v-show="error" class="alert alert-danger" role="alert">
                    {{ error }}
                </div>
                <div v-show="loading && !error" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div v-show="!loading && !error" class="row">
                    <div v-for="laboratory in filteredLaboratories" :key="laboratory.id"
                        class="col-12 col-sm-6 col-md-4 col-lg-3 p-2">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title">{{ laboratory.name }}</h5>
                                <p class="card-text" v-if="laboratory.distance">{{
                                    parseInt(laboratory.distance).toLocaleString() }}m away</p>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary w-50">Visit lab</a>
                                    <a href="#" class="btn btn-secondary w-50">Show analysis</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="filteredLaboratories.length === 0" class="text-center">
                        <p>No laboratories found</p>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script>
export default {
    data() {
        return {
            laboratories: [],
            error: null,
            searchQuery: '',
            loading: false,
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
            this.loading = true;
            navigator.geolocation.getCurrentPosition(async (position) => {
                const { latitude, longitude } = position.coords;
                try {
                    const url = new URL('/laboratories', window.location.origin);
                    url.searchParams.append('lat', latitude);
                    url.searchParams.append('long', longitude);

                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error('Failed to fetch laboratories');
                    }
                    const data = await response.json();
                    this.laboratories = data.laboratories;
                    console.log(data);
                } catch (error) {
                    this.error = error.message;
                } finally {
                    this.loading = false;
                }
            }, (error) => {
                this.error = "Failed to get user's location";
                this.loading = false;
            });
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
