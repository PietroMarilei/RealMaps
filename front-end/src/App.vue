<script setup>

import FileUpload from "./components/FileUpload.vue"
import DataDisplayer from "./components/DataDisplayer.vue"
import SearchData from "./components/SearchData.vue"
import AnalyzeData from "./components/AnalyzeData.vue"
</script>

<template>
  <h1>CSV Displayer</h1>
  <FileUpload />
  <DataDisplayer />
  <hr>
  <AnalyzeData />
  <hr>
  <SearchData />
</template>

<style scoped>

</style>
