<script>
import axios from 'axios';

export default {
    name: 'EpidemicAlert',
    data() {
        return {
            epidemics: [],
            caseThreshold: 50 // Valore di default per il numero di casi
        };
    },

    methods: {
        detectEpidemics() {
            axios.get(`http://localhost:8000/AnalyzeData`, {
                params: {
                    caseThreshold: this.caseThreshold
                }
            })
                .then(response => {
                    this.epidemics = response.data;
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
        <input type="number" v-model="caseThreshold" placeholder="Numero di casi per l'epidemia">
        <button @click="detectEpidemics()">Detect Epidemics</button>

        <div v-if="epidemics.length">
            <h2>Epidemia Rilevata</h2>
            <ul>
                <li v-for="epidemic in epidemics" :key="epidemic.Diagnosis">
                    Luogo: {{ epidemic.Location }} - Diagnosi: {{ epidemic.Diagnosis }}
                </li>
            </ul>
        </div>
        <div v-else>
            <p>Nessuna epidemia rilevata.</p>
        </div>
    </div>
</template>