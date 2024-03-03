<script setup>
import { reactive } from 'vue';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';
import { Guard } from './../../Authenticator/Guard';

const resourceUrl = `${ApiRouteGenerator.generatePath('/clients', true, true)}?page=1&limit=10`;
const response = await fetch(resourceUrl, {
    method: "GET",
    headers: {
        'Authorization': `Bearer ${Guard.getToken()}`,
    },
});

const downloadImage = async (path) => {
    const response = await fetch(path, {
        method: "GET",
        headers: {
            'Authorization': `Bearer ${Guard.getToken()}`,
        },
    });
    const blob = await response.blob();
    const url = URL.createObjectURL(blob);
    window.open(url, '_blank');
};

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
                
                <a v-for="file in clientData.files" @click="downloadImage(file.path)" class="pl-3 text-blue-700 hover:underline">
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