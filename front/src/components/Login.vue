<template>
    <div>
        <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                @submit.prevent="login"
        >
        <v-container>
            <v-row justify="center">
                    <v-col cols="12" md="4">
                        <v-alert
                                :value="error"
                                color="pink"
                                dark
                                border="top"
                                icon="mdi-home"
                                transition="scale-transition"
                        >
                            {{ message }}
                        </v-alert>
                    </v-col>
            </v-row>
            <v-row justify="center">
                <v-col cols="12" md="4">
                    <v-text-field
                            v-model="email"
                            :rules="emailRules"
                            label="E-mail"
                            required
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col cols="12"
                       md="4">
                    <v-text-field
                            v-model="password"
                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                            :rules="[rules.required, rules.min]"
                            :type="show1 ? 'text' : 'password'"
                            name="input-10-1"
                            label="Normal with hint text"
                            hint="At least 8 characters"
                            counter
                            @click:append="show1 = !show1"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col cols="12"
                       md="4">
                    <v-btn block color="primary" type="submit">
                        Login
                    </v-btn>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col cols="12" md="4">
                    <v-btn block @click="()=>{this.$router.push('/register')}">
                        Register
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
        </v-form>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data(){
            return {
                email : "",
                password : "",
                valid: true,
                show1: false,
                error: false,
                message:'',
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => (`The email and password you entered don't match`),

                },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
            }
        },
        methods: {
            login: function () {
                let email = this.email;
                let password = this.password;
                this.error = false;
                this.$store.dispatch('login', { email, password })
                    .then(() => {
                        const token = localStorage.getItem('access_token');
                        this.$http.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                        this.$emit('update-menu');
                        this.$router.push('/')
                    })
                    .catch(err => {
                        if (err.response.data.error){
                            this.error = true;
                            this.message = err.response.data.message;
                        }
                    })
            }
        }
    }
</script>

<style scoped>

</style>