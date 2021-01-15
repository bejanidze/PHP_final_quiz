<template>
    <v-app id="inspire">
        <v-app-bar
                app
                color="white"
                flat
        >
            <v-container class="py-0 fill-height">
                <v-avatar
                        class="mr-10"
                        color="grey darken-1"
                        size="32"
                ></v-avatar>

                <v-btn
                        v-for="link in links"
                        :key="link.title"
                        text
                >
                    {{ link.title }}
                </v-btn>

                <v-spacer></v-spacer>

                <v-responsive max-width="260">
                    <v-text-field
                            dense
                            flat
                            hide-details
                            rounded
                            solo-inverted
                    ></v-text-field>
                </v-responsive>
            </v-container>
        </v-app-bar>

        <v-main class="grey lighten-3">
            <v-container>
                <v-row>
                    <v-col cols="12" sm="12" md="2" v-if="isLoggedIn">
                        <v-sheet rounded="lg">
                            <v-list color="transparent">
                                <v-list-item
                                        v-for="side in sides"
                                        :key="side.id"
                                        link
                                        @click="goTo(side.url)"
                                >
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            <v-icon small>
                                                {{ side.icon }}
                                            </v-icon>
                                            {{ side.title }}
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-divider class="my-2"></v-divider>

                                <v-list-item
                                        link
                                        color="grey lighten-4"
                                >
                                    <v-list-item-content>
                                        <v-list-item-title @click="logout">
                                            Logout
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list>
                        </v-sheet>
                    </v-col>

                    <v-col cols="12" sm="12" :md="isLoggedIn ? 10 : 12">
                        <v-sheet
                                min-height="70vh"
                                rounded="lg"
                        >
                            <router-view @update-menu="updateMenu"/>
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>


<script>
import HelloWorld from './components/HelloWorld';
import store from '@/store/index';

export default {
    name: 'App',

    components: {
      HelloWorld,
    },
    computed : {
        isLoggedIn : function(){ return this.$store.getters.isLoggedIn}
    },
    data () {
        return {
            links: [],
            sides: []
        }
    },
    created: function() {
        this.$http.interceptors.response.use(undefined, function (err) {
            return new Promise(function (resolve, reject) {
                if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
                    this.$store.dispatch(logout)
                }
                throw err;
            });
        });
        this.updateMenu();
    },
    methods:{
        updateMenu(){
            if (store.getters.isLoggedIn){
                const user = JSON.parse(localStorage.getItem('user'));
                if (user.role){
                    this.links = this.getAdminMenu();
                    this.sides = this.getAdminSides();
                }else{
                    this.links = this.getStudentMenu();
                    this.sides = this.getStudentSides();
                }
            }else{
                this.links = this.getDefaultMenu();
            }
        },
        logout: function () {
            this.$store.dispatch('logout')
                .then(() => {
                    this.updateMenu();
                    this.$router.push('/login')
                })
        },
        goTo: function(url){
            this.$router.push(url).catch(err => {})
        },
        getDefaultMenu(){
            return [
                { title: 'Privacy', icon: 'mdi-view-dashboard' },
                { title: 'Contact', icon: 'mdi-account-box' },
            ]
        },
        getStudentMenu(){
            return [
                { title: 'Dashboard', icon: 'mdi-view-dashboard' },
                { title: 'Account', icon: 'mdi-account-box' },
            ]
        },
        getAdminMenu(){
            return [
                { title: 'Dashboard', icon: 'mdi-view-dashboard' },
                { title: 'Account', icon: 'mdi-account-box' },
                { title: 'Admin', icon: 'mdi-gavel' },
            ]
        },
        getStudentSides(){
            return [
                {id:1,url:"/quizzes",title: 'My Quizzes',icon:"mdi-cast-education"},
                {id:2,url:"/",title: 'Leaderboard',icon:"mdi-format-list-bulleted-square"}
            ]
        },
        getAdminSides(){
            return [
                {id:1,url:"/admin/quizzes",title: 'All Quizzes',icon:"mdi-cast-education"},
                {id:2,url:"/admin/students",title: 'Students',icon:"mdi-format-list-bulleted-square"}
            ]
        }
    },

};
</script>
