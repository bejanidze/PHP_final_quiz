<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12" sm="12" md="12">
                    <v-alert :value="error" color="orange" dark border="top" icon="mdi-alert-octagram" transition="scale-transition">
                        {{ message }}
                    </v-alert>
                    <v-card class="mx-auto" max-width="" outlined v-if="!error">
                        <v-list-item three-line>
                            <v-list-item-content>
                                <div class="overline mb-4">
                                    {{completed}}/{{total}}
                                </div>
                                <v-list-item-title class="headline mb-1 text-center">
                                    {{question.title}}
                                </v-list-item-title>
                                <v-list-item-subtitle>{{question.description}}</v-list-item-subtitle>
                            </v-list-item-content>

                        </v-list-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-item-group active-class="primary" cols="12" md="12">
            <v-container>
                <v-row>
                    <v-col v-for="(answer, index) in question.answers" :key="index" cols="12" md="6" v-if="!error">
                        <v-item v-slot="{ toggle }" class="pa-4" :activeClass="activeClass">
                            <v-card class="d-flex align-center" dark height="" @click="toggle(); setAnswer(answer.id);" color="#4a4a4a" :disabled="disabled">

                                <div v-if="!answer.active" class="flex-grow-1 text-center">
                                    {{ answer.answer }}
                                </div>
                                <div v-else class="flex-grow-1 text-center">
                                    <span v-if="status">Correct</span>
                                    <span v-else>Wrong</span>
                                </div>

                            </v-card>
                        </v-item>
                    </v-col>
                </v-row>
            </v-container>
        </v-item-group>
    </div>

</template>

<script>
    export default {
        name: "Quiz.vue",
        data(){
            return {
                question:{},
                answer:null,
                status:0,
                activeClass:'',
                disabled:true,
                message:'',
                error:false,
                total:0,
                completed:0
            }
        },
        mounted(){
            this.getQuestion();
        },
        methods:{
            getQuestion(){
                this.activeClass = '';
                this.$http.get('/quizzes/'+this.$route.params.id)
                    .then(resp=>{
                        this.answer = null;
                        this.question = resp.data.question;
                        this.total = resp.data.total;
                        this.completed = resp.data.total - resp.data.incomplete;
                        this.disabled = false;
                        for (const i in this.question.answers){
                            this.question.answers[i].active = false;
                        }
                    })
                    .catch((err)=>{
                        this.error = true;
                        this.message = err.response.data.message;
                    })
            },
            makeAnswer(){
                this.$http.post('/quizzes/'+this.$route.params.id,{
                    question_id:this.question.id,
                    answer_id:this.answer
                }).then(response=>{
                    this.status = response.data.status;
                    this.activeClass = this.status?'success':'pink';
                    setTimeout(()=>{
                        this.getQuestion();
                    },1000)
                })
                .catch((err)=>{
                    this.error = true;
                    this.message = err.response.data.message;
                })
            },
            setAnswer(id){
                this.answer = id;
                this.disabled = true;
                this.makeAnswer();
            }
        }
    }
</script>

<style scoped>

</style>