<template>
    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th scope="col" class="hover d-none d-md-table-cell" @click="changeSort('diagnose_id')">Diagnose ID</th>
                            <th scope="col" class="hover" @click="changeSort('diseases.name')">Disease Name</th>
                            <th scope="col" class="hover d-none d-md-table-cell" @click="changeSort('symptoms')">Symptoms</th>
                            <th scope="col" class="hover" @click="changeSort('location')">Location</th>
                            <th scope="col" class="hover" @click="changeSort('diagnosis_date')">Diagnosis Date</th>
                            <th scope="col" class="hover d-none d-md-table-cell" @click="changeSort('patient_id')">Patient ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="result in results" :key="result.id">
                            <th class="d-none d-md-table-cell" scope="row">{{ result.id }}</th>
                            <td> {{ result.disease_name }}</td>
                            <td class="d-none d-md-table-cell">{{ result.symptoms }}</td>
                            <td>{{ result.location }}</td>
                            <td>{{ result.diagnosis_date }}</td>
                            <td class="d-none d-md-table-cell">{{ result.patient_id }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-between mt-3">
            <div class="col-auto">
                <button class="btn btn-secondary" @click="changePage(currentPage - 1)" :disabled="currentPage <= 1">Prev</button>
            </div>
            <div class="col-auto">
                Pagina corrente: {{ currentPage }} di {{ totalPages() }}
            </div>
            <div class="col-auto">
                <button class="btn btn-secondary" @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages()">Next</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            results: [],
            sortColumn: 'id',
            sortDirection: 'ASC',
            currentPage: 1,
            recordsPerPage: 30,
            totalRecords: 0
        };
    },
    methods: {
        doSearch() {
            const params = new URLSearchParams({
                page: this.currentPage,
                limit: this.recordsPerPage,
                sort: this.sortColumn,
                direction: this.sortDirection,
            }).toString();

            axios.get(`http://localhost:8000/GetData?${params}`)
                .then(response => {
                    this.results = response.data.data;
                    this.totalRecords = response.data.totalRecords;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        },
        changeSort(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'ASC' ? 'DESC' : 'ASC';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'ASC';
            }
            this.doSearch();
        },
        changePage(newPage) {
            this.currentPage = newPage;
            this.doSearch();
        },
        totalPages() {
            return Math.ceil(this.totalRecords / this.recordsPerPage);
        },
    },
    mounted() {
        this.doSearch();
    }
};
</script>

<style scoped>
.hover:hover {
    cursor: pointer;
    text-decoration: underline;
}
</style>
