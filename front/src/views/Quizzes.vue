<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12" xs="12" sm="6" md="3" v-for="(quiz,index) in quizzes" :key="index">
                    <v-card class="mx-auto" max-width="344" >
                        <v-img :src="'http://127.0.0.1:8000/images/'+quiz.cover" height="200px" @click="goTo(quiz.id)"></v-img>

                        <v-card-title>
                            {{ quiz.name }}
                        </v-card-title>

                        <v-card-subtitle>
                            {{ quiz.completed }}/{{ quiz.questions_count }}
                        </v-card-subtitle>

                        <v-card-actions>
                            <v-btn color="orange lighten-2" text>
                                Explore
                            </v-btn>

                            <v-spacer></v-spacer>
                            <v-btn icon @click="quiz.show = !quiz.show">
                                <v-icon>{{ quiz.show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                            </v-btn>
                        </v-card-actions>

                        <v-expand-transition>
                            <div v-show="quiz.show">
                                <v-divider></v-divider>

                                <v-card-text>
                                    {{ quiz.description }}
                                </v-card-text>
                            </div>
                        </v-expand-transition>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
    export default {
        name: "Quizzes",
        data(){
            return {
                quizzes:[]
            }
        },
        mounted(){
            this.getQuizzes()
        },
        methods:{
            goTo(path){
                this.$router.push('/quizzes/'+path)
            },
            getQuizzes(){
                this.$http.get('/quizzes').then(response=>{
                    let data = [];
                    for (const item of response.data){
                        data.push({
                            id:item.id,
                            name:item.name,
                            cover:item.cover,
                            description:item.description,
                            completed:item.questions_count-item.in_completed_answers_count,
                            questions_count:item.questions_count,
                            show:false
                        })
                    }
                    this.quizzes = data;
                })
            }
        }
    }
</script>

<style scoped>

</style>