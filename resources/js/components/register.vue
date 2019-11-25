<template>
    <div>
        <div class="alert alert-success" v-if="showSuccess">             
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <user-create :user="profileUser" :departments="departments"  @user-saved="savedUser" @user-canceled="cancelEdit"></user-create>
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
                departments: [],
                successMessage: "",
                showSuccess: false
            }
        },
        methods: {
            getInformationFromLoggedUser() {
                //console.log('getInformationFromLoggedUser')
                //this.profileUser = this.$store.state.user; //get USER FROM VUEX STORE
                                                            //DEU **** NO INICIO
            },
            savedUser: function(){
                this.showSuccess = true;
                this.successMessage = "User's Profile Created";
            },
            cancelEdit: function(){
                this.showSuccess = false;
            },            
        },
        mounted() {
            this.getInformationFromLoggedUser();
            
        }        
    }
</script>