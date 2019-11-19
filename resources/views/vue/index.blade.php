@extends('master')

@section('title', 'Vue.js App')

@section('content')
    <router-link to="/users">Users</router-link> -  
    <router-link to="/departments">Departments</router-link> -
    <router-link to="/tictactoe">Tic Tac Toe</router-link> -
    <router-link to="/profile">Profile</router-link> -
    <router-link to="/login">Login</router-link> -
    <router-link to="/logout">Logout</router-link>
    <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vue.js"></script>
 @stop  
