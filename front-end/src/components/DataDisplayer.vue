
<script>
import axios from 'axios';

export default {
    data() {
        return {
            patients: [],
        };
    },
    methods: {
        getData() {
            axios.get('http://localhost:8000/GetData')
                .then(response => {
                    this.patients = response.data;
                })
                .catch(error => {
                    console.error("C'è stato un errore nel recupero dei dati:", error);
                });
        }
    },
    mounted() {
        //this.getData(); // Chiama getData quando il componente viene montato
    }
}
</script>

<template>
    <h2 class="text-center">Elenco Pazienti</h2>
    <button @click="getData"> get  Data</button>
    <div v-for="patient in patients">

            {{ patient.FirstName }}
    </div>

</template>