
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
    },
    mounted() {
        this.doSearch(); 
    }
    

};
</script>

<template>
    <div>
        <table class="table ">
            <thead>
                <tr>
                    <th scope="col" @click="changeSort('id')">&#x2023; Diagnose.id</th>
                    <th scope="col">Disease Name</th>
                    <th scope="col">symptoms</th>
                    <th scope="col">Location</th>
                    <th scope="col" @click="changeSort('diagnosis_date')">&#x2023; Diagnosis Date</th>
                    <th scope="col">patient_id</th>
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

        <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1">Precedente</button>
        <button @click="changePage(currentPage + 1)" :disabled="currentPage * recordsPerPage >= totalRecords">Successivo</button>
    </div>
</template>
