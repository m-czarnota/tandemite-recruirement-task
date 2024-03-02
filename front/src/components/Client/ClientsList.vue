<script setup>
import { reactive } from 'vue';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';

const resourceUrl = `${ApiRouteGenerator.generatePath('/clients', true)}?page=1&limit=10`;
const response = await fetch(resourceUrl, {
    method: "GET",
});

const isResponseOk = reactive(response.ok);
const data = await response.json();
</script>

<template>
    <div class="gap-4 flex flex-col">
        <div v-for="clientData in data.records" class="p-3 shadow-md shadow-slate-800 rounded">
            <p>Firstname: {{ clientData.firstname }}</p>
            <p>Lastname: {{ clientData.lastname }}</p>

            <div v-if="Object.values(clientData.files).length > 0">
                <hr class="my-2 border-slate-400">
                <p>Files:</p>
                
                <a v-for="file in clientData.files" :href="file.path" target="_blank" class="pl-3 text-blue-700 hover:underline">
                    <font-awesome-icon icon="fa-solid fa-file" />
                    {{ file.name }}
                </a>
            </div>
        </div>

        <div v-if="data.paginationDataDto.totalRecords === 0">
            No clients. First add one!
        </div>

        <div v-if="!isResponseOk">
            Cannot fetch data from api
        </div>
    </div>
</template>