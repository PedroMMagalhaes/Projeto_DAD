<template>
	<div class="jumbotron">
	    <h2>Create User</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="user.name"
	            name="name" id="inputName"
	            placeholder="Fullname"/>
	    </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control" v-model="user.email"
	            name="email" id="inputEmail"
	            placeholder="Email address"/>
	    </div>
	    <div class="form-group">
	        <label for="inputActive">Active</label>
	        <input
	            type="number" class="form-control" v-model="user.active"
	            name="active" id="inputActive"
	            placeholder="Active"/>
	    </div>
		
		<div class="form-group">
	        <label for="inputType">Type</label>
	        <input
	            type="text" class="form-control" v-model="user.type"
	            name="type" id="inputType"
	            placeholder="Type"/>
	    </div>

		<div class="form-group">
	        <label for="inputNif">Nif</label>
	        <input
	            type="number" class="form-control" v-model="user.nif"
	            name="nif" id="inputNif"
	            placeholder="99999999"/>
	    </div>
	
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="createUser()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelCreate()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	export default {
		props: ['user'],
	    methods: {
	        createUser: function(){
	            axios.post('api/users', this.user)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-saved', this.user)
	                });
	        },
	        cancelCreate: function(){
	        	axios.get('api/users/'+this.user.id)
	                .then(response=>{
	                	// Copy object properties from response.data.data to this.user
	                	// without creating a new reference
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-canceled', this.user);
	                });
	        }
		}
	}
</script>

<style scoped>

</style>
