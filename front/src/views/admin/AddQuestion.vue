<template>
    <v-container>
        <v-row>
            <v-col cols="12" col="12">
                <v-expansion-panels>
                    <v-expansion-panel v-for="(question,i) in questions" :key="i">
                        <v-expansion-panel-header>
                            <span><strong>{{i+1}})</strong> {{ question.title}}</span>
                            <v-row class="justify-end">
                                <v-btn class="mx-2" fab dark small color="pink" @click="remove(question.id)">
                                    <v-icon dark>
                                        mdi-minus
                                    </v-icon>
                                </v-btn>
                            </v-row>
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-text-field v-model="questions[i].title" label="Question Title*" required></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-textarea
                                            v-model="questions[i].description"
                                            clearable
                                            clear-icon="mdi-close-circle"
                                            label="Description"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6" sm="12" v-for="(answer,index) in question.answers" :key="index">
                                    <v-row>
                                        <v-col cols="12" md="10">
                                            <v-text-field v-model="questions[i].answers[index].answer" label="Answer Title*" required></v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="2">
                                            <v-checkbox
                                                    v-model="questions[i].answers[index].is_correct"
                                                    label="Correct"
                                                    color="success"
                                                    hide-details
                                            ></v-checkbox>
                                        </v-col>
                                    </v-row>

                                </v-col>
                            </v-row>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>
                <v-btn block color="success" class="mt-5" @click="addNewQuestion">
                    <v-icon dark>
                        mdi-plus
                    </v-icon>
                    Add New Question
                </v-btn>

                <v-btn depressed color="primary" class="mt-10" @click="save">
                    Save
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "AddQuestion",
        data(){
            return {
                questions:[]
            }
        },
        mounted(){
            this.getQuestions();
        },
        methods:{
            getQuestions(){
              this.$http.get('/getQuizzes/'+this.$route.params.id)
                  .then(resp=>{
                      this.questions = resp.data.questions
                  }).catch(err=>{

                  })
            },
            addNewQuestion(){
                this.questions.push({
                    id:Math.floor((Math.random() * 1000000000000) + 1),
                    title:'Untitled '+(this.questions.length+1),
                    description:'',
                    answers:[
                        {answer:'',is_correct:0},
                        {answer:'',is_correct:0},
                        {answer:'',is_correct:0},
                        {answer:'',is_correct:0},
                    ]
                })
            },
            remove(id){
                this.questions = this.questions
                    .filter(function (item) {
                        return item.id !== id
                    })
            },
            save(){
                this.$http.post('/updateQuizQuestions/'+this.$route.params.id,{questions:this.questions})
                    .then(resp=>{
                        console.log(resp)
                    }).catch(err=>{
                        console.log(err.response)
                    })
            }
        }
    }
</script>

<style scoped>

</style>