<script setup lang="ts">
import { reactive, ref } from 'vue';
import { Client } from './Client';

let file = null;
const client: Client = reactive(new Client());

const changeFile = (event) => {
    file = event.target.files[0];
};

const sendForm = async () => {
    console.log(client);
    console.log(file);

    const formData = new FormData();
    formData.append('firstname', client.firstname);
    formData.append('lastname', client.lastname);

    if (file) {
        formData.append('files[]', file);
    }

    const response = await fetch('http://localhost:8080/api/v1/clients?XDEBUG_SESSION_START=PHPSTORM', {
        method: "POST",
        body: formData,
    });
    console.log(response.ok);
};
</script>

<template>
    <form @submit.prevent="sendForm">
        <div>
            <label for="firstname">
                Firstname:
            </label>
            <input type="text" name="firstname" id="firstname" v-model="client.firstname"> 
        </div>

        <div>
            <label for="lastname">
                Lastname:
            </label>
            <input type="text" name="lastname" id="lastname" v-model="client.lastname"> 
        </div>

        <div>
            <label for="file">
                File:
            </label>
            <input type="file" name="file" id="file" accept="image/*, image/*" @change="changeFile"> 
        </div>

        <button type="submit">Submit</button>
    </form>
</template>