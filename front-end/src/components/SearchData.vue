<script>
import axios from 'axios';

export default {
    data() {
        return {
            search: {
                FirstName: '',
                LastName: '',
                BirthDate: '',
                Email: '',
                Symptoms: '',
                Diagnosis: '',
                Location: '',
                DiagnosisDate: '',
                PatientStatus: ''

            },
            patients: []
        };
    },
    methods: {
        doSearch() {
            const params = new URLSearchParams(this.search).toString();

            axios.get(`http://localhost:8000/SearchData?${params}`)
                .then(response => {
                    this.patients = response.data;
                    //svuotamento campi
                    this.search = {
                        FirstName: '',
                        LastName: '',
                        BirthDate: '',
                        Email: '',
                        Symptoms: '',
                        Diagnosis: '',
                        Location: '',
                        DiagnosisDate: '',
                        PatientStatus: ''

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.response) {
                        console.error(error.response.data);
                        console.error(error.response.status);
                        console.error(error.response.headers);
                    } else if (error.request) {
                        console.error(error.request);
                    } else {
                        console.error('Error', error.message);
                    }
                });
        }
    }
};
</script>

<template>
    <div>
        <input v-model="search.FirstName" placeholder="Nome" />
        <input v-model="search.LastName" placeholder="Cognome" />
        <input v-model="search.BirthDate" placeholder="Data di nascita" type="date" />
        <input v-model="search.Email" placeholder="Email" type="email" />
        <input v-model="search.Symptoms" placeholder="Sintomi" />
        <input v-model="search.Diagnosis" placeholder="Diagnosi" />
        <input v-model="search.Location" placeholder="Località" />
        <input v-model="search.DiagnosisDate" placeholder="Data di diagnosi" type="date" />
        <input v-model="search.PatientStatus" placeholder="Stato del paziente" />

        <button @click="doSearch">Cerca</button>

        <div v-for="patient in patients" :key="patient.id">
            {{ patient.FirstName }} {{ patient.LastName }} {{ patient.Email }} - {{ patient.Symptoms }} - {{ patient.Diagnosis }} - {{ patient.Location }} - {{ patient.DiagnosisDate }} - {{ patient.PatientStatus }}

        </div>
    </div>
</template>