<template>
 <div>
    <navbar />
    <div>
        <div class="alert alert-success" v-if="showSuccess">             
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <user-create :user="profileUser" @user-create="createUser" @user-canceled="cancelCreate"></user-create>
    </div>
 </div>             
</template>

<script type="text/javascript">    
    import userCreate from './userCreate.vue';
    
    export default {
        components: {
            'user-create': userCreate, 
        },
        data: function(){
            return { 
                profileUser: {
                    name:"",
                    email:"",
                    type:"",
                    active: "1"
                },
                successMessage: "",
                showSuccess: false
            }
        },
        methods: {
            getInformationFromLoggedUser() {
                this.profileUser = this.$store.state.user; //get USER FROM VUEX STORE
                console.log(this.$store.state.user);                                        //DEU **** NO INICIO
            },
            createUser: function(){
                this.showSuccess = true;
                this.successMessage = "User's Profile Created";
            },
            cancelCreate: function(){
                this.showSuccess = false;
            },            
        },
        mounted() {
            this.getInformationFromLoggedUser();
            
        }        
    }
</script>