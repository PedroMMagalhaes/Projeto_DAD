<template>
    <div>
        <div class="alert alert-success" v-if="showSuccess">             
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <user-edit :user="profileUser" :departments="departments"  @user-saved="savedUser" @user-canceled="cancelEdit"></user-edit>
    </div>              
</template>

<script type="text/javascript">    
    import userEdit from './userEdit.vue';
    export default {
        components: {
            'user-edit': userEdit, 
        },
        data: function(){
            return { 
                profileUser: {
                    email:"",
                    age:"",
                    name:"",
                    department_id: "1"
                },
                departments: [],
                successMessage: "",
                showSuccess: false
            }
        },
        methods: {
            getInformationFromLoggedUser() {
                console.log('getInformationFromLoggedUser')
            },
            savedUser: function(){
                this.showSuccess = true;
                this.successMessage = "User's Profile Updated";
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