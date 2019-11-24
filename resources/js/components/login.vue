<template>
    <div class="jumbotron">
        <h2>Login</h2>
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input
                type="email" class="form-control" v-model="user.email"
                name="email" id="inputEmail"
                placeholder="Email address"/>
        </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password" class="form-control" v-model="user.password"
                name="password" id="inputPassword"/>
        </div>
        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
        </div>
    </div>
</template>

<script type="text/javascript">    
    export default {
        data: function(){
            return { 
                user: {
                    email:"",
                    password:""
                },
            }
        },
        methods: {
            login() {
                 //axios.post('api/login')
                   // .then(response=>{this.numWallets = response.data; });
                
                console.log(this.user.email);
                console.log(this.user.password);
            }

        },
         mounted() {
            this.login();
            
        }  
    }
</script>