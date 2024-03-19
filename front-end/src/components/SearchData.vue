<script>
import axios from 'axios';

export default {
    data() {
        return {
            search: {
                BirthDate: '',
                Symptoms: '',
                Disease: '', 
                Location: '',
                Diagnosis_date: '',
            },
            results: []
        };
    },
    methods: {
        doSearch() {
            const params = new URLSearchParams(this.search).toString();

            axios.get(`http://localhost:8000/SearchData?${params}`)
                .then(response => {
                    this.results = response.data;
                    console.log(response.data);
                    Object.keys(this.search).forEach(key => {
                        this.search[key] = '';
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
};
</script>

<template>
    <div>
        <input v-model="search.Location" placeholder="Località" />
        <input v-model="search.BirthDate" placeholder="Data di nascita" type="date" />
        <input v-model="search.Symptoms" placeholder="Sintomi" />
        <input v-model="search.Disease" placeholder="Malattia" />
        <input v-model="search.Diagnosis_date" placeholder="Data di diagnosi" type="date" />

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
    </div>
</template>
