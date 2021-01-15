<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <v-card>
        <v-card-title>
            <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="desserts" :search="search">
            <template v-slot:item.created_at="{ item }">
                <span>{{ new Date(item.created_at).toLocaleString() }}</span>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    export default {
        name: "Students",
        data(){
            return {
                search: '',
                headers: [
                    {
                        text: 'ID',
                        align: 'start',
                        value: 'id',
                    },
                    { text: 'Name', value: 'name' },
                    { text: 'Email', value: 'email' },
                    { text: 'Phone', value: 'phone' },
                    { text: 'Birth', value: 'birth' },
                    { text: 'created at', value: 'created_at' },
                ],
                desserts:[]
            }
        },
        mounted(){
          this.getUsers();
        },
        methods:{
            getUsers(){
                this.$http.get('/getUsers',)
                    .then(resp=>{
                        this.desserts = resp.data.users;
                    });
            }
        }
    }
</script>

<style scoped>

</style>