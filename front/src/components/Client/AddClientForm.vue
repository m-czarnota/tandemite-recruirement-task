<script setup lang="ts">
import { reactive, ref } from 'vue';
import { Client } from './Client';
import { ApiRouteGenerator } from './../../http-client/ApiRouteGenerator';

let file = null;
const client: Client = reactive(new Client());
const form = ref(null);

const changeFile = (event) => {
    file = event.target.files[0];
};

const errors = ref({});
const fileErrors = ref([]);
const successInfo = ref(null);

const sendForm = async () => {
    successInfo.value = null;

    const formData = new FormData();
    formData.append('firstname', client.firstname);
    formData.append('lastname', client.lastname);

    if (file) {
        formData.append('files[]', file);
    }

    const resourceUrl = ApiRouteGenerator.generatePath('clients', true);
    const response = await fetch(resourceUrl, {
        method: "POST",
        body: formData,
    });
    console.log(response.ok);

    if (response.ok) {
        successInfo.value = "Successfully added a client";
        form.value.reset();
        client.reset();

        return;
    }

    
    if (response.status === 413) {
        console.log('tutaj')
        fileErrors.value = {
            'size': 'Image is too large, allowed size: 2 MB',
        };

        return;
    }

    const reponseErrors = await response.json();
    console.log(reponseErrors)

    errors.value = reponseErrors;
    fileErrors.value = reponseErrors.files?.length ? reponseErrors.files[0] : [];
};
</script>

<template>
    <form @submit.prevent="sendForm" ref="form">
        <div v-if="successInfo" class="p-3 mb-3 shadow-sm shadow-green-700 rounded text-green-600">
            {{ successInfo }}
        </div>

        <div v-if="errors.generalError">
            {{ errors.generalError }}
        </div>

        <div>
            <div>
                <label for="firstname">
                    Firstname:
                </label>
                <input type="text" name="firstname" id="firstname" v-model="client.firstname"> 
            </div>

            <span v-if="errors.firstname" class="form-input-error">
                {{ errors.firstname }}
            </span>
        </div>

        <div>
            <div>
                <label for="lastname">
                    Lastname:
                </label>
                <input type="text" name="lastname" id="lastname" v-model="client.lastname"> 
            </div>

            <span v-if="errors.lastname" class="form-input-error">
                {{ errors.lastname }}
            </span>
        </div>

        <div>
            <div>
                <label for="file">
                    Image:
                </label>
                <input type="file" name="file" id="file" accept="image/*, image/*" @change="changeFile"> 
                
                <div class="flex flex-col">
                    <span class="text-sm">Only images.</span>
                    <span class="text-sm">Allowed size: 2MB</span>
                </div>
            </div>

            <span v-if="fileErrors">
                <span v-for="(error, key) in fileErrors" class="form-input-error">
                    {{ key }}: {{ error }}
                </span>
            </span>
        </div>

        <button type="submit">Submit</button>
    </form>
</template>