<script>
import axios from 'axios';

export default {

    data() {
        let currentDate = new Date();
        let formattedDate = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(currentDate.getDate()).padStart(2, '0')}`;
        return {
            results: [],
            caseThreshold: 3,
            startDate: '1900-01-01',
            endDate: formattedDate
        };
    },

    methods: {
        detectEpidemics() {
            axios.get(`http://localhost:8000/AnalyzeData`, {
                params: {
                    caseThreshold: this.caseThreshold,
                    startDate: this.startDate,
                    endDate: this.endDate
                }
            })
                .then(response => {
                    this.results = response.data;
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
        <div class="container mt-3">
            <input type="number" v-model="caseThreshold" placeholder="Numero di casi per l'epidemia">
            <input type="date" v-model="startDate" placeholder="Data Inizio">
            <input type="date" v-model="endDate" placeholder="Data Fine">
            <button @click="detectEpidemics()">Detect Epidemics</button>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Location</th>
                        <th scope="col">Disease Name</th>
                        <th scope="col">Case Count</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(result, i) in results" :key="i">
                        <th scope="row">{{ i + 1 }}</th>
                        <td>{{ result.location }}</td>
                        <td>{{ result.disease_name }}</td>
                        <td>{{ result.CaseCount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
      
    </div>
</template>