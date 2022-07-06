<template>
<div>
    <h1>
        ALors les users ?
    </h1>

    <div>
        <b-form @submit.prevent="userForceLogin">
            <b-form-input  type="number" v-model="number" placeholder="Enter your user ID" required></b-form-input>
            <div class="mt-2">Value: {{ number }}</div>
            <b-button variant="outline-primary" type="submit">Connexion</b-button>
        </b-form>
        <b-button @click="userLogout" class="mt-3" variant="danger">Déconnexion</b-button>
    </div>


</div>
</template>

<script>
    export default {name: 'UserView',
                    data(){
                        return{
                            number: '',
                        }
                    },
                    methods: {
                        userForceLogin(){
                            this.$axios.get('http://127.0.0.1:8000/api/users/force-login/'+this.number)
                            .then((data) => {this.$store.commit('setConnectionData', data.data);
                            });
                        },
                        userLogout(){
                            this.$axios.get('http://127.0.0.1:8000/api/timers/stop-timer');
                            this.$axios.get('http://127.0.0.1:8000/api/users/logout')
                            .then(() => {this.$store.commit('clearConnectionData')});
                        }
                    },
                    }
</script>