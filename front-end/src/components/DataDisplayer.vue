
<script>
import axios from 'axios';
export default {
    data() {
        return {

            results: [],
            sortColumn: 'id',
            sortDirection: 'ASC',
            // pagination
            currentPage: 1,
            recordsPerPage: 30, 
            totalRecords: 0 
        };
    },

    methods: {
        doSearch() {
           
            const params = new URLSearchParams({
                ...this.search,
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
            return Math.ceil(this.totalRecords/ this.recordsPerPage )
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
    },
    mounted() {
        this.doSearch(); 
    }
    

};
</script>

<template>
    <div class="container">

    <div class="row">
        <div class="col">
            <table class="table ">
                <thead>
                    <tr>
                        <th scope="col" class="hover" @click="changeSort('diagnose_id')">Diagnose ID</th>
                        <th scope="col" class="hover" @click="changeSort('diseases.name')">Disease Name</th>
                        <th scope="col" class="hover" @click="changeSort('symptoms')">Symptoms</th>
                        <th scope="col" class="hover" @click="changeSort('location')">Location</th>
                        <th scope="col" class="hover" @click="changeSort('diagnosis_date')">Diagnosis Date</th>
                        <th scope="col" class="hover" @click="changeSort('patient_id')">Patient ID</th>
        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="result in results">
                        <th scope="row">{{ result.id }}</th>
                        <td>{{ result.disease_name }}</td>
                        <td>{{ result.symptoms }}</td>
                        <td>{{ result.location }}</td>
                        <td>{{ result.diagnosis_date }}</td>
                        <td>{{ result.patient_id }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="row align-items-center">
            <div class="col-4">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1">Prev</button>

            </div>
            <div class="col-4">
                currentPage - {{ currentPage }} - totalPages {{ totalPages() }}

            </div>
            <div class="col-4">
                <button @click="changePage(currentPage + 1)" :disabled="currentPage * recordsPerPage >= totalRecords">Next</button>

            </div>

        </div>
        
    </div>
   

    </div>
</template>

<style scoped>

</style>
