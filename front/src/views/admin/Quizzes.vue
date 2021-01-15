<template>
    <v-card>
        <v-alert dense text type="success" v-if="message">
            <strong>{{message}}</strong>
        </v-alert>
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Add New Quiz</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-text-field v-model="quiz.name" label="Quiz Name*" required></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-file-input
                                    v-model="quiz.cover"
                                    accept="image/png, image/jpeg, image/bmp"
                                    prepend-icon="mdi-camera"
                                    label="Cover Image"
                            ></v-file-input>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="12">
                                <v-textarea
                                        v-model="quiz.description"
                                        clearable
                                        clear-icon="mdi-close-circle"
                                        label="Description"
                                ></v-textarea>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="addQuiz">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-btn color="blue-grey" class="ma-2 white--text float-right" @click="dialog=true">
            add
            <v-icon right dark>
                mdi-plus
            </v-icon>
        </v-btn>
        <v-card-title>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="desserts" :search="search">
            <template v-slot:item.action="{ item }">
                <v-btn color="blue-grey" class="white--text" @click="goTo(item.id)">View</v-btn>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    export default {
        name: "Quizzes.vue",
        data(){
            return {
                dialog:false,
                search: '',
                headers: [
                    {
                        text: 'ID',
                        align: 'start',
                        filterable: false,
                        value: 'id',
                    },
                    { text: 'Name', value: 'name' },
                    { text: 'Questions', value: 'questions_count' },
                    { text: 'Students', value: 'students_count' },
                    { text: 'action', value: 'action' },
                ],
                desserts:[],
                quiz:{
                    name:'',
                    description:'',
                    cover:null
                },
                message:null
            }
        },
        mounted(){
            this.getQuizzes();
        },
        methods:{
            getQuizzes(){
                this.$http.get('/getQuizzes',)
                    .then(resp=>{
                        this.desserts = resp.data.quizzes;
                    });
            },
            addQuiz(){
                let form = new FormData();
                for (const quiz in this.quiz){
                    form.append(quiz,this.quiz[quiz]);
                }

                this.$http.post('/addQuiz',form).then(resp=>{
                    console.log(resp.data.quiz)
                    this.desserts.unshift(resp.data.quiz)
                    this.message = resp.data.message
                    this.dialog = false
                })
            },
            goTo(path){
                this.$router.push('/admin/quizzes/'+path).catch(err=>{})
            },
        }
    }
</script>

<style scoped>

</style>