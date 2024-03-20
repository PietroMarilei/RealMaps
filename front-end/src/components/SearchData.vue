<script>
import axios from 'axios';

export default {
    data() {
        return {
            currentPage: 1, 
            totalPages: 0,
            search: {
                birthDate: '',
                symptoms: '',
                disease: '',
                location: '',
                diagnosis_date_start: '',  // Data di inizio per l'intervallo di diagnosi
                diagnosis_date_end: '',    // Data di fine per l'intervallo di diagnosi
            },
            results: []
        };
    },
    methods: {
        doSearch() {
            const params = new URLSearchParams({
                ...this.search,
                page: this.currentPage, // Includi la pagina corrente nella ricerca
            }).toString();

            axios.get(`http://localhost:8000/SearchData?${params}`)
                .then(response => {
                    this.results = response.data.results;
                    this.totalPages = response.data.totalPages; // Supponendo che il backend invii il numero totale di pagine
                    console.log(response.data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }, goToNextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.doSearch();
            }
        },
        goToPreviousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.doSearch();
            }
        }
    }
};
</script>

<template>
    <div>
        <input v-model="search.location" placeholder="Location" />
        <input v-model="search.disease" placeholder="Disease Name" />
        <input v-model="search.symptoms" placeholder="Symptoms" />
        <br>
        <input v-model="search.birthDate" placeholder="Birth Date" type="date" />
        <input v-model="search.diagnosis_date_start" placeholder="Start Diagnosis Date" type="date" />
        <input v-model="search.diagnosis_date_end" placeholder="End Diagnosis Date" type="date" />

        <button @click="doSearch">Cerca</button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Location</th>
                    <th scope="col">Disease Name</th>
                    <th scope="col">Symptoms</th>
                    <th scope="col">Diagnosis Date</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(result, i) in results" :key="i">
                    <th scope="row">{{ i + 1 }}</th>
                    <td>{{ result.location }}</td>
                    <td>{{ result.disease_name }}</td>
                    <td>{{ result.symptoms }}</td>
                    <td>{{ result.diagnosis_date }}</td>
                </tr>
            </tbody>
        </table>
        <button @click="goToPreviousPage" :disabled="currentPage === 1">Precedente</button>
        <button @click="goToNextPage" :disabled="currentPage === totalPages">Successiva</button>
    </div>
</template>
