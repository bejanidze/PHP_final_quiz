<template>
    <div>
        <v-form
                ref="form"
                v-model="valid"
                lazy-validation
                @submit.prevent="register"
        >
            <v-container>
                <v-row justify="center">
                    <v-col cols="12" md="4">
                        <v-alert
                                v-for="(message, index) in messages"
                                :value="error"
                                border="top"
                                transition="scale-transition"
                                type="warning"
                                dense
                        >
                            {{ message[0] }}
                        </v-alert>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12" md="4">
                        <v-text-field
                                v-model="name"
                                label="Full Name"
                                :rules="[rules.required]"
                                required
                        ></v-text-field>
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
                    <v-col cols="12" md="4">
                        <v-text-field
                                v-model="phone"
                                :rules="phoneRules"
                                label="Phone Number"
                                type="number"
                                required
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12" md="4">
                        <v-menu
                                ref="menu"
                                v-model="menu"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                        v-model="birth"
                                        label="Birthday date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                    ref="picker"
                                    v-model="birth"
                                    :max="new Date().toISOString().substr(0, 10)"
                                    min="1950-01-01"
                                    @change="save"
                            ></v-date-picker>
                        </v-menu>
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
                                label="Password"
                                hint="At least 8 characters"
                                counter
                                @click:append="show1 = !show1"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12"
                           md="4">
                        <v-text-field
                                v-model="confirm_password"
                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                :rules="[rules.required, rules.min,rules.same]"
                                :type="show1 ? 'text' : 'password'"
                                name="input-10-1"
                                label="Confirm Password"
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
                            Register
                        </v-btn>
                    </v-col>
                </v-row>
                <v-row justify="center">
                    <v-col cols="12" md="4">
                        <v-btn block @click="()=>{this.$router.push('/login')}">
                            Login
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
    </div>
</template>

<script>
    export default {
        name: "Register",
        data(){
            return {
                email : "",
                password : "",
                name:"",
                confirm_password : "",
                phone:"",
                birth:"",
                valid: true,
                show1: false,
                error: false,
                menu:"",
                messages:{},
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => (`The email and password you entered don't match`),
                    same:v=> v === this.password || "Passwords does not match"
                },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                phoneRules: [
                    v => !!v || 'Phone is required',
                    v => (v.match(/\d/g) || []).length === 9 || 'Phone must be valid',
                ],
            }
        },
        methods: {
            register: function () {
                let data = {
                    name: this.name,
                    email: this.email,
                    phone: this.phone,
                    birth: this.birth,
                    password: this.password,
                    password_confirmation: this.confirm_password
                };
                this.$store.dispatch('register', data)
                    .then(() => this.$router.push('/'))
                    .catch(err =>{
                        this.error = true;
                        this.messages = err.response.data
                    })
            },
            save (date) {
                this.$refs.menu.save(date)
            },
        }
    }
</script>

<style scoped>

</style>