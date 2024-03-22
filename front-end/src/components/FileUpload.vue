
<script>
import axios from 'axios';
import config from '../config.js';
export default {
    data() {
        return {
            file: null, 
            uploadStatus: '' 
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0]; 
        },
        submitFile() {
            if (!this.file) {
                this.uploadStatus = 'Seleziona un file prima di caricarlo.';
                return;
            }

            let formData = new FormData();
            formData.append('csv_file', this.file); // 'csv_file' deve corrispondere al nome utilizzato nello script PHP

            this.uploadStatus = 'Caricamento in corso...';

            axios.post(`${config.API_BASE_URL}/UploadData`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    this.uploadStatus = response.data.message;
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.uploadStatus = 'Si è verificato un errore durante il caricamento.';
                });
        }
    }
};
</script>

<template>
    <div>
        <form @submit.prevent="submitFile" class="p-3">
            <label class="m-1 d-block fs-3" for="fileinput">Load CSV</label>
            <input type="file" @change="handleFileUpload" class="form-control-file" id="fileinput" />
            <button type="submit" class="btn btn-danger">&#128190; Load</button>
        </form>
        <div v-if="uploadStatus">
            {{ uploadStatus }}
        </div>
    </div>
</template>
