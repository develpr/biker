@extends('layouts.simple')
@section('title', "Setup - Account Linking")

<form action="/register" method="post">
    {!! csrf_field() !!}
    <label for="email">email</label>
    <input name="email" type="text">

    <label for="password">password</label>
    <input name="password" type="password">

    <label for="password_confirmation">confirm password</label>
    <input name="password_confirmation" type="password">

    <input name="state" value="{{$state}}" />

    <button type="submit">register</button>
</form>

<form action="/login" method="post">
    {!! csrf_field() !!}
    <label for="email">email</label>
    <input name="email" type="text">

    <label for="password">password</label>
    <input name="password" type="password">

    <input name="state" value="{{$state}}" />

    <button type="submit">register</button>
</form>