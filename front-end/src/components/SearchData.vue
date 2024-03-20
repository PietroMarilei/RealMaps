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
                    this.totalPages = response.data.total_pages;
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
        },
        changeOrderBy(field) {
            if (this.search.order_by === field) {
                this.search.order = this.search.order === 'asc' ? 'desc' : 'asc';
            } else {
                this.search.order_by = field;
                this.search.order = 'asc';
            }
            this.doSearch();
        }
    }
};
</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>
                    Data Searcher
                </h2>

            </div>
        </div>
        <div class="row">
            <div class="col-4 "><input class="form-control" v-model="search.location" placeholder="Location" /></div>
            <div class="col-4 "><input class="form-control" v-model="search.disease" placeholder="Disease Name" /></div>
            <div class="col-4 "><input class="form-control" v-model="search.symptoms" placeholder="Symptoms" /></div>
        </div>

        <div class="row">
            <div class="col-4">
                <label class="d-block" for="birthdate"> Birthdate</label>
                <input class="" v-model="search.birthDate" placeholder="Birth Date" type="date" id="birthdate" />

            </div>
            <div class="col-4">
                <label class="d-block" for="diagnosis_date_start"> Diagnosis_date_start</label>
                <input class="date" v-model="search.diagnosis_date_start" placeholder="Start Diagnosis Date" type="date" id="diagnosis_date_start" />

            </div>
            <div class="col-4">
                <label class="d-block" for="diagnosis_date_end"> Diagnosis_date_end</label>

                <input class="" v-model="search.diagnosis_date_end" placeholder="End Diagnosis Date" type="date" id="diagnosis_date_end" />

            </div>
        </div>



        <div class="row ">
            <div class="col ">
                <button class="" @click="doSearch">&#128269; Search</button>
            </div>
        </div>

        <div class="row" v-if="results.length">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="hover" @click="changeOrderBy('diagnose_id')">Diagnose ID</th>
                            <th scope="col" class="hover" @click="changeOrderBy('location')">Location</th>
                            <th scope="col" class="hover" @click="changeOrderBy('disease_name')">Disease Name</th>
                            <th scope="col" class="hover" @click="changeOrderBy('symptoms')">Symptoms</th>
                            <th scope="col" class="hover" @click="changeOrderBy('diagnosis_date')">Diagnosis Date</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(result, i) in results" :key="i">
                            <th scope="row">{{ result.diagnose_id }}</th>
                            <td>{{ result.location }}</td>
                            <td>{{ result.disease_name }}</td>
                            <td>{{ result.symptoms }}</td>
                            <td>{{ result.diagnosis_date }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="row align-items-center" v-if="results.length">
            <div class="col-4">
                <button @click="goToPreviousPage" :disabled="currentPage === 1">Precedente</button>
            </div>
            <div class="col-4">
                total pages {{ totalPages }} - currentPage {{ currentPage }}
            </div>
            <div class="col-4">
                <button @click="goToNextPage" :disabled="currentPage === totalPages">Successiva</button>
            </div>
        </div>

    </div>
    <div>
    </div>
</template>

<style scoped>
input[type="date"] {

    padding: 10px;
    font-family: "Roboto Mono", monospace;
    color: #ffffff;
    font-size: 18px;
    border: none;
    outline: none;
    border-radius: 5px;
}

label {
    background-color: rgb(77, 94, 94);
    border-radius: 5px;
    margin: 20px 0;
    margin-bottom: 5px;
}
</style>
